<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            // morphs() crée déjà commentable_type, commentable_id + l'index automatiquement
            $table->morphs('commentable');

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('body');
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
            $table->boolean('is_deleted')->default(false);
            $table->enum('deleted_display', ['blurred', 'strikethrough'])->default('blurred');
            $table->timestamp('deleted_at')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->boolean('is_pinned')->default(false);
            $table->unsignedInteger('likes_count')->default(0);
            $table->timestamps();


            // ✅ Garder uniquement ceux-ci
            $table->index(['parent_id']);
            $table->index(['user_id']);
        });

        // Table des mentions (@user)
        Schema::create('comment_mentions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained('comments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Table des likes
        Schema::create('comment_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained('comments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unique(['comment_id', 'user_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_likes');
        Schema::dropIfExists('comment_mentions');
        Schema::dropIfExists('comments');
    }
};