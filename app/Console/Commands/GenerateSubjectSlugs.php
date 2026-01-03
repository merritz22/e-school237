<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subject;
use Illuminate\Support\Str;

class GenerateSubjectSlugs extends Command
{
    protected $signature = 'subjects:generate-slugs';
    protected $description = 'Génère les slugs pour les sujets sans slug';

    public function handle()
    {
        $subjects = Subject::whereNull('slug')->orWhere('slug', '')->get();

        $this->info("Found {$subjects->count()} subjects without slug.");

        foreach ($subjects as $subject) {
            $slug = Str::slug($subject->name);

            $counter = 1;
            $originalSlug = $slug;
            while (Subject::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $subject->slug = $slug;
            $subject->save();

            $this->info("Updated: {$subject->name} -> {$slug}");
        }

        $this->info("All done!");
        return 0;
    }
}
