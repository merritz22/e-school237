<?php

namespace App\Http\Controllers;

use App\Models\EvaluationSubject;
use App\Models\Category;
use App\Models\DownloadLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\Fpdi\PdfParser\PdfParserException;


class EvaluationSubjectController extends Controller
{
    /**
     * Liste des sujets d'évaluation
     */
    public function index(Request $request)
    {
        $query = EvaluationSubject::with('category');

        // Filtrage par niveau
        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        // Filtrage par matière
        if ($request->filled('subject')) {
            $query->where('subject_id', 'LIKE', '%' . $request->subject . '%');
        }

        // Filtrage par catégorie
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filtrage par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtrage par année
        // if ($request->filled('year')) {
        //     $query->whereYear('exam_date', $request->year);
        // }

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

        $subjects = $query->paginate(15);

        // Données pour les filtres
        $levels = EvaluationSubject::distinct()->pluck('level_id')->filter()->sort();
        $subject_names = EvaluationSubject::distinct()->pluck('subject_id')->filter()->sort();
        $types = EvaluationSubject::distinct()->pluck('type')->filter()->sort();
        $authors = [];
        $years = [];
        // $years = EvaluationSubject::selectRaw('YEAR(exam_date) as year')
        //     ->whereNotNull('exam_date')
        //     ->distinct()
        //     ->orderBy('year', 'desc')
        //     ->pluck('year');
        $filter_subjects = Category::all()->where('type', 'subject');
        $levels = Category::all()->where('type', 'level');

        // dd($subjects[0]->level_id);
        
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
                      ->orWhere('subject_id', $subject->subject_id);
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

        // 📄 Chemin réel du PDF
        $pdfPath = Storage::disk('private')->path($subject->file_path);

        // 👤 Infos du filigrane
        $watermarkText = "E-School237";

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
                $pdf->SetFont('helvetica', 'B', 80);
                $pdf->SetTextColor(30, 64, 175); // blue-600
                $pdf->SetAlpha(0.12);

                $pdf->Rotate(45, $size['width'] / 2, $size['height'] / 2);
                $pdf->Text(20, $size['height'] / 2, $watermarkText);
                $pdf->Rotate(0);
            }

            // 📤 Téléchargement
            $fileName = $subject->title . '.pdf';

            return response($pdf->Output($fileName, 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header(
                    'Content-Disposition',
                    'attachment; filename="' . $fileName . '"'
            );
        } catch (\Exception $e) {
            // Ici tu peux gérer l’erreur comme tu veux
            \Log::error('Erreur lors de la génération du PDF : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Impossible de télécharger ce fichier. Le PDF semble corrompu.');
        }
        // return Storage::disk('private')->download($subject->file_path, $subject->title . '.' . pathinfo($subject->file_path, PATHINFO_EXTENSION));
    }

    /**
     * Sujets par niveau
     */
    public function byLevel($level_id)
    {
        $subjects = EvaluationSubject::with('category')
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
        $subjects = EvaluationSubject::with('category')
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

        $query = EvaluationSubject::with( 'category', 'author');

        // Filtres
        if ($request->filled('level')) {
            $query->where('level_id', $request->level);
            // echo($request->level_id);
        }

        if ($request->filled('category')) {
            $query->where('subject_id', $request->category);
        }

        // if ($request->filled('subject_id')) {
        //     $query->where('subject_id', $request->subject_id);
        //     dd('ok');
        // }

        $subjects = $query->orderBy('created_at', 'desc')->paginate(20);

        $categories = Category::where('type', 'subject')->orderBy('name')->get();
        $levels = Category::where('type', 'level')->orderBy('name')->get();

        $authors = User::where('role', 'author')->get();

        // dd($levels);

        // dd($categories);
        return view('admin.subjects.index', compact('subjects', 'levels', 'categories', 'authors'));
    }

    /**
     * Formulaire de création de sujet
     */
    public function create()
    {
        // $this->authorize('create', EvaluationSubject::class);
        
        $categories = Category::where('type', 'subject')->orderBy('name')->get();
        $levels = Category::where('type', 'level')->orderBy('name')->get();
        $types = ['Examen', 'Séquence', 'Travaux dirigés'];
        
        return view('admin.subjects.create', compact('categories', 'levels', 'types'));
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
            'subject_id' => 'required|exists:categories,id',
            'type' => 'required|max:50',
            // 'exam_date' => 'nullable|date',
            'duration_minutes' => 'nullable|integer|min:1',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'correction_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $data = $request->only([
            'title', 
            'description', 
            'level_id', 
            'subject_id', 
            'type', 
            // 'exam_date', 
            'duration_minutes'
        ]);
        
        $data['author_id'] = Auth::id();

        // Upload du fichier principal
        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('subjects', 'private');
            $data['file_size'] = $request->file('file')->getSize();
            $data['file_type'] = $request->file('file')->getClientOriginalExtension();
        }

        // Upload du fichier de correction
        if ($request->hasFile('correction_file')) {
            $data['correction_file_path'] = $request->file('correction_file')->store('subjects/corrections', 'private');
        }

        try {
            $subject = EvaluationSubject::create($data);
            return redirect()->route('admin.subjects.index')
                ->with('success', 'Sujet créé avec succès.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    /**
     * Formulaire d'édition de sujet
     */
    public function edit(EvaluationSubject $subject)
    {
        // $this->authorize('update', $subject);
        $categories = Category::where('type', 'subject')->orderBy('name')->get();
        $levels = Category::where('type', 'level')->orderBy('name')->get();
        
        // $categories = Category::orderBy('name')->get();
        // $levels = EvaluationSubject::distinct()->pluck('level_id')->filter()->sort();
        // $types = ['Examen', 'Contrôle', 'QCM', 'Exercice', 'Devoir'];
        // dd($subject);
        $types = ['Examen', 'Séquence', 'Travaux dirigés'];

        
        return view('admin.subjects.edit', compact('subject', 'categories', 'levels', 'types'));
    }

    /**
     * Met à jour un sujet
     */
    public function update(Request $request, EvaluationSubject $subject)
    {
        // $this->authorize('update', $subject);

        // $request->validate([
        //     'title' => 'required|max:255',
        //     'description' => 'nullable|max:1000',
        //     'level_id' => 'required|integer',
        //     'subject_id' => 'required|exists:categories,id',
        //     'type' => 'required|max:50',
        //     // 'exam_date' => 'nullable|date',
        //     'duration_minutes' => 'nullable|integer|min:1',
        //     'file' => 'sometimes|file|mimes:pdf,doc,docx|max:10240',
        //     'correction_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        // ]);

        // dd($request);
        $data = $request->except(['file', 'correction_file']);

        // Upload du nouveau fichier principal
        if ($request->hasFile('file')) {
            // Supprimer l'ancien fichier
            if ($subject->file_path) {
                Storage::disk('private')->delete($subject->file_path);
            }
            
            $data['file_path'] = $request->file('file')->store('subjects', 'private');
            $data['file_size'] = $request->file('file')->getSize();
            $data['file_type'] = $request->file('file')->getClientOriginalExtension();
        }

        // Upload du nouveau fichier de correction
        if ($request->hasFile('correction_file')) {
            // Supprimer l'ancien fichier
            if ($subject->correction_file_path) {
                Storage::disk('private')->delete($subject->correction_file_path);
            }
            
            $data['correction_file_path'] = $request->file('correction_file')->store('subjects/corrections', 'private');
        }

        $subject->update($data);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Sujet mis à jour avec succès.');
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

        $subject->delete();

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Sujet supprimé avec succès.');
    }
}