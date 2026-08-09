<?php

namespace Database\Seeders;

use App\Models\Training;
use App\Models\User;
use Illuminate\Database\Seeder;

class TrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = User::where('role', 'admin')->value('id');

        $trainings = [
            [
                'title' => 'Bureautique : Word, Excel, PowerPoint',
                'description' => "Maîtrisez les outils bureautiques indispensables pour l'école, l'université et le monde professionnel. Rédaction de documents, tableaux et formules Excel, présentations PowerPoint percutantes : tout ce qu'il faut pour être opérationnel dès le premier cours.",
                'duration' => '4 semaines',
                'price' => 15000,
                'original_price' => 20000,
                'technical_prerequisites' => "Disposer d'un ordinateur (Windows ou Mac) et d'une connexion internet stable.",
                'intellectual_prerequisites' => "Aucun niveau requis, accessible dès la classe de 3ème.",
            ],
            [
                'title' => "Initiation à l'Intelligence Artificielle",
                'description' => "Découvrez les bases de l'IA et des outils comme ChatGPT : comment ça marche, comment bien formuler ses requêtes (prompt engineering), et comment utiliser l'IA pour étudier, créer et gagner du temps au quotidien, en toute responsabilité.",
                'duration' => '6 semaines',
                'price' => 25000,
                'original_price' => 30000,
                'technical_prerequisites' => "Ordinateur ou smartphone avec accès internet.",
                'intellectual_prerequisites' => "Niveau classe de Seconde minimum, curiosité pour les nouvelles technologies.",
            ],
            [
                'title' => 'Robotique pour débutants',
                'description' => "Construisez et programmez vos premiers robots avec des kits pédagogiques (Arduino). Une formation ludique et pratique pour comprendre l'électronique, la logique de programmation et l'automatisation, idéale pour les passionnés de sciences.",
                'duration' => '8 semaines',
                'price' => 35000,
                'original_price' => null,
                'technical_prerequisites' => "Kit Arduino fourni pendant la formation, aucun matériel personnel requis.",
                'intellectual_prerequisites' => "Niveau classe de 4ème minimum, bases en mathématiques et sciences physiques.",
            ],
            [
                'title' => 'Création de contenu digital & réseaux sociaux',
                'description' => "Apprenez à créer des contenus visuels percutants (photo, vidéo, montage) et à gérer une présence professionnelle sur Instagram, TikTok et YouTube. Une formation pensée pour les jeunes qui veulent se lancer dans le digital ou développer leur marque personnelle.",
                'duration' => '5 semaines',
                'price' => 20000,
                'original_price' => 25000,
                'technical_prerequisites' => "Un smartphone avec appareil photo suffit pour démarrer.",
                'intellectual_prerequisites' => "Aucun niveau requis, ouvert à tous à partir de 15 ans.",
            ],
            [
                'title' => 'Développement Web : HTML, CSS, JavaScript',
                'description' => "Créez vos premiers sites web de A à Z. Structure des pages avec HTML, mise en forme avec CSS, interactivité avec JavaScript : les fondations indispensables pour se lancer dans le développement web et découvrir un métier d'avenir.",
                'duration' => '10 semaines',
                'price' => 40000,
                'original_price' => 50000,
                'technical_prerequisites' => "Ordinateur avec connexion internet, aucun logiciel payant requis.",
                'intellectual_prerequisites' => "Niveau classe de 3ème minimum, logique et goût pour la résolution de problèmes.",
            ],
            [
                'title' => 'Introduction à la Cybersécurité',
                'description' => "Comprenez les bases de la sécurité informatique : protection des comptes en ligne, reconnaissance des arnaques et du phishing, bonnes pratiques pour naviguer et travailler en toute sécurité sur internet.",
                'duration' => '3 semaines',
                'price' => 18000,
                'original_price' => null,
                'technical_prerequisites' => "Ordinateur ou smartphone avec accès internet.",
                'intellectual_prerequisites' => "Niveau classe de Seconde minimum.",
            ],
        ];

        foreach ($trainings as $data) {
            Training::updateOrCreate(
                ['title' => $data['title']],
                array_merge($data, [
                    'status' => 'published',
                    'published_at' => now(),
                    'created_by' => $adminId,
                ])
            );
        }
    }
}
