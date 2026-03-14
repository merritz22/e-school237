<?php

namespace App\Http\Controllers;

use App\Models\EvaluationSubject;
use App\Models\Category;
use App\Models\Subject;
use App\Models\Level;
use App\Models\DownloadLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\PdfWatermarkService;
use Illuminate\Support\Facades\Response;
use App\Services\PdfThumbnailService;


class EvaluationSubjectController extends Controller
{
    public function __construct(
        protected PdfThumbnailService $thumbnailService
    ) {}

    /**
     * Liste des sujets d'évaluation
     */
    public function index(Request $request)
    {

        $query = EvaluationSubject::with('subject', 'level');

        // Filtrage par niveau
        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
        }
        // Filtrage par matière
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        // Filtrage par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        // Filtrage par année
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
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
            case 'level_id':
                $query->orderBy('level_id', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $subjects = $query->latest()->paginate(15);

        // Données pour les filtres
        $subject_names = [];
        $types = EvaluationSubject::distinct()->pluck('type')->filter()->sort();
        $authors = [];
        $years = EvaluationSubject::selectRaw('YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Liste des sujets disponnibles dans mon abonement
        $filter_subjects = Subject::all()->where('is_active', 1);

        // Liste des niveaux disponnibles dans mon abonement
        $levels = Level::all()->where('is_active', 1);
        
        return view('subjects.index', compact('subjects', 'levels', 'subject_names', 'types', 'years', 'filter_subjects','authors'));
    }

    /**
     * Affiche un sujet spécifique
     */
    public function show(EvaluationSubject $subject)
    {
        // Sujets similaires
        $related_subjects = EvaluationSubject::where('id', '!=', $subject->id)
            ->where(function ($query) use ($subject) {
                $query->where('level_id', $subject->level_id)
                      ->where('subject_id', $subject->subject_id);
                    //   ->orWhere('category_id', $subject->category_id);
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('subjects.show', compact('subject', 'related_subjects'));
    }

    /**
     * Télécharge un sujet
     */
    public function download(EvaluationSubject $subject)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour télécharger.');
        }

        if (!Storage::disk('private')->exists($subject->file_path)) {
            abort(404, 'Fichier non trouvé.');
        }

        // Enregistrer le téléchargement
        DownloadLog::create([
            'user_id' => Auth::id(),
            'downloadable_type' => EvaluationSubject::class,
            'downloadable_id' => $subject->id,
            'ip_address' => request()->ip(),
            'resource_id' => $subject->id
        ]);

        // Incrémenter le compteur
        $subject->increment('downloads_count');

        // Télécharger le fichier déjà filigrané
        return Storage::disk('private')->download(
            $subject->file_path,
            $subject->file_name
        );
    }

    /**
     * Sujets par niveau
     */
    public function byLevel($level_id)
    {
        $subjects = EvaluationSubject::with('level')
            ->where('level_id', $level_id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('subjects.level_id', compact('subjects', 'level_id'));
    }

    /**
     * Sujets par matière
     */
    public function bySubject($subject_id)
    {
        $subjects = EvaluationSubject::with('subject')
            ->where('subject_id', $subject_id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('subjects.subject', compact('subjects', 'subject_id'));
    }

    /**
     * Liste des sujets pour l'admin
     */
    public function adminIndex(Request $request)
    {
        // $this->authorize('manage', EvaluationSubject::class);

        $query = EvaluationSubject::with( 'subject', 'level', 'author');

        // Filtres
        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
            // echo($request->level_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        
        if ($request->filled('author_id')) {
            $query->where('author_id', $request->author_id);
        }


        $evalsubjects = $query->orderBy('created_at', 'desc')->paginate(20);

        $subjects = Subject::all()->where('is_active', 1);
        $levels = Level::all()->where('is_active', 1);

        $authors = User::where('role', 'author')->get();

        // dd($levels);

        // dd($subjects);
        return view('admin.subjects.index', compact('evalsubjects', 'levels', 'subjects', 'authors'));
    }

    /**
     * Formulaire de création de sujet
     */
    public function create()
    {
        // $this->authorize('create', EvaluationSubject::class);
        
        $subjects = Subject::all()->where('is_active', 1);
        $levels = Level::all()->where('is_active', 1);
        $types = ['Examen', 'Séquence', 'Travaux dirigés'];
        
        return view('admin.subjects.create', compact('subjects', 'levels', 'types'));
    }

    /**
     * Enregistre un nouveau sujet
     */
    public function store(Request $request)
    {
        // $this->authorize('create', EvaluationSubject::class);

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|max:1000',
            'level_id' => 'required|integer',
            'subject_id' => 'required|exists:subjects,id',
            'type' => 'required|max:50',
            // 'exam_date' => 'nullable|date',
            // 'is_free' => 'nullable|integer|min:1',
            'file' => 'required|file|mimes:pdf|max:102400',
            'correction_file' => 'nullable|file|mimes:pdf|max:102400',
        ]);

        $data = $request->only([
            'title', 
            'description', 
            'level_id', 
            'subject_id', 
            'type', 
            // 'exam_date', 
            'is_free'
        ]);

        $data['author_id'] = Auth::id();

        try {

            /*
            |--------------------------------------------------------------------------
            | Traitement du fichier principal
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('file')) {

                $file = $request->file('file');

                if (!$file->isValid()) {
                    throw new \Exception("Le fichier principal est invalide.");
                }

                $tempPath = $file->getRealPath();

                $watermarkText = "E-School237.com";
                $watermarkLink = "https://e-school237.com";

                // Générer le PDF filigrané
                $watermarkedPdf = PdfWatermarkService::apply(
                    $tempPath,
                    $watermarkText,
                    $watermarkLink
                );

                if (!$watermarkedPdf || strlen($watermarkedPdf) === 0) {
                    throw new \Exception("Le PDF généré est vide.");
                }

                $fileName = uniqid('sujet_') . '.pdf';
                $path = 'subjects/' . $fileName;

                Storage::disk('private')->put($path, $watermarkedPdf);

                if (!Storage::disk('private')->exists($path)) {
                    throw new \Exception("Erreur lors de la sauvegarde du PDF.");
                }

                $data['file_name'] = $file->getClientOriginalName();
                $data['file_path'] = $path;
                $data['file_size'] = strlen($watermarkedPdf);
                $data['file_type'] = 'pdf';
            }

            /*
            |--------------------------------------------------------------------------
            | Traitement du fichier correction
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('correction_file')) {

                $correctionFile = $request->file('correction_file');

                if (!$correctionFile->isValid()) {
                    throw new \Exception("Le fichier de correction est invalide.");
                }

                $tempPath = $correctionFile->getRealPath();

                $watermarkedPdf = PdfWatermarkService::apply(
                    $tempPath,
                    $watermarkText 
                );

                $fileName = uniqid('sujet_') . '.pdf';
                $path = 'subjects/corrections/' . $fileName;

                Storage::disk('private')->put($path, $watermarkedPdf);

                $data['correction_file_path'] = $path;
            }

            /*
            |--------------------------------------------------------------------------
            | Création du sujet
            |--------------------------------------------------------------------------
            */

            $subject = EvaluationSubject::create($data);

            // Générer le thumbnail
            $this->thumbnailService->generate(
                model: $subject,
                filePath: $subject->file_path,
            );

            return redirect()
                ->route('admin.subjects.index')
                ->with('success', 'Sujet créé avec succès.');

        } catch (\Throwable $e) {

            Log::error('Erreur création sujet : ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Formulaire d'édition de sujet
     */
    public function edit(EvaluationSubject $subject)
    {
        // $this->authorize('update', $subject);
        $subjects = Subject::all()->where('is_active', 1);
        $levels = Level::all()->where('is_active', 1);
        
        // $categories = Category::orderBy('name')->get();
        // $levels = EvaluationSubject::distinct()->pluck('level_id')->filter()->sort();
        // $types = ['Examen', 'Contrôle', 'QCM', 'Exercice', 'Devoir'];
        // dd($subject);
        $types = ['Examen', 'Séquence', 'Travaux dirigés'];

        
        return view('admin.subjects.edit', compact('subject', 'subjects', 'levels', 'types'));
    }

    /**
     * Met à jour un sujet
     */
    public function update(Request $request, EvaluationSubject $subject)
    {
        // $this->authorize('update', $subject);

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|max:1000',
            'level_id' => 'required|integer',
            'subject_id' => 'required|exists:subjects,id',
            'type' => 'required|max:50',
            // 'exam_date' => 'nullable|date',
            // 'duration_minutes' => 'nullable|integer|min:1',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:102400',
            'correction_file' => 'nullable|file|mimes:pdf|max:102400',
        ]);
        // dd($request);
        $data = $request->only([
            'title', 
            'description', 
            'level_id', 
            'subject_id', 
            'type', 
            // 'exam_date', 
            'is_free'
        ]);

        try {

            /*
            |--------------------------------------------------------------------------
            | Traitement du fichier principal
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('file')) {

                $file = $request->file('file');

                $tempPath = $file->getRealPath();

                $watermarkText = "E-School237.com";
                $watermarkLink = "https://e-school237.com";

                // Générer le PDF filigrané
                $watermarkedPdf = PdfWatermarkService::apply(
                    $tempPath,
                    $watermarkText,
                    $watermarkLink
                );

                if (!$watermarkedPdf || strlen($watermarkedPdf) === 0) {
                    throw new \Exception("Le PDF généré est vide.");
                }

                if ($subject->file_path) {
                    Storage::disk('private')->delete($subject->file_path);
                }

                $fileName = uniqid('sujet_') . '.pdf';
                $path = 'subjects/' . $fileName;

                Storage::disk('private')->put($path, $watermarkedPdf);

                if (!Storage::disk('private')->exists($path)) {
                    throw new \Exception("Erreur lors de la sauvegarde du PDF.");
                }

                $data['file_name'] = $file->getClientOriginalName();
                $data['file_path'] = $path;
                $data['file_size'] = strlen($watermarkedPdf);
                $data['file_type'] = 'pdf';
            }

            /*
            |--------------------------------------------------------------------------
            | Traitement du fichier correction
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('correction_file')) {

                $correctionFile = $request->file('correction_file');
                $tempPath = $correctionFile->getRealPath();

                $watermarkedPdf = PdfWatermarkService::apply(
                    $tempPath,
                    $watermarkText,
                    $watermarkLink
                );


                if ($subject->correction_file_path) {
                    Storage::disk('private')->delete($subject->correction_file_path);
                }

                $fileName = uniqid('sujet_') . '.pdf';
                $path = 'subjects/corrections/' . $fileName;

                Storage::disk('private')->put($path, $watermarkedPdf);

                $data['correction_file_path'] = $path;
            }

            /*
            |--------------------------------------------------------------------------
            | Mise à jour
            |--------------------------------------------------------------------------
            */

            $subject->update($data);

            return redirect()->route('admin.subjects.index')
                ->with('success', 'Sujet mis à jour avec succès.');

        } catch (\Throwable $e) {

            Log::error('Erreur création sujet : ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Supprime un sujet
     */
    public function destroy(EvaluationSubject $subject)
    {
        // $this->authorize('delete', $subject);

        // Supprimer les fichiers
        if ($subject->file_path) {
            Storage::disk('private')->delete($subject->file_path);
        }
        
        if ($subject->correction_file_path) {
            Storage::disk('private')->delete($subject->correction_file_path);
        }

        // Supprimer le thumbnail
        if ($subject->preview_image && Storage::disk('public')->exists($subject->preview_image)) {
            Storage::disk('public')->delete($subject->preview_image);
            $this->warn("  → Thumbnail supprimé : {$subject->preview_image}");
        }

        $subject->delete();

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Sujet supprimé avec succès.');
    }
}