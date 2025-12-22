<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');

        // Ordre d'exécution important à respecter à cause des clés étrangères
        $this->call([
            // 1. Tables de base sans dépendances
            UserSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            
            // 2. Tables dépendantes
            ArticleSeeder::class,
            EvaluationSubjectSeeder::class,
            EducationalResourceSeeder::class,
            ForumTopicSeeder::class,
            ForumReplySeeder::class,
            
            // 3. Tables de liaison
            ArticleTagSeeder::class,
            UserLikeSeeder::class,
            DownloadLogSeeder::class,
        ]);

        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('🔑 Default login credentials:');
        $this->command->info('📧 Admin: admin@site-educatif.com');
        $this->command->info('🔒 Password: password123');
        $this->command->info('');
        $this->command->info('📊 Database contains:');
        $this->command->info('👥 Users: ' . \App\Models\User::count());
        $this->command->info('📂 Categories: ' . \App\Models\Category::count());
        $this->command->info('🏷️ Tags: ' . \App\Models\Tag::count());
        $this->command->info('📰 Articles: ' . \App\Models\Article::count());
        $this->command->info('📝 Evaluation Subjects: ' . \App\Models\EvaluationSubject::count());
        $this->command->info('📚 Educational Resources: ' . \App\Models\EducationalResource::count());
        $this->command->info('💬 Forum Topics: ' . \App\Models\ForumTopic::count());
        $this->command->info('💭 Forum Replies: ' . \App\Models\ForumReply::count());
    }
}