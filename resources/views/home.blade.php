@extends('layouts.app')

@section('title', 'Accueil - ' . config('app.name'))
@section('description', 'Découvrez notre plateforme éducative avec des articles, sujets d\'évaluation et supports pédagogiques pour tous les niveaux.')

@section('content')
<!-- Hero Section -->
@component('components.homepanel', ['title' => 'Bienvenue sur E-School237', 'description' => 'Votre plateforme éducative complète avec des articles, sujets d\'évaluation et supports pédagogiques'])
@endcomponent


<!-- Stats Section -->
<div class="bg-white py-3">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-3 md:grid-cols-3 gap-2">
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-[#03386a] mb-2">{{ number_format($stats['total_articles']) }}</div>
                <div class="text-gray-600">Articles</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-green-600 mb-2">{{ number_format($stats['total_subjects']) }}</div>
                <div class="text-gray-600">Sujets</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-purple-600 mb-2">{{ number_format($stats['total_supports']) }}</div>
                <div class="text-gray-600">Supports</div>
            </div>
        </div>
    </div>
</div>

<div class="px-4 sm:px-6 lg:px-8 py-3">
    <!-- Latest Articles -->
    <section class="mb-12">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Derniers Articles</h2>
            <a href="{{ route('articles.index') }}" class="text-[#03386a] hover:text-blue-800 font-medium">
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
                                {{ $article->subject->name }}
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
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden border border-gray-200">
                    <div class="w-full h-32 bg-green-100 flex items-center justify-center">
                        <svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded uppercase">
                                {{ $subject->type }}
                            </span>
                            @if($subject->subject->name)
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">{{ $subject->subject->name }}</span>
                            @endif
                        </div>
                        
                        <h3 class="font-semibold mb-2 line-clamp-2">
                            <a href="{{ route('subjects.show', $subject->id) }}" class="text-gray-900 hover:text-green-600">
                                {{ $subject->title }}
                            </a>
                        </h3>
                        
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span>{{ number_format($subject->file_size / 1024, 0) }} KB</span>
                            <span>{{ $subject->downloads_count }} téléchargements</span>
                        </div>
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
    <section class="mb-12">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Derniers Supports pédagogiques</h2>
            <a href="{{ route('resources.index') }}" class="text-purple-600 hover:text-purple-800 font-medium">
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
                                {{ $support->level->name }}
                            </span>
                            @if($support->category->name)
                                <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2 py-1 rounded">{{ $support->category->name }}</span>
                            @endif
                        </div>
                        
                        <h3 class="font-semibold mb-2 line-clamp-2">
                            <a href="{{ route('resources.show', $support->id) }}" class="text-gray-900 hover:text-purple-600">
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
    </section>


    <!-- Categories Quick Access -->
    {{-- <section>
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Explorer par catégorie</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Article Subjects -->
            @if($article_categories->isNotEmpty())
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold mb-4 text-blue-600">Catégories d'articles</h3>
                    <div class="space-y-2">
                        @foreach($article_categories->take(5) as $subject)
                            <a href="{{ route('articles.subject', $subject->slug) }}" class="flex justify-between items-center py-2 text-sm text-gray-700 hover:text-blue-600">
                                <span>{{ $subject->name }}</span>
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ $subject->articles_count }}</span>
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
    </section> --}}
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