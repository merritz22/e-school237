<div>
    {{-- ===== FILTRES ===== --}}
    <div class="grid md:grid-cols-3 gap-4 mb-8 mt-3">

        <flux:select
            wire:model.live="subject"
            placeholder="{{ __('app.articles.filters.all_subjects') }}"
        >
            <option value="">{{ __('app.articles.filters.all_subjects') }}</option>
            @foreach($subjects as $sub)
                <option value="{{ $sub->slug }}">{{ $sub->name }}</option>
            @endforeach
        </flux:select>

        <flux:select wire:model.live="sort">
            <option value="latest">{{ __('app.articles.filters.sort_latest') }}</option>
            <option value="popular">{{ __('app.articles.filters.sort_popular') }}</option>
            <option value="oldest">{{ __('app.articles.filters.sort_oldest') }}</option>
            <option value="title">{{ __('app.articles.filters.sort_title') }}</option>
        </flux:select>

        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('app.articles.filters.search_placeholder') }}"
            icon="magnifying-glass"
        />
    </div>

    {{-- ===== SKELETON ===== --}}
    <div wire:loading.flex class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @for($i = 0; $i < 6; $i++)
            <flux:card class="overflow-hidden p-0">
                <flux:skeleton class="w-full h-40" animate="shimmer"/>
                <div class="p-5 space-y-3">
                    <div class="flex justify-between">
                        <flux:skeleton class="h-5 w-20 rounded-full" animate="shimmer"/>
                        <flux:skeleton class="h-4 w-24 rounded" animate="shimmer"/>
                    </div>
                    <flux:skeleton class="h-6 w-full rounded" animate="shimmer"/>
                    <flux:skeleton class="h-6 w-4/5 rounded" animate="shimmer"/>
                    <flux:skeleton class="h-4 w-full rounded" animate="shimmer"/>
                    <flux:skeleton class="h-4 w-full rounded" animate="shimmer"/>
                    <flux:skeleton class="h-4 w-3/4 rounded" animate="shimmer"/>
                    <div class="flex justify-between pt-1">
                        <flux:skeleton class="h-4 w-24 rounded" />
                        <flux:skeleton class="h-4 w-20 rounded" />
                    </div>
                </div>
            </flux:card>
        @endfor
    </div>

    {{-- ===== LISTE ARTICLES ===== --}}
    <div wire:loading.remove class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($articles as $article)
            <flux:card class="p-0 overflow-hidden hover:shadow-md transition-shadow duration-200">

                {{-- Image --}}
                @if($article->featured_image)
                    <a wire:navigate href="{{ route('articles.show', $article->slug) }}">
                        <img
                            src="{{ Storage::url($article->featured_image) }}"
                            alt="{{ $article->title }}"
                            class="w-full h-40 object-cover hover:opacity-90 transition-opacity"
                        />
                    </a>
                @else
                    <div class="w-full h-40 flex items-center justify-center
                        bg-{{ config('theme.primary') }}-50
                        dark:bg-{{ config('theme.primary') }}-900/20">
                        <flux:icon name="document-text"
                            class="w-10 h-10 text-{{ config('theme.primary') }}-300" />
                    </div>
                @endif

                <div class="p-5 space-y-3">

                    {{-- Badge + date --}}
                    <div class="flex items-center justify-between">
                        <flux:badge color="{{ config('theme.primary') }}">
                            {{ $article->subject->name }}
                        </flux:badge>
                        <flux:text size="sm" class="text-zinc-400">
                            {{ $article->created_at->diffForHumans() }}
                        </flux:text>
                    </div>

                    {{-- Titre --}}
                    <flux:heading size="md" class="font-semibold line-clamp-2 leading-snug">
                        <a wire:navigate
                           href="{{ route('articles.show', $article->slug) }}"
                           class="hover:text-{{ config('theme.primary') }}-600 transition-colors">
                            {{ $article->title }}
                        </a>
                    </flux:heading>

                    {{-- Excerpt --}}
                    @if($article->excerpt)
                        <flux:text size="sm" class="line-clamp-3 text-zinc-500">
                            {{ $article->excerpt }}
                        </flux:text>
                    @endif

                    <flux:separator />

                    {{-- Auteur + vues --}}
                    <div class="flex items-center justify-between text-sm text-zinc-500">
                        <div class="flex items-center gap-2">
                            <img
                                src="{{ $article->author->avatar_url
                                    ? Storage::url($article->author->avatar_url)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($article->author->name) . '&background=3b82f6&color=fff' }}"
                                alt="{{ $article->author->name }}"
                                class="w-6 h-6 rounded-full object-cover"
                            />
                            <span>{{ $article->author->name }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <flux:icon name="eye" class="w-4 h-4" />
                            <span>{{ number_format($article->views_count) }}
                                {{ __('app.articles.views') }}</span>
                        </div>
                    </div>

                </div>
            </flux:card>
        @empty
            <div class="col-span-3 text-center py-20 space-y-3">
                <flux:icon name="document-text" class="w-12 h-12 text-zinc-300 mx-auto" />
                <flux:text class="text-zinc-500">
                    {{ __('app.articles.empty') }}
                </flux:text>
            </div>
        @endforelse
    </div>

    {{-- ===== PAGINATION ===== --}}
    @if($articles->hasPages())
        <div class="mt-10">
            {{ $articles->links() }}
        </div>
    @endif
</div>