<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filtrage par rôle
        if ($request->filled('role')) {
            $query->byRole($request->role);
        }

        // Filtrage par statut (actif/inactif)
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Recherche par nom/email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15);
        
        $roles = ['admin', 'moderator', 'author', 'user'];

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = ['admin', 'moderator', 'author', 'user'];
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,moderator,author,user',
            'bio' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $user = User::create($validated);

        return redirect()->route('admin.users.index')
                        ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['articles', 'evaluationSubjects', 'educationalResources', 'forumTopics', 'forumReplies']);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = ['admin', 'moderator', 'author', 'user'];
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,moderator,author,user',
            'bio' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $user->update($validated);

        return redirect()->route('admin.users.index')
                        ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Empêcher la suppression du dernier admin
        if ($user->isAdmin() && User::byRole('admin')->count() === 1) {
            return redirect()->route('admin.users.index')
                            ->with('error', 'Impossible de supprimer le dernier administrateur.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Suspend the specified user.
     */
    public function suspend(User $user)
    {
        if ($user->isAdmin() && User::byRole('admin')->where('is_active', true)->count() === 1) {
            return redirect()->route('admin.users.index')
                            ->with('error', 'Impossible de suspendre le dernier administrateur actif.');
        }

        $user->update(['is_active' => false]);

        return redirect()->route('admin.users.index')
                        ->with('success', 'Utilisateur suspendu avec succès.');
    }

    /**
     * Activate the specified user.
     */
    public function activate(User $user)
    {
        $user->update(['is_active' => true]);

        return redirect()->route('admin.users.index')
                        ->with('success', 'Utilisateur activé avec succès.');
    }

    /**
     * Update the role of the specified user.
     */
    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,moderator,author,user',
        ]);

        // Empêcher la modification du rôle du dernier admin
        if ($user->isAdmin() && $validated['role'] !== 'admin' && User::byRole('admin')->count() === 1) {
            return redirect()->route('admin.users.index')
                            ->with('error', 'Impossible de modifier le rôle du dernier administrateur.');
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
                        ->with('success', 'Rôle de l\'utilisateur mis à jour avec succès.');
    }
}