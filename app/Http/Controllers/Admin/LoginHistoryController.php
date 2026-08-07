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
        $query = LoginHistory::with('user')->latest('created_at');

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

        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        $failedLast24h = LoginHistory::where('status', 'failed')
            ->where('created_at', '>=', now()->subDay())
            ->count();

        return view('admin.login-history.index', compact('logins', 'users', 'failedLast24h'));
    }
}
