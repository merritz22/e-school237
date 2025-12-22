<?php

namespace App\Http\Controllers;

use App\Models\Support;
use App\Models\Category;
use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class SupportController extends Controller
{
    /**
     * Liste des supports pédagogiques
     */
    public function index(Request $request)
    {
        $query = Support::with('category');

        // Filtrage par type de fichier
        if ($request->filled('type')) {
            $query->where('file_type', $request->type);
        }

        // Filtrage par catégorie
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filtrage par niveau
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Recherche par titre
        if ($request->filled('search')) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        // Tri
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'popular':
                $query->orderBy('downloads_count', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'size':
                $query->orderBy('file_size', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $supports = $query->paginate(12);

        // Données pour les filtres
        $file_types = Support::distinct()->pluck('file_type')->filter()->sort();
        $levels = Support::distinct()->pluck('level')->filter()->sort();
        $categories = Category::whereHas('supports')->withCount('supports')->orderBy('name')->get();

        return view('supports.index', compact('supports', 'file_types', 'levels', 'categories'));
    }

    /**
     * Affiche un support spécifique
     */
    public function show(Support $support)
    {
        // Supports similaires
        $related_supports = Support::where('id', '!=', $support->id)
            ->where(function ($query) use ($support) {
                $query->where('category_id', $support->category_id)
                      ->orWhere('file_type', $support->file_type)
                      ->orWhere('level', $support->level);
            })
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('supports.show', compact('support', 'related_supports'));
    }

    /**
     * Télécharge un support
     */
    public function download(Support $support)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour télécharger.');
        }

        if (!Storage::disk('private')->exists($support->file_path)) {
            abort(404, 'Fichier non trouvé.');
        }

        // Enregistrer le téléchargement
        Download::create([
            'user_id' => Auth::id(),
            'downloadable_type' => Support::class,
            'downloadable_id' => $support->id,
            'ip_address' => request()->ip(),
        ]);

        // Incrémenter le compteur
        $support->increment('downloads_count');

        $filename = $support->title . '.' . $support->file_type;
        return Storage::disk('private')->download($support->file_path, $filename);
    }

    /**
     * Supports par type de fichier
     */
    public function byType($type)
    {
        $supports = Support::with('category')
            ->where('file_type', $type)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('supports.type', compact('supports', 'type'));
    }

    /**
     * Prévisualisation d'un support (pour les PDF/images)
     */
    public function preview(Support $support)
    {
        if (!in_array(strtolower($support->file_type), ['pdf', 'jpg', 'jpeg', 'png', 'gif'])) {
            abort(404, 'Prévisualisation non disponible pour ce type de fichier.');
        }

        if (!Storage::disk('private')->exists($support->file_path)) {
            abort(404, 'Fichier non trouvé.');
        }

        $file = Storage::disk('private')->get($support->file_path);
        $mimeType = Storage::disk('private')->mimeType($support->file_path);

        return Response::make($file, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $support->title . '.' . $support->file_type . '"'
        ]);
    }

    /**
     * Liste des supports pour l'admin
     */
    public function adminIndex(Request $request)
    {
        $this->authorize('manage', Support::class);

        $query = Support::with('category', 'author');

        // Filtres
        if ($request->filled('type')) {
            $query->where('file_type', $request->type);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        $supports = $query->orderBy('created_at', 'desc')->paginate(20);

        $file_types = Support::distinct()->pluck('file_type')->filter()->sort();
        $levels = Support::distinct()->pluck('level')->filter()->sort();
        $categories = Category::orderBy('name')->get();

        return view('admin.supports.index', compact('supports', 'file_types', 'levels', 'categories'));
    }

    /**
     * Formulaire de création de support
     */
    public function create()
    {
        $this->authorize('create', Support::class);
        
        $categories = Category::orderBy('name')->get();
        $levels = Support::distinct()->pluck('level')->filter()->sort();
        
        return view('admin.supports.create', compact('categories', 'levels'));
    }

    /**
     * Enregistre un nouveau support
     */
    public function store(Request $request)
    {
        $this->authorize('create', Support::class);

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|max:1000',
            'category_id' => 'required|exists:categories,id',
            'level' => 'nullable|max:50',
            'file' => 'required|file|max:20480', // 20MB max
            'preview_image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        // Upload du fichier
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data['file_path'] = $file->store('supports', 'private');
            $data['file_size'] = $file->getSize();
            $data['file_type'] = strtolower($file->getClientOriginalExtension());
            $data['original_filename'] = $file->getClientOriginalName();
        }

        // Upload de l'image de prévisualisation
        if ($request->hasFile('preview_image')) {
            $data['preview_image'] = $request->file('preview_image')->store('supports/previews', 'public');
        }

        Support::create($data);

        return redirect()->route('admin.supports.index')
            ->with('success', 'Support pédagogique créé avec succès.');
    }

    /**
     * Formulaire d'édition de support
     */
    public function edit(Support $support)
    {
        $this->authorize('update', $support);
        
        $categories = Category::orderBy('name')->get();
        $levels = Support::distinct()->pluck('level')->filter()->sort();
        
        return view('admin.supports.edit', compact('support', 'categories', 'levels'));
    }

    /**
     * Met à jour un support
     */
    public function update(Request $request, Support $support)
    {
        $this->authorize('update', $support);

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|max:1000',
            'category_id' => 'required|exists:categories,id',
            'level' => 'nullable|max:50',
            'file' => 'nullable|file|max:20480',
            'preview_image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['file', 'preview_image']);

        // Upload du nouveau fichier
        if ($request->hasFile('file')) {
            // Supprimer l'ancien fichier
            if ($support->file_path) {
                Storage::disk('private')->delete($support->file_path);
            }
            
            $file = $request->file('file');
            $data['file_path'] = $file->store('supports', 'private');
            $data['file_size'] = $file->getSize();
            $data['file_type'] = strtolower($file->getClientOriginalExtension());
            $data['original_filename'] = $file->getClientOriginalName();
        }

        // Upload de la nouvelle image de prévisualisation
        if ($request->hasFile('preview_image')) {
            // Supprimer l'ancienne image
            if ($support->preview_image) {
                Storage::disk('public')->delete($support->preview_image);
            }
            
            $data['preview_image'] = $request->file('preview_image')->store('supports/previews', 'public');
        }

        $support->update($data);

        return redirect()->route('admin.supports.index')
            ->with('success', 'Support pédagogique mis à jour avec succès.');
    }

    /**
     * Supprime un support
     */
    public function destroy(Support $support)
    {
        $this->authorize('delete', $support);

        // Supprimer le fichier
        if ($support->file_path) {
            Storage::disk('private')->delete($support->file_path);
        }

        // Supprimer l'image de prévisualisation
        if ($support->preview_image) {
            Storage::disk('public')->delete($support->preview_image);
        }

        $support->delete();

        return redirect()->route('admin.supports.index')
            ->with('success', 'Support pédagogique supprimé avec succès.');
    }
}