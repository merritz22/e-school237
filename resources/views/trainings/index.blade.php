<x-layouts.app>
    @php
        $theme = config('theme');
    @endphp

    <div class="space-y-8">

        {{-- ===== HERO ===== --}}
        <div class="relative overflow-hidden rounded-2xl
            bg-gradient-to-br from-{{ $theme['primary'] }}-600 to-{{ $theme['primary'] }}-800
            dark:from-{{ $theme['primary'] }}-800 dark:to-{{ $theme['primary'] }}-950
            px-8 py-12 text-white shadow-lg">

            <div class="absolute inset-0 opacity-10">
                <div class="absolute -top-10 -right-10 w-64 h-64 rounded-full bg-white"></div>
                <div class="absolute -bottom-16 -left-10 w-80 h-80 rounded-full bg-white"></div>
            </div>

            <div class="relative z-10 max-w-2xl">
                <flux:badge color="yellow" class="mb-4">🎓 Formations</flux:badge>

                <flux:heading size="xl" class="text-white text-3xl sm:text-4xl font-bold leading-tight">
                    Développez de nouvelles compétences, à tout âge
                </flux:heading>

                <flux:text class="mt-4 text-white/80 text-lg leading-relaxed">
                    Bureautique, Intelligence Artificielle, Robotique, création de contenu et bien plus : des formations pratiques animées par des professionnels.
                </flux:text>
            </div>
        </div>

        {{-- ===== LISTE DES FORMATIONS ===== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($trainings as $training)
                <flux:card class="overflow-hidden flex flex-col p-0">
                    @if($training->banner)
                        <img src="{{ $training->banner_url }}" alt="{{ $training->title }}" class="w-full aspect-2/1 object-cover">
                    @else
                        <div class="w-full aspect-2/1 bg-{{ $theme['primary'] }}-100 dark:bg-{{ $theme['primary'] }}-900/30 flex items-center justify-center">
                            <flux:icon name="academic-cap" class="w-10 h-10 text-{{ $theme['primary'] }}-600" />
                        </div>
                    @endif

                    <div class="p-6 flex flex-col flex-1">
                        <flux:heading size="sm" class="font-semibold">{{ $training->title }}</flux:heading>
                        <flux:text size="sm" class="text-zinc-500 mt-2 flex-1 line-clamp-3">{{ $training->description }}</flux:text>

                        <div class="flex items-center justify-between mt-4">
                            <flux:text size="xs" class="text-zinc-400">{{ $training->duration }}</flux:text>
                            <div class="text-right">
                                <span class="font-semibold text-{{ $theme['primary'] }}-600">{{ number_format($training->price, 0, ',', ' ') }} FCFA</span>
                                @if($training->original_price)
                                    <span class="text-xs text-zinc-400 line-through ml-1">{{ number_format($training->original_price, 0, ',', ' ') }}</span>
                                @endif
                            </div>
                        </div>

                        <flux:button
                            wire:navigate
                            href="{{ route('trainings.show', $training) }}"
                            variant="primary"
                            class="mt-4"
                        >
                            En savoir plus
                        </flux:button>
                    </div>
                </flux:card>
            @empty
                <div class="col-span-full text-center py-12">
                    <flux:text class="text-zinc-500">Aucune formation disponible pour le moment.</flux:text>
                </div>
            @endforelse
        </div>

    </div>
</x-layouts.app>
