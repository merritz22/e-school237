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
        Schema::table('educational_resources', function (Blueprint $table) {
            $table->foreignId('subject_id')
                ->nullable()
                ->after('category_id')
                ->constrained('subjects')
                ->nullOnDelete();

            $table->foreignId('level_id')
                ->nullable()
                ->after('subject_id')
                ->constrained('levels')
                ->nullOnDelete();


            $table->index(['subject_id', 'id', 'level_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('educational_resources', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropColumn('subject_id');

            $table->dropForeign(['level_id']);
            $table->dropColumn('level_id');
        });
    }
};
