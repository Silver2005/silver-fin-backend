<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('debts', function (Blueprint $table) {
            // On change le type de la colonne pour accepter les bonnes valeurs
            // Note: Il faut installer 'doctrine/dbal' si ce n'est pas fait, 
            // sinon utilise un simple VARCHAR pour plus de souplesse.
            $table->string('type', 20)->change(); 
        });
    }

    public function down(): void
    {
        Schema::table('debts', function (Blueprint $table) {
            $table->string('type')->change();
        });
    }
};