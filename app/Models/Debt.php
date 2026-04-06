<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    use HasFactory;

    /**
     * Nom de la table associée
     */
    protected $table = 'debts';

    /**
     * Les attributs qui peuvent être assignés en masse.
     * Ajout de 'contact_email' pour la gestion des rappels automatiques.
     */
    protected $fillable = [
        'user_id',
        'contact_name', 
        'contact_email', // <-- Nouveau champ ajouté
        'amount',
        'type',
        'due_date',
        'status',
        'notes'
    ];

    /**
     * Relation : Une dette appartient à un utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Casts pour transformer automatiquement les types de données
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope pour récupérer uniquement les dettes en attente (Utile pour les rappels)
     */
    public function scopeEnAttente($query)
    {
        return $query->where('status', 'en_attente');
    }
}