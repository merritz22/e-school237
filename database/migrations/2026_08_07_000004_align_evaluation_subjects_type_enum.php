<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * La colonne `type` d'evaluation_subjects avait dérivé : la migration
 * d'origine déclarait un enum anglais ('exam','quiz','exercise','qcm')
 * jamais utilisé, tandis que la base réelle avait été modifiée directement
 * (hors migration) avec l'enum français réellement utilisé par
 * l'application ('Examen','Séquence','Travaux dirigés'). Cette migration
 * fait de cet état réel la source de vérité versionnée, pour que toute
 * nouvelle installation (migrate:fresh) obtienne le même schéma que la
 * base actuelle au lieu d'un schéma incompatible avec le code applicatif.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE evaluation_subjects MODIFY type ENUM('Examen', 'Séquence', 'Travaux dirigés') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE evaluation_subjects MODIFY type ENUM('exam', 'quiz', 'exercise', 'qcm') NOT NULL");
    }
};
