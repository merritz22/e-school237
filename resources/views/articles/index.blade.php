@extends('layouts.app')

@section('title', 'Articles')
@section('description', 'Découvrez notre collection d\'articles éducatifs couvrant diverses matières et niveaux.')

@section('content')
<!-- Hero Section -->
@component('components.homepanel', 
    [
        'title' => 'Articles éducatifs', 
        'description' => 'Explorez notre collection d\'articles soigneusement rédigés pour enrichir vos connaissances.'])
@endcomponent
<div class="bg-white">
    <div class="px-4 sm:px-6 lg:px-8 py-3">
        <!-- Filters & Search -->
        {{-- <div class="bg-gray-50 rounded-lg mb-3">
            <form method="GET" action="{{ route('articles.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-5">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Titre, contenu..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                        <select id="category" 
                                name="category" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" 
                                        @if(request('category') === $category->slug) selected @endif>
                                    {{ $category->name }} ({{ $category->articles_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Trier par</label>
                        <select id="sort" 
                                name="sort" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="latest" @if(request('sort') === 'latest') selected @endif>Plus récents</option>
                            <option value="popular" @if(request('sort') === 'popular') selected @endif>Plus populaires</option>
                            <option value="title" @if(request('sort') === 'title') selected @endif>Par titre</option>
                            <option value="oldest" @if(request('sort') === 'oldest') selected @endif>Plus anciens</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="bg-[#03386a] text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Rechercher
                    </button>
                    
                    @if(request()->hasAny(['search', 'category', 'sort']))
                        <a href="{{ route('articles.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                            Effacer les filtres
                        </a>
                    @endif
                </div>
            </form>
        </div> --}}

        <!-- Articles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" x-show="view === 'grid'">
            @forelse($articles as $article)
                <article class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden border border-gray-200">
                    @if($article->featured_image)
                        <div class="aspect-w-16 aspect-h-9">
                            <img src="{{ Storage::url($article->featured_image) }}" 
                                 alt="{{ $article->title }}" 
                                 class="w-full h-48 object-cover">
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                {{ $article->category->name }}
                            </span>
                            <span class="text-gray-500 text-xs">
                                {{ $article->created_at->diffForHumans() }}
                            </span>
                        </div>
                        
                        <h3 class="font-semibold text-lg mb-3 line-clamp-2">
                            <a href="{{ route('articles.show', $article->slug) }}" 
                               class="text-gray-900 hover:text-blue-600 transition-colors">
                                {{ $article->title }}
                            </a>
                        </h3>
                        
                        @if($article->excerpt)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $article->excerpt }}</p>
                        @endif
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-sm text-gray-500">
                                <img src="{{ $article->author->avatar ? Storage::url($article->author->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($article->author->name) . '&background=3b82f6&color=fff' }}" 
                                     alt="{{ $article->author->name }}" 
                                     class="w-6 h-6 rounded-full mr-2">
                                {{ $article->author->name }}
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                {{ number_format($article->views_count) }}
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-3">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun article trouvé</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Essayez de modifier vos critères de recherche.
                        </p>
                        @if(request()->hasAny(['search', 'category']))
                            <div class="mt-6">
                                <a href="{{ route('articles.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    Voir tous les articles
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Articles List -->
        <div class="space-y-6" x-show="view === 'list'" style="display: none;">
            @forelse($articles as $article)
                <article class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200 overflow-hidden">
                    <div class="md:flex">
                        @if($article->featured_image)
                            <div class="md:w-48 md:flex-shrink-0">
                                <img src="{{ Storage::url($article->featured_image) }}" 
                                     alt="{{ $article->title }}" 
                                     class="w-full h-48 md:h-full object-cover">
                            </div>
                        @endif
                        
                        <div class="p-6 flex-1">
                            <div class="flex items-center justify-between mb-3">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $article->category->name }}
                                </span>
                                <span class="text-gray-500 text-sm">
                                    {{ $article->created_at->diffForHumans() }}
                                </span>
                            </div>
                            
                            <h3 class="font-semibold text-xl mb-3">
                                <a href="{{ route('articles.show', $article->slug) }}" 
                                   class="text-gray-900 hover:text-blue-600 transition-colors">
                                    {{ $article->title }}
                                </a>
                            </h3>
                            
                            @if($article->excerpt)
                                <p class="text-gray-600 mb-4">{{ $article->excerpt }}</p>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-sm text-gray-500">
                                    <img src="{{ $article->author->avatar ? Storage::url($article->author->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($article->author->name) . '&background=3b82f6&color=fff' }}" 
                                         alt="{{ $article->author->name }}" 
                                         class="w-8 h-8 rounded-full mr-3">
                                    <div>
                                        <div class="font-medium">{{ $article->author->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $article->published_at->format('d M Y') }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        {{ number_format($article->views_count) }} vues
                                    </div>
                                    <a href="{{ route('articles.show', $article->slug) }}" 
                                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                        Lire l'article
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun article trouvé</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Essayez de modifier vos critères de recherche.
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($articles->hasPages())
            <div class="mt-8">
                {{ $articles->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush
@endsection