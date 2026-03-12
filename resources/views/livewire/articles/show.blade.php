<div>
    <!-- Hero Section -->
    @if($article->featured_image)
        <div class="relative h-96 bg-gray-900">
            <img src="{{ Storage::url($article->featured_image) }}" 
                 alt="{{ $article->title }}" 
                 class="w-full h-full object-cover opacity-80">
            <div class="absolute inset-0"></div>
            
            <div class="absolute bottom-0 left-0 right-0 p-8">
                <div class="max-w-4xl mx-auto">
                    <span class="px-3 py-1 rounded-full text-sm font-medium">
                        <flux:badge color="blue">{{ $article->subject?->name }}</flux:badge>
                    </span>
                    <h1 class="text-4xl md:text-5xl font-bold mt-4 mb-4">
                        {{ $article->title }}
                    </h1>
                </div>
            </div>
        </div>
    @else
        <div>
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                    {{ $article->subject?->name }}
                </span>
                <h1 class="text-4xl md:text-5xl font-bold mt-4 mb-4">
                    {{ $article->title }}
                </h1>
            </div>
        </div>
    @endif

    <!-- Article Meta -->
    <div class="mx-0 px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="flex items-center gap-6 border-y py-3 mb-4">

            <flux:button icon="hand-thumb-up" wire:click="toggleLike" class="flex items-center gap-2">
                {{ $this->likesFormatted }}
            </flux:button>

            <div class="flex items-center gap-3 ml-auto">

                <a href="https://wa.me/?text=Je%20te%20partage%20cet%20article%20de%20E-School237%20:%20{{ urlencode($article->title) }}%20{{ urlencode(url()->current()) }}" 
                    target="_blank">
                    <svg class="w-6 h-6 rounded-lg" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><rect width="512" height="512" rx="15%" fill="#25d366"></rect><path fill="#25d366" stroke="#ffffff" stroke-width="26" d="M123 393l14-65a138 138 0 1150 47z"></path><path fill="#ffffff" d="M308 273c-3-2-6-3-9 1l-12 16c-3 2-5 3-9 1-15-8-36-17-54-47-1-4 1-6 3-8l9-14c2-2 1-4 0-6l-12-29c-3-8-6-7-9-7h-8c-2 0-6 1-10 5-22 22-13 53 3 73 3 4 23 40 66 59 32 14 39 12 48 10 11-1 22-10 27-19 1-3 6-16 2-18"></path></g></svg>
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" 
                    target="_blank">
                    <svg class="w-6 h-6 rounded-lg" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.338 16.338H13.67V12.16c0-.995-.017-2.277-1.387-2.277-1.39 0-1.601 1.086-1.601 2.207v4.248H8.014v-8.59h2.559v1.174h.037c.356-.675 1.227-1.387 2.526-1.387 2.703 0 3.203 1.778 3.203 4.092v4.711zM5.005 6.575a1.548 1.548 0 11-.003-3.096 1.548 1.548 0 01.003 3.096zm-1.337 9.763H6.34v-8.59H3.667v8.59zM17.668 1H2.328C1.595 1 1 1.581 1 2.298v15.403C1 18.418 1.595 19 2.328 19h15.34c.734 0 1.332-.582 1.332-1.299V2.298C19 1.581 18.402 1 17.668 1z" clip-rule="evenodd"></path>
                    </svg>
                </a>

                <flux:button 
                    icon="link" 
                    onclick="navigator.clipboard.writeText('{{ url()->current() }}');"
                >
                    Copier le lien
                </flux:button>

            </div>

        </div>
        <div class="flex flex-wrap items-center justify-between py-6 border-b border-gray-200 mb-8">
            <div class="flex items-center mb-4 md:mb-0">
                <img src="{{ $article->author->avatar ? Storage::url($article->author->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($article->author->name) . '&background=3b82f6&color=fff' }}" 
                     alt="{{ $article->author->name }}" 
                     class="w-12 h-12 rounded-full mr-4">
                <div>
                    <div class="font-medium">{{ $article->author->name }}</div>
                    <div class="text-sm">
                        Publié le {{ $article->published_at?->format('d/m/Y à H:i') ?? '-' }}
                    </div>
                </div>
            </div>
            
            <div class="flex items-center space-x-6 text-sm">
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
        <div class="prose prose-lg">
            {!! $article->content !!}
        </div>

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
    </div>
</div>
