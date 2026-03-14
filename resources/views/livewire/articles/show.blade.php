<div>
    {{-- ===== HERO ===== --}}
    @if($article->featured_image)
        <div class="relative h-96 bg-zinc-900 rounded-2xl overflow-hidden mb-8">
            <img
                src="{{ Storage::url($article->featured_image) }}"
                alt="{{ $article->title }}"
                class="w-full h-full object-cover opacity-70"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

            <div class="absolute bottom-0 left-0 right-0 p-8">
                <div class="max-w-4xl mx-auto">
                    <flux:badge color="{{ config('theme.primary') }}">
                        {{ $article->subject?->name }}
                    </flux:badge>
                    <h1 class="text-3xl md:text-4xl font-bold text-white mt-4 leading-tight">
                        {{ $article->title }}
                    </h1>
                </div>
            </div>
        </div>
    @else
        <div class="max-w-4xl mx-auto py-10">
            <flux:badge color="{{ config('theme.primary') }}" class="mb-4">
                {{ $article->subject?->name }}
            </flux:badge>
            <h1 class="text-3xl md:text-4xl font-bold leading-tight">
                {{ $article->title }}
            </h1>
        </div>
    @endif

    <div class="max-w-4xl mx-auto space-y-6">

        {{-- ===== ACTIONS (like + partage) ===== --}}
        <div class="flex items-center gap-4 border-y border-zinc-200 dark:border-zinc-700 py-3">

            <flux:button
                icon="hand-thumb-up"
                wire:click="toggleLike"
                variant="ghost"
                size="sm"
            >
                {{ $this->likesFormatted }}
            </flux:button>

            <div class="flex items-center gap-3 ml-auto">

                {{-- WhatsApp --}}
                <a href="https://wa.me/?text={{ urlencode($article->title . ' - ' . url()->current()) }}"
                   target="_blank"
                   title="Partager sur WhatsApp"
                   class="hover:opacity-80 transition-opacity">
                    <svg class="w-7 h-7 rounded-lg" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <rect width="512" height="512" rx="15%" fill="#25d366"></rect>
                        <path fill="#25d366" stroke="#ffffff" stroke-width="26" d="M123 393l14-65a138 138 0 1150 47z"></path>
                        <path fill="#ffffff" d="M308 273c-3-2-6-3-9 1l-12 16c-3 2-5 3-9 1-15-8-36-17-54-47-1-4 1-6 3-8l9-14c2-2 1-4 0-6l-12-29c-3-8-6-7-9-7h-8c-2 0-6 1-10 5-22 22-13 53 3 73 3 4 23 40 66 59 32 14 39 12 48 10 11-1 22-10 27-19 1-3 6-16 2-18"></path>
                    </svg>
                </a>

                {{-- LinkedIn --}}
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}"
                   target="_blank"
                   title="Partager sur LinkedIn"
                   class="hover:opacity-80 transition-opacity">
                    <svg class="w-7 h-7 rounded-lg text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.338 16.338H13.67V12.16c0-.995-.017-2.277-1.387-2.277-1.39 0-1.601 1.086-1.601 2.207v4.248H8.014v-8.59h2.559v1.174h.037c.356-.675 1.227-1.387 2.526-1.387 2.703 0 3.203 1.778 3.203 4.092v4.711zM5.005 6.575a1.548 1.548 0 11-.003-3.096 1.548 1.548 0 01.003 3.096zm-1.337 9.763H6.34v-8.59H3.667v8.59zM17.668 1H2.328C1.595 1 1 1.581 1 2.298v15.403C1 18.418 1.595 19 2.328 19h15.34c.734 0 1.332-.582 1.332-1.299V2.298C19 1.581 18.402 1 17.668 1z" clip-rule="evenodd"></path>
                    </svg>
                </a>

                {{-- Copier lien --}}
                <flux:button
                    icon="link"
                    variant="ghost"
                    size="sm"
                    onclick="navigator.clipboard.writeText('{{ url()->current() }}');
                             this.innerText='{{ __('app.articles.show.link_copied') }}';"
                >
                    {{ __('app.articles.show.copy_link') }}
                </flux:button>
            </div>
        </div>

        {{-- ===== META AUTEUR ===== --}}
        <div class="flex flex-wrap items-center justify-between py-4
            border-b border-zinc-200 dark:border-zinc-700">

            {{-- Auteur --}}
            <div class="flex items-center gap-3 mb-4 md:mb-0">
                <img
                    src="{{ $article->author->avatar_url
                        ? Storage::url($article->author->avatar_url)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($article->author->name) . '&background=3b82f6&color=fff' }}"
                    alt="{{ $article->author->name }}"
                    class="w-11 h-11 rounded-full object-cover ring-2 ring-{{ config('theme.primary') }}-100"
                />
                <div>
                    <div class="font-semibold text-sm">{{ $article->author->name }}</div>
                    <div class="text-xs text-zinc-400">
                        {{ __('app.articles.show.published_at') }}
                        {{ $article->published_at?->format('d/m/Y à H:i') ?? '-' }}
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="flex items-center gap-6 text-sm text-zinc-500">
                <div class="flex items-center gap-1.5">
                    <flux:icon name="clock" class="w-4 h-4" />
                    <span>{{ $article->reading_time }} {{ __('app.articles.show.reading_time') }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <flux:icon name="eye" class="w-4 h-4" />
                    <span>{{ number_format($article->views_count) }} {{ __('app.articles.views') }}</span>
                </div>
            </div>
        </div>

        {{-- ===== CONTENU ===== --}}
        <div class="prose prose-lg dark:prose-invert max-w-none
            prose-headings:font-bold
            prose-a:text-{{ config('theme.primary') }}-600
            prose-img:rounded-xl prose-img:shadow-md">
            {!! $article->content !!}
        </div>

        {{-- ===== ARTICLES SIMILAIRES ===== --}}
        @if($related_articles->isNotEmpty())
            <div class="mt-12 pt-8 border-t border-zinc-200 dark:border-zinc-700">

                <flux:heading size="xl" class="font-bold mb-6">
                    {{ __('app.articles.show.related_title') }}
                </flux:heading>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($related_articles as $related)
                        <flux:card class="p-0 overflow-hidden hover:shadow-md transition-shadow duration-200">

                            @if($related->featured_image)
                                <img
                                    src="{{ Storage::url($related->featured_image) }}"
                                    alt="{{ $related->title }}"
                                    class="w-full h-32 object-cover"
                                />
                            @else
                                <div class="w-full h-32 flex items-center justify-center
                                    bg-{{ config('theme.primary') }}-50
                                    dark:bg-{{ config('theme.primary') }}-900/20">
                                    <flux:icon name="document-text"
                                        class="w-8 h-8 text-{{ config('theme.primary') }}-300" />
                                </div>
                            @endif

                            <div class="p-4 space-y-2">
                                <flux:heading size="sm" class="font-semibold line-clamp-2 leading-snug">
                                    <a wire:navigate
                                       href="{{ route('articles.show', $related->slug) }}"
                                       class="hover:text-{{ config('theme.primary') }}-600 transition-colors">
                                        {{ $related->title }}
                                    </a>
                                </flux:heading>

                                <div class="flex items-center justify-between text-xs text-zinc-400">
                                    <span>{{ $related->published_at?->format('d/m/Y') ?? '-' }}</span>
                                    <div class="flex items-center gap-1">
                                        <flux:icon name="eye" class="w-3 h-3" />
                                        <span>{{ number_format($related->views_count) }}</span>
                                    </div>
                                </div>
                            </div>
                        </flux:card>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>