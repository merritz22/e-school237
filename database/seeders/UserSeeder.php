<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer l'administrateur principal
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@site-educatif.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'first_name' => 'Admin',
            'last_name' => 'Principal',
            'role' => 'admin',
            'bio' => 'Administrateur principal du site éducatif.',
            'is_active' => true,
        ]);

        // Créer quelques modérateurs
        $moderators = [
            [
                'name' => 'Marie Dupont',
                'email' => 'marie.dupont@site-educatif.com',
                'first_name' => 'Marie',
                'last_name' => 'Dupont',
                'bio' => 'Modératrice spécialisée en mathématiques et sciences.',
            ],
            [
                'name' => 'Jean Martin',
                'email' => 'jean.martin@site-educatif.com',
                'first_name' => 'Jean',
                'last_name' => 'Martin',
                'bio' => 'Modérateur pour les matières littéraires.',
            ],
        ];

        foreach ($moderators as $moderator) {
            User::create(array_merge($moderator, [
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'moderator',
                'is_active' => true,
            ]));
        }

        // Créer des auteurs (enseignants)
        $authors = [
            [
                'name' => 'Sophie Bernard',
                'email' => 'sophie.bernard@site-educatif.com',
                'first_name' => 'Sophie',
                'last_name' => 'Bernard',
                'bio' => 'Professeure de mathématiques avec 15 ans d\'expérience.',
            ],
            [
                'name' => 'Pierre Dubois',
                'email' => 'pierre.dubois@site-educatif.com',
                'first_name' => 'Pierre',
                'last_name' => 'Dubois',
                'bio' => 'Enseignant de français et littérature.',
            ],
            [
                'name' => 'Claire Rousseau',
                'email' => 'claire.rousseau@site-educatif.com',
                'first_name' => 'Claire',
                'last_name' => 'Rousseau',
                'bio' => 'Professeure de sciences physiques et chimie.',
            ],
            [
                'name' => 'Michel Leroy',
                'email' => 'michel.leroy@site-educatif.com',
                'first_name' => 'Michel',
                'last_name' => 'Leroy',
                'bio' => 'Enseignant d\'histoire-géographie.',
            ],
            [
                'name' => 'Anne Moreau',
                'email' => 'anne.moreau@site-educatif.com',
                'first_name' => 'Anne',
                'last_name' => 'Moreau',
                'bio' => 'Professeure d\'anglais.',
            ],
        ];

        foreach ($authors as $author) {
            User::create(array_merge($author, [
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'author',
                'is_active' => true,
            ]));
        }

        // Créer des membres (étudiants)
        $members = [
            [
                'name' => 'Lucas Petit',
                'email' => 'lucas.petit@exemple.com',
                'first_name' => 'Lucas',
                'last_name' => 'Petit',
                'bio' => 'Étudiant en terminale scientifique.',
            ],
            [
                'name' => 'Emma Girard',
                'email' => 'emma.girard@exemple.com',
                'first_name' => 'Emma',
                'last_name' => 'Girard',
                'bio' => 'Collégienne passionnée de littérature.',
            ],
            [
                'name' => 'Thomas Roux',
                'email' => 'thomas.roux@exemple.com',
                'first_name' => 'Thomas',
                'last_name' => 'Roux',
                'bio' => 'Lycéen intéressé par les mathématiques.',
            ],
            [
                'name' => 'Léa Simon',
                'email' => 'lea.simon@exemple.com',
                'first_name' => 'Léa',
                'last_name' => 'Simon',
                'bio' => 'Étudiante en première littéraire.',
            ],
            [
                'name' => 'Hugo Garcia',
                'email' => 'hugo.garcia@exemple.com',
                'first_name' => 'Hugo',
                'last_name' => 'Garcia',
                'bio' => 'Collégien curieux de sciences.',
            ],
        ];

        foreach ($members as $member) {
            User::create(array_merge($member, [
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'member',
                'is_active' => true,
            ]));
        }

        // Générer des utilisateurs aléatoires avec Factory (si besoin de plus d'utilisateurs)
        User::factory(20)->create();

        $this->command->info('Users created successfully!');
        $this->command->info('Default login credentials:');
        $this->command->info('Admin: admin@site-educatif.com / password123');
        $this->command->info('Moderator: marie.dupont@site-educatif.com / password123');
        $this->command->info('Author: sophie.bernard@site-educatif.com / password123');
        $this->command->info('Member: lucas.petit@exemple.com / password123');
    }
}