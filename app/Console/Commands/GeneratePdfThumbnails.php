<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EvaluationSubject;
use App\Models\EducationalResource;
use App\Services\PdfThumbnailService;

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
    public function handle(PdfThumbnailService $thumbnailService)
    {
        $this->info("-> Subjects section");

        $subjects = EvaluationSubject::whereNull('preview_image')->whereNotNull('file_path')->get();

        foreach ($subjects as $subject) {
            if ($thumbnailService->generate(model: $subject, filePath: $subject->file_path)) {
                $this->info("✓ {$subject->title}");
            } else {
                $this->error("✗ {$subject->title} — voir storage/logs/laravel.log");
            }
        }

        $this->info("-> Supports section");
        $supports = EducationalResource::whereNull('preview_image')->whereNotNull('file_path')->get();

        foreach ($supports as $support) {
            if ($thumbnailService->generate(model: $support, filePath: $support->file_path)) {
                $this->info("✓ {$support->title}");
            } else {
                $this->error("✗ {$support->title} — voir storage/logs/laravel.log");
            }
        }
    }
}
