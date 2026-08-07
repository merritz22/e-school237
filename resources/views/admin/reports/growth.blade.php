@extends('layouts.admin')

@section('title', 'Rapport de croissance')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Rapport de croissance des utilisateurs</h1>
        <a href="{{ route('admin.reports.growth.export', request()->only('from', 'to')) }}"
           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow">
            Exporter en CSV
        </a>
    </div>

    <!-- Filtre période -->
    <div class="bg-white shadow rounded-lg mb-6 p-4">
        <form method="GET" action="{{ route('admin.reports.growth') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label for="from" class="block text-sm font-medium text-gray-700 mb-1">Du</label>
                <input type="date" id="from" name="from" value="{{ $from->toDateString() }}"
                       class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
            </div>
            <div>
                <label for="to" class="block text-sm font-medium text-gray-700 mb-1">Au</label>
                <input type="date" id="to" name="to" value="{{ $to->toDateString() }}"
                       class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
            </div>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow text-sm">
                Appliquer
            </button>
        </form>
    </div>

    <!-- Cartes résumé -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-6">
            <p class="text-sm text-blue-100">Nouveaux inscrits (période)</p>
            <p class="text-3xl font-bold">{{ number_format($newUsers) }}</p>
        </div>
        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-lg p-6">
            <p class="text-sm text-indigo-100">Total utilisateurs</p>
            <p class="text-3xl font-bold">{{ number_format($totalUsers) }}</p>
        </div>
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-6">
            <p class="text-sm text-green-100">Comptes actifs</p>
            <p class="text-3xl font-bold">{{ number_format($activeUsers) }}</p>
        </div>
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg p-6">
            <p class="text-sm text-purple-100">Profils complets</p>
            <p class="text-3xl font-bold">{{ number_format($profileCompleteCount) }}</p>
            <p class="text-xs text-purple-200">{{ $totalUsers > 0 ? round(($profileCompleteCount / $totalUsers) * 100, 1) : 0 }}% du total</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Par niveau -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Répartition par niveau</h3>
            </div>
            <div class="p-6">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-gray-500">
                            <th class="pb-2">Niveau</th>
                            <th class="pb-2 text-right">Utilisateurs</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($byLevel as $row)
                            <tr class="border-t">
                                <td class="py-2 font-medium">{{ $row->level_name }}</td>
                                <td class="py-2 text-right">{{ $row->nb }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="py-4 text-center text-gray-500">Aucune donnée</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Par rôle -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Répartition par rôle</h3>
            </div>
            <div class="p-6">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-gray-500">
                            <th class="pb-2">Rôle</th>
                            <th class="pb-2 text-right">Utilisateurs</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($byRole as $row)
                            <tr class="border-t">
                                <td class="py-2 font-medium">{{ ucfirst($row->role) }}</td>
                                <td class="py-2 text-right">{{ $row->nb }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tendance mensuelle -->
    <div class="bg-white border border-gray-200 rounded-lg mb-8">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Nouvelles inscriptions par mois</h3>
        </div>
        <div class="p-6">
            <canvas id="growthChart" height="90"></canvas>
        </div>
    </div>
</div>

@push('styles')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const monthly = @json($monthly);
    new Chart(document.getElementById('growthChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: monthly.map(m => m.label),
            datasets: [{
                label: 'Nouveaux utilisateurs',
                data: monthly.map(m => m.value),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
});
</script>
@endpush
@endsection
