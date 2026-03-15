<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;
use App\Models\Subject;

class LevelSubjectSeeder extends Seeder
{
    public function run(): void
    {
        $map = [

            /*
            |--------------------------------------------------------------------------
            | COLLÈGE (Enseignement secondaire premier cycle)
            |--------------------------------------------------------------------------
            */

            '6e' => [
                'Français',
                'Mathématiques',
                'Anglais',
                'Histoire',
                'Géographie',
                'Sciences de la Vie et de la Terre (SVT)',
                'Éducation Civique et Morale (ECM)',
                'Éducation Physique et Sportive (EPS)',
            ],

            '5e' => [
                'Français',
                'Mathématiques',
                'Anglais',
                'Histoire',
                'Géographie',
                'Sciences de la Vie et de la Terre (SVT)',
                'Éducation Civique et Morale (ECM)',
                'Éducation Physique et Sportive (EPS)',
            ],

            '4e' => [
                'Français',
                'Mathématiques',
                'Anglais',
                'Histoire',
                'Géographie',
                'Sciences de la Vie et de la Terre (SVT)',
                'Physique',
                'Chimie',
                'Éducation Civique et Morale (ECM)',
                'Informatique',
                'Éducation Physique et Sportive (EPS)',
            ],

            '3e' => [
                'Français',
                'Mathématiques',
                'Anglais',
                'Histoire',
                'Géographie',
                'Sciences de la Vie et de la Terre (SVT)',
                'Physique',
                'Chimie',
                'Éducation Civique et Morale (ECM)',
                'Informatique',
                'Éducation Physique et Sportive (EPS)',
            ],

            /*
            |--------------------------------------------------------------------------
            | LYCÉE (Second cycle)
            |--------------------------------------------------------------------------
            */

            '2nde A' => [
                'Français',
                'Mathématiques',
                'Anglais',
                'Histoire',
                'Géographie',
                'Sciences de la Vie et de la Terre (SVT)',
                'Physique',
                'Chimie',
                'Éducation Civique et Morale (ECM)',
                'Informatique',
                'Éducation Physique et Sportive (EPS)',
            ],

            '2nde C' => [
                'Mathématiques',
                'Physique',
                'Chimie',
                'Sciences de la Vie et de la Terre (SVT)',
                'Français',
                'Anglais',
                'Histoire',
                'Géographie',
                'Informatique',
                'Éducation Physique et Sportive (EPS)',
            ],

            /*
            |--------------------------------------------------------------------------
            | PREMIÈRE
            |--------------------------------------------------------------------------
            */

            '1ère A' => [
                'Français',
                'Histoire',
                'Géographie',
                'Anglais',
                'Philosophie',
                'Mathématiques',
                'Éducation Physique et Sportive (EPS)',
            ],

            '1ère C' => [
                'Mathématiques',
                'Physique',
                'Chimie',
                'Sciences de la Vie et de la Terre (SVT)',
                'Français',
                'Anglais',
                'Philosophie',
                'Éducation Physique et Sportive (EPS)',
            ],

            '1ère D' => [
                'Mathématiques',
                'Sciences de la Vie et de la Terre (SVT)',
                'Physique',
                'Chimie',
                'Français',
                'Anglais',
                'Philosophie',
                'Éducation Physique et Sportive (EPS)',
            ],

            /*
            |--------------------------------------------------------------------------
            | TERMINALE
            |--------------------------------------------------------------------------
            */

            'Terminale A' => [
                'Philosophie',
                'Français',
                'Histoire',
                'Géographie',
                'Anglais',
                'Mathématiques',
                'Éducation Physique et Sportive (EPS)',
            ],

            'Terminale C' => [
                'Mathématiques',
                'Physique',
                'Chimie',
                'Sciences de la Vie et de la Terre (SVT)',
                'Philosophie',
                'Anglais',
                'Éducation Physique et Sportive (EPS)',
            ],

            'Terminale D' => [
                'Mathématiques',
                'Sciences de la Vie et de la Terre (SVT)',
                'Physique',
                'Chimie',
                'Philosophie',
                'Anglais',
                'Éducation Physique et Sportive (EPS)',
            ],
        ];

        foreach ($map as $levelName => $subjectNames) {

            $level = Level::where('name', $levelName)->first();

            if (!$level) {
                $this->command->warn("Niveau introuvable : {$levelName}");
                continue;
            }

            $subjectIds = [];

            foreach ($subjectNames as $subjectName) {

                $subject = Subject::where('name', $subjectName)->first();

                if (!$subject) {
                    $this->command->warn("Matière introuvable : {$subjectName}");
                    continue;
                }

                $subjectIds[] = $subject->id;
            }

            $level->subjects()->syncWithoutDetaching($subjectIds);

            $this->command->info("✓ {$levelName} → " . count($subjectIds) . " matières liées");
        }
    }
}