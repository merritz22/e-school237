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
        Schema::create('user_informations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('establishment')->nullable();   // obligatoire
            $table->date('birth_date')->nullable();        // obligatoire
            $table->enum('gender', ['male', 'female', 'other'])->nullable(); // obligatoire
            $table->foreignId('profession_id')->nullable()->constrained('professions')->nullOnDelete(); // obligatoire
            $table->foreignId('current_level_id')->nullable()->constrained('levels')->nullOnDelete();   // obligatoire
            $table->boolean('needs_special_support')->default(false);
            $table->boolean('class_filter_enabled')->default(false);
            $table->boolean('is_complete')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_informations');
    }
};
