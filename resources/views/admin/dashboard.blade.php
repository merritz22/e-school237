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
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Utilisateurs -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m3 5.197H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Utilisateurs
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">
                                    {{ $stats['total_users'] ?? 0 }}
                                </div>
                                @if(isset($stats['new_users_this_month']) && $stats['new_users_this_month'] > 0)
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                    <svg class="w-3 h-3 mr-0.5 flex-shrink-0 self-center" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    +{{ $stats['new_users_this_month'] }}
                                </div>
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('admin.users.index') }}" class="font-medium text-blue-700 hover:text-blue-900">
                        Gérer les utilisateurs
                    </a>
                </div>
            </div>
        </div>

        <!-- Articles -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Articles publiés
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">
                                    {{ $stats['total_articles'] ?? 0 }}
                                </div>
                                @if(isset($stats['pending_articles']) && $stats['pending_articles'] > 0)
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-orange-600">
                                    <span class="text-xs">{{ $stats['pending_articles'] }} en attente</span>
                                </div>
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('admin.articles.index') }}" class="font-medium text-green-700 hover:text-green-900">
                        Gérer les articles
                    </a>
                </div>
            </div>
        </div>

        <!-- Sujets -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 00-2 2v2a2 2 0 002 2m0 0h14m-14 0a2 2 0 002 2v2a2 2 0 01-2 2M5 9V7a2 2 0 012-2h10a2 2 0 012 2v2M5 9a2 2 0 002-2h10a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Sujets d'évaluation
                            </dt>
                            <dd>
                                <div class="text-2xl font-semibold text-gray-900">
                                    {{ $stats['total_subjects'] ?? 0 }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('admin.subjects.index') }}" class="font-medium text-purple-700 hover:text-purple-900">
                        Gérer les sujets
                    </a>
                </div>
            </div>
        </div>

        <!-- Supports -->
        {{-- <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Supports pédagogiques
                            </dt>
                            <dd>
                                <div class="text-2xl font-semibold text-gray-900">
                                    {{ $stats['total_supports'] ?? 0 }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('admin.supports.index') }}" class="font-medium text-orange-700 hover:text-orange-900">
                        Gérer les supports
                    </a>
                </div>
            </div>
        </div> --}}
    </div>

    <!-- Charts & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Activity Chart -->
        {{-- <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Activité des 30 derniers jours</h3>
                <div class="flex space-x-2">
                    <button class="text-sm text-blue-600 hover:text-blue-900" onclick="toggleChart('users')">
                        Utilisateurs
                    </button>
                    <span class="text-gray-300">|</span>
                    <button class="text-sm text-green-600 hover:text-green-900" onclick="toggleChart('content')">
                        Contenu
                    </button>
                </div>
            </div>
            
            <!-- Placeholder for chart -->
            <div id="activity-chart" class="h-64 bg-gray-50 rounded-md flex items-center justify-center">
                <div class="text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <p class="text-gray-500">Graphique des statistiques</p>
                    <p class="text-xs text-gray-400 mt-1">Intégration Chart.js recommandée</p>
                </div>
            </div>
        </div> --}}
         <!-- Quick Actions -->
        <div class=" bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Actions rapides</h3>
            
            <div class="grid grid-cols-1 gap-4">
                <a href="{{ route('admin.articles.create') }}" 
                   class="block p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:shadow-sm transition-all duration-200 group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Nouvel article</p>
                            <p class="text-xs text-gray-500">Créer du contenu</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.users.create') }}" 
                   class="block p-4 border border-gray-200 rounded-lg hover:border-green-300 hover:shadow-sm transition-all duration-200 group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Nouvel utilisateur</p>
                            <p class="text-xs text-gray-500">Ajouter un membre</p>
                        </div>
                    </div>
                </a>

                {{-- <a href="{{ route('admin.categories.create') }}" 
                   class="block p-4 border border-gray-200 rounded-lg hover:border-purple-300 hover:shadow-sm transition-all duration-200 group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Nouvelle catégorie</p>
                            <p class="text-xs text-gray-500">Organiser le contenu</p>
                        </div>
                    </div>
                </a> --}}

                {{-- <a href="{{ route('admin.subjects.create') }}" 
                   class="block p-4 border border-gray-200 rounded-lg hover:border-orange-300 hover:shadow-sm transition-all duration-200 group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Nouveau sujet</p>
                            <p class="text-xs text-gray-500">Évaluation</p>
                        </div>
                    </div>
                </a> --}}

                {{-- <a href="{{ route('admin.supports.create') }}" 
                   class="block p-4 border border-gray-200 rounded-lg hover:border-indigo-300 hover:shadow-sm transition-all duration-200 group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Nouveau support</p>
                            <p class="text-xs text-gray-500">Support pédagogique</p>
                        </div>
                    </div>
                </a> --}}

                <a href="{{ route('admin.stats') }}" 
                   class="block p-4 border border-gray-200 rounded-lg hover:border-gray-400 hover:shadow-sm transition-all duration-200 group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-gray-200 transition-colors">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Statistiques</p>
                            <p class="text-xs text-gray-500">Voir les détails</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Activité récente</h3>
            </div>
            
            <div class="divide-y divide-gray-200">
                @forelse($recent_activities ?? [] as $activity)
                <div class="p-6">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            @if($activity['type'] === 'user_registered')
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @elseif($activity['type'] === 'article_published')
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $activity['title'] }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $activity['description'] }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ $activity['created_at'] == null ? '' : $activity['created_at']->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                    <p class="text-gray-500">Aucune activité récente</p>
                </div>
                @endforelse
            </div>
            
            @if(isset($recentActivities) && count($recentActivities) > 0)
            <div class="bg-gray-50 px-6 py-3">
                <div class="text-sm">
                    <a href="#" class="font-medium text-blue-700 hover:text-blue-900">
                        Voir toute l'activité →
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

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