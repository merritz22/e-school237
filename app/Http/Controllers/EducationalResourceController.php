<?php

namespace App\Http\Controllers;

use App\Models\EducationalResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EducationalResourceController extends Controller
{
    // Middleware pour les méthodes nécessitant une authentification
    public function __construct()
    {
        // $this->middleware('auth')->except(['index', 'show', 'download']);
        // $this->middleware('can:approve,App\Models\EducationalResource')->only(['approve', 'reject']);
    }

    /**
     * Index d'administration
     */
    public function adminIndex(Request $request)
    {
        $resources = EducationalResource::with(['category', 'uploader'])
            ->latest()
            // ->filter($request->all())
            ->paginate(20);

        return view('admin.resources.index', [
            'resources' => $resources,
            'categories' => Category::all(),
        ]);
    }

    /**
     * Afficher la liste des ressources
     */
    public function index(Request $request)
    {
        $resources = EducationalResource::query()
            ->when($request->has('search'), function($query) use ($request) {
                $query->search($request->search);
            })
            ->when($request->has('category'), function($query) use ($request) {
                $query->byCategory($request->category);
            })
            ->when($request->has('type'), function($query) use ($request) {
                $query->byFileType($request->type);
            })
            ->when($request->has('popular'), function($query) {
                $query->popular(10);
            })
            ->when(!$request->user() || !$request->user()->isAdmin(), function($query) {
                $query->approved();
            })
            ->with(['category', 'uploader'])
            ->latest()
            ->paginate(15);

        $categories = Category::all();

        return view('admin.resources.index', compact('resources', 'categories'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.resources.create', compact('categories'));
    }

    /**
     * Enregistrer une nouvelle ressource
     */
    public function store(Request $request)
    {
        $validated = $this->validateResource($request);

        $file = $request->file('resource_file');
        $filePath = $file->store('educational-resources');

        $resource = EducationalResource::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'file_type' => $file->getClientOriginalExtension(),
            'mime_type' => $file->getMimeType(),
            'uploader_id' => Auth::id(),
            'category_id' => $validated['category_id'],
            'is_approved' => Auth::user()->can('approve', EducationalResource::class),
        ]);

        return redirect()->route('resources.show', $resource)
            ->with('success', 'Ressource créée avec succès. ' . 
                ($resource->is_approved ? '' : 'En attente d\'approbation.'));
    }

    /**
     * Afficher une ressource spécifique
     */
    public function show(EducationalResource $resource)
    {
        if (!$resource->canBeDownloadedBy(Auth::user())) {
            abort(403, 'Cette ressource n\'est pas encore approuvée.');
        }

        return view('educational-resources.show', [
            'resource' => $resource->load(['category', 'uploader', 'likes']),
            'relatedResources' => EducationalResource::where('category_id', $resource->category_id)
                ->where('id', '!=', $resource->id)
                ->approved()
                ->popular(5)
                ->get(),
        ]);
    }

    /**
     * Télécharger le fichier de la ressource
     */
    public function download(EducationalResource $resource): StreamedResponse
    {
        if (!$resource->canBeDownloadedBy(Auth::user())) {
            abort(403, 'Accès non autorisé à cette ressource.');
        }

        $resource->incrementDownloads();

        if (Auth::check()) {
            $resource->downloadLogs()->create(['user_id' => Auth::id()]);
        }

        return Storage::download($resource->file_path, $resource->file_name, [
            'Content-Type' => $resource->mime_type,
        ]);
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(EducationalResource $resource)
    {
        $this->authorize('update', $resource);

        $categories = Category::all();
        return view('educational-resources.edit', compact('resource', 'categories'));
    }

    /**
     * Mettre à jour une ressource
     */
    public function update(Request $request, EducationalResource $resource)
    {
        $this->authorize('update', $resource);

        $validated = $this->validateResource($request, $resource);

        $updateData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
        ];

        if ($request->hasFile('resource_file')) {
            // Supprimer l'ancien fichier
            $resource->deleteFile();

            // Enregistrer le nouveau fichier
            $file = $request->file('resource_file');
            $filePath = $file->store('educational-resources');

            $updateData = array_merge($updateData, [
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'file_type' => $file->getClientOriginalExtension(),
                'mime_type' => $file->getMimeType(),
                'is_approved' => Auth::user()->can('approve', EducationalResource::class),
            ]);
        }

        $resource->update($updateData);

        return redirect()->route('resources.show', $resource)
            ->with('success', 'Ressource mise à jour avec succès.');
    }

    /**
     * Supprimer une ressource
     */
    public function destroy(EducationalResource $resource)
    {
        $this->authorize('delete', $resource);

        $resource->deleteFile();
        $resource->delete();

        return redirect()->route('resources.index')
            ->with('success', 'Ressource supprimée avec succès.');
    }

    /**
     * Approuver une ressource
     */
    public function approve(EducationalResource $resource)
    {
        $resource->approve();

        return back()->with('success', 'Ressource approuvée avec succès.');
    }

    /**
     * Rejeter une ressource
     */
    public function reject(EducationalResource $resource)
    {
        $resource->reject();

        return back()->with('success', 'Ressource rejetée avec succès.');
    }

    /**
     * Valider les données de la ressource
     */
    protected function validateResource(Request $request, ?EducationalResource $resource = null): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'category_id' => 'required|exists:categories,id',
        ];

        if (!$resource || $request->hasFile('resource_file')) {
            $rules['resource_file'] = [
                'required',
                'file',
                'max:20480', // 20MB
                'mimes:' . implode(',', EducationalResource::allowedFileExtensions()),
            ];
        }

        return $request->validate($rules);
    }
}