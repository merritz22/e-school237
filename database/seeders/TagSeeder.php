<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            // Tags généraux
            ['name' => 'Cours', 'usage_count' => 0],
            ['name' => 'Exercices', 'usage_count' => 0],
            ['name' => 'Corrections', 'usage_count' => 0],
            ['name' => 'Examens', 'usage_count' => 0],
            ['name' => 'Révisions', 'usage_count' => 0],
            ['name' => 'Méthodologie', 'usage_count' => 0],
            ['name' => 'Annales', 'usage_count' => 0],
            ['name' => 'Fiches', 'usage_count' => 0],

            // Tags par niveau
            ['name' => 'Primaire', 'usage_count' => 0],
            ['name' => 'Collège', 'usage_count' => 0],
            ['name' => 'Lycée', 'usage_count' => 0],
            ['name' => 'Supérieur', 'usage_count' => 0],

            // Tags par difficulté
            ['name' => 'Débutant', 'usage_count' => 0],
            ['name' => 'Intermédiaire', 'usage_count' => 0],
            ['name' => 'Avancé', 'usage_count' => 0],
            ['name' => 'Expert', 'usage_count' => 0],

            // Tags spécifiques aux matières
            ['name' => 'Algèbre', 'usage_count' => 0],
            ['name' => 'Géométrie', 'usage_count' => 0],
            ['name' => 'Calcul', 'usage_count' => 0],
            ['name' => 'Trigonométrie', 'usage_count' => 0],
            ['name' => 'Probabilités', 'usage_count' => 0],
            ['name' => 'Statistiques', 'usage_count' => 0],
            
            ['name' => 'Grammaire', 'usage_count' => 0],
            ['name' => 'Orthographe', 'usage_count' => 0],
            ['name' => 'Conjugaison', 'usage_count' => 0],
            ['name' => 'Vocabulaire', 'usage_count' => 0],
            ['name' => 'Littérature', 'usage_count' => 0],
            ['name' => 'Poésie', 'usage_count' => 0],
            ['name' => 'Roman', 'usage_count' => 0],
            ['name' => 'Théâtre', 'usage_count' => 0],

            ['name' => 'Mécanique', 'usage_count' => 0],
            ['name' => 'Électricité', 'usage_count' => 0],
            ['name' => 'Optique', 'usage_count' => 0],
            ['name' => 'Thermodynamique', 'usage_count' => 0],
            ['name' => 'Chimie organique', 'usage_count' => 0],
            ['name' => 'Chimie minérale', 'usage_count' => 0],

            ['name' => 'Biologie', 'usage_count' => 0],
            ['name' => 'Géologie', 'usage_count' => 0],
            ['name' => 'Écologie', 'usage_count' => 0],
            ['name' => 'Génétique', 'usage_count' => 0],

            ['name' => 'Moyen Âge', 'usage_count' => 0],
            ['name' => 'Renaissance', 'usage_count' => 0],
            ['name' => 'Révolution française', 'usage_count' => 0],
            ['name' => 'Première Guerre mondiale', 'usage_count' => 0],
            ['name' => 'Seconde Guerre mondiale', 'usage_count' => 0],
            ['name' => 'Géographie physique', 'usage_count' => 0],
            ['name' => 'Géographie humaine', 'usage_count' => 0],

            // Tags par type de contenu
            ['name' => 'Vidéo', 'usage_count' => 0],
            ['name' => 'PDF', 'usage_count' => 0],
            ['name' => 'Interactive', 'usage_count' => 0],
            ['name' => 'Quiz', 'usage_count' => 0],
            ['name' => 'QCM', 'usage_count' => 0],
            ['name' => 'Schémas', 'usage_count' => 0],
            ['name' => 'Graphiques', 'usage_count' => 0],
            ['name' => 'Cartes mentales', 'usage_count' => 0],

            // Tags par période/saison
            ['name' => 'Rentrée', 'usage_count' => 0],
            ['name' => 'Trimestre 1', 'usage_count' => 0],
            ['name' => 'Trimestre 2', 'usage_count' => 0],
            ['name' => 'Trimestre 3', 'usage_count' => 0],
            ['name' => 'Brevet', 'usage_count' => 0],
            ['name' => 'Baccalauréat', 'usage_count' => 0],
            ['name' => 'Concours', 'usage_count' => 0],

            // Tags pédagogiques
            ['name' => 'Ludique', 'usage_count' => 0],
            ['name' => 'Pratique', 'usage_count' => 0],
            ['name' => 'Théorique', 'usage_count' => 0],
            ['name' => 'Expérimental', 'usage_count' => 0],
            ['name' => 'Travail de groupe', 'usage_count' => 0],
            ['name' => 'Travail individuel', 'usage_count' => 0],
            ['name' => 'Projet', 'usage_count' => 0],
            ['name' => 'Recherche', 'usage_count' => 0],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }

        $this->command->info('Tags created successfully!');
    }
}