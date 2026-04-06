<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * SUPPRESSION DU CONSTRUCTEUR : 
     * On laisse le fichier routes/api.php gérer la protection Sanctum.
     */

    /**
     * ÉTAPE 1 : Récupérer les catégories pour le formulaire React
     */
    public function getCategories()
    {
        try {
            // Utilise Eloquent pour récupérer toutes les catégories
            $categories = Category::orderBy('name', 'asc')->get();
            return response()->json($categories);
        } catch (\Exception $e) {
            Log::error("Erreur Categories: " . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur'], 500);
        }
    }

    /**
     * ÉTAPE 2 : Récupère les données consolidées pour le Dashboard
     */
    public function getSummary()
    {
        try {
            $userId = Auth::id();

            $totalRevenues = Transaction::where('user_id', $userId)
                ->where('type', 'revenu')
                ->sum('amount');

            $totalExpenses = Transaction::where('user_id', $userId)
                ->where('type', 'depense')
                ->sum('amount');

            $recentTransactions = Transaction::where('user_id', $userId)
                ->with('category')
                ->orderBy('transaction_date', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'revenues' => (float)$totalRevenues,
                'expenses' => (float)$totalExpenses,
                'balance'  => (float)($totalRevenues - $totalExpenses),
                'recent_transactions' => $recentTransactions
            ], 200);

        } catch (\Exception $e) {
            Log::error("Erreur Dashboard: " . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la récupération des données'], 500);
        }
    }

    /**
     * ÉTAPE 3 : Lister toutes les transactions
     */
    public function index()
    {
        return response()->json(
            Transaction::where('user_id', Auth::id())
                ->with('category')
                ->orderBy('transaction_date', 'desc')
                ->get()
        );
    }

    /**
     * ÉTAPE 4 : Enregistrer une nouvelle transaction
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'type'             => 'required|in:revenu,depense',
            'amount'           => 'required|numeric|min:0.01',
            'description'      => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        try {
            $transaction = Transaction::create([
                'user_id'          => Auth::id(),
                'category_id'      => $validated['category_id'],
                'type'             => $validated['type'],
                'amount'           => $validated['amount'],
                'description'      => $validated['description'],
                'transaction_date' => $validated['transaction_date'],
            ]);

            return response()->json($transaction->load('category'), 201);

        } catch (\Exception $e) {
            Log::error("Erreur Store Transaction: " . $e->getMessage());
            return response()->json(['message' => "Impossible d'enregistrer"], 500);
        }
    }

    /**
     * ÉTAPE 5 : Supprimer une transaction
     */
    public function destroy($id)
    {
        try {
            $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);
            $transaction->delete();
            return response()->json(['message' => 'Transaction supprimée']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Transaction introuvable'], 404);
        }
    }
}