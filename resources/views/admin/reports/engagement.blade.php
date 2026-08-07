@extends('layouts.admin')

@section('title', 'Rapport d\'engagement')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Rapport d'engagement contenu</h1>
        <a href="{{ route('admin.reports.engagement.export', request()->only('from', 'to')) }}"
           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow">
            Exporter en CSV
        </a>
    </div>

    <!-- Filtre période -->
    <div class="bg-white shadow rounded-lg mb-6 p-4">
        <form method="GET" action="{{ route('admin.reports.engagement') }}" class="flex flex-wrap items-end gap-4">
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
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-6">
            <p class="text-sm text-blue-100">Téléchargements (période)</p>
            <p class="text-3xl font-bold">{{ number_format($totalDownloads) }}</p>
        </div>
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg p-6">
            <p class="text-sm text-purple-100">Utilisateurs distincts</p>
            <p class="text-3xl font-bold">{{ number_format($uniqueDownloaders) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Top ressources -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-base font-medium text-gray-900">Top supports pédagogiques</h3>
            </div>
            <div class="p-4">
                <ol class="space-y-2 text-sm">
                    @forelse($topResources as $i => $resource)
                        <li class="flex justify-between border-b pb-2">
                            <span class="truncate mr-2">{{ $i + 1 }}. {{ $resource->title }}</span>
                            <span class="font-semibold text-gray-700 shrink-0">{{ number_format($resource->downloads_count) }}</span>
                        </li>
                    @empty
                        <li class="text-gray-500 text-center py-4">Aucune donnée</li>
                    @endforelse
                </ol>
            </div>
        </div>

        <!-- Top sujets -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-base font-medium text-gray-900">Top sujets d'évaluation</h3>
            </div>
            <div class="p-4">
                <ol class="space-y-2 text-sm">
                    @forelse($topSubjects as $i => $subject)
                        <li class="flex justify-between border-b pb-2">
                            <span class="truncate mr-2">{{ $i + 1 }}. {{ $subject->title }}</span>
                            <span class="font-semibold text-gray-700 shrink-0">{{ number_format($subject->downloads_count) }}</span>
                        </li>
                    @empty
                        <li class="text-gray-500 text-center py-4">Aucune donnée</li>
                    @endforelse
                </ol>
            </div>
        </div>

        <!-- Top articles -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-base font-medium text-gray-900">Top articles</h3>
            </div>
            <div class="p-4">
                <ol class="space-y-2 text-sm">
                    @forelse($topArticles as $i => $article)
                        <li class="flex justify-between border-b pb-2">
                            <span class="truncate mr-2">{{ $i + 1 }}. {{ $article->title }}</span>
                            <span class="font-semibold text-gray-700 shrink-0">{{ number_format($article->views_count) }}</span>
                        </li>
                    @empty
                        <li class="text-gray-500 text-center py-4">Aucune donnée</li>
                    @endforelse
                </ol>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Gratuit vs premium ressources -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Supports : gratuit vs premium</h3>
            </div>
            <div class="p-6">
                <canvas id="resourcesFreeChart" height="120"></canvas>
            </div>
        </div>

        <!-- Gratuit vs premium sujets -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Sujets : gratuit vs premium</h3>
            </div>
            <div class="p-6">
                <canvas id="subjectsFreeChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <!-- Tendance mensuelle -->
    <div class="bg-white border border-gray-200 rounded-lg mb-8">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Téléchargements par mois</h3>
        </div>
        <div class="p-6">
            <canvas id="downloadsChart" height="90"></canvas>
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
    new Chart(document.getElementById('downloadsChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: monthly.map(m => m.label),
            datasets: [{
                label: 'Téléchargements',
                data: monthly.map(m => m.value),
                backgroundColor: '#3b82f6',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    function freeVsPremiumData(rows) {
        const free = rows.find(r => Number(r.is_free) === 1);
        const premium = rows.find(r => Number(r.is_free) === 0);
        return [free ? free.nb : 0, premium ? premium.nb : 0];
    }

    const resourcesData = @json($resourcesFreeVsPremium);
    new Chart(document.getElementById('resourcesFreeChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Gratuit', 'Premium'],
            datasets: [{ data: freeVsPremiumData(resourcesData), backgroundColor: ['#10b981', '#f59e0b'] }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    const subjectsData = @json($subjectsFreeVsPremium);
    new Chart(document.getElementById('subjectsFreeChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Gratuit', 'Premium'],
            datasets: [{ data: freeVsPremiumData(subjectsData), backgroundColor: ['#10b981', '#f59e0b'] }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
});
</script>
@endpush
@endsection
