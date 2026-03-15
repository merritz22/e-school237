<x-layouts.app>
    @php $theme = config('theme'); @endphp

    <div class="space-y-12">

        {{-- ===== HERO ===== --}}
        <div class="text-center max-w-2xl mx-auto space-y-4">
            <flux:badge color="{{ $theme['warning'] }}" class="mb-2">
                🎓 {{ __('app.subscriptions.hero.badge') }}
            </flux:badge>
            <flux:heading size="xl" class="text-3xl sm:text-4xl font-bold leading-tight">
                {{ __('app.subscriptions.hero.title') }}
            </flux:heading>
            <flux:text class="text-lg text-zinc-500 leading-relaxed">
                {{ __('app.subscriptions.hero.description') }}
            </flux:text>
        </div>

        {{-- ===== GRILLE PLANS ===== --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">

            {{-- ===== PLAN CLASSIQUE ===== --}}
            <flux:card class="relative flex flex-col p-6 space-y-6
                border border-zinc-200 dark:border-zinc-700
                hover:shadow-lg transition-shadow duration-300">

                <div>
                    <flux:heading size="lg" class="font-bold">
                        {{ __('app.subscriptions.classic.name') }}
                    </flux:heading>
                    <flux:text class="text-zinc-500 mt-1 text-sm">
                        {{ __('app.subscriptions.classic.tagline') }}
                    </flux:text>
                </div>

                {{-- Prix --}}
                <div class="flex items-end gap-1">
                    <span class="text-4xl font-extrabold text-zinc-800 dark:text-white">
                        3 000
                    </span>
                    <span class="text-zinc-400 mb-1">XAF / an</span>
                </div>

                <flux:separator />

                {{-- Features --}}
                <ul class="space-y-3 flex-1">
                    @foreach([
                        __('app.subscriptions.classic.f1'),
                        __('app.subscriptions.classic.f2'),
                        __('app.subscriptions.classic.f3'),
                        __('app.subscriptions.classic.f4'),
                    ] as $feature)
                        <li class="flex items-start gap-2.5 text-sm">
                            <flux:icon name="check-circle"
                                class="w-4 h-4 mt-0.5 shrink-0 text-{{ $theme['success'] }}-500" />
                            <span>{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>

                <flux:button
                    href="{{ url('subscription/create') }}"
                    variant="ghost"
                    class="w-full border border-zinc-300 dark:border-zinc-600"
                >
                    {{ __('app.subscriptions.cta_subscribe') }}
                </flux:button>
            </flux:card>

            {{-- ===== PLAN PREMIUM (mis en avant) ===== --}}
            <flux:card class="relative flex flex-col p-6 space-y-6
                border-2 border-{{ $theme['primary'] }}-500
                bg-{{ $theme['primary'] }}-50 dark:bg-{{ $theme['primary'] }}-900/20
                shadow-xl scale-[1.02]
                hover:shadow-2xl transition-shadow duration-300">

                {{-- Badge populaire --}}
                <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                    <flux:badge
                        color="{{ $theme['warning'] }}"
                        variant="solid"
                        class="px-4 py-1 text-sm font-semibold shadow"
                    >
                        ⭐ {{ __('app.subscriptions.premium.badge') }}
                    </flux:badge>
                </div>

                <div>
                    <flux:heading size="lg" class="font-bold text-{{ $theme['primary'] }}-700
                        dark:text-{{ $theme['primary'] }}-300">
                        {{ __('app.subscriptions.premium.name') }}
                    </flux:heading>
                    <flux:text class="text-zinc-500 mt-1 text-sm">
                        {{ __('app.subscriptions.premium.tagline') }}
                    </flux:text>
                </div>

                {{-- Prix barré --}}
                <div>
                    <span class="text-sm line-through text-zinc-400">10 000 XAF</span>
                    <div class="flex items-end gap-1">
                        <span class="text-4xl font-extrabold text-{{ $theme['primary'] }}-700
                            dark:text-{{ $theme['primary'] }}-300">
                            6 000
                        </span>
                        <span class="text-zinc-400 mb-1">XAF / an</span>
                    </div>
                    <flux:badge color="{{ $theme['success'] }}" class="mt-1">
                        🎉 -40% {{ __('app.subscriptions.discount') }}
                    </flux:badge>
                </div>

                <flux:separator />

                {{-- Features --}}
                <ul class="space-y-3 flex-1">
                    @foreach([
                        __('app.subscriptions.premium.f1'),
                        __('app.subscriptions.premium.f2'),
                        __('app.subscriptions.premium.f3'),
                        __('app.subscriptions.premium.f4'),
                        __('app.subscriptions.premium.f5'),
                        __('app.subscriptions.premium.f6'),
                        __('app.subscriptions.premium.f7'),
                    ] as $feature)
                        <li class="flex items-start gap-2.5 text-sm">
                            <flux:icon name="check-circle"
                                class="w-4 h-4 mt-0.5 shrink-0 text-{{ $theme['primary'] }}-500" />
                            <span>{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>

                <flux:button
                    href="{{ url('subscription/create') }}"
                    variant="primary"
                    class="w-full"
                >
                    {{ __('app.subscriptions.cta_start') }}
                </flux:button>
            </flux:card>

            {{-- ===== PLAN EXCELLENCE ===== --}}
            <flux:card class="relative flex flex-col p-6 space-y-6
                border border-zinc-200 dark:border-zinc-700
                hover:shadow-lg transition-shadow duration-300">

                <div>
                    <flux:heading size="lg" class="font-bold">
                        {{ __('app.subscriptions.excellence.name') }}
                    </flux:heading>
                    <flux:text class="text-zinc-500 mt-1 text-sm">
                        {{ __('app.subscriptions.excellence.tagline') }}
                    </flux:text>
                </div>

                {{-- Prix --}}
                <div class="flex items-end gap-1">
                    <span class="text-4xl font-extrabold text-zinc-800 dark:text-white">
                        8 000
                    </span>
                    <span class="text-zinc-400 mb-1">XAF / an</span>
                </div>

                <flux:separator />

                {{-- Features --}}
                <ul class="space-y-3 flex-1">
                    @foreach([
                        __('app.subscriptions.excellence.f1'),
                        __('app.subscriptions.excellence.f2'),
                        __('app.subscriptions.excellence.f3'),
                        __('app.subscriptions.excellence.f4'),
                    ] as $feature)
                        <li class="flex items-start gap-2.5 text-sm">
                            <flux:icon name="check-circle"
                                class="w-4 h-4 mt-0.5 shrink-0 text-{{ $theme['success'] }}-500" />
                            <span>{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>

                <flux:button
                    href="{{ url('subscription/create') }}"
                    variant="ghost"
                    class="w-full border border-zinc-300 dark:border-zinc-600"
                >
                    {{ __('app.subscriptions.cta_subscribe') }}
                </flux:button>
            </flux:card>

        </div>

        {{-- ===== GARANTIES ===== --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-3xl mx-auto">
            @foreach([
                ['icon' => 'shield-check',  'label' => __('app.subscriptions.guarantees.secure')],
                ['icon' => 'arrow-path',    'label' => __('app.subscriptions.guarantees.cancel')],
                ['icon' => 'bolt',          'label' => __('app.subscriptions.guarantees.instant')],
            ] as $guarantee)
                <div class="flex items-center gap-3 text-sm text-zinc-500">
                    <flux:icon name="{{ $guarantee['icon'] }}"
                        class="w-5 h-5 text-{{ $theme['success'] }}-500 shrink-0" />
                    <span>{{ $guarantee['label'] }}</span>
                </div>
            @endforeach
        </div>

    </div>
</x-layouts.app>