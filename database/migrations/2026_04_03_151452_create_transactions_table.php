<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            
            // Relation Utilisateur avec Index (Accélère le Dashboard)
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->index(); 
            
            // Relation Catégorie (onDelete null pour ne pas perdre les données financières)
            $table->foreignId('category_id')
                  ->nullable() 
                  ->constrained()
                  ->onDelete('set null');
            
            // Type (Revenu/Dépense) - On garde tes noms en français pour coller à ton interface
            $table->enum('type', ['revenu', 'depense'])->index();
            
            // Montant (15 chiffres dont 2 après la virgule, idéal pour les gros chiffres)
            $table->decimal('amount', 15, 2); 
            
            $table->string('description')->nullable();
            
            // Date de l'opération avec Index pour les filtres par mois/année
            $table->date('transaction_date')->index();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};