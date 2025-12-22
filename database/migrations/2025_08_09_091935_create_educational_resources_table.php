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
        Schema::create('educational_resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_name');
            $table->string('file_path', 500);
            $table->bigInteger('file_size'); // en bytes
            $table->string('file_type', 50);
            $table->string('mime_type', 100);
            $table->foreignId('uploader_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->integer('downloads_count')->default(0);
            $table->boolean('is_approved')->default(false);
            $table->timestamps();

            $table->index(['is_approved', 'category_id']);
            $table->index(['uploader_id', 'is_approved']);
            $table->index(['file_type', 'is_approved']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educational_resources');
    }
};