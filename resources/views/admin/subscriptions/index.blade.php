@extends('layouts.admin')

@section('title', 'Gestion des abonnements')

@section('content')
<div class="bg-white p-5">
    <!-- Header -->
    <div class="border-b border-gray-200 pb-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gestion des abonnements</h1>
                <p class="text-gray-600 mt-1">Gérez tous les abonnements depuis cette interface</p>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white border border-gray-200 rounded-lg mb-6">
        <div class="px-4 py-3 border-b border-gray-200">
            <h6 class="text-sm font-semibold text-gray-700">Filtres</h6>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('admin.subscriptions.index') }}" class="flex flex-wrap items-end gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select id="status" name="status" class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expiré</option>
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow text-sm">
                        Filtrer
                    </button>
                    <a href="{{ route('admin.subscriptions.index') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg shadow text-sm">
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!--e liste des abonnements -->

    @component('components.adminsubscription', ['subscriptions' =>$subscriptions])
    @endcomponent
</div>

@endsection