<?php

use Livewire\Component;
use App\Models\EducationalResource;

new class extends Component
{
    public $latest_supports = [];

    public function mount()
    {
        $this->latest_supports = EducationalResource::with(['subject', 'level'])
            ->latest()
            ->take(8)
            ->get();
    }
};
?>

{{-- ===== SKELETON ===== --}}
<x-slot:placeholder>
    <div class="mb-12">
        <div class="flex justify-between items-center mb-8">
            <flux:skeleton animate="shimmer" class="h-8 w-72 rounded" />
            <flux:skeleton animate="shimmer" class="h-9 w-40 rounded-lg" />
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @for($i = 0; $i < 8; $i++)
                <flux:card class="space-y-3">
                    <flux:skeleton animate="shimmer" class="w-full h-40 rounded-md" />
                    <flux:skeleton animate="shimmer" class="h-5 w-full rounded" />
                    <flux:skeleton animate="shimmer" class="h-5 w-3/4 rounded" />
                    <div class="flex items-center gap-2">
                        <flux:skeleton animate="shimmer" class="h-6 w-16 rounded-full" />
                    </div>
                    <div class="flex items-center justify-between">
                        <flux:skeleton animate="shimmer" class="h-5 w-20 rounded-full" />
                        <flux:skeleton animate="shimmer" class="h-5 w-24 rounded-full" />
                    </div>
                    <div class="flex items-center justify-between">
                        <flux:skeleton animate="shimmer" class="h-4 w-16 rounded" />
                        <flux:skeleton animate="shimmer" class="h-4 w-28 rounded" />
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
            {{ __('app.home.latest_supports.title') }}
        </flux:heading>
        <flux:button
            wire:navigate
            href="{{ route('resources.index') }}"
            icon:trailing="arrow-right"
            variant="ghost"
        >
            {{ __('app.home.latest_supports.see_all') }}
        </flux:button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($latest_supports as $resource)
            <flux:card class="p-0 overflow-hidden hover:shadow-md transition-shadow duration-200">

                {{-- Aperçu PDF --}}
                <a href="{{ route('resources.show', $resource->id) }}">
                    <div class="w-full h-40 overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                        @if($resource->preview_image)
                            <img
                                src="{{ asset('storage/' . $resource->preview_image) }}"
                                alt="{{ $resource->title }}"
                                class="w-full h-full object-cover object-top
                                    hover:scale-105 transition-transform duration-300"
                            />
                        @else
                            <div class="w-full h-full flex items-center justify-center
                                bg-{{ config('theme.success') }}-50
                                dark:bg-{{ config('theme.success') }}-900/20">
                                <flux:icon name="book-open"
                                    class="w-12 h-12 text-{{ config('theme.success') }}-300" />
                            </div>
                        @endif
                    </div>
                </a>

                <div class="p-4 space-y-3">

                    {{-- Titre --}}
                    <flux:heading size="sm" class="font-semibold line-clamp-2 leading-snug">
                        <a href="{{ route('resources.show', $resource->id) }}"
                           class="hover:text-{{ config('theme.primary') }}-600 transition-colors">
                            {{ $resource->title }}
                        </a>
                    </flux:heading>

                    {{-- Badge gratuit/premium --}}
                    <flux:badge
                        icon="sparkles"
                        variant="solid"
                        color="{{ $resource->is_free ? 'emerald' : 'yellow' }}"
                    >
                        {{ $resource->is_free
                            ? __('app.common.free')
                            : __('app.common.premium') }}
                    </flux:badge>

                    {{-- Niveau + Matière --}}
                    <div class="flex items-center justify-between flex-wrap gap-1">
                        @if($resource->level?->name)
                            <flux:badge variant="outline">
                                {{ $resource->level->name }}
                            </flux:badge>
                        @endif
                        @if($resource->subject?->name)
                            <flux:badge color="{{ config('theme.primary') }}">
                                {{ $resource->subject->name }}
                            </flux:badge>
                        @endif
                    </div>

                    <flux:separator />

                    {{-- Taille + téléchargements --}}
                    <div class="flex items-center justify-between text-xs text-zinc-500">
                        <div class="flex items-center gap-1">
                            <flux:icon name="document" class="w-3.5 h-3.5" />
                            <span>{{ number_format($resource->file_size / 1024, 0) }} KB</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <flux:icon name="arrow-down-tray" class="w-3.5 h-3.5" />
                            <span>{{ number_format($resource->downloads_count) }}</span>
                        </div>
                    </div>

                </div>
            </flux:card>
        @empty
            <div class="col-span-4 text-center py-16">
                <flux:icon name="book-open" class="w-12 h-12 text-zinc-300 mx-auto mb-3" />
                <flux:text class="text-zinc-500">
                    {{ __('app.home.latest_supports.empty') }}
                </flux:text>
            </div>
        @endforelse
    </div>
</div>