<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    // Ajout de 'type' (revenue/expense) si tu ne l'as pas déjà dans ta table
    protected $fillable = [
        'user_id', 
        'category_id', 
        'amount', 
        'type', // Très important pour séparer + et -
        'description', 
        'transaction_date'
    ];

    // Dates automatiques pour faciliter le filtrage par mois
    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2'
    ];

    // --- RELATIONS ---

    // Chaque transaction appartient à un utilisateur
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Chaque transaction est classée (Ex: "Vente Boutique" ou "Loyer")
    public function category() {
        return $this->belongsTo(Category::class);
    }

    // --- SCOPES (Raccourcis pour le Dashboard) ---

    // Utilisation : Transaction::forUser(auth()->id())->revenues()->sum('amount')
    public function scopeRevenues($query) {
        return $query->where('type', 'revenue');
    }

    public function scopeExpenses($query) {
        return $query->where('type', 'expense');
    }
}