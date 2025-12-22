<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.* @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role  Le rôle attendu (ex: admin)
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     *
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        // Vérifie que l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Vérifie que le rôle correspond
        if (Auth::user()->role !== $role) {
            abort(403, 'Accès refusé.');
        }

        return $next($request);
    }
}
