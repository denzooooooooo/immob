<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Les administrateurs n'ont pas besoin d'abonnement
        if ($user && $user->role === 'admin') {
            return $next($request);
        }

        // Vérifier si l'utilisateur a un abonnement actif
        if ($user && !$user->hasActiveSubscription()) {
            return redirect()->route('subscriptions.plans')
                ->with('warning', 'Vous devez avoir un abonnement actif pour accéder à cette fonctionnalité.');
        }

        return $next($request);
    }
}
