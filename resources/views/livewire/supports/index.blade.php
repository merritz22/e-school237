<div>
    {{-- ===== FILTRES ===== --}}
    <div class="grid md:grid-cols-2 gap-4 mb-8 mt-3 items-end">

        <flux:select
            wire:model.live="subject_id"
            label="{{ __('app.resources.filters.subject') }}"
            placeholder="{{ __('app.resources.filters.all_subjects') }}"
        >
            <option value="">{{ __('app.resources.filters.all_subjects') }}</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->slug }}">{{ $subject->name }}</option>
            @endforeach
        </flux:select>

        <flux:select
            wire:model.live="level_id"
            label="{{ __('app.resources.filters.level') }}"
            placeholder="{{ __('app.resources.filters.all_levels') }}"
        >
            <option value="">{{ __('app.resources.filters.all_levels') }}</option>
            @foreach($levels as $level)
                <option value="{{ $level->slug }}">{{ $level->name }}</option>
            @endforeach
        </flux:select>

        <flux:input
            label="{{ __('app.resources.filters.search_label') }}"
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('app.resources.filters.search_placeholder') }}"
            icon="magnifying-glass"
            class="md:col-span-2"
        />
    </div>

    {{-- ===== SKELETON ===== --}}
    <div wire:loading.grid class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @for($i = 0; $i < 6; $i++)
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

    {{-- ===== LISTE SUPPORTS ===== --}}
    <div wire:loading.remove class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($resources as $resource)
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
                                bg-sky-50 dark:bg-sky-900/20">
                                <flux:icon name="book-open"
                                    class="w-12 h-12 text-sky-300" />
                            </div>
                        @endif
                    </div>
                </a>

                <div class="p-4 space-y-3">

                    {{-- Titre --}}
                    <flux:heading size="sm" class="font-semibold line-clamp-2 leading-snug">
                        <a href="{{ route('resources.show', $resource->id) }}"
                           class="hover:text-sky-600 transition-colors">
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
                    <div class="flex flex-wrap items-center gap-2">
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
                            <span>{{ number_format($resource->downloads_count) }}
                                {{ __('app.resources.downloads') }}</span>
                        </div>
                    </div>

                </div>
            </flux:card>
        @empty
            <div class="col-span-3 text-center py-16 space-y-3">
                <flux:icon name="book-open" class="w-12 h-12 text-zinc-300 mx-auto" />
                <flux:text class="text-zinc-500">
                    {{ __('app.resources.empty') }}
                </flux:text>
            </div>
        @endforelse
    </div>

    {{-- ===== PAGINATION ===== --}}
    @if($resources->hasPages())
        <div class="mt-8">
            {{ $resources->links() }}
        </div>
    @endif

</div>