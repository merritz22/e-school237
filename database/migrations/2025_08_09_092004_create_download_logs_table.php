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
        Schema::create('download_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('resource_type', ['resource', 'evaluation']);
            $table->unsignedBigInteger('resource_id');
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->timestamp('downloaded_at')->useCurrent();

            $table->index(['resource_type', 'resource_id']);
            $table->index(['user_id', 'downloaded_at']);
            $table->index(['downloaded_at']);
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('download_logs');
    }
};