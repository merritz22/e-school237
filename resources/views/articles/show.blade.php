@extends('layouts.app')

@section('title', $article->title)
@section('description', $article->excerpt ?: Str::limit(strip_tags($article->content), 160))

@push('meta')
    <meta property="og:title" content="{{ $article->title }}">
    <meta property="og:description" content="{{ $article->excerpt ?: Str::limit(strip_tags($article->content), 160) }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($article->featured_image)
        <meta property="og:image" content="{{ Storage::url($article->featured_image) }}">
    @endif
    <meta name="author" content="{{ $article->author->name }}">
    <meta name="article:published_time" content="{{ $article->published_at?->toISOString() ?? '-' }}">
    <meta name="article:section" content="{{ $article->subject->name }}">
@endpush

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    @if($article->featured_image)
        <div class="relative h-96 bg-gray-900">
            <img src="{{ Storage::url($article->featured_image) }}" 
                 alt="{{ $article->title }}" 
                 class="w-full h-full object-cover opacity-80">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            
            <div class="absolute bottom-0 left-0 right-0 p-8">
                <div class="max-w-4xl mx-auto">
                    <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                        {{ $article->subject->name }}
                    </span>
                    <h1 class="text-4xl md:text-5xl font-bold text-white mt-4 mb-4">
                        {{ $article->title }}
                    </h1>
                </div>
            </div>
        </div>
    @else
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                    {{ $article->subject->name }}
                </span>
                <h1 class="text-4xl md:text-5xl font-bold mt-4 mb-4">
                    {{ $article->title }}
                </h1>
            </div>
        </div>
    @endif

    <!-- Article Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Article Meta -->
        <div class="flex flex-wrap items-center justify-between py-6 border-b border-gray-200 mb-8">
            <div class="flex items-center mb-4 md:mb-0">
                <img src="{{ $article->author->avatar ? Storage::url($article->author->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($article->author->name) . '&background=3b82f6&color=fff' }}" 
                     alt="{{ $article->author->name }}" 
                     class="w-12 h-12 rounded-full mr-4">
                <div>
                    <div class="font-medium text-gray-900">{{ $article->author->name }}</div>
                    <div class="text-sm text-gray-500">
                        Publié le {{ $article->published_at?->format('d/m/Y à H:i') ?? '-' }}
                    </div>
                </div>
            </div>
            
            <div class="flex items-center space-x-6 text-sm text-gray-500">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $article->reading_time }} min de lecture
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    {{ number_format($article->views_count) }} vues
                </div>
            </div>
        </div>

        <!-- Article Body -->
        <div class="prose prose-lg max-w-none">
            {!! $article->content !!}
        </div>

        <!-- Article Tags -->
        {{-- @if($article->tags->isNotEmpty())
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-medium text-gray-900 mb-3">Tags :</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach($article->tags as $tag)
                        <a href="{{ route('articles.index', ['tag' => $tag->slug]) }}" 
                           class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm hover:bg-gray-200 transition-colors">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif --}}

        <!-- Share & Actions -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <!-- Like Button -->
                    @auth
                        <button onclick="toggleLike({{ $article->id }})" 
                                id="like-btn-{{ $article->id }}"
                                class="flex items-center space-x-2 px-4 py-2 rounded-lg border transition-colors {{ $article->isLikedByUser() ? 'bg-red-50 border-red-200 text-red-600' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="{{ $article->isLikedByUser() ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span id="like-count-{{ $article->id }}">{{ $article->likes->count() }}</span>
                        </button>
                    @endauth

                    <!-- Bookmark Button -->
                    @auth
                        <button onclick="toggleBookmark({{ $article->id }})" 
                                id="bookmark-btn-{{ $article->id }}"
                                class="flex items-center space-x-2 px-4 py-2 rounded-lg border transition-colors {{ $article->isBookmarkedByUser() ? 'bg-yellow-50 border-yellow-200 text-yellow-600' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="{{ $article->isBookmarkedByUser() ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                            </svg>
                            <span>Sauvegarder</span>
                        </button>
                    @endauth
                </div>

                <!-- Share Buttons -->
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500 mr-2">Partager :</span>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(url()->current()) }}" 
                       target="_blank"
                       class="bg-blue-400 text-white p-2 rounded-lg hover:bg-blue-500 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"></path>
                        </svg>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" 
                       target="_blank"
                       class="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.338 16.338H13.67V12.16c0-.995-.017-2.277-1.387-2.277-1.39 0-1.601 1.086-1.601 2.207v4.248H8.014v-8.59h2.559v1.174h.037c.356-.675 1.227-1.387 2.526-1.387 2.703 0 3.203 1.778 3.203 4.092v4.711zM5.005 6.575a1.548 1.548 0 11-.003-3.096 1.548 1.548 0 01.003 3.096zm-1.337 9.763H6.34v-8.59H3.667v8.59zM17.668 1H2.328C1.595 1 1 1.581 1 2.298v15.403C1 18.418 1.595 19 2.328 19h15.34c.734 0 1.332-.582 1.332-1.299V2.298C19 1.581 18.402 1 17.668 1z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        @if($article->getPreviousArticle() || $article->getNextArticle())
            <div class="mt-12 pt-8 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($article->getPreviousArticle())
                        <a href="{{ route('articles.show', $article->getPreviousArticle()->slug) }}" 
                           class="block bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition-colors">
                            <div class="text-sm text-gray-500 mb-2">← Article précédent</div>
                            <div class="font-medium text-gray-900">{{ $article->getPreviousArticle()->title }}</div>
                        </a>
                    @endif
                    
                    @if($article->getNextArticle())
                        <a href="{{ route('articles.show', $article->getNextArticle()->slug) }}" 
                           class="block bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition-colors text-right">
                            <div class="text-sm text-gray-500 mb-2">Article suivant →</div>
                            <div class="font-medium text-gray-900">{{ $article->getNextArticle()->title }}</div>
                        </a>
                    @endif
                </div>
            </div>
        @endif

        <!-- Related Articles -->
        @if($related_articles->isNotEmpty())
            <div class="mt-12 pt-8 border-t border-gray-200">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Articles similaires</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($related_articles as $related)
                        <article class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200 overflow-hidden">
                            @if($related->featured_image)
                                <div class="aspect-w-16 aspect-h-9">
                                    <img src="{{ Storage::url($related->featured_image) }}" 
                                         alt="{{ $related->title }}" 
                                         class="w-full h-32 object-cover">
                                </div>
                            @endif
                            
                            <div class="p-4">
                                <h4 class="font-semibold mb-2 line-clamp-2">
                                    <a href="{{ route('articles.show', $related->slug) }}" 
                                       class="text-gray-900 hover:text-blue-600 transition-colors">
                                        {{ $related->title }}
                                    </a>
                                </h4>
                                
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span>{{ $related->published_at?->format('d/m/Y') ?? '-' }}</span>
                                    <span>{{ number_format($related->views_count) }} vues</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Comments Section -->
        {{-- <div class="mt-12 pt-8 border-t border-gray-200">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">
                Commentaires ({{ $article->comments->count() }})
            </h3>

            <!-- Comment Form -->
            @auth
                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <form action="{{ route('comments.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="article_id" value="{{ $article->id }}">
                        
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                Votre commentaire
                            </label>
                            <textarea id="content" 
                                      name="content" 
                                      rows="4" 
                                      required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Partagez votre avis sur cet article..."></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Publier le commentaire
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-gray-50 rounded-lg p-6 mb-8 text-center">
                    <p class="text-gray-600 mb-4">Connectez-vous pour laisser un commentaire</p>
                    <a href="{{ route('login') }}" 
                       class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors inline-block">
                        Se connecter
                    </a>
                </div>
            @endauth

            <!-- Comments List -->
            <div class="space-y-6">
                @forelse($article->comments as $comment)
                    <div class="flex space-x-4">
                        <img src="{{ $comment->user->avatar ? Storage::url($comment->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) . '&background=3b82f6&color=fff' }}" 
                             alt="{{ $comment->user->name }}" 
                             class="w-10 h-10 rounded-full flex-shrink-0">
                        
                        <div class="flex-1">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="font-medium text-gray-900">{{ $comment->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</div>
                                </div>
                                <p class="text-gray-700">{{ $comment->content }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">Aucun commentaire pour le moment. Soyez le premier à commenter !</p>
                @endforelse
            </div>
        </div> --}}
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

.prose img {
    border-radius: 8px;
    margin: 1.5rem auto;
    max-width: 100%;
}

.prose blockquote {
    border-left: 4px solid #3b82f6;
    background: #f8fafc;
    padding: 1rem 1.5rem;
    margin: 1.5rem 0;
    border-radius: 0 8px 8px 0;
}
</style>
@endpush

@push('scripts')
<script>
function toggleLike(articleId) {
    fetch(`/articles/${articleId}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        const btn = document.getElementById(`like-btn-${articleId}`);
        const count = document.getElementById(`like-count-${articleId}`);
        
        if (data.liked) {
            btn.className = btn.className.replace('bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100', 'bg-red-50 border-red-200 text-red-600');
            btn.querySelector('svg').setAttribute('fill', 'currentColor');
        } else {
            btn.className = btn.className.replace('bg-red-50 border-red-200 text-red-600', 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100');
            btn.querySelector('svg').setAttribute('fill', 'none');
        }
        
        count.textContent = data.count;
    });
}

function toggleBookmark(articleId) {
    fetch(`/articles/${articleId}/bookmark`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        const btn = document.getElementById(`bookmark-btn-${articleId}`);
        
        if (data.bookmarked) {
            btn.className = btn.className.replace('bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100', 'bg-yellow-50 border-yellow-200 text-yellow-600');
            btn.querySelector('svg').setAttribute('fill', 'currentColor');
        } else {
            btn.className = btn.className.replace('bg-yellow-50 border-yellow-200 text-yellow-600', 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100');
            btn.querySelector('svg').setAttribute('fill', 'none');
        }
    });
}
</script>
@endpush
@endsection