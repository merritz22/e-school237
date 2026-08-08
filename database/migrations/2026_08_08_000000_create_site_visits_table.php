<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            // Identifie un visiteur unique (session) pour dédupliquer les visites du même jour
            $table->string('visitor_key');
            $table->string('ip_address', 45)->nullable();
            $table->date('visited_on');
            $table->timestamp('created_at')->useCurrent();

            // Un seul enregistrement par visiteur et par jour
            $table->unique(['visitor_key', 'visited_on']);
            $table->index('visited_on');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_visits');
    }
};
