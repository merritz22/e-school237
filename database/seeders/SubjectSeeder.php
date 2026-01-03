<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            'Mathématiques',
            'Français',
            'Anglais',
            'Physique',
            'Chimie',
            'Sciences de la Vie et de la Terre (SVT)',
            'Histoire',
            'Géographie',
            'Éducation Civique et Morale (ECM)',
            'Informatique',
            'Philosophie',
            'Économie',
            'Comptabilité',
            'Sciences Économiques et Sociales (SES)',
            'Littérature',
            'Latin',
            'Grec',
            'Allemand',
            'Espagnol',
            'Arabe',
            'Éducation Physique et Sportive (EPS)',
            'Arts plastiques / Dessin',
            'Musique',
            'Technologie',
            'Sciences de l’Éducation Familiale et Sociale (SEF)',
            'Agriculture',
            'Entrepreneuriat',
        ];

        foreach ($subjects as $subject) {
            DB::table('subjects')->insert([
                'name' => $subject,
                'description' => 'Matière du programme officiel de l’enseignement secondaire au Cameroun.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
