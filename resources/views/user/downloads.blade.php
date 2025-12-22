@extends('layouts.app')

@section('title', 'Historique des téléchargements')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold mb-6">Historique des téléchargements</h1>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <div class="text-indigo-600 text-3xl font-semibold">{{ $stats['total'] }}</div>
            <div class="mt-2 text-gray-600">Total téléchargements</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <div class="text-indigo-600 text-3xl font-semibold">{{ $stats['this_month'] }}</div>
            <div class="mt-2 text-gray-600">Ce mois-ci</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <div class="text-indigo-600 text-3xl font-semibold">{{ $stats['subjects'] }}</div>
            <div class="mt-2 text-gray-600">Sujets</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <div class="text-indigo-600 text-3xl font-semibold">{{ $stats['supports'] }}</div>
            <div class="mt-2 text-gray-600">Supports</div>
        </div>
    </div>

    <!-- Filtres -->
    <form method="GET" action="{{ route('user.downloads') }}" class="mb-6 bg-white p-6 rounded-lg shadow grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label for="type" class="block mb-1 font-semibold">Type</label>
            <select id="type" name="type" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                <option value="">Tous</option>
                <option value="subject" {{ request('type') === 'subject' ? 'selected' : '' }}>Sujet</option>
                <option value="support" {{ request('type') === 'support' ? 'selected' : '' }}>Support</option>
            </select>
        </div>
        <div>
            <label for="date_from" class="block mb-1 font-semibold">Date de début</label>
            <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-indigo-500 focus:outline-none">
        </div>
        <div>
            <label for="date_to" class="block mb-1 font-semibold">Date de fin</label>
            <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-indigo-500 focus:outline-none">
        </div>
        <div class="flex items-end space-x-2">
            <button type="submit" class="bg-indigo-600 text-white rounded px-6 py-2 hover:bg-indigo-700 font-semibold">Filtrer</button>
            <a href="{{ route('user.downloads') }}" class="text-gray-700 hover:underline">Réinitialiser</a>
        </div>
    </form>

    <!-- Table des téléchargements -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($downloads as $download)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $download->downloadable->title ?? 'Fichier supprimé' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ class_basename($download->downloadable_type) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $download->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">Aucun téléchargement trouvé</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $downloads->withQueryString()->links() }}
    </div>
</div>
@endsection
