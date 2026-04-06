<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Gère une requête entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. On récupère l'utilisateur connecté via le token Sanctum
        $user = $request->user();

        // 2. Vérification du privilège Admin
        // On vérifie le rôle en base ET on garde une sécurité sur l'email par défaut
        if ($user && ($user->role === 'admin' || $user->email === 'admin@silver.com')) {
            return $next($request);
        }

        // 3. Si ce n'est pas un admin, on renvoie une erreur 403 (Forbidden)
        return response()->json([
            'message' => 'Accès refusé. Cette zone est réservée à l\'administrateur Silver Fin.',
            'error' => 'unauthorized_admin'
        ], 403);
    }
}