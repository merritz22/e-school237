<x-layouts.app>
    <div class="space-y-8">

        {{-- ===== HERO ===== --}}
        <div class="relative overflow-hidden rounded-2xl
            bg-gradient-to-br from-{{ config('theme.primary') }}-600 to-{{ config('theme.primary') }}-800
            dark:from-{{ config('theme.primary') }}-800 dark:to-{{ config('theme.primary') }}-950
            px-8 py-12 text-white shadow-lg">

            {{-- Décoration fond --}}
            <div class="absolute inset-0 opacity-10">
                <div class="absolute -top-10 -right-10 w-64 h-64 rounded-full bg-white"></div>
                <div class="absolute -bottom-16 -left-10 w-80 h-80 rounded-full bg-white"></div>
            </div>

            <div class="relative z-10 max-w-2xl">
                <flux:badge color="yellow" class="mb-4">
                    {{ __('app.home.hero.badge') }}
                </flux:badge>

                <flux:heading size="xl" class="text-white text-3xl sm:text-4xl font-bold leading-tight">
                    {{ __('app.home.hero.title') }}
                </flux:heading>

                <flux:text class="mt-4 text-white/80 text-lg leading-relaxed">
                    {{ __('app.home.hero.description') }}
                </flux:text>

                <div class="mt-6 flex flex-wrap gap-3">
                    <flux:button
                        wire:navigate
                        href="{{ route('subjects.index') }}"
                        variant="primary"
                        class="bg-white! text-{{ config('theme.primary') }}-700! hover:bg-zinc-100!"
                    >
                        {{ __('app.home.hero.cta_subjects') }}
                    </flux:button>
                    <flux:button
                        wire:navigate
                        href="{{ route('articles.index') }}"
                        variant="ghost"
                        class="text-white! border-white/40! hover:bg-white/10!"
                    >
                        {{ __('app.home.hero.cta_articles') }}
                    </flux:button>
                </div>
            </div>
        </div>

        {{-- ===== STATS ===== --}}
        <livewire:dashboard.stats wire:lazy />

        {{-- ===== DERNIERS CONTENUS ===== --}}
        <div class="space-y-10">
            <livewire:dashboard.latest-articles wire:lazy />
            <livewire:dashboard.latest-subjects wire:lazy />
            <livewire:dashboard.latest-supports wire:lazy />
        </div>

    </div>
</x-layouts.app>