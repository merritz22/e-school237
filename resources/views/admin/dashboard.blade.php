@extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('description', 'Vue d\'ensemble de l\'administration de la plateforme éducative')

@push('breadcrumb')
    <li>
        <div class="flex items-center">
            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>
            <span class="ml-4 text-sm font-medium text-gray-900">Tableau de bord</span>
        </div>
    </li>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h1 class="text-2xl font-bold text-gray-900">Bienvenue, {{ auth()->user()->name }} !</h1>
            <p class="mt-1 text-sm text-gray-500">
                Dernière connexion : {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('d/m/Y à H:i') : 'Première connexion' }}
            </p>
        </div>
    </div>

    <!-- Quick Stats -->
    @component('components.admincard', ['stats' =>$stats])
    @endcomponent

    <!-- Charts & Recent Activity -->
    @component('components.adminquickaction')
    @endcomponent

    <!-- Quick Actions & System Info -->
    {{-- <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
       

        <!-- System Information -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informations système</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Version Laravel</span>
                    <span class="text-sm font-medium text-gray-900">{{ app()->version() }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Version PHP</span>
                    <span class="text-sm font-medium text-gray-900">{{ PHP_VERSION }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Espace de stockage</span>
                    <span class="text-sm font-medium text-gray-900">
                        {{ $stats['storage_used'] ?? 'N/A' }}
                    </span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Cache actif</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ config('cache.default') !== 'array' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ config('cache.default') !== 'array' ? 'Activé' : 'Désactivé' }}
                    </span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Environment</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ app()->environment() === 'production' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst(app()->environment()) }}
                    </span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Dernière sauvegarde</span>
                    <span class="text-sm font-medium text-gray-900">
                        {{ $stats['last_backup'] ?? 'Jamais' }}
                    </span>
                </div>
            </div>
            
            <!-- System Health -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-medium text-gray-900 mb-3">Santé du système</h4>
                
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Base de données</span>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                            <span class="text-xs text-green-600">Connectée</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Stockage des fichiers</span>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                            <span class="text-xs text-green-600">Accessible</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Top Content & Users -->
    {{-- <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Most Popular Content -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Contenu le plus consulté</h3>
            </div>
            
            <div class="divide-y divide-gray-200">
                @forelse($popularContent ?? [] as $content)
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                {{ $content['title'] }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $content['type'] }} • {{ $content['category'] ?? 'Non catégorisé' }}
                            </p>
                        </div>
                        <div class="ml-4 flex-shrink-0 text-right">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $content['views'] }} vues
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $content['downloads'] ?? 0 }} téléchargements
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <p class="text-gray-500">Aucune donnée de popularité</p>
                </div>
                @endforelse
            </div>
            
            @if(isset($popularContent) && count($popularContent) > 0)
            <div class="bg-gray-50 px-6 py-3">
                <div class="text-sm">
                    <a href="{{ route('admin.articles.stats') }}" class="font-medium text-blue-700 hover:text-blue-900">
                        Voir toutes les statistiques →
                    </a>
                </div>
            </div>
            @endif
        </div>

        <!-- Most Active Users -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Utilisateurs les plus actifs</h3>
            </div>
            
            <div class="divide-y divide-gray-200">
                @forelse($activeUsers ?? [] as $user)
                <div class="p-6">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" 
                                 src="{{ $user['avatar'] ? Storage::url($user['avatar']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['name']) . '&background=3b82f6&color=fff' }}" 
                                 alt="{{ $user['name'] }}">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $user['name'] }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $user['role'] ?? 'Membre' }} • Inscrit {{ $user['joined_date'] }}
                            </p>
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $user['contributions'] }} contributions
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $user['last_active'] }}
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m3 5.197H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500">Aucun utilisateur actif récemment</p>
                </div>
                @endforelse
            </div>
            
            @if(isset($activeUsers) && count($activeUsers) > 0)
            <div class="bg-gray-50 px-6 py-3">
                <div class="text-sm">
                    <a href="{{ route('admin.users.index') }}" class="font-medium text-blue-700 hover:text-blue-900">
                        Voir tous les utilisateurs →
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Pending Actions -->
    @if(isset($pendingActions) && count($pendingActions) > 0)
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Actions en attente</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    {{ count($pendingActions) }} {{ count($pendingActions) > 1 ? 'éléments' : 'élément' }}
                </span>
            </div>
        </div>
        
        <div class="divide-y divide-gray-200">
            @foreach($pendingActions as $action)
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            @if($action['type'] === 'article_review')
                                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            @elseif($action['type'] === 'user_approval')
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $action['title'] }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $action['description'] }} • {{ $action['created_at']->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex-shrink-0 flex space-x-2">
                        <a href="{{ $action['action_url'] }}" 
                           class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors">
                            Examiner
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div> 
    @endif--}}
</div>

@push('scripts')
<script>
// Toggle chart function placeholder
function toggleChart(type) {
    console.log('Basculer vers:', type);
    // Intégrer ici la logique pour basculer entre les graphiques
    // Exemple avec Chart.js ou autre bibliothèque de graphiques
}

// Auto-refresh dashboard every 5 minutes
setInterval(function() {
    if (!document.hidden) {
        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Update only statistics without full page reload
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newStats = doc.querySelectorAll('.text-2xl.font-semibold.text-gray-900');
            const currentStats = document.querySelectorAll('.text-2xl.font-semibold.text-gray-900');
            
            newStats.forEach((stat, index) => {
                if (currentStats[index] && stat.textContent !== currentStats[index].textContent) {
                    currentStats[index].textContent = stat.textContent;
                    // Add flash effect
                    currentStats[index].classList.add('bg-yellow-100');
                    setTimeout(() => {
                        currentStats[index].classList.remove('bg-yellow-100');
                    }, 1000);
                }
            });
        })
        .catch(error => {
            console.log('Erreur de mise à jour automatique:', error);
        });
    }
}, 300000); // 5 minutes

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Add any initialization code here
    console.log('Dashboard administrateur chargé');
});
</script>
@endpush
@endsection