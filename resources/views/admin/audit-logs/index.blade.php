@extends('layouts.admin')

@section('title', 'Journal d\'audit')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Journal d'audit</h1>
    </div>

    <!-- Filtres -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-3 border-b border-gray-200">
            <h6 class="text-sm font-semibold text-gray-700">Filtres</h6>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('admin.audit-logs.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
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
                        <label for="action" class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                        <select id="action" name="action" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                            <option value="">Toutes</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                                    {{ $action }}
                                </option>
                            @endforeach
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
                        <a href="{{ route('admin.audit-logs.index') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg shadow text-sm">
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
            <h6 class="text-sm font-semibold text-gray-700">Actions enregistrées ({{ $logs->total() }})</h6>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Date</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Auteur</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Action</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Description</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="border-b hover:bg-gray-50 align-top">
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-600">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2 text-sm">
                                {{ $log->causer->name ?? 'Système' }}
                            </td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-800">{{ $log->action }}</span>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-700 max-w-xl">{{ $log->description }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500">{{ $log->ip_address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500">Aucune action enregistrée</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-gray-200">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
