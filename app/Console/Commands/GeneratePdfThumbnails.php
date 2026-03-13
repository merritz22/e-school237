<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\PdfToImage\Pdf;
use App\Models\EvaluationSubject;
use App\Models\EducationalResource;

class GeneratePdfThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-pdf-thumbnails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $this->info("-> Subjects section");

        $subjects = EvaluationSubject::whereNull('preview_image')->get();

        foreach ($subjects as $subject) {
            try {
                $pdf = new Pdf(storage_path('app/private/' . $subject->file_path));
                $path = 'thumbnails/' . $subject->file_name . '.jpg';

                $pdf->selectPage(1)
                    ->save(storage_path('app/public/' . $path));

                $subject->update(['preview_image' => $path]);
                $this->info("✓ {$subject->title}");
            } catch (\Exception $e) {
                $this->error("✗ {$subject->title} : " . $e->getMessage());
            }
        }

        $this->info("-> Supports section");
        $supports = EducationalResource::whereNull('preview_image')->get();

        foreach ($supports as $support) {
            try {
                $pdf = new Pdf(storage_path('app/private/' . $support->file_path));
                $path = 'thumbnails/' . $support->file_name . '.jpg';

                $pdf->selectPage(1)
                    ->save(storage_path('app/public/' . $path));

                $support->update(['preview_image' => $path]);
                $this->info("✓ {$support->title}");
            } catch (\Exception $e) {
                $this->error("✗ {$support->title} : " . $e->getMessage());
            }
        }
    }
}
