<div>
    {{-- ===== FILTRES ===== --}}
    <div class="rounded-lg mb-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <flux:select wire:model.live="subject_id" label="{{ __('app.subjects.filters.subject') }}">
                <option value="">{{ __('app.subjects.filters.all_subjects') }}</option>
                @foreach($filter_subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </flux:select>

            <flux:select wire:model.live="level_id" label="{{ __('app.subjects.filters.level') }}">
                <option value="">{{ __('app.subjects.filters.all_levels') }}</option>
                @foreach($levels as $level)
                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                @endforeach
            </flux:select>

            <flux:select wire:model.live="type" label="{{ __('app.subjects.filters.type') }}">
                <option value="">{{ __('app.subjects.filters.all_types') }}</option>
                @foreach($types as $t)
                    <option value="{{ $t }}">{{ $t }}</option>
                @endforeach
            </flux:select>

            <flux:select wire:model.live="year" label="{{ __('app.subjects.filters.year') }}">
                <option value="">{{ __('app.subjects.filters.choose_year') }}</option>
                @foreach($years as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </flux:select>
        </div>

        <flux:input
            label="{{ __('app.subjects.filters.search_label') }}"
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('app.subjects.filters.search_placeholder') }}"
            icon="magnifying-glass"
        />
    </div>

    {{-- ===== SKELETON ===== --}}
    <div wire:loading.grid class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
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
                    <flux:skeleton animate="shimmer" class="h-5 w-20 rounded-full" />
                    <flux:skeleton animate="shimmer" class="h-5 w-24 rounded-full" />
                </div>
                <flux:skeleton animate="shimmer" class="h-4 w-full rounded" />
                <flux:skeleton animate="shimmer" class="h-4 w-5/6 rounded" />
                <div class="flex items-center justify-between">
                    <flux:skeleton animate="shimmer" class="h-4 w-16 rounded" />
                    <flux:skeleton animate="shimmer" class="h-4 w-28 rounded" />
                </div>
            </flux:card>
        @endfor
    </div>

    {{-- ===== LISTE SUJETS ===== --}}
    <div wire:loading.remove class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($subjects as $subject)
            <flux:card class="p-0 overflow-hidden hover:shadow-md transition-shadow duration-200">

                {{-- Aperçu PDF --}}
                <a href="{{ route('subjects.show', $subject->id) }}">
                    <div class="w-full h-40 overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                        @if($subject->preview_image)
                            <img
                                src="{{ asset('storage/' . $subject->preview_image) }}"
                                alt="{{ $subject->title }}"
                                class="w-full h-full object-cover object-top
                                    hover:scale-105 transition-transform duration-300"
                            />
                        @else
                            <div class="w-full h-full flex items-center justify-center
                                bg-purple-50 dark:bg-purple-900/20">
                                <flux:icon name="book-open"
                                    class="w-12 h-12 text-purple-300" />
                            </div>
                        @endif
                    </div>
                </a>

                <div class="p-4 space-y-3">

                    {{-- Titre --}}
                    <flux:heading size="sm" class="font-semibold line-clamp-2 leading-snug">
                        <a href="{{ route('subjects.show', $subject->id) }}"
                           class="hover:text-purple-600 transition-colors">
                            {{ $subject->title }}
                        </a>
                    </flux:heading>

                    {{-- Badge gratuit/premium --}}
                    <flux:badge
                        icon="sparkles"
                        variant="solid"
                        color="{{ $subject->is_free ? 'emerald' : 'yellow' }}"
                    >
                        {{ $subject->is_free
                            ? __('app.common.free')
                            : __('app.common.premium') }}
                    </flux:badge>

                    {{-- Type + Niveau + Matière --}}
                    <div class="flex flex-wrap items-center gap-2">
                        @if($subject->type)
                            <flux:badge variant="outline">
                                {{ $subject->type }}
                            </flux:badge>
                        @endif
                        @if($subject->level?->name)
                            <flux:badge variant="outline">
                                {{ $subject->level->name }}
                            </flux:badge>
                        @endif
                        @if($subject->subject?->name)
                            <flux:badge color="blue">
                                {{ $subject->subject->name }}
                            </flux:badge>
                        @endif
                    </div>

                    {{-- Description --}}
                    @if($subject->description)
                        <flux:text class="text-xs text-zinc-500 line-clamp-2">
                            {{ Str::limit($subject->description, 140) }}
                        </flux:text>
                    @endif

                    <flux:separator />

                    {{-- Taille + téléchargements --}}
                    <div class="flex items-center justify-between text-xs text-zinc-500">
                        <div class="flex items-center gap-1">
                            <flux:icon name="document" class="w-3.5 h-3.5" />
                            <span>{{ number_format($subject->file_size / 1024, 0) }} KB</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <flux:icon name="arrow-down-tray" class="w-3.5 h-3.5" />
                            <span>{{ number_format($subject->downloads_count) }}
                                {{ __('app.subjects.downloads') }}</span>
                        </div>
                    </div>

                </div>
            </flux:card>
        @empty
            <div class="col-span-3 text-center py-16 space-y-3">
                <flux:icon name="book-open" class="w-12 h-12 text-zinc-300 mx-auto" />
                <flux:text class="text-zinc-500">
                    {{ __('app.subjects.empty') }}
                </flux:text>
            </div>
        @endforelse
    </div>

    {{-- ===== PAGINATION ===== --}}
    @if($subjects->hasPages())
        <div class="mt-8">
            {{ $subjects->links() }}
        </div>
    @endif
</div>