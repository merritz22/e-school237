<x-layouts.app>
    @php
        $theme = config('theme');
        $phoneRaw = config('subscriptions.payment_accounts.orange.number');
        $waNumber = config('subscriptions.country_code') . $phoneRaw;
        $contactEmail = config('mail.contact_address');
        $waMessage = urlencode("Bonjour, je souhaite m'inscrire à la formation « {$training->title} ».");
    @endphp

    <div class="space-y-8">

        <flux:button wire:navigate href="{{ route('trainings.index') }}" variant="ghost" size="sm">
            <flux:icon name="arrow-left" class="w-4 h-4" />
            Toutes les formations
        </flux:button>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                @if($training->banner)
                    <img src="{{ $training->banner_url }}" alt="{{ $training->title }}" class="w-full h-64 object-cover rounded-2xl">
                @else
                    <div class="w-full h-64 rounded-2xl bg-{{ $theme['primary'] }}-100 dark:bg-{{ $theme['primary'] }}-900/30 flex items-center justify-center">
                        <flux:icon name="academic-cap" class="w-16 h-16 text-{{ $theme['primary'] }}-600" />
                    </div>
                @endif

                <flux:heading size="xl" class="font-bold">{{ $training->title }}</flux:heading>

                <flux:text class="text-zinc-600 dark:text-zinc-300 whitespace-pre-line leading-relaxed">
                    {{ $training->description }}
                </flux:text>

                @if($training->technical_prerequisites || $training->intellectual_prerequisites)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @if($training->technical_prerequisites)
                            <flux:card class="p-5">
                                <flux:heading size="sm" class="font-semibold">Prérequis techniques</flux:heading>
                                <flux:text size="sm" class="text-zinc-500 mt-2 whitespace-pre-line">{{ $training->technical_prerequisites }}</flux:text>
                            </flux:card>
                        @endif
                        @if($training->intellectual_prerequisites)
                            <flux:card class="p-5">
                                <flux:heading size="sm" class="font-semibold">Prérequis intellectuels</flux:heading>
                                <flux:text size="sm" class="text-zinc-500 mt-2 whitespace-pre-line">{{ $training->intellectual_prerequisites }}</flux:text>
                            </flux:card>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div>
                <flux:card class="p-6 space-y-4 sticky top-20">
                    <div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-extrabold text-{{ $theme['primary'] }}-600">
                                {{ number_format($training->price, 0, ',', ' ') }} FCFA
                            </span>
                            @if($training->original_price)
                                <span class="text-sm text-zinc-400 line-through">{{ number_format($training->original_price, 0, ',', ' ') }}</span>
                            @endif
                        </div>
                        @if($training->discount_percent)
                            <flux:badge color="green" class="mt-2">-{{ $training->discount_percent }}%</flux:badge>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 text-sm text-zinc-500">
                        <flux:icon name="clock" class="w-4 h-4" />
                        {{ $training->duration }}
                    </div>

                    <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}"
                       target="_blank" rel="noopener noreferrer"
                       class="flex items-center justify-center gap-2 w-full rounded-lg bg-{{ $theme['primary'] }}-600 text-white px-4 py-2.5 font-medium hover:bg-{{ $theme['primary'] }}-700 transition-colors">
                        S'inscrire via WhatsApp
                        <flux:icon name="arrow-up-right" class="w-4 h-4" />
                    </a>

                    <a href="mailto:{{ $contactEmail }}?subject={{ urlencode('Inscription - ' . $training->title) }}"
                       class="flex items-center justify-center gap-2 w-full rounded-lg border border-zinc-200 dark:border-zinc-700 px-4 py-2.5 font-medium text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                        Nous écrire
                    </a>
                </flux:card>
            </div>
        </div>

        @if($relatedTrainings->isNotEmpty())
            <div>
                <flux:heading size="lg" class="font-bold mb-4">Autres formations</flux:heading>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedTrainings as $related)
                        <flux:card class="p-6">
                            <flux:heading size="sm" class="font-semibold">{{ $related->title }}</flux:heading>
                            <flux:text size="sm" class="text-zinc-500 mt-2 line-clamp-2">{{ $related->description }}</flux:text>
                            <flux:button wire:navigate href="{{ route('trainings.show', $related) }}" variant="ghost" size="sm" class="mt-3">
                                Découvrir
                            </flux:button>
                        </flux:card>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</x-layouts.app>
