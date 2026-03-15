<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Profession;

class ProfessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $professions = [
            ['name' => 'Élève',        'slug' => 'eleve'],
            ['name' => 'Étudiant',     'slug' => 'etudiant'],
            ['name' => 'Professeur',   'slug' => 'professeur'],
            ['name' => 'Indépendant',  'slug' => 'independant'],
        ];

        foreach ($professions as $p) {
            Profession::firstOrCreate(['slug' => $p['slug']], $p);
        }
    }
}
