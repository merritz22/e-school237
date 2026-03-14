<div class="max-w-4xl mx-auto py-6 space-y-6">

    <flux:card class="overflow-hidden p-0">

        {{-- ===== APERÇU PDF ===== --}}
        @if($subject->preview_image)
            <div class="w-full h-56 overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                <img
                    src="{{ asset('storage/' . $subject->preview_image) }}"
                    alt="{{ $subject->title }}"
                    class="w-full h-full object-cover object-top"
                />
            </div>
        @else
            <div class="w-full h-40 flex items-center justify-center
                bg-purple-50 dark:bg-purple-900/20">
                <flux:icon name="book-open" class="w-16 h-16 text-purple-300" />
            </div>
        @endif

        <div class="p-6 space-y-6">

            {{-- ===== HEADER ===== --}}
            <div class="flex items-start justify-between gap-4">
                <div class="space-y-2">
                    <flux:heading size="xl" class="font-bold leading-tight">
                        {{ $subject->title }}
                    </flux:heading>
                    <div class="flex items-center gap-3 text-sm text-zinc-500">
                        <div class="flex items-center gap-1.5">
                            <flux:icon name="user" class="w-4 h-4" />
                            <span>{{ $subject->author->name }}</span>
                        </div>
                        <span>•</span>
                        <div class="flex items-center gap-1.5">
                            <flux:icon name="calendar" class="w-4 h-4" />
                            <span>{{ $subject->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>

                <flux:badge
                    icon="sparkles"
                    variant="solid"
                    color="{{ $subject->is_free ? 'emerald' : 'yellow' }}"
                    class="shrink-0"
                >
                    {{ $subject->is_free ? __('app.common.free') : __('app.common.premium') }}
                </flux:badge>
            </div>

            <flux:separator />

            {{-- ===== META ===== --}}
            <div class="flex flex-wrap items-center gap-2">
                @if($subject->type)
                    <flux:badge variant="outline">
                        {{ $subject->type }}
                    </flux:badge>
                @endif
                @if($subject->level?->name)
                    <flux:badge variant="outline">
                        <flux:icon name="academic-cap" class="w-3.5 h-3.5 mr-1" />
                        {{ $subject->level->name }}
                    </flux:badge>
                @endif
                @if($subject->subject?->name)
                    <flux:badge color="{{ config('theme.primary') }}">
                        {{ $subject->subject->name }}
                    </flux:badge>
                @endif
                <div class="ml-auto flex items-center gap-1 text-xs text-zinc-400">
                    <flux:icon name="arrow-down-tray" class="w-3.5 h-3.5" />
                    <span>{{ number_format($subject->downloads_count) }}
                        {{ __('app.common.downloads') }}</span>
                </div>
            </div>

            {{-- ===== DESCRIPTION ===== --}}
            @if($subject->description)
                <flux:text class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                    {{ $subject->description }}
                </flux:text>
            @endif

            <flux:separator />

            {{-- ===== FICHIER + TÉLÉCHARGEMENT ===== --}}
            <div class="flex items-center gap-4 p-4 rounded-xl
                bg-zinc-50 dark:bg-zinc-800/50
                border border-zinc-200 dark:border-zinc-700">

                {{-- Icône fichier --}}
                <div class="h-12 w-12 rounded-lg flex items-center justify-center
                    bg-purple-100 dark:bg-purple-900/30 shrink-0">
                    <span class="text-purple-700 dark:text-purple-300 font-bold text-sm">
                        {{ strtoupper($subject->file_extension) }}
                    </span>
                </div>

                {{-- Infos fichier --}}
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium truncate">{{ $subject->file_name }}</div>
                    <div class="text-xs text-zinc-400 mt-0.5">{{ $subject->formatted_file_size }}</div>
                </div>

                {{-- Bouton téléchargement --}}
                <flux:button
                    icon="arrow-down-tray"
                    href="{{ route('subjects.download', $subject) }}"
                    variant="primary"
                    class="shrink-0"
                >
                    {{ __('app.subjects.show.download') }}
                </flux:button>
            </div>

        </div>
    </flux:card>

    {{-- ===== SUJETS SIMILAIRES ===== --}}
    @if($relatedSubjects->isNotEmpty())
        <div class="space-y-4">
            <flux:heading size="lg" class="font-bold">
                {{ __('app.subjects.show.related_title') }}
            </flux:heading>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($relatedSubjects as $related)
                    <flux:card class="p-0 overflow-hidden hover:shadow-md transition-shadow duration-200">

                        {{-- Aperçu --}}
                        <a href="{{ route('subjects.show', $related) }}">
                            <div class="w-full h-32 overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                                @if($related->preview_image)
                                    <img
                                        src="{{ asset('storage/' . $related->preview_image) }}"
                                        alt="{{ $related->title }}"
                                        class="w-full h-full object-cover object-top
                                            hover:scale-105 transition-transform duration-300"
                                    />
                                @else
                                    <div class="w-full h-full flex items-center justify-center
                                        bg-purple-50 dark:bg-purple-900/20">
                                        <flux:icon name="book-open" class="w-8 h-8 text-purple-300" />
                                    </div>
                                @endif
                            </div>
                        </a>

                        <div class="p-4 space-y-2">
                            <flux:heading size="sm" class="font-semibold line-clamp-2">
                                <a href="{{ route('subjects.show', $related) }}"
                                   class="hover:text-purple-600 transition-colors">
                                    {{ $related->title }}
                                </a>
                            </flux:heading>

                            <div class="flex items-center justify-between text-xs text-zinc-400">
                                @if($related->subject?->name)
                                    <flux:badge color="{{ config('theme.primary') }}" size="sm">
                                        {{ $related->subject->name }}
                                    </flux:badge>
                                @endif
                                <div class="flex items-center gap-1 ml-auto">
                                    <flux:icon name="arrow-down-tray" class="w-3 h-3" />
                                    <span>{{ number_format($related->downloads_count) }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:card>
                @endforeach
            </div>
        </div>
    @endif

</div>