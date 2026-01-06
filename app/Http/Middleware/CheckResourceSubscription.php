<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;

class CheckResourceSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Vous devez être connecté pour accéder à cette ressource.');
        }

        // 2. Récupérer le resource (depuis la route)
        $resource = $request->route('resource');
        
        if (!$resource) {
            abort(404, 'Ressource introuvable.');
        }
        
        // 3. Vérifier l'abonnement
        $hasSubscription = Subscription::where('user_id', Auth::id())
        ->where('subject_id', $resource->subject_id)
        ->where('level_id', $resource->level_id)
        ->exists();
        
        if (!$hasSubscription) {
            return redirect()
                ->route('subscriptions.index')
                ->with('error', 'Vous devez avoir un abonnement actif pour accéder à cette ressource.');
        }

        // 4. Autoriser l'accès
        return $next($request);
    }
}
