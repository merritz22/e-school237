<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [

            /*
            |--------------------------------------------------------------------------
            | 🔵 FRANCOPHONE
            |--------------------------------------------------------------------------
            */

            // Collège
            ['name' => '6e', 'system' => 'francophone', 'school' => 'Collège', 'description' => 'Classe de sixième – début du collège.'],
            ['name' => '5e', 'system' => 'francophone', 'school' => 'Collège', 'description' => 'Classe de cinquième – collège.'],
            ['name' => '4e', 'system' => 'francophone', 'school' => 'Collège', 'description' => 'Classe de quatrième – collège.'],
            ['name' => '3e', 'system' => 'francophone', 'school' => 'Collège', 'description' => 'Classe de troisième – préparation au BEPC.'],

            // Lycée – Général
            ['name' => '2nde A', 'system' => 'francophone', 'school' => 'Lycée – Général', 'description' => 'Seconde série A – lettres et sciences humaines.'],
            ['name' => '2nde C', 'system' => 'francophone', 'school' => 'Lycée – Général', 'description' => 'Seconde série C – sciences et mathématiques.'],

            ['name' => '1ère A', 'system' => 'francophone', 'school' => 'Lycée – Général', 'description' => 'Première série A – lettres et philosophie.'],
            ['name' => '1ère C', 'system' => 'francophone', 'school' => 'Lycée – Général', 'description' => 'Première série C – mathématiques et sciences physiques.'],
            ['name' => '1ère D', 'system' => 'francophone', 'school' => 'Lycée – Général', 'description' => 'Première série D – sciences de la vie et de la terre.'],

            ['name' => 'Terminale A', 'system' => 'francophone', 'school' => 'Lycée – Général', 'description' => 'Terminale série A – baccalauréat littéraire.'],
            ['name' => 'Terminale C', 'system' => 'francophone', 'school' => 'Lycée – Général', 'description' => 'Terminale série C – baccalauréat scientifique.'],
            ['name' => 'Terminale D', 'system' => 'francophone', 'school' => 'Lycée – Général', 'description' => 'Terminale série D – baccalauréat sciences biologiques.'],

            // Lycée – Technique
            ['name' => '2nde TI', 'system' => 'francophone', 'school' => 'Lycée – Technique', 'description' => 'Seconde Technique Industrielle.'],
            ['name' => '1ère TI', 'system' => 'francophone', 'school' => 'Lycée – Technique', 'description' => 'Première Technique Industrielle.'],
            ['name' => 'Terminale TI', 'system' => 'francophone', 'school' => 'Lycée – Technique', 'description' => 'Terminale Technique Industrielle.'],

            ['name' => '2nde ALL', 'system' => 'francophone', 'school' => 'Lycée – Technique', 'description' => 'Seconde Arts, Lettres et Langues.'],
            ['name' => '1ère ALL', 'system' => 'francophone', 'school' => 'Lycée – Technique', 'description' => 'Première Arts, Lettres et Langues.'],
            ['name' => 'Terminale ALL', 'system' => 'francophone', 'school' => 'Lycée – Technique', 'description' => 'Terminale Arts, Lettres et Langues.'],

            /*
            |--------------------------------------------------------------------------
            | 🟢 ANGLOPHONE
            |--------------------------------------------------------------------------
            */

            ['name' => 'Form 1', 'system' => 'anglophone', 'school' => 'High School', 'description' => 'First year of secondary education.'],
            ['name' => 'Form 2', 'system' => 'anglophone', 'school' => 'High School', 'description' => 'Second year of secondary education.'],
            ['name' => 'Form 3', 'system' => 'anglophone', 'school' => 'High School', 'description' => 'Third year of secondary education.'],
            ['name' => 'Form 4', 'system' => 'anglophone', 'school' => 'High School', 'description' => 'Fourth year of secondary education.'],
            ['name' => 'Form 5', 'system' => 'anglophone', 'school' => 'High School', 'description' => 'Preparation for GCE Ordinary Level.'],

            ['name' => 'Lower Sixth Arts', 'system' => 'anglophone', 'school' => 'High School', 'description' => 'Lower Sixth – Arts stream.'],
            ['name' => 'Lower Sixth Science', 'system' => 'anglophone', 'school' => 'High School', 'description' => 'Lower Sixth – Science stream.'],
            ['name' => 'Upper Sixth Arts', 'system' => 'anglophone', 'school' => 'High School', 'description' => 'Upper Sixth – GCE Advanced Level Arts.'],
            ['name' => 'Upper Sixth Science', 'system' => 'anglophone', 'school' => 'High School', 'description' => 'Upper Sixth – GCE Advanced Level Science.'],
        ];

        foreach ($levels as $level) {
            DB::table('levels')->insert([
                'name' => $level['name'],
                'slug' => Str::slug($level['name']),
                'system' => $level['system'],
                'school' => $level['school'],
                'description' => $level['description'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
