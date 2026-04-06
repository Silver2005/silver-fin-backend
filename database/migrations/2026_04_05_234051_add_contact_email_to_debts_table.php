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
        Schema::table('debts', function (Blueprint $table) {
            // Ajoute la colonne email juste après le nom du contact
            // On la met en 'nullable' car tout le monde n'a pas forcément d'email
            $table->string('contact_email')->nullable()->after('contact_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('debts', function (Blueprint $table) {
            // Supprime la colonne si on fait un rollback
            $table->dropColumn('contact_email');
        });
    }
};