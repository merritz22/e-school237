@extends('layouts.app')

@section('title', 'Articles - ' . $category->name)
@section('description', $category->description ?: 'Découvrez tous les articles de la catégorie ' . $category->name)

@section('content')
<div class="bg-white">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <!-- Breadcrumb -->
            <nav class="mb-4">
                <ol class="flex items-center space-x-2 text-blue-100">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-white transition-colors">Accueil</a>
                    </li>
                    <li>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </li>
                    <li>
                        <a href="{{ route('articles.index') }}" class="hover:text-white transition-colors">Articles</a>
                    </li>
                    <li>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </li>
                    <li class="text-white">{{ $category->name }}</li>
                </ol>
            </nav>

            <div class="flex items-center mb-4">
                @if($category->icon)
                    <div class="w-16 h-16 bg-blue-500 rounded-lg flex items-center justify-center mr-6">
                        <i class="{{ $category->icon }} text-2xl text-white"></i>
                    </div>
                @endif
                
                <div>
                    <h1 class="text-4xl font-bold mb-2">{{ $category->name }}</h1>
                    @if($category->description)
                        <p class="text-xl text-blue-100 max-w-2xl">{{ $category->description }}</p>
                    @endif
                </div>
            </div>

            <!-- Category Stats -->
            <div class="flex items-center space-x-6 text-blue-100">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ $articles->total() }} {{ Str::plural('article', $articles->total()) }}
                </div>
                
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    {{ number_format($articles->sum('views_count')) }} vues au total
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Sorting Options -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div class="mb-4 sm:mb-0">
                <h2 class="text-lg font-medium text-gray-900">
                    @if($articles->total() > 0)
                        {{ $articles->firstItem() }} - {{ $articles->lastItem() }} sur {{ number_format($articles->total()) }} articles
                    @else
                        Aucun article dans cette catégorie
                    @endif
                </h2>
            </div>

            <form method="GET" action="{{ route('articles.category', $category->slug) }}" class="flex items-center space-x-4">
                <label for="sort" class="text-sm font-medium text-gray-700">Trier par :</label>
                <select id="sort" 
                        name="sort" 
                        onchange="this.form.submit()"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="latest" @if(request('sort', 'latest') === 'latest') selected @endif>Plus récents</option>
                    <option value="popular" @if(request('sort') === 'popular') selected @endif>Plus populaires</option>
                    <option value="title" @if(request('sort') === 'title') selected @endif>Par titre</option>
                    <option value="oldest" @if(request('sort') === 'oldest') selected @endif>Plus anciens</option>
                </select>
            </form>
        </div>

        <!-- Articles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
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
                            <span class="text-gray-500 text-sm">
                                {{ $article->published_at->diffForHumans() }}
                            </span>
                            <span class="text-gray-500 text-sm">
                                {{ $article->reading_time }} min de lecture
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
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun article dans cette catégorie</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Il n'y a pas encore d'articles publiés dans cette catégorie.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('articles.index') }}" 
                               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                Voir tous les articles
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($articles->hasPages())
            <div class="mt-8">
                {{ $articles->appends(request()->query())->links() }}
            </div>
        @endif

        <!-- Other Categories -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Autres catégories</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach(\App\Models\Category::whereHas('articles', function($q) { $q->published(); })->where('id', '!=', $category->id)->withCount(['articles' => function($q) { $q->published(); }])->orderBy('name')->get() as $otherCategory)
                    <a href="{{ route('articles.category', $otherCategory->slug) }}" 
                       class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                        @if($otherCategory->icon)
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                                <i class="{{ $otherCategory->icon }} text-xl text-blue-600"></i>
                            </div>
                        @endif
                        
                        <h4 class="font-semibold text-gray-900 mb-2">{{ $otherCategory->name }}</h4>
                        
                        @if($otherCategory->description)
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $otherCategory->description }}</p>
                        @endif
                        
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">{{ $otherCategory->articles_count }} {{ Str::plural('article', $otherCategory->articles_count) }}</span>
                            <span class="text-blue-600 font-medium">Voir →</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
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