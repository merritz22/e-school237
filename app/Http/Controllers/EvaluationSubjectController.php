<?php

namespace App\Http\Controllers;

use App\Models\EvaluationSubject;
use App\Models\Subject;
use App\Models\Level;
use App\Models\DownloadLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\PdfWatermarkService;
use App\Services\PdfThumbnailService;
use App\Services\AuditLogger;


class EvaluationSubjectController extends Controller
{
    public function __construct(
        protected PdfThumbnailService $thumbnailService
    ) {}

    /**
     * Télécharge un sujet (ou son corrigé, réservé aux admins)
     */
    public function download(Request $request, EvaluationSubject $subject)
    {
        // Le corrigé n'est accessible qu'aux administrateurs et n'est jamais
        // exposé aux utilisateurs, même via manipulation du paramètre ?type=.
        $wantsCorrection = $request->query('type') === 'correction' && Auth::user()?->isAdmin();

        $filePath = $wantsCorrection ? $subject->correction_file_path : $subject->file_path;
        $fileName = $wantsCorrection ? ('corrige-' . $subject->file_name) : $subject->file_name;

        if (!$filePath || !Storage::disk('private')->exists($filePath)) {
            abort(404, 'Fichier non trouvé.');
        }

        // Le corrigé n'entre pas dans les statistiques de téléchargement des utilisateurs
        if (!$wantsCorrection) {
            // Plusieurs téléchargements du même fichier par le même utilisateur
            // (ou la même IP si anonyme) le même jour ne comptent que pour un.
            $alreadyDownloadedToday = DownloadLog::alreadyDownloadedToday('evaluation', $subject->id, Auth::id(), $request->ip());

            if (!$alreadyDownloadedToday) {
                DownloadLog::create([
                    'user_id' => Auth::id(),
                    'resource_type' => 'evaluation',
                    'resource_id' => $subject->id,
                    'ip_address' => $request->ip(),
                    'downloaded_at' => now(),
                ]);

                $subject->increment('downloads_count');
            }
        }

        // Télécharger le fichier déjà filigrané
        return Storage::disk('private')->download($filePath, $fileName);
    }

    /**
     * Liste des sujets pour l'admin
     */
    public function adminIndex(Request $request)
    {
        // $this->authorize('manage', EvaluationSubject::class);

        $query = EvaluationSubject::with( 'subject', 'level', 'author');

        // Filtres
        if ($request->filled('file_name')) {
            $query->where('file_name', 'like', '%' . $request->file_name . '%');
        }

        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
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
                ->with('error', 'Une erreur est survenue : ');
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
            'file' => 'nullable|file|mimes:pdf|max:102400',
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

        // Définis en amont : utilisés par le fichier principal ET par le
        // corrigé, qui peut être modifié indépendamment du fichier principal.
        $watermarkText = "E-School237.com";
        $watermarkLink = "https://e-school237.com";

        try {

            /*
            |--------------------------------------------------------------------------
            | Traitement du fichier principal
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('file')) {

                $file = $request->file('file');

                $tempPath = $file->getRealPath();

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
                ->with('error', 'Une erreur est survenue : ');
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
        }

        AuditLogger::log(
            'evaluation_subject.deleted',
            "Suppression du sujet d'évaluation « {$subject->title} »",
            null,
            ['id' => $subject->id, 'title' => $subject->title],
            []
        );

        $subject->delete();

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Sujet supprimé avec succès.');
    }
}