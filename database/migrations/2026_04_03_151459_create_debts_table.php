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
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            // Lien avec l'utilisateur (le commerçant ou l'entrepreneur) [cite: 3]
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Nom du contact (Client ou Fournisseur)
            $table->string('contact_name');
            
            // Montant de la dette ou de la facture 
            $table->decimal('amount', 15, 2);
            
            // Type : 'a_encaisser' (créance client) ou 'a_payer' (dette fournisseur) 
            $table->enum('type', ['a_encaisser', 'a_payer']);
            
            // Date d'échéance pour les alertes intelligentes [cite: 17]
            $table->date('due_date')->nullable();
            
            // Statut du paiement pour le suivi en temps réel [cite: 14]
            $table->enum('status', ['non_paye', 'partiel', 'paye'])->default('non_paye');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};