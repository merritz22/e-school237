@extends('layouts.admin')

@section('title', 'Historique de connexion')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Historique de connexion</h1>
        @if($failedLast24h > 0)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                {{ $failedLast24h }} échec(s) de connexion sur les dernières 24h
            </span>
        @endif
    </div>

    <!-- Filtres -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-3 border-b border-gray-200">
            <h6 class="text-sm font-semibold text-gray-700">Filtres</h6>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('admin.login-history.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Utilisateur</label>
                        <select id="user_id" name="user_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                            <option value="">Tous</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ (string) request('user_id') === (string) $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                        <select id="status" name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                            <option value="">Tous</option>
                            <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Réussie</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Échouée</option>
                        </select>
                    </div>
                    <div>
                        <label for="provider" class="block text-sm font-medium text-gray-700 mb-1">Méthode</label>
                        <select id="provider" name="provider" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                            <option value="">Toutes</option>
                            <option value="password" {{ request('provider') === 'password' ? 'selected' : '' }}>Email / mot de passe</option>
                            <option value="google" {{ request('provider') === 'google' ? 'selected' : '' }}>Google</option>
                        </select>
                    </div>
                    <div>
                        <label for="from" class="block text-sm font-medium text-gray-700 mb-1">Du</label>
                        <input type="date" id="from" name="from" value="{{ request('from') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                    </div>
                    <div>
                        <label for="to" class="block text-sm font-medium text-gray-700 mb-1">Au</label>
                        <input type="date" id="to" name="to" value="{{ request('to') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow text-sm">
                            Filtrer
                        </button>
                        <a href="{{ route('admin.login-history.index') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg shadow text-sm">
                            Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-3 border-b border-gray-200">
            <h6 class="text-sm font-semibold text-gray-700">Connexions ({{ $logins->total() }})</h6>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Date</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Utilisateur</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Email saisi</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Méthode</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Statut</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logins as $login)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-600">{{ $login->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2 text-sm">
                                @if($login->user)
                                    <a href="{{ route('admin.users.show', $login->user) }}" class="text-indigo-600 hover:underline">{{ $login->user->name }}</a>
                                @else
                                    <span class="text-gray-400">Inconnu</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-600">{{ $login->email }}</td>
                            <td class="px-4 py-2 text-sm text-gray-600">{{ $login->provider === 'google' ? 'Google' : 'Email / mot de passe' }}</td>
                            <td class="px-4 py-2">
                                @if($login->status === 'success')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Réussie</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Échouée</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-500">{{ $login->ip_address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500">Aucune connexion enregistrée</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logins->hasPages())
            <div class="p-4 border-t border-gray-200">
                {{ $logins->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
