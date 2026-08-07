@extends('layouts.admin')

@section('title', 'Rapport de revenus')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Rapport de revenus / abonnements</h1>
        <a href="{{ route('admin.reports.revenue.export', request()->only('from', 'to')) }}"
           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow">
            Exporter en CSV
        </a>
    </div>

    <!-- Filtre période -->
    <div class="bg-white shadow rounded-lg mb-6 p-4">
        <form method="GET" action="{{ route('admin.reports.revenue') }}" class="flex flex-wrap items-end gap-4">
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
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-6">
            <p class="text-sm text-green-100">Revenus sur la période</p>
            <p class="text-3xl font-bold">{{ number_format($total, 0, ',', ' ') }} {{ config('subscriptions.currency') }}</p>
        </div>
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-6">
            <p class="text-sm text-blue-100">Abonnements validés</p>
            <p class="text-3xl font-bold">{{ number_format($count) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Par type -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Revenus par type d'abonnement</h3>
            </div>
            <div class="p-6">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-gray-500">
                            <th class="pb-2">Type</th>
                            <th class="pb-2">Nb</th>
                            <th class="pb-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($byType as $row)
                            <tr class="border-t">
                                <td class="py-2 font-medium">{{ $row->type ?: 'Non défini' }}</td>
                                <td class="py-2">{{ $row->nb }}</td>
                                <td class="py-2 text-right">{{ number_format($row->total, 0, ',', ' ') }} {{ config('subscriptions.currency') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-4 text-center text-gray-500">Aucune donnée</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Par niveau -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Revenus par niveau</h3>
            </div>
            <div class="p-6">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-gray-500">
                            <th class="pb-2">Niveau</th>
                            <th class="pb-2">Nb</th>
                            <th class="pb-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($byLevel as $row)
                            <tr class="border-t">
                                <td class="py-2 font-medium">{{ $row->level_name }}</td>
                                <td class="py-2">{{ $row->nb }}</td>
                                <td class="py-2 text-right">{{ number_format($row->total, 0, ',', ' ') }} {{ config('subscriptions.currency') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-4 text-center text-gray-500">Aucune donnée</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tendance mensuelle -->
    <div class="bg-white border border-gray-200 rounded-lg mb-8">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Évolution mensuelle des revenus</h3>
        </div>
        <div class="p-6">
            <canvas id="revenueChart" height="90"></canvas>
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
    new Chart(document.getElementById('revenueChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: monthly.map(m => m.label),
            datasets: [{
                label: 'Revenus ({{ config('subscriptions.currency') }})',
                data: monthly.map(m => m.value),
                backgroundColor: '#10b981',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
});
</script>
@endpush
@endsection
