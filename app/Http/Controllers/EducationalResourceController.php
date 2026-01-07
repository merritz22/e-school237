<?php

namespace App\Http\Controllers;

use App\Models\EducationalResource;
use App\Models\Category;
use App\Models\Subject;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\DownloadLog;
use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\Fpdi\PdfParser\PdfParserException;

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
        $resources = EducationalResource::query()
            ->when($request->has('search'), function($query) use ($request) {
                $query->search($request->search);
            })
            ->when($request->has('subject'), function($query) use ($request) {
                $query->bySubject($request->subject);
            })
            ->when($request->has('level'), function($query) use ($request) {
                $query->byLevel($request->level);
            })
            ->with(['subject', 'level', 'uploader'])
            ->latest()
            ->paginate(20);

        return view('admin.resources.index', [
            'resources' => $resources,
            'subjects' => Subject::all()->where('is_active', 1),
            'levels' => Level::all()->where('is_active', 1),
        ]);
    }

    /**
     * Afficher la liste des ressources
     */
    public function index(Request $request)
    {
            $query = EducationalResource::with('subject', 'level');
    
            // Filtrage par niveau
            if ($request->filled('level_id')) {
                $query->where('level_id', $request->level_id);
            }
            // Filtrage par matière
            if ($request->filled('subject_id')) {
                $query->where('subject_id', $request->subject_id);
            }
            
            $resources = $query->where('is_approved', 1)->latest()->paginate(15);

            // Liste des sujets disponnibles dans mon abonement
            $subjects = Subject::all()->where('is_active', 1);

            // Liste des niveaux disponnibles dans mon abonement
            $levels = Level::all()->where('is_active', 1);
            
            return view('resources.index', compact('resources', 'subjects', 'levels'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $subjects = Subject::all()->where('is_active', 1);
        $levels = Level::all()->where('is_active', 1);
        return view('admin.resources.create', compact('subjects', 'levels'));
    }

    /**
     * Enregistrer une nouvelle ressource
     */
    public function store(Request $request)
    {
        $validated = $this->validateResource($request);

        $file = $request->file('resource_file');
        $filePath = $file->store('educational_resources', 'private');

        // dd($request);

        $resource = EducationalResource::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'file_type' => $file->getClientOriginalExtension(),
            'mime_type' => $file->getMimeType(),
            'uploader_id' => Auth::id(),
            'category_id' => $validated['subject_id'],
            'subject_id' => $validated['subject_id'],
            'level_id' => $validated['level_id'],
            'is_approved' => 0,
            'is_free' => $request->boolean('is_free'),
        ]);

        return redirect()->route('admin.resources.index')
            ->with('success', 'Support crée avec succès. ' . 
                ($resource->is_approved ? '' : 'En attente d\'approbation.'));
    }

    /**
     * Afficher une ressource spécifique
     */
    public function show(EducationalResource $resource)
    {
        // if (!$resource->canBeDownloadedBy(Auth::user())) {
        //     abort(403, 'Cette ressource n\'est pas encore approuvée.');
        // }
        
        return view('resources.show', [
            'resource' => $resource->load(['category', 'uploader', 'likes']),
            'relatedResources' => EducationalResource::where('category_id', $resource->category_id)
                ->where('level_id', $resource->level_id)
                ->where('id', '!=', $resource->id)
                ->where('is_approved', 1)
                ->popular(5)
                ->get(),
        ]);
    }

    /**
     * Télécharger le fichier de la ressource
     */
    public function download(EducationalResource $resource)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour télécharger.');
        }

        if (!Storage::disk('private')->exists($resource->file_path)) {
            abort(404, 'Fichier non trouvé.');
        }

        // Enregistrer le téléchargement
        DownloadLog::create([
            'user_id' => Auth::id(),
            'downloadable_type' => EducationalResource::class,
            'downloadable_id' => $resource->id,
            'ip_address' => request()->ip(),
            'resource_id' => $resource->id
        ]);

        // Incrémenter le compteur
        $resource->increment('downloads_count');

        // 📄 Chemin réel du PDF
        $pdfPath = Storage::disk('private')->path($resource->file_path);

        // 👤 Infos du filigrane
        $watermarkText = "© E-School237.com";
        $watermarkLink = "https://e-school237.com";

        // 🧠 Création du PDF filigrané
        try {
            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($pdfPath);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $tplId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($tplId);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tplId);

                // 🎨 Filigrane
                $pdf->SetFont('helvetica', 'B', 30);
                $pdf->SetTextColor(30, 64, 175); // bleu
                $pdf->SetAlpha(0.8);

                // Y = hauteur page - marge basse
                $footerY = 1;
                $footerX = ($size['width'] / 4);

                // Texte cliquable
                $pdf->SetXY($footerX, $footerY);
                $pdf->Write(5, $watermarkText, $watermarkLink);
            }

            // 📤 Téléchargement
            $fileName = $resource->title . '.pdf';

            return response($pdf->Output($fileName, 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header(
                    'Content-Disposition',
                    'attachment; filename="' . $fileName . '"'
            );
        } catch (\Exception $e) {
            // Ici tu peux gérer l’erreur comme tu veux
            \Log::error('Erreur lors de la génération du PDF : ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage() .' Impossible de télécharger ce fichier. Le PDF semble corrompu.');
        }
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(EducationalResource $resource)
    {
        // $this->authorize('update', $resource);

        $subjects = Subject::all()->where('is_active', 1);
        $levels = Level::all()->where('is_active', 1);
        return view('admin.resources.edit', compact('resource', 'subjects', 'levels'));
    }

    /**
     * Mettre à jour une ressource
     */
    public function update(Request $request, EducationalResource $resource)
    {
        // $this->authorize('update', $resource);

        $validated = $this->validateResource($request, $resource);

        $updateData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['subject_id'],
            'subject_id' => $validated['subject_id'],
            'level_id' => $validated['level_id'],
            'is_free' => $request->boolean('is_free'),
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

        return redirect()->route('admin.resources.index', $resource)
            ->with('success', 'Support mis à jour avec succès.');
    }

    /**
     * Supprimer une ressource
     */
    public function destroy(EducationalResource $resource)
    {
        // $this->authorize('delete', $resource);

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
            'subject_id' => 'required|exists:subjects,id',
            'level_id' => 'required|exists:levels,id',
        ];

        if (!$resource || $request->hasFile('resource_file')) {
            $rules['resource_file'] = [
                'required',
                'file',
                'max:102400', // 100MB
                'mimetypes:' . implode(',', EducationalResource::allowedMimeTypes()),
            ];
        }

        return $request->validate($rules);
    }

    /**
     * Publier/dépublier un article
     */
    public function publish(EducationalResource $resource)
    {
        // $this->authorize('update', $article);
        Auth::user()->hasRole([ 'admin', 'author']);
        // dd(now());

        $resource->publishOrUnPublish();

        $status = $resource->is_approved == 1 ? 'publié' : 'dépublié';
        return redirect()->back()->with('success', "Support {$status} avec succès.");
    }
}