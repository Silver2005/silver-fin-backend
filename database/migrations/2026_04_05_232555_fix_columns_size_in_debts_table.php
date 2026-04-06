<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Augmenter la taille des colonnes pour éviter l'erreur "Data truncated"
     */
    public function up(): void
    {
        Schema::table('debts', function (Blueprint $table) {
            // On utilise string() avec une taille de 50 pour être large
            // 'change()' nécessite d'avoir installé doctrine/dbal
            $table->string('type', 50)->change();
            $table->string('status', 50)->change();
        });
    }

    /**
     * Revenir en arrière (en cas de rollback)
     */
    public function down(): void
    {
        Schema::table('debts', function (Blueprint $table) {
            $table->string('type')->change();
            $table->string('status')->change();
        });
    }
};