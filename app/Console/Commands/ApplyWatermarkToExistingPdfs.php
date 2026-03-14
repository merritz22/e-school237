<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\EvaluationSubject;
use App\Models\EducationalResource;
use App\Services\PdfWatermarkService;
use App\Services\PdfThumbnailService;

class ApplyWatermarkToExistingPdfs extends Command
{
    protected $signature = 'app:apply-watermark';
    protected $description = 'Applique le filigrane aux PDFs existants sans filigrane';

    protected string $watermarkText = "E-School237.com";
    protected string $watermarkLink = "https://e-school237.com";

    public function __construct(
        protected PdfThumbnailService $thumbnailService
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->info("-> Traitement des Subjects");
        $subjects = EvaluationSubject::all();
        $this->processModels($subjects, 'subjects');

        $this->info("-> Traitement des Supports");
        $supports = EducationalResource::all();
        $this->processModels($supports, 'supports');

        $this->info("✅ Terminé !");
    }

    protected function processModels($models, string $section): void
    {
        $success = 0;
        $failed  = 0;

        foreach ($models as $model) {
            try {
                $this->processOne($model);
                $this->info("✓ [{$section}] {$model->title}");
                $success++;
            } catch (\Throwable $e) {
                $this->error("✗ [{$section}] {$model->title} : " . $e->getMessage());
                $this->cleanup($model);
                $failed++;
            }
        }

        $this->info("  → {$success} succès / {$failed} échecs");
    }

    protected function processOne($model): void
    {
        /*
        |----------------------------------------------------------------------
        | 1. Vérifier que le fichier source existe
        |----------------------------------------------------------------------
        */
        if (!Storage::disk('private')->exists($model->file_path)) {
            throw new \Exception("Fichier PDF introuvable : {$model->file_path}");
        }

        $sourcePath = storage_path('app/private/' . $model->file_path);

        /*
        |----------------------------------------------------------------------
        | 2. Appliquer le filigrane
        |----------------------------------------------------------------------
        */
        $watermarkedPdf = PdfWatermarkService::apply(
            $sourcePath,
            $this->watermarkText,
            $this->watermarkLink
        );

        if (!$watermarkedPdf || strlen($watermarkedPdf) === 0) {
            throw new \Exception("Le PDF filigrané généré est vide.");
        }

        /*
        |----------------------------------------------------------------------
        | 3. Remplacer l'ancien fichier par le nouveau
        |----------------------------------------------------------------------
        */
        Storage::disk('private')->put($model->file_path, $watermarkedPdf);

        /*
        |----------------------------------------------------------------------
        | 4. Mettre à jour le thumbnail
        |----------------------------------------------------------------------
        */
        $generated = $this->thumbnailService->generate($model, $model->file_path);

        if (!$generated) {
            throw new \Exception("Échec de la génération du thumbnail.");
        }
    }

    protected function cleanup($model): void
    {
        $this->warn("  ⚠ Suppression des données pour : {$model->title}");

        try {
            // Supprimer le fichier PDF principal
            if ($model->file_path && Storage::disk('private')->exists($model->file_path)) {
                Storage::disk('private')->delete($model->file_path);
                $this->warn("  → PDF supprimé : {$model->file_path}");
            }

            // Supprimer le fichier de correction si présent
            if (
                isset($model->correction_file_path) &&
                $model->correction_file_path &&
                Storage::disk('private')->exists($model->correction_file_path)
            ) {
                Storage::disk('private')->delete($model->correction_file_path);
                $this->warn("  → Correction supprimée : {$model->correction_file_path}");
            }

            // Supprimer le thumbnail
            if ($model->preview_image && Storage::disk('public')->exists($model->preview_image)) {
                Storage::disk('public')->delete($model->preview_image);
                $this->warn("  → Thumbnail supprimé : {$model->preview_image}");
            }

            // Supprimer la ligne en base
            $model->delete();
            $this->warn("  → Entrée base de données supprimée (ID: {$model->id})");

        } catch (\Throwable $e) {
            Log::error("Cleanup échoué pour #{$model->id} : " . $e->getMessage());
            $this->error("  → Échec du cleanup : " . $e->getMessage());
        }
    }
}