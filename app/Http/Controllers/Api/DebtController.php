<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Debt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebtController extends Controller
{
    // Liste toutes les dettes (A encaisser et A payer)
    public function index()
    {
        $debts = Debt::where('user_id', Auth::id())
            ->orderBy('due_date', 'asc')
            ->get();

        return response()->json($debts);
    }

    // Enregistrer une nouvelle dette ou facture impayée
    public function store(Request $request)
    {
        $request->validate([
            'contact_name' => 'required|string|max:255',
            'amount'       => 'required|numeric|min:1',
            'type'         => 'required|in:a_encaisser,a_payer',
            'due_date'     => 'nullable|date',
        ]);

        $debt = Debt::create([
            'user_id'      => Auth::id(),
            'contact_name' => $request->contact_name,
            'amount'       => $request->amount,
            'type'         => $request->type,
            'due_date'     => $request->due_date,
            'status'       => 'non_paye',
        ]);

        return response()->json(['message' => 'Dette enregistrée', 'data' => $debt], 201);
    }

    // Marquer une dette comme payée (L'innovation de simplification)
    public function markAsPaid($id)
    {
        $debt = Debt::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $debt->update(['status' => 'paye']);

        return response()->json(['message' => 'La dette a été soldée avec succès !']);
    }
}