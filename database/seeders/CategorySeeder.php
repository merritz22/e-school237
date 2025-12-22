<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categories de matières (subjects)
        $subjects = [
            ['name' => 'Mathématiques', 'slug' => 'mathematiques', 'type' => 'subject'],
            ['name' => 'Français', 'slug' => 'francais', 'type' => 'subject'],
            ['name' => 'Anglais', 'slug' => 'anglais', 'type' => 'subject'],
            ['name' => 'Sciences Physiques', 'slug' => 'sciences-physiques', 'type' => 'subject'],
            ['name' => 'Sciences de la Vie et de la Terre', 'slug' => 'svt', 'type' => 'subject'],
            ['name' => 'Histoire-Géographie', 'slug' => 'histoire-geographie', 'type' => 'subject'],
            ['name' => 'Philosophie', 'slug' => 'philosophie', 'type' => 'subject'],
            ['name' => 'Économie', 'slug' => 'economie', 'type' => 'subject'],
            ['name' => 'Informatique', 'slug' => 'informatique', 'type' => 'subject'],
            ['name' => 'Arts Plastiques', 'slug' => 'arts-plastiques', 'type' => 'subject'],
        ];

        foreach ($subjects as $subject) {
            Category::create($subject);
        }

        // Categories de niveaux (levels)
        $levels = [
            ['name' => 'CP', 'slug' => 'cp', 'type' => 'level'],
            ['name' => 'CE1', 'slug' => 'ce1', 'type' => 'level'],
            ['name' => 'CE2', 'slug' => 'ce2', 'type' => 'level'],
            ['name' => 'CM1', 'slug' => 'cm1', 'type' => 'level'],
            ['name' => 'CM2', 'slug' => 'cm2', 'type' => 'level'],
            ['name' => '6ème', 'slug' => '6eme', 'type' => 'level'],
            ['name' => '5ème', 'slug' => '5eme', 'type' => 'level'],
            ['name' => '4ème', 'slug' => '4eme', 'type' => 'level'],
            ['name' => '3ème', 'slug' => '3eme', 'type' => 'level'],
            ['name' => '2nde', 'slug' => '2nde', 'type' => 'level'],
            ['name' => '1ère', 'slug' => '1ere', 'type' => 'level'],
            ['name' => 'Terminale', 'slug' => 'terminale', 'type' => 'level'],
            ['name' => 'Supérieur', 'slug' => 'superieur', 'type' => 'level'],
        ];

        foreach ($levels as $level) {
            Category::create($level);
        }

        // Categories de types de fichiers (file_type)
        $fileTypes = [
            ['name' => 'Documents PDF', 'slug' => 'pdf', 'type' => 'file_type'],
            ['name' => 'Documents Word', 'slug' => 'word', 'type' => 'file_type'],
            ['name' => 'Présentations PowerPoint', 'slug' => 'powerpoint', 'type' => 'file_type'],
            ['name' => 'Feuilles de calcul Excel', 'slug' => 'excel', 'type' => 'file_type'],
            ['name' => 'Images', 'slug' => 'images', 'type' => 'file_type'],
            ['name' => 'Vidéos', 'slug' => 'videos', 'type' => 'file_type'],
            ['name' => 'Audios', 'slug' => 'audios', 'type' => 'file_type'],
            ['name' => 'Archives', 'slug' => 'archives', 'type' => 'file_type'],
        ];

        foreach ($fileTypes as $fileType) {
            Category::create($fileType);
        }

        // Sous-catégories pour certaines matières
        $mathCategory = Category::where('slug', 'mathematiques')->first();
        if ($mathCategory) {
            $mathSubCategories = [
                ['name' => 'Algèbre', 'slug' => 'algebre', 'type' => 'subject', 'parent_id' => $mathCategory->id],
                ['name' => 'Géométrie', 'slug' => 'geometrie', 'type' => 'subject', 'parent_id' => $mathCategory->id],
                ['name' => 'Statistiques', 'slug' => 'statistiques', 'type' => 'subject', 'parent_id' => $mathCategory->id],
                ['name' => 'Analyse', 'slug' => 'analyse', 'type' => 'subject', 'parent_id' => $mathCategory->id],
            ];

            foreach ($mathSubCategories as $subCategory) {
                Category::create($subCategory);
            }
        }

        $sciencesCategory = Category::where('slug', 'sciences-physiques')->first();
        if ($sciencesCategory) {
            $sciencesSubCategories = [
                ['name' => 'Physique', 'slug' => 'physique', 'type' => 'subject', 'parent_id' => $sciencesCategory->id],
                ['name' => 'Chimie', 'slug' => 'chimie', 'type' => 'subject', 'parent_id' => $sciencesCategory->id],
            ];

            foreach ($sciencesSubCategories as $subCategory) {
                Category::create($subCategory);
            }
        }

        $this->command->info('Categories created successfully!');
    }
}