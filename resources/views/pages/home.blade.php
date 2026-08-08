<x-layouts.app>
    @php
        $schoolStats = config('school_stats');
        $exam = $schoolStats['exam'];
        $failRate = round(100 - $exam['pass_rate'], 2);
        $diff = round($exam['pass_rate'] - $exam['previous_pass_rate'], 2);
        $theme = config('theme');
    @endphp

    <div class="space-y-14">

        {{-- ===== HERO : statistique choc ===== --}}
        <div class="relative overflow-hidden rounded-2xl
            bg-gradient-to-br from-{{ $theme['primary'] }}-600 to-{{ $theme['primary'] }}-800
            dark:from-{{ $theme['primary'] }}-800 dark:to-{{ $theme['primary'] }}-950
            px-8 py-12 text-white shadow-lg">

            {{-- Décoration fond --}}
            <div class="absolute inset-0 opacity-10">
                <div class="absolute -top-10 -right-10 w-64 h-64 rounded-full bg-white"></div>
                <div class="absolute -bottom-16 -left-10 w-80 h-80 rounded-full bg-white"></div>
            </div>

            <div class="relative z-10 max-w-2xl">
                <flux:badge color="yellow" class="mb-4">
                    {{ __('app.home.hero.badge', ['year' => $schoolStats['year']]) }}
                </flux:badge>

                <flux:heading size="xl" class="text-white text-3xl sm:text-4xl font-bold leading-tight">
                    {{ __('app.home.hero.title', [
                        'fail_rate' => number_format($failRate, 2, ',', ' '),
                        'year' => $schoolStats['year'],
                    ]) }}
                </flux:heading>

                <flux:text class="mt-4 text-white/80 text-lg leading-relaxed">
                    {{ __('app.home.hero.description', [
                        'pass_rate' => number_format($exam['pass_rate'], 2, ',', ' '),
                        'year' => $schoolStats['year'],
                        'diff' => number_format(abs($diff), 2, ',', ' '),
                        'previous_year' => $exam['previous_year'],
                    ]) }}
                </flux:text>

                <div class="mt-6 flex flex-wrap gap-3">
                    @guest
                        <flux:button
                            wire:navigate
                            href="{{ route('register') }}"
                            variant="primary"
                            class="bg-white! text-{{ $theme['primary'] }}-700! hover:bg-zinc-100!"
                        >
                            {{ __('app.home.hero.cta_register') }}
                        </flux:button>
                    @else
                        <flux:button
                            wire:navigate
                            href="{{ route('subjects.index') }}"
                            variant="primary"
                            class="bg-white! text-{{ $theme['primary'] }}-700! hover:bg-zinc-100!"
                        >
                            {{ __('app.home.hero.cta_subjects') }}
                        </flux:button>
                    @endguest
                    <flux:button
                        wire:navigate
                        href="{{ route('subscriptions.index') }}"
                        variant="ghost"
                        class="text-white! border-white/40! hover:bg-white/10!"
                    >
                        {{ __('app.home.hero.cta_plans') }}
                    </flux:button>
                </div>

                <flux:text size="xs" class="mt-5 text-white/50 block">
                    {{ __('app.home.hero.source_prefix') }}
                    <a href="{{ $schoolStats['source_url'] }}" target="_blank" rel="noopener noreferrer" class="underline hover:text-white/80">
                        {{ $schoolStats['source_label'] }}
                    </a>
                </flux:text>
            </div>
        </div>

        {{-- ===== BANDEAU DE STATISTIQUES ===== --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-5 text-center">
                <div class="text-3xl font-extrabold text-{{ $theme['success'] }}-600">
                    {{ number_format($exam['pass_rate'], 2, ',', ' ') }}%
                </div>
                <flux:text size="sm" class="text-zinc-500 mt-1 block">
                    {{ __('app.home.exam_stats.pass_rate', ['year' => $schoolStats['year']]) }}
                </flux:text>
                <flux:text size="xs" class="text-red-500 mt-1 block">
                    ▼ {{ __('app.home.exam_stats.pass_rate_change', [
                        'diff' => number_format(abs($diff), 2, ',', ' '),
                        'previous_year' => $exam['previous_year'],
                    ]) }}
                </flux:text>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-5 text-center">
                <div class="text-3xl font-extrabold text-red-600">
                    {{ number_format($failRate, 2, ',', ' ') }}%
                </div>
                <flux:text size="sm" class="text-zinc-500 mt-1 block">
                    {{ __('app.home.exam_stats.fail_rate', ['year' => $schoolStats['year']]) }}
                </flux:text>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-5 text-center">
                <div class="text-3xl font-extrabold text-{{ $theme['primary'] }}-600">
                    {{ number_format($exam['candidates_present']) }}
                </div>
                <flux:text size="sm" class="text-zinc-500 mt-1 block">
                    {{ __('app.home.exam_stats.candidates') }}
                </flux:text>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-5 text-center">
                <div class="text-3xl font-extrabold text-{{ $theme['success'] }}-600">
                    {{ number_format($exam['admitted']) }}
                </div>
                <flux:text size="sm" class="text-zinc-500 mt-1 block">
                    {{ __('app.home.exam_stats.admitted', ['girls' => number_format($exam['admitted_girls'])]) }}
                </flux:text>
            </div>
        </div>

        {{-- ===== LA SOLUTION ===== --}}
        <div>
            <div class="text-center max-w-2xl mx-auto mb-10">
                <flux:badge color="{{ $theme['primary'] }}" class="mb-3">
                    {{ __('app.home.solution.badge') }}
                </flux:badge>
                <flux:heading size="xl" class="font-bold">
                    {{ __('app.home.solution.title') }}
                </flux:heading>
                <flux:text class="mt-2 text-zinc-500">
                    {{ __('app.home.solution.description') }}
                </flux:text>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <flux:card class="p-6 space-y-3">
                    <div class="w-11 h-11 rounded-lg bg-{{ $theme['primary'] }}-100 dark:bg-{{ $theme['primary'] }}-900/30 flex items-center justify-center">
                        <flux:icon name="document-text" class="w-6 h-6 text-{{ $theme['primary'] }}-600" />
                    </div>
                    <flux:heading size="sm" class="font-semibold">{{ __('app.home.solution.f1_title') }}</flux:heading>
                    <flux:text size="sm" class="text-zinc-500">{{ __('app.home.solution.f1_desc') }}</flux:text>
                </flux:card>

                <flux:card class="p-6 space-y-3">
                    <div class="w-11 h-11 rounded-lg bg-{{ $theme['success'] }}-100 dark:bg-{{ $theme['success'] }}-900/30 flex items-center justify-center">
                        <flux:icon name="book-open" class="w-6 h-6 text-{{ $theme['success'] }}-600" />
                    </div>
                    <flux:heading size="sm" class="font-semibold">{{ __('app.home.solution.f2_title') }}</flux:heading>
                    <flux:text size="sm" class="text-zinc-500">{{ __('app.home.solution.f2_desc') }}</flux:text>
                </flux:card>

                <flux:card class="p-6 space-y-3">
                    <div class="w-11 h-11 rounded-lg bg-{{ $theme['warning'] }}-100 dark:bg-{{ $theme['warning'] }}-900/30 flex items-center justify-center">
                        <flux:icon name="academic-cap" class="w-6 h-6 text-{{ $theme['warning'] }}-600" />
                    </div>
                    <flux:heading size="sm" class="font-semibold">{{ __('app.home.solution.f3_title') }}</flux:heading>
                    <flux:text size="sm" class="text-zinc-500">{{ __('app.home.solution.f3_desc') }}</flux:text>
                </flux:card>
            </div>
        </div>

        {{-- ===== COMMENT CA MARCHE ===== --}}
        <div>
            <flux:heading size="xl" class="font-bold text-center mb-10">
                {{ __('app.home.how_it_works.title') }}
            </flux:heading>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-12 h-12 mx-auto rounded-full bg-{{ $theme['primary'] }}-600 text-white flex items-center justify-center font-bold text-lg mb-4">1</div>
                    <flux:heading size="sm" class="font-semibold">{{ __('app.home.how_it_works.s1_title') }}</flux:heading>
                    <flux:text size="sm" class="text-zinc-500 mt-1">{{ __('app.home.how_it_works.s1_desc') }}</flux:text>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 mx-auto rounded-full bg-{{ $theme['primary'] }}-600 text-white flex items-center justify-center font-bold text-lg mb-4">2</div>
                    <flux:heading size="sm" class="font-semibold">{{ __('app.home.how_it_works.s2_title') }}</flux:heading>
                    <flux:text size="sm" class="text-zinc-500 mt-1">{{ __('app.home.how_it_works.s2_desc') }}</flux:text>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 mx-auto rounded-full bg-{{ $theme['primary'] }}-600 text-white flex items-center justify-center font-bold text-lg mb-4">3</div>
                    <flux:heading size="sm" class="font-semibold">{{ __('app.home.how_it_works.s3_title') }}</flux:heading>
                    <flux:text size="sm" class="text-zinc-500 mt-1">{{ __('app.home.how_it_works.s3_desc') }}</flux:text>
                </div>
            </div>
        </div>

        {{-- ===== PLATEFORME EN CHIFFRES ===== --}}
        <livewire:dashboard.stats wire:lazy />

        {{-- ===== CTA FINAL (invités uniquement) ===== --}}
        @guest
            <div class="relative overflow-hidden rounded-2xl
                bg-gradient-to-br from-{{ $theme['success'] }}-600 to-{{ $theme['success'] }}-800
                px-8 py-10 text-white text-center shadow-lg">
                <flux:heading size="xl" class="text-white font-bold">
                    {{ __('app.home.cta_banner.title', ['fail_rate' => number_format($failRate, 2, ',', ' ')]) }}
                </flux:heading>
                <flux:text class="mt-2 text-white/80">
                    {{ __('app.home.cta_banner.description') }}
                </flux:text>
                <flux:button
                    wire:navigate
                    href="{{ route('register') }}"
                    variant="primary"
                    class="mt-6 bg-white! text-{{ $theme['success'] }}-700! hover:bg-zinc-100!"
                >
                    {{ __('app.home.cta_banner.cta_register') }}
                </flux:button>
            </div>
        @endguest

        {{-- ===== DERNIERS CONTENUS ===== --}}
        <div class="space-y-10">
            <livewire:dashboard.latest-articles wire:lazy />
            <livewire:dashboard.latest-subjects wire:lazy />
            <livewire:dashboard.latest-supports wire:lazy />
        </div>

    </div>
</x-layouts.app>
