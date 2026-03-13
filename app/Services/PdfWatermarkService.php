<?php

namespace App\Services;

use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\Fpdi\PdfParser\PdfParserException;
use Illuminate\Support\Facades\Log;

class PdfWatermarkService
{
    public static function apply($sourcePath, $watermarkText, $watermarkLink = null)
    {
        try {

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


        } catch (\Throwable $e) {

            Log::error('Erreur watermark PDF : ' . $e->getMessage());

            throw new \Exception('Impossible d\' ajouter le filigrane au PDF.' );
        }
    }
}
