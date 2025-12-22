@extends('layouts.admin')

@section('title', 'Statistiques des articles')

@section('content')
<div class="bg-white p-5">
    <!-- Header -->
    <div class="border-b border-gray-200 pb-4 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Statistiques des articles</h1>
                <p class="text-gray-600 mt-1">Analyse détaillée de vos articles et leur performance</p>
            </div>
            
            {{-- <a href="{{ route('admin.articles.index') }}" 
               class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour
            </a> --}}
        </div>
    </div>

    <!-- Global Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-400 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-blue-100">Total articles</p>
                    <p class="text-3xl font-bold">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-400 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-green-100">Articles publiés</p>
                    <p class="text-3xl font-bold">{{ number_format($stats['published']) }}</p>
                    <p class="text-xs text-green-200">{{ $stats['total'] > 0 ? round(($stats['published'] / $stats['total']) * 100, 1) : 0 }}% du total</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-400 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-yellow-100">Brouillons</p>
                    <p class="text-3xl font-bold">{{ number_format($stats['drafts']) }}</p>
                    <p class="text-xs text-yellow-200">{{ $stats['total'] > 0 ? round(($stats['drafts'] / $stats['total']) * 100, 1) : 0 }}% du total</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-400 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-purple-100">Total vues</p>
                    <p class="text-3xl font-bold">{{ number_format($stats['total_views']) }}</p>
                    <p class="text-xs text-purple-200">{{ $stats['published'] > 0 ? number_format(round($stats['total_views'] / $stats['published'])) : 0 }} vues/article</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top Articles -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Articles les plus populaires</h3>
            </div>
            
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($stats['popular_articles'] as $index => $article)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-4">
                                    {{ $index + 1 }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-sm font-medium text-gray-900 truncate">
                                        <a href="{{ route('admin.articles.edit', $article) }}" class="hover:text-blue-600">
                                            {{ $article->title }}
                                        </a>
                                    </h4>
                                    <p class="text-xs text-gray-500">{{ $article->category->name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-semibold text-gray-900">{{ number_format($article->views_count) }}</div>
                                <div class="text-xs text-gray-500">vues</div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Aucun article avec des vues</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Articles -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Articles récents</h3>
            </div>
            
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($stats['recent_articles'] as $article)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 {{ $article->isPublished() ? 'bg-green-500' : 'bg-yellow-500' }} text-white rounded-full flex items-center justify-center mr-4">
                                    @if($article->isPublished())
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-sm font-medium text-gray-900 truncate">
                                        <a href="{{ route('admin.articles.edit', $article) }}" class="hover:text-blue-600">
                                            {{ $article->title }}
                                        </a>
                                    </h4>
                                    <p class="text-xs text-gray-500">{{ $article->category->name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-gray-500">
                                    {{ $article->published_at ? $article->published_at->format('d/m/Y') : $article->created_at->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $article->published_at ? $article->published_at->format('H:i') : $article->created_at->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Aucun article récent</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Stats -->
    <div class="mt-8 bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Répartition par catégories</h3>
        </div>
        
        <div class="p-6">
            <div class="space-y-4">
                @forelse($stats['categories_stats'] as $category)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            @if($category->icon)
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="{{ $category->icon }} text-blue-600"></i>
                                </div>
                            @endif
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $category->name }}</h4>
                                @if($category->description)
                                    <p class="text-xs text-gray-500">{{ Str::limit($category->description, 50) }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <div class="text-sm font-semibold text-gray-900">{{ $category->articles_count }}</div>
                                <div class="text-xs text-gray-500">{{ Str::plural('article', $category->articles_count) }}</div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" 
                                     style="width: {{ $stats['published'] > 0 ? ($category->articles_count / $stats['published']) * 100 : 0 }}%"></div>
                            </div>
                            
                            <div class="text-xs text-gray-500 w-12 text-right">
                                {{ $stats['published'] > 0 ? round(($category->articles_count / $stats['published']) * 100, 1) : 0 }}%
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Aucune catégorie avec des articles</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Performance Charts -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Publication Timeline -->
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Publications par mois</h3>
            </div>
            
            <div class="p-6">
                <canvas id="publicationChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Views Distribution -->
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Répartition des vues</h3>
            </div>
            
            <div class="p-6">
                <canvas id="viewsChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    {{-- <div class="mt-8 flex flex-col sm:flex-row gap-4">
        <a href="{{ route('admin.articles.export') }}" 
           class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Exporter les données
        </a>
        
        <a href="{{ route('admin.articles.create') }}" 
           class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Créer un nouvel article
        </a>
        
        <button onclick="refreshStats()" 
                class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Actualiser les statistiques
        </button>
    </div> --}}
</div>

@push('styles')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Publication Timeline Chart
    const publicationCtx = document.getElementById('publicationChart').getContext('2d');
    
    // Get last 6 months data
    

    new Chart(publicationCtx, {
        type: 'line',
        data: {
            labels: publicationData.map(item => {
                const date = new Date(item.month + '-01');
                return date.toLocaleDateString('fr-FR', { month: 'short', year: 'numeric' });
            }),
            datasets: [{
                label: 'Articles publiés',
                data: publicationData.map(item => item.count),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Views Distribution Chart
    const viewsCtx = document.getElementById('viewsChart').getContext('2d');
    
    const categoriesData = @json($stats['categories_stats']);
    
    new Chart(viewsCtx, {
        type: 'doughnut',
        data: {
            labels: categoriesData.map(cat => cat.name),
            datasets: [{
                data: categoriesData.map(cat => cat.articles_count),
                backgroundColor: [
                    '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6',
                    '#06b6d4', '#84cc16', '#f97316', '#ec4899', '#6b7280'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
});

function refreshStats() {
    // Show loading indicator
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Actualisation...';
    button.disabled = true;
    
    // Reload page after 1 second
    setTimeout(function() {
        window.location.reload();
    }, 1000);
}
</script>
@endpush
@endsection