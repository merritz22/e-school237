<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }}</title>
    <meta name="description" content="@yield('description', __('app.meta.description'))">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 antialiased font-[Inter]">

    @php
        $languages = [
            'fr' => ['label' => 'Français', 'flag' => '🇫🇷', 'short' => 'FR'],
            'en' => ['label' => 'English',  'flag' => '🇬🇧', 'short' => 'EN'],
        ];
        $locale = session('locale', config('app.locale'));
        if (!is_string($locale) || !array_key_exists($locale, $languages)) {
            $locale = 'fr';
        }
        $theme = config('theme');
    @endphp

    {{-- Barre minimale avec logo + switcher langue --}}
    <div class="w-full px-6 py-4 flex items-center justify-between
        border-b border-zinc-200 dark:border-zinc-800
        bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <img src="{{ Vite::asset('resources/images/e-school-logo.jpg') }}"
                 alt="{{ config('app.name') }}"
                 class="h-8 w-8 rounded-md object-cover" />
            <span class="font-semibold text-zinc-800 dark:text-white tracking-tight">
                {{ config('app.name', 'E-School237') }}
            </span>
        </a>

        {{-- Actions --}}
        <div class="flex items-center gap-2">

            {{-- Dark mode --}}
            <flux:button
                icon="moon"
                x-data
                x-on:click="$flux.dark = !$flux.dark"
                variant="subtle"
                size="sm"
                class="rounded-full"
            />

            {{-- Sélecteur de langue --}}
            <flux:dropdown position="bottom" align="end">
                <flux:button variant="subtle" size="sm" class="rounded-full gap-1 font-medium">
                    <span>{{ $languages[$locale]['flag'] }}</span>
                    <span class="hidden sm:inline">{{ $languages[$locale]['short'] }}</span>
                    <flux:icon name="chevron-down" class="w-3 h-3 opacity-50" />
                </flux:button>

                <flux:menu>
                    @foreach($languages as $code => $lang)
                        <flux:menu.item
                            href="{{ route('lang.switch', ['locale' => $code]) }}"
                            class="{{ $locale === $code ? 'font-semibold' : '' }}"
                        >
                            <span class="mr-2">{{ $lang['flag'] }}</span>
                            {{ $lang['label'] }}
                            @if($locale === $code)
                                <flux:icon name="check" class="w-3.5 h-3.5 ml-auto text-{{ $theme['primary'] }}-500" />
                            @endif
                        </flux:menu.item>
                    @endforeach
                </flux:menu>
            </flux:dropdown>
        </div>
    </div>

    {{-- Contenu --}}
    <main class="flex min-h-[calc(100vh-65px)] flex-col items-center justify-center px-4 py-12">
        {{ $slot }}
    </main>

    @fluxScripts
</body>
</html>