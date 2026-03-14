<x-layouts.app>
    <div class="space-y-8">

        {{-- ===== HERO ===== --}}
        <div class="relative overflow-hidden rounded-2xl
            bg-gradient-to-br from-sky-600 to-sky-800
            dark:from-sky-800 dark:to-sky-950
            px-8 py-12 text-white shadow-lg">

            {{-- Décoration fond --}}
            <div class="absolute inset-0 opacity-10">
                <div class="absolute -top-10 -right-10 w-64 h-64 rounded-full bg-white"></div>
                <div class="absolute -bottom-16 -left-10 w-80 h-80 rounded-full bg-white"></div>
            </div>

            <div class="relative z-10 max-w-2xl">
                <flux:badge color="sky" class="mb-4">
                    {{ __('app.resources.hero.badge') }}
                </flux:badge>

                <flux:heading size="xl" class="text-white text-3xl sm:text-4xl font-bold leading-tight">
                    {{ __('app.resources.hero.title') }}
                </flux:heading>

                <flux:text class="mt-4 text-white/80 text-lg leading-relaxed">
                    {{ __('app.resources.hero.description') }}
                </flux:text>
            </div>
        </div>

        {{-- ===== LISTE SUPPORTS ===== --}}
        <livewire:supports.index wire:lazy />

    </div>
</x-layouts.app>