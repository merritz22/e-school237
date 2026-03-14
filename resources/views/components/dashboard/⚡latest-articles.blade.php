<?php

use Livewire\Component;
use App\Models\Article;

new class extends Component
{
    public $latest_articles = [];

    public function mount()
    {
        $this->latest_articles = Article::with(['subject', 'author'])
            ->where('status', 'published')
            ->latest()
            ->take(6)
            ->get();
    }
};
?>

{{-- ===== SKELETON ===== --}}
<x-slot:placeholder>
    <div class="mb-12">
        <div class="flex justify-between items-center mb-8">
            <flux:skeleton animate="shimmer" class="h-8 w-48 rounded" />
            <flux:skeleton animate="shimmer" class="h-9 w-40 rounded-lg" />
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @for($i = 0; $i < 6; $i++)
                <flux:card class="overflow-hidden">
                    <flux:skeleton animate="shimmer" class="w-full h-48" />
                    <div class="p-6 space-y-3">
                        <div class="flex items-center justify-between">
                            <flux:skeleton animate="shimmer" class="h-5 w-20 rounded-full" />
                            <flux:skeleton animate="shimmer" class="h-4 w-24 rounded" />
                        </div>
                        <flux:skeleton animate="shimmer" class="h-6 w-full rounded" />
                        <flux:skeleton animate="shimmer" class="h-6 w-3/4 rounded" />
                        <flux:skeleton animate="shimmer" class="h-4 w-full rounded" />
                        <flux:skeleton animate="shimmer" class="h-4 w-5/6 rounded" />
                        <div class="flex items-center justify-between pt-2">
                            <div class="flex items-center gap-2">
                                <flux:skeleton animate="shimmer" class="h-6 w-6 rounded-full" />
                                <flux:skeleton animate="shimmer" class="h-4 w-24 rounded" />
                            </div>
                            <flux:skeleton animate="shimmer" class="h-4 w-16 rounded" />
                        </div>
                    </div>
                </flux:card>
            @endfor
        </div>
    </div>
</x-slot:placeholder>

{{-- ===== CONTENU RÉEL ===== --}}
<div class="mb-12">
    <div class="flex justify-between items-center mb-8">
        <flux:heading size="2xl" class="font-bold">
            {{ __('app.home.latest_articles.title') }}
        </flux:heading>
        <flux:button
            wire:navigate
            href="{{ route('articles.index') }}"
            icon:trailing="arrow-right"
            variant="ghost"
        >
            {{ __('app.home.latest_articles.see_all') }}
        </flux:button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($latest_articles as $article)
            <flux:card class="overflow-hidden p-0 hover:shadow-md transition-shadow duration-200">

                {{-- Image --}}
                @if($article->featured_image)
                    <a href="{{ route('articles.show', $article->slug) }}">
                        <img
                            src="{{ Storage::url($article->featured_image) }}"
                            alt="{{ $article->title }}"
                            class="w-full h-48 object-cover hover:opacity-90 transition-opacity"
                        />
                    </a>
                @else
                    <div class="w-full h-48 bg-{{ config('theme.primary') }}-50
                        dark:bg-{{ config('theme.primary') }}-900/20
                        flex items-center justify-center">
                        <flux:icon name="document-text"
                            class="w-12 h-12 text-{{ config('theme.primary') }}-300" />
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
                        <a href="{{ route('articles.show', $article->slug) }}"
                           class="hover:text-{{ config('theme.primary') }}-600 transition-colors">
                            {{ $article->title }}
                        </a>
                    </flux:heading>

                    {{-- Excerpt --}}
                    @if($article->excerpt)
                        <flux:text size="sm" class="line-clamp-2 text-zinc-500">
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
                            <span>{{ number_format($article->views_count) }}</span>
                        </div>
                    </div>

                </div>
            </flux:card>
        @empty
            <div class="col-span-3 text-center py-16">
                <flux:icon name="document-text" class="w-12 h-12 text-zinc-300 mx-auto mb-3" />
                <flux:text class="text-zinc-500">
                    {{ __('app.home.latest_articles.empty') }}
                </flux:text>
            </div>
        @endforelse
    </div>
</div>