@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-6">Bienvenue, {{ auth()->user()->name }}</h1>

    <div class="mb-8 flex flex-wrap gap-4">
        <a href="{{ route('user.profile') }}" 
        class="flex items-center gap-2 bg-indigo-600 text-white px-5 py-3 rounded-lg shadow-md hover:bg-indigo-700 transition">
            <!-- Icône utilisateur -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A12.083 12.083 0 0112 15c2.761 0 5.293.955 7.121 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Voir mon profil
        </a>

        <a href="{{ route('user.downloads') }}" 
        class="flex items-center gap-2 bg-green-600 text-white px-5 py-3 rounded-lg shadow-md hover:bg-green-700 transition">
            <!-- Icône téléchargement -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
            </svg>
            Mes téléchargements
        </a>

        <a href="{{ route('user.posts') }}" 
        class="flex items-center gap-2 bg-yellow-500 text-white px-5 py-3 rounded-lg shadow-md hover:bg-yellow-600 transition">
            <!-- Icône posts/blog -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h9l7 7v9a2 2 0 01-2 2z" />
            </svg>
            Mes posts
        </a>

        <a href="{{ route('user.history') }}" 
        class="flex items-center gap-2 bg-gray-700 text-white px-5 py-3 rounded-lg shadow-md hover:bg-gray-800 transition">
            <!-- Icône historique -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Historique
        </a>
    </div>


    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <div class="text-indigo-600 text-3xl font-semibold">{{ $stats['downloads_count'] }}</div>
            <div class="mt-2 text-gray-600">Téléchargements</div>
        </div>
        {{-- <div class="bg-white p-6 rounded-lg shadow text-center">
            <div class="text-indigo-600 text-3xl font-semibold">{{ $stats['blog_posts_count'] }}</div>
            <div class="mt-2 text-gray-600">Posts de blog</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <div class="text-indigo-600 text-3xl font-semibold">{{ $stats['published_posts_count'] }}</div>
            <div class="mt-2 text-gray-600">Posts publiés</div>
        </div> --}}
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <div class="text-indigo-600 text-3xl font-semibold">{{ $stats['articles_count'] }}</div>
            <div class="mt-2 text-gray-600">Articles</div>
        </div>
    </div>

    <!-- Activités récentes -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Téléchargements récents -->
        <section class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Téléchargements récents</h2>
            @if($recent_downloads->isEmpty())
                <p class="text-gray-500">Aucun téléchargement récent.</p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($recent_downloads as $download)
                        <li class="py-2 flex justify-between">
                            <span>{{ $download->downloadable->title ?? 'Fichier supprimé' }}</span>
                            <span class="text-gray-400 text-sm">{{ $download->created_at->format('d/m/Y H:i') }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        <!-- Posts récents -->
        {{-- <section class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Posts récents</h2>
            @if($recent_posts->isEmpty())
                <p class="text-gray-500">Aucun post récent.</p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($recent_posts as $post)
                        <li class="py-2 flex justify-between">
                            <span>{{ $post->title }}</span>
                            <span class="text-gray-400 text-sm">{{ $post->created_at->format('d/m/Y H:i') }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section> --}}
    </div>
</div>
@endsection
