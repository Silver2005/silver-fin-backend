<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * On utilise Schema::table (pour modifier) et non Schema::create
     */
    public function up(): void
    {
        Schema::table('debts', function (Blueprint $table) {
            // On vérifie si la colonne n'existe pas déjà pour éviter les erreurs
            if (!Schema::hasColumn('debts', 'notes')) {
                $table->text('notes')->nullable()->after('due_date');
            }
        });
    }

    /**
     * Annuler les changements
     */
    public function down(): void
    {
        Schema::table('debts', function (Blueprint $table) {
            if (Schema::hasColumn('debts', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};