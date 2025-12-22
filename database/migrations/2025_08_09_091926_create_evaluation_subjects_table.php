<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluation_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['exam', 'quiz', 'exercise', 'qcm']);
            
            // Liens vers catégories & auteur
            $table->foreignId('subject_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('level_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');

            // Durée de l'examen
            $table->integer('duration_minutes')->nullable();

            // Infos sur le fichier principal
            $table->string('file_path')->nullable();
            $table->integer('file_size')->nullable(); // en octets
            $table->string('file_type', 10)->nullable(); // ex: pdf, docx

            // Fichier de correction
            $table->string('correction_file_path')->nullable();

            // Métadonnées supplémentaires
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->integer('downloads_count')->default(0);

            $table->timestamps();

            // Index pour performance
            $table->index(['type', 'difficulty']);
            $table->index(['subject_id', 'level_id']);
            $table->index(['author_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_subjects');
    }
};
