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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();

            $table->date('starts_at');
            $table->date('ends_at');

            $table->enum('status', [
                'pending',
                'active',
                'expired',
                'cancelled'
            ])->default('pending');

            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('XAF');
            $table->timestamps();

            // ⚠️ Un seul abonnement actif par matière
            $table->unique(['user_id', 'subject_id', 'starts_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
