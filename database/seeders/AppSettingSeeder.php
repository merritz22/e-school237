<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AppSettings;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppSettings::insert([
            [
                'code' => 'FREE_DOWNLOAD_LIMIT',
                'value' => '3',
                'is_active' => 1,
                'description' => 'Limitte de téléchargement journalière',
            ],
            [
                'code' => 'DAILY_DOWNLOAD_LIMIT',
                'value' => '5',
                'is_active' => 1,
                'description' => 'Limitte de téléchargement journalière',
            ],
            [
                'code' => 'MONTHLY_DOWNLOAD_LIMIT_P1',
                'value' => '25',
                'is_active' => 1,
                'description' => 'Limitte de téléchargement mensuel pour le forfait basique',
            ],
            [
                'code' => 'MONTHLY_DOWNLOAD_LIMIT_P2',
                'value' => '50',
                'is_active' => 1,
                'description' => 'Limitte de téléchargement mensuel pour le forfait intermédiaire',
            ],
        ]);
    }
}
