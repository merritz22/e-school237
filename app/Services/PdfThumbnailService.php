<?php

namespace App\Services;

use Spatie\PdfToImage\Pdf;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PdfThumbnailService
{
    /**
     * Génère le thumbnail de la première page d'un PDF
     * et met à jour le modèle en base.
     *
     * @param Model  $model      N'importe quel modèle (EvaluationSubject, EducationalResource, etc.)
     * @param string $filePath   Chemin du PDF relatif au disk private
     * @param string $column     Colonne à mettre à jour (default: 'preview_image')
     * @param string $disk       Disk source du PDF (default: 'private')
     */
    public function generate(
        Model $model,
        string $filePath,
        string $column = 'preview_image',
        string $disk = 'private'
    ): bool {
        try {
            $sourcePath = storage_path('app/' . $disk . '/' . $filePath);

            if (!file_exists($sourcePath)) {
                Log::warning("PdfThumbnailService: fichier introuvable → {$sourcePath}");
                return false;
            }

            $thumbnailPath = 'thumbnails/' . $model->id . $model->file_name . '.jpg';
            $destinationPath = storage_path('public/storage/' . $thumbnailPath);

            // Créer le dossier si inexistant
            $dir = dirname($destinationPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $pdf = new Pdf($sourcePath);
            $pdf->selectPage(1)->save($destinationPath);

            $model->update([$column => $thumbnailPath]);

            return true;

        } catch (\Throwable $e) {
            Log::error("PdfThumbnailService: erreur pour le modèle #{$model->id} → " . $e->getMessage());
            return false;
        }
    }
}