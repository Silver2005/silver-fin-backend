<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DebtController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Debt;

/*
|--------------------------------------------------------------------------
| API Routes - SILVER FIN v1.4
|--------------------------------------------------------------------------
*/

// --- ROUTES PUBLIQUES ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- ROUTES PROTÉGÉES ---
Route::middleware('auth:sanctum')->group(function () {
    
    // 1. UTILISATEUR & PROFIL
    Route::get('/user', [AuthController::class, 'profile']);
    Route::put('/user', [AuthController::class, 'updateProfile']);

    // 2. TRANSACTIONS
    Route::get('/categories', [TransactionController::class, 'getCategories']);
    Route::get('/transactions/summary', [TransactionController::class, 'getSummary']); 
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);

    // 3. DETTES
    Route::get('/debts', [DebtController::class, 'index']);
    Route::post('/debts', [DebtController::class, 'store']);
    Route::patch('/debts/{id}/pay', [DebtController::class, 'markAsPaid']); 
    Route::delete('/debts/{id}', [DebtController::class, 'destroy']);

    // --- ZONE ADMIN (Correction de l'URL) ---
    // On s'assure que le middleware est bien appelé
    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('/admin/stats', function () {
            return response()->json([
                'total_users' => User::count(),
                'total_transactions' => Transaction::count(),
                'total_debts' => Debt::count(),
                'total_volume' => Transaction::sum('amount') ?? 0,
                'recent_users' => User::latest()->take(5)->get(['name', 'email', 'created_at']),
                'system_status' => 'Stable',
                'server_time' => now()->toDateTimeString(),
                'admin_identity' => 'admin@silver.com'
            ]);
        });
    });

    // 4. LOGOUT
    Route::post('/logout', [AuthController::class, 'logout']);
});