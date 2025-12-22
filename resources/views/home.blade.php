@extends('layouts.app')

@section('title', 'Accueil - ' . config('app.name'))
@section('description', 'Découvrez notre plateforme éducative avec des articles, sujets d\'évaluation et supports pédagogiques pour tous les niveaux.')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                Bienvenue sur <span class="text-blue-200">{{ config('app.name') }}</span>
            </h1>
            <p class="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto">
                Votre plateforme éducative complète avec des articles, sujets d'évaluation et supports pédagogiques
            </p>
            
            <!-- Search Bar -->
            {{-- <div class="max-w-2xl mx-auto">
                <form action="{{ route('search') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               name="q" 
                               placeholder="Rechercher des articles, sujets, supports..." 
                               class="w-full px-6 py-4 text-gray-900 rounded-lg text-lg focus:ring-4 focus:ring-blue-300 focus:outline-none">
                    </div>
                    <button type="submit" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-blue-50 transition-colors">
                        Rechercher
                    </button>
                </form> 
            </div>--}}
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-2 gap-8">
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-blue-600 mb-2">{{ number_format($stats['total_articles']) }}</div>
                <div class="text-gray-600">Articles</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-green-600 mb-2">{{ number_format($stats['total_subjects']) }}</div>
                <div class="text-gray-600">Sujets</div>
            </div>
            {{-- <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-purple-600 mb-2">{{ number_format($stats['total_supports']) }}</div>
                <div class="text-gray-600">Supports</div>
            </div> --}}
            {{-- <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-orange-600 mb-2">{{ number_format($stats['total_users']) }}</div>
                <div class="text-gray-600">Utilisateurs</div>
            </div> --}}
            {{-- <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-red-600 mb-2">{{ number_format($stats['total_downloads']) }}</div>
                <div class="text-gray-600">Téléchargements</div>
            </div> --}}
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Latest Articles -->
    <section class="mb-12">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Derniers Articles</h2>
            <a href="{{ route('articles.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                Voir tous les articles →
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($latest_articles as $article)
                <article class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden border border-gray-200">
                    @if($article->featured_image)
                        <img src="{{ Storage::url($article->featured_image) }}" 
                             alt="{{ $article->title }}" 
                             class="w-full h-48 object-cover">
                    @endif
                    
                    <div class="p-6">
                        <div class="flex items-center mb-3">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                {{ $article->category->name }}
                            </span>
                            <span class="text-gray-500 text-sm ml-auto">
                                {{ $article->created_at->diffForHumans() }}
                            </span>
                        </div>
                        
                        <h3 class="font-semibold text-lg mb-2 line-clamp-2">
                            <a href="{{ route('articles.show', $article->slug) }}" class="text-gray-900 hover:text-blue-600">
                                {{ $article->title }}
                            </a>
                        </h3>
                        
                        @if($article->excerpt)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $article->excerpt }}</p>
                        @endif
                        
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <div class="flex items-center">
                                <img src="{{ $article->author->avatar ? Storage::url($article->author->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($article->author->name) . '&background=3b82f6&color=fff' }}" 
                                     alt="{{ $article->author->name }}" 
                                     class="w-6 h-6 rounded-full mr-2">
                                {{ $article->author->name }}
                            </div>
                            <span>{{ number_format($article->views_count) }} vues</span>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500">Aucun article disponible.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Latest Subjects -->
    <section class="mb-12">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Derniers Sujets d'évaluation</h2>
            <a href="{{ route('subjects.index') }}" class="text-green-600 hover:text-green-800 font-medium">
                Voir tous les sujets →
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($latest_subjects as $subject)
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                            {{ $subject->level->name }}
                        </span>
                        <span class="text-xs text-gray-500">
                            {{ $subject->type }}
                        </span>
                    </div>
                    
                    <h3 class="font-semibold text-lg mb-2">
                        <a href="{{ route('subjects.show', $subject->id) }}" class="text-gray-900 hover:text-green-600">
                            {{ $subject->title }}
                        </a>
                    </h3>
                    
                    <p class="text-sm text-gray-600 mb-3">{{ $subject->subject_name }}</p>
                    
                    @if($subject->description)
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $subject->description }}</p>
                    @endif
                    
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">
                            {{ $subject->downloads_count }} téléchargements
                        </span>
                        @if($subject->exam_date)
                            <span class="text-gray-500">
                                {{ $subject->exam_date->format('Y') }}
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500">Aucun sujet disponible.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Latest Supports -->
    {{-- <section class="mb-12">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Derniers Supports pédagogiques</h2>
            <a href="{{ route('supports.index') }}" class="text-purple-600 hover:text-purple-800 font-medium">
                Voir tous les supports →
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($latest_supports as $support)
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden border border-gray-200">
                    @if($support->preview_image)
                        <img src="{{ Storage::url($support->preview_image) }}" 
                             alt="{{ $support->title }}" 
                             class="w-full h-32 object-cover">
                    @else
                        <div class="w-full h-32 bg-purple-100 flex items-center justify-center">
                            <svg class="w-12 h-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2 py-1 rounded uppercase">
                                {{ $support->file_type }}
                            </span>
                            @if($support->level)
                                <span class="text-xs text-gray-500">{{ $support->level }}</span>
                            @endif
                        </div>
                        
                        <h3 class="font-semibold mb-2 line-clamp-2">
                            <a href="{{ route('supports.show', $support->id) }}" class="text-gray-900 hover:text-purple-600">
                                {{ $support->title }}
                            </a>
                        </h3>
                        
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span>{{ number_format($support->file_size / 1024, 0) }} KB</span>
                            <span>{{ $support->downloads_count }} téléchargements</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center py-12">
                    <p class="text-gray-500">Aucun support disponible.</p>
                </div>
            @endforelse
        </div>
    </section> --}}

    <!-- Popular Content -->
    {{-- <section class="mb-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Contenu populaire</h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
            <!-- Popular Articles -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h3 class="text-xl font-semibold mb-4 flex items-center">
                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Articles les plus lus
                </h3>
                <div class="space-y-4 inline-block">
                    @forelse($popular_articles as $article)
                        <div class="flex items-center space-x-5">
                            <div class="flex-shrink-0">
                                @if($article->featured_image)
                                    <img src="{{ Storage::url($article->featured_image) }}" alt="{{ $article->title }}" class="w-12 h-12 rounded object-cover">
                                @else
                                    <div class="w-12 h-12 bg-blue-100 rounded flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('articles.show', $article->slug) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600 line-clamp-2">
                                    {{ $article->title }}
                                </a>
                                <p class="text-xs text-gray-500 mt-1">{{ number_format($article->views_count) }} vues</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">Aucun article populaire.</p>
                    @endforelse
                </div>
            </div>

            <!-- Popular Supports -->
             <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h3 class="text-xl font-semibold mb-4 flex items-center">
                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Supports les plus téléchargés
                </h3>
                <div class="space-y-4">
                    @forelse($popular_supports as $support)
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-purple-100 rounded flex items-center justify-center">
                                    <span class="text-xs font-bold text-purple-600 uppercase">{{ $support->file_type }}</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('supports.show', $support->id) }}" class="text-sm font-medium text-gray-900 hover:text-purple-600 line-clamp-2">
                                    {{ $support->title }}
                                </a>
                                <p class="text-xs text-gray-500 mt-1">{{ number_format($support->downloads_count) }} téléchargements</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">Aucun support populaire.</p>
                    @endforelse
                </div>
            </div> 
        </div>
    </section> --}}

    <!-- Latest Blog Posts -->
    @if($latest_blog_posts->isNotEmpty())
        <section class="mb-12">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Derniers posts du Blog</h2>
                <a href="{{ route('blog.index') }}" class="text-orange-600 hover:text-orange-800 font-medium">
                    Voir tous les posts →
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($latest_blog_posts as $post)
                    <article class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-200">
                        <div class="flex items-center mb-3">
                            <img src="{{ $post->author->avatar ? Storage::url($post->author->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($post->author->name) . '&background=f97316&color=fff' }}" 
                                 alt="{{ $post->author->name }}" 
                                 class="w-8 h-8 rounded-full mr-3">
                            <div>
                                <p class="font-medium text-sm">{{ $post->author->name }}</p>
                                <p class="text-xs text-gray-500">{{ $post->published_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        
                        <h3 class="font-semibold text-lg mb-3">
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-gray-900 hover:text-orange-600">
                                {{ $post->title }}
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                            {{ Str::limit(strip_tags($post->content), 120) }}
                        </p>
                        
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span>{{ $post->comments_count ?? 0 }} commentaires</span>
                            <span>{{ $post->likes_count ?? 0 }} likes</span>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Categories Quick Access -->
    <section>
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Explorer par catégorie</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Article Categories -->
            @if($article_categories->isNotEmpty())
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold mb-4 text-blue-600">Catégories d'articles</h3>
                    <div class="space-y-2">
                        @foreach($article_categories->take(5) as $category)
                            <a href="{{ route('articles.category', $category->slug) }}" class="flex justify-between items-center py-2 text-sm text-gray-700 hover:text-blue-600">
                                <span>{{ $category->name }}</span>
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ $category->articles_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Subject Categories -->
            @if($subject_categories->isNotEmpty())
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold mb-4 text-green-600">Catégories de sujets</h3>
                    <div class="space-y-2">
                        @foreach($subject_categories->take(5) as $category)
                            <a href="{{ route('subjects.index', ['category' => $category->slug]) }}" class="flex justify-between items-center py-2 text-sm text-gray-700 hover:text-green-600">
                                <span>{{ $category->name }}</span>
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ $category->subjects_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Support Categories -->
            @if($support_categories->isNotEmpty())
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold mb-4 text-purple-600">Catégories de supports</h3>
                    <div class="space-y-2">
                        @foreach($support_categories->take(5) as $category)
                            <a href="{{ route('supports.index', ['category' => $category->slug]) }}" class="flex justify-between items-center py-2 text-sm text-gray-700 hover:text-purple-600">
                                <span>{{ $category->name }}</span>
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ $category->supports_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
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