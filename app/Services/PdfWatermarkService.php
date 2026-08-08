<?php

namespace App\Services;

use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\Fpdi\PdfParser\PdfParserException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class PdfWatermarkService
{
    public static function apply($sourcePath, $watermarkText, $watermarkLink = null)
    {
        try {
            return self::render($sourcePath, $watermarkText, $watermarkLink);
        } catch (\Throwable $e) {

            // Le parseur FPDI gratuit ne sait pas lire certains PDF récents
            // (flux de références croisées compressés, PDF 1.5+...). On tente
            // une seconde fois après réécriture du PDF par Ghostscript, qui
            // produit un PDF "normalisé" que FPDI sait lire.
            Log::warning('Filigrane PDF : échec direct (' . $e->getMessage() . '), tentative via Ghostscript.');

            $normalizedPath = self::normalizeWithGhostscript($sourcePath);

            if (!$normalizedPath) {
                Log::error('Erreur watermark PDF : ' . $e->getMessage());
                throw new \Exception('Impossible d\' ajouter le filigrane au PDF.');
            }

            try {
                return self::render($normalizedPath, $watermarkText, $watermarkLink);
            } catch (\Throwable $e2) {
                Log::error('Erreur watermark PDF (après normalisation Ghostscript) : ' . $e2->getMessage());
                throw new \Exception('Impossible d\' ajouter le filigrane au PDF.');
            } finally {
                @unlink($normalizedPath);
            }
        }
    }

    /**
     * Applique le filigrane sur chaque page du PDF source et retourne le
     * contenu binaire du PDF résultant.
     */
    private static function render($sourcePath, $watermarkText, $watermarkLink = null): string
    {
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($sourcePath);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {

            $tplId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($tplId);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tplId, 0, 0, $size['width'], $size['height']);

            // 🎨 Style du filigrane
            $pdf->SetFont('helvetica', 'B', 50);
            $pdf->SetTextColor(30, 64, 175);
            $pdf->SetAlpha(0.8);

            $x = $size['width'] / 4;
            $y = $size['height'] / 2;

            $pdf->StartTransform();
            $pdf->Rotate(30, $x, $y);

            $pdf->SetXY($x, $y);

            if ($watermarkLink) {
                $pdf->Write(5, $watermarkText, $watermarkLink);
            } else {
                $pdf->Write(5, $watermarkText);
            }

            $pdf->StopTransform();
        }

        $content = $pdf->Output('', 'S');

        if (!$content || strlen($content) === 0) {
            throw new \Exception("Le PDF généré est vide.");
        }

        return $content;
    }

    /**
     * Réécrit le PDF via Ghostscript (compatibilité PDF 1.4) pour contourner
     * les limites du parseur FPDI gratuit. Retourne le chemin du fichier
     * temporaire normalisé, ou null si Ghostscript est indisponible ou a
     * échoué (l'appelant retombe alors sur l'erreur d'origine).
     */
    private static function normalizeWithGhostscript(string $sourcePath): ?string
    {
        $outputPath = sys_get_temp_dir() . '/' . uniqid('gs_normalized_') . '.pdf';

        try {
            $result = Process::timeout(60)->run([
                'gs',
                '-sDEVICE=pdfwrite',
                '-dCompatibilityLevel=1.4',
                '-dPDFSETTINGS=/prepress',
                '-dNOPAUSE',
                '-dQUIET',
                '-dBATCH',
                '-sOutputFile=' . $outputPath,
                $sourcePath,
            ]);

            if (!$result->successful() || !file_exists($outputPath)) {
                Log::warning('Normalisation Ghostscript échouée : ' . $result->errorOutput());
                return null;
            }

            return $outputPath;
        } catch (\Throwable $e) {
            Log::warning('Ghostscript indisponible pour la normalisation PDF : ' . $e->getMessage());
            return null;
        }
    }
}
