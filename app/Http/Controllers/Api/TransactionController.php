<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Liste des transactions pour le Dashboard
     */
    public function index()
    {
        $transactions = Transaction::with('category')
            ->where('user_id', Auth::id())
            ->orderBy('transaction_date', 'desc')
            ->get();

        return response()->json($transactions);
    }

    /**
     * Enregistrement simple d'une entrée ou sortie
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'amount'           => 'required|numeric|min:0',
            'type'             => 'required|in:revenu,depense',
            'transaction_date' => 'required|date',
            'description'      => 'nullable|string|max:255',
        ]);

        $transaction = Transaction::create([
            'user_id'          => Auth::id(),
            'category_id'      => $request->category_id,
            'amount'           => $request->amount,
            'type'             => $request->type,
            'transaction_date' => $request->transaction_date,
            'description'      => $request->description,
        ]);

        return response()->json([
            'message' => 'Transaction enregistrée avec succès !',
            'data'    => $transaction
        ], 201);
    }

    /**
     * INNOVATION : Analyse Intelligente des flux financiers
     * Cette méthode permet de donner une visibilité claire sur la santé de l'entreprise.
     */
    public function getSummary()
    {
        $userId = Auth::id();

        // Totaux financiers
        $totalRevenu = Transaction::where('user_id', $userId)->where('type', 'revenu')->sum('amount');
        $totalDepense = Transaction::where('user_id', $userId)->where('type', 'depense')->sum('amount');
        
        $solde = $totalRevenu - $totalDepense;

        // Analyse du ratio (Innovation)
        $ratioDepense = ($totalRevenu > 0) ? ($totalDepense / $totalRevenu) * 100 : 0;

        return response()->json([
            'total_revenu'  => (float) $totalRevenu,
            'total_depense' => (float) $totalDepense,
            'solde'         => (float) $solde,
            'ratio_depense' => round($ratioDepense, 2),
            'sante_financiere' => [
                'status' => $solde >= 0 ? 'Positif' : 'Déficit',
                'alerte' => $ratioDepense > 80, // Vrai si les charges dépassent 80% des revenus
                'conseil' => $this->getSmartAdvice($solde, $ratioDepense)
            ]
        ]);
    }

    /**
     * Génère un conseil basé sur l'analyse (IA Simple)
     */
    private function getSmartAdvice($solde, $ratio)
    {
        if ($solde < 0) return "Alerte : Vous dépensez plus que vous ne gagnez. Réduisez vos charges urgentes.";
        if ($ratio > 70) return "Attention : Vos marges sont faibles. Surveillez vos achats de stock.";
        return "Votre gestion est saine. C'est le moment idéal pour épargner ou réinvestir.";
    }
}