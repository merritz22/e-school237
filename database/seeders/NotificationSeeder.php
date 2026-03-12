<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Notification::insert([
            [
                'code' => 'WAITING_PAYMENT',
                'title' => 'Paiement en attente',
                'description' => 'Veuillez consulter vos mail afin de suivre les étapes d\'activation de votre abonnement',
                'type' => 'billing',
            ],
            [
                'code' => 'VERIFY_EMAIL',
                'title' => 'Adresse mail non vérifié',
                'description' => 'Veuillez-vous rendre dans votre profil et demander un mail de vérification.',
                'type' => 'alert',
            ]
        ]);
    }
}
