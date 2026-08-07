<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Http\Request;

class LoginHistoryController extends Controller
{
    public function index(Request $request)
    {
        // Les comptes admin ne doivent pas apparaître dans l'historique de connexion.
        // On exclut par email (et pas seulement par user_id) car une tentative
        // échouée peut n'avoir aucun user_id résolu tout en portant l'email d'un admin.
        $adminEmails = User::where('role', 'admin')->pluck('email');

        $query = LoginHistory::with('user')
            ->whereNotIn('email', $adminEmails)
            ->latest('created_at');

        if ($request->filled('user_id')) {
            $query->where('user_id', (int) $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('provider')) {
            $query->where('provider', $request->provider);
        }

        $query->dateRange($request->input('from'), $request->input('to'));

        $logins = $query->paginate(25)->withQueryString();

        $users = User::where('role', '!=', 'admin')->orderBy('name')->get(['id', 'name', 'email']);

        $failedLast24h = LoginHistory::where('status', 'failed')
            ->whereNotIn('email', $adminEmails)
            ->where('created_at', '>=', now()->subDay())
            ->count();

        return view('admin.login-history.index', compact('logins', 'users', 'failedLast24h'));
    }
}
