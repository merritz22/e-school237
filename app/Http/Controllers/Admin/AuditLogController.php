<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('causer')->latest('created_at');

        if ($request->filled('user_id')) {
            $query->causedBy((int) $request->user_id);
        }

        if ($request->filled('action')) {
            $query->action($request->action);
        }

        $query->dateRange($request->input('from'), $request->input('to'));

        $logs = $query->paginate(25)->withQueryString();

        $actions = AuditLog::select('action')->distinct()->orderBy('action')->pluck('action');
        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.audit-logs.index', compact('logs', 'actions', 'users'));
    }
}
