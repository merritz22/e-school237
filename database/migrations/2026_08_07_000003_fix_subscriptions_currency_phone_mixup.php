<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Le champ `currency` a toujours reçu le numéro de téléphone de dépôt
     * au lieu d'un code devise. On introduit un vrai champ `phone`, on y
     * recopie la valeur historiquement mal placée dans `currency`, puis on
     * remet `currency` à la vraie devise de l'application. Aucune donnée
     * n'est perdue : le numéro de téléphone reste lisible, juste au bon
     * endroit.
     */
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('currency');
        });

        DB::table('subscriptions')->whereNotNull('currency')->update([
            'phone' => DB::raw('currency'),
        ]);

        DB::table('subscriptions')->update([
            'currency' => config('subscriptions.currency', 'XAF'),
        ]);
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
};
