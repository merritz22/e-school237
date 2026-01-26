<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;

class CheckSubjectSubscription
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

        // 2. Récupérer le subject (depuis la route)
        $subject = $request->route('subject');

        if (!$subject) {
            abort(404, 'Ressource introuvable.');
        }

        // Si l'utilisateur es admin alors pas de vérification
        if(Auth::user()->role !== 'admin'){
            // 3. Vérifier l'abonnement
            $hasSubscription = Subscription::where('user_id', Auth::id())
                ->where('subject_id', $subject->subject_id)
                ->where('level_id', $subject->level_id)
                ->exists();

            if (!$hasSubscription && !$subject->is_free) {
                return redirect()
                    ->route('subscriptions.index')
                    ->with('error', 'Vous devez avoir un abonnement actif pour accéder à cette ressource.');
            }
        }

        // 4. Autoriser l'accès
        return $next($request);
    }
}
