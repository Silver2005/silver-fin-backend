<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Debt;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DebtController extends Controller
{
    /**
     * Liste toutes les dettes de l'utilisateur connecté
     */
    public function index()
    {
        return Debt::where('user_id', Auth::id())
            ->orderBy('due_date', 'asc')
            ->get();
    }

    /**
     * Enregistrer une nouvelle dette
     */
    public function store(Request $request)
    {
        // 1. Mise à jour de la validation pour inclure contact_email
        $validated = $request->validate([
            'contact_name'  => 'required|string|max:100',
            'contact_email' => 'nullable|email|max:255', // AJOUTÉ
            'amount'        => 'required|numeric|min:1',
            'type'          => 'required|in:a_recevoir,a_payer',
            'due_date'      => 'required|date',
            'notes'         => 'nullable|string'
        ]);

        // 2. Enregistrement en incluant le champ email
        $debt = Debt::create([
            'user_id'       => Auth::id(),
            'contact_name'  => $validated['contact_name'],
            'contact_email' => $validated['contact_email'] ?? null, // AJOUTÉ
            'amount'        => $validated['amount'],
            'type'          => $validated['type'],
            'due_date'      => $validated['due_date'],
            'notes'         => $validated['notes'],
            'status'        => 'en_attente'
        ]);

        return response()->json($debt, 201);
    }

    /**
     * Mettre à jour une dette (Nécessaire pour le bouton Modifier de React)
     */
    public function update(Request $request, $id)
    {
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'contact_name'  => 'required|string|max:100',
            'contact_email' => 'nullable|email|max:255', // AJOUTÉ
            'amount'        => 'required|numeric|min:1',
            'type'          => 'required|in:a_recevoir,a_payer',
            'due_date'      => 'required|date',
            'notes'         => 'nullable|string'
        ]);

        $debt->update($validated);

        return response()->json($debt);
    }

    /**
     * Marquer une dette comme payée
     */
    public function markAsPaid($id)
    {
        return DB::transaction(function () use ($id) {
            $debt = Debt::where('user_id', Auth::id())->findOrFail($id);

            if ($debt->status === 'paye') {
                return response()->json(['message' => 'Dette déjà réglée'], 400);
            }

            $debt->update(['status' => 'paye']);

            Transaction::create([
                'user_id'          => Auth::id(),
                'type'             => $debt->type === 'a_recevoir' ? 'revenu' : 'depense',
                'amount'           => $debt->amount,
                'description'      => "Règlement dette : " . $debt->contact_name,
                'transaction_date' => now(),
                'category_id'      => null 
            ]);

            return response()->json([
                'message' => 'Dette réglée avec succès !',
                'debt' => $debt
            ]);
        });
    }

    /**
     * Supprimer une dette
     */
    public function destroy($id)
    {
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);
        $debt->delete();
        
        return response()->json(['message' => 'Dette supprimée']);
    }
}