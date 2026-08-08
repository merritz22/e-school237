@extends('layouts.admin')

@section('title', 'Fréquentation du site')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Fréquentation du site</h1>
        <a href="{{ route('admin.reports.visits.export', request()->only('from', 'to')) }}"
           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow">
            Exporter en CSV
        </a>
    </div>

    <!-- Cartes résumé -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-lg p-6">
            <p class="text-sm text-indigo-100">Visiteurs aujourd'hui</p>
            <p class="text-3xl font-bold">{{ number_format($today) }}</p>
        </div>
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-6">
            <p class="text-sm text-blue-100">Visiteurs cette semaine</p>
            <p class="text-3xl font-bold">{{ number_format($thisWeek) }}</p>
        </div>
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg p-6">
            <p class="text-sm text-purple-100">Visiteurs ce mois</p>
            <p class="text-3xl font-bold">{{ number_format($thisMonth) }}</p>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white shadow rounded-lg mb-6 p-4">
        <form method="GET" action="{{ route('admin.reports.visits') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label for="granularity" class="block text-sm font-medium text-gray-700 mb-1">Vue</label>
                <select id="granularity" name="granularity" class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                    <option value="day" {{ $granularity === 'day' ? 'selected' : '' }}>Par jour</option>
                    <option value="week" {{ $granularity === 'week' ? 'selected' : '' }}>Par semaine</option>
                    <option value="month" {{ $granularity === 'month' ? 'selected' : '' }}>Par mois</option>
                </select>
            </div>
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
            <a href="{{ route('admin.reports.visits') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg shadow text-sm">
                Réinitialiser
            </a>
        </form>
    </div>

    <!-- Total sur la période -->
    <div class="bg-white border border-gray-200 rounded-lg mb-8 p-6">
        <p class="text-sm text-gray-500">Total visiteurs sur la période sélectionnée</p>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($periodTotal) }}</p>
    </div>

    <!-- Graphique -->
    <div class="bg-white border border-gray-200 rounded-lg mb-8">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                Évolution des visites —
                {{ $granularity === 'day' ? 'par jour' : ($granularity === 'week' ? 'par semaine' : 'par mois') }}
            </h3>
        </div>
        <div class="p-6">
            <canvas id="visitsChart" height="90"></canvas>
        </div>
    </div>
</div>

@push('styles')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const buckets = @json($buckets);
    new Chart(document.getElementById('visitsChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: buckets.map(b => b.label),
            datasets: [{
                label: 'Visiteurs',
                data: buckets.map(b => b.value),
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
