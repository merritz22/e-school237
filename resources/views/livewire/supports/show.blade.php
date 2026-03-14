<div class="max-w-4xl mx-auto py-6 space-y-6">

    <flux:card class="overflow-hidden p-0">

        {{-- ===== APERÇU ===== --}}
        @if($resource->preview_image)
            <div class="w-full h-56 overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                <img
                    src="{{ asset('storage/' . $resource->preview_image) }}"
                    alt="{{ $resource->title }}"
                    class="w-full h-full object-cover object-top"
                />
            </div>
        @else
            <div class="w-full h-40 flex items-center justify-center
                bg-sky-50 dark:bg-sky-900/20">
                <flux:icon name="book-open" class="w-16 h-16 text-sky-300" />
            </div>
        @endif

        <div class="p-6 space-y-6">

            {{-- ===== HEADER ===== --}}
            <div class="flex items-start justify-between gap-4">
                <div class="space-y-2">
                    <flux:heading size="xl" class="font-bold leading-tight">
                        {{ $resource->title }}
                    </flux:heading>
                    <div class="flex items-center gap-3 text-sm text-zinc-500">
                        <div class="flex items-center gap-1.5">
                            <flux:icon name="user" class="w-4 h-4" />
                            <span>{{ $resource->uploader->name }}</span>
                        </div>
                        <span>•</span>
                        <div class="flex items-center gap-1.5">
                            <flux:icon name="calendar" class="w-4 h-4" />
                            <span>{{ $resource->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>

                <flux:badge
                    icon="sparkles"
                    variant="solid"
                    color="{{ $resource->is_free ? 'emerald' : 'yellow' }}"
                    class="shrink-0"
                >
                    {{ $resource->is_free ? __('app.common.free') : __('app.common.premium') }}
                </flux:badge>
            </div>

            <flux:separator />

            {{-- ===== META ===== --}}
            <div class="flex flex-wrap items-center gap-2">
                @if($resource->level?->name)
                    <flux:badge variant="outline">
                        <flux:icon name="academic-cap" class="w-3.5 h-3.5 mr-1" />
                        {{ $resource->level->name }}
                    </flux:badge>
                @endif
                @if($resource->subject?->name)
                    <flux:badge color="{{ config('theme.primary') }}">
                        {{ $resource->subject->name }}
                    </flux:badge>
                @endif
                <div class="ml-auto flex items-center gap-1 text-xs text-zinc-400">
                    <flux:icon name="arrow-down-tray" class="w-3.5 h-3.5" />
                    <span>{{ number_format($resource->downloads_count) }}
                        {{ __('app.resources.downloads') }}</span>
                </div>
            </div>

            {{-- ===== DESCRIPTION ===== --}}
            @if($resource->description)
                <flux:text class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                    {{ $resource->description }}
                </flux:text>
            @endif

            <flux:separator />

            {{-- ===== FICHIER + TÉLÉCHARGEMENT ===== --}}
            <div class="flex items-center gap-4 p-4 rounded-xl
                bg-zinc-50 dark:bg-zinc-800/50
                border border-zinc-200 dark:border-zinc-700">

                {{-- Icône / aperçu fichier --}}
                @if($resource->isImage())
                    <img
                        src="{{ $resource->getFileUrl() }}"
                        alt="{{ $resource->file_name }}"
                        class="h-12 w-12 object-cover rounded-lg shrink-0"
                    />
                @else
                    <div class="h-12 w-12 rounded-lg flex items-center justify-center
                        bg-sky-100 dark:bg-sky-900/30 shrink-0">
                        <span class="text-sky-700 dark:text-sky-300 font-bold text-sm">
                            {{ strtoupper($resource->file_extension) }}
                        </span>
                    </div>
                @endif

                {{-- Infos fichier --}}
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium truncate">{{ $resource->file_name }}</div>
                    <div class="text-xs text-zinc-400 mt-0.5">{{ $resource->formatted_file_size }}</div>
                </div>

                {{-- Bouton téléchargement --}}
                <flux:button
                    icon="arrow-down-tray"
                    href="{{ route('resources.download', $resource) }}"
                    variant="primary"
                    class="shrink-0"
                >
                    {{ __('app.resources.show.download') }}
                </flux:button>
            </div>

        </div>
    </flux:card>

    {{-- ===== RESSOURCES SIMILAIRES ===== --}}
    @if(!empty($relatedResources) && count($relatedResources) > 0)
        <div class="space-y-4">
            <flux:heading size="lg" class="font-bold">
                {{ __('app.resources.show.related_title') }}
            </flux:heading>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($relatedResources as $related)
                    <flux:card class="p-0 overflow-hidden hover:shadow-md transition-shadow duration-200">

                        {{-- Aperçu --}}
                        <a href="{{ route('resources.show', $related) }}">
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
                                        bg-sky-50 dark:bg-sky-900/20">
                                        <flux:icon name="book-open" class="w-8 h-8 text-sky-300" />
                                    </div>
                                @endif
                            </div>
                        </a>

                        <div class="p-4 space-y-2">
                            <flux:heading size="sm" class="font-semibold line-clamp-2">
                                <a href="{{ route('resources.show', $related) }}"
                                   class="hover:text-sky-600 transition-colors">
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