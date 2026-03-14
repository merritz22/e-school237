<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }}</title>
    <meta name="description" content="@yield('description', 'Plateforme éducative avec articles, sujets d\'évaluation et supports pédagogiques')">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 antialiased font-[Inter]">
    @php
        $theme  = config('theme');
        $locale = session('locale', config('app.locale'));

        $languages = [
            'fr' => ['label' => 'Français', 'flag' => '🇫🇷', 'short' => 'FR'],
            'en' => ['label' => 'English',  'flag' => '🇬🇧', 'short' => 'EN'],
        ];
        // ✅ Déclaré APRÈS $languages pour pouvoir valider
        $locale = session('locale', config('app.locale'));
        if (!is_string($locale) || !array_key_exists($locale, $languages)) {
            $locale = 'fr';
        }

        $unreadNotifications = 0;

        if (Auth::check()) {
            $user                = auth()->user();
            $initials            = strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1));
            $unreadNotifications = $user->notifications()->wherePivot('read_at', null)->count();
        }
    @endphp

    {{-- ===== HEADER ===== --}}
    <flux:header container class="sticky top-0 z-50
        bg-white/80 dark:bg-zinc-900/80
        backdrop-blur-md
        border-b border-zinc-200 dark:border-zinc-800
        shadow-sm">

        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:brand
            href="{{ route('home') }}"
            logo="{{ Vite::asset('resources/images/e-school-logo.jpg') }}"
            name="{{ config('app.name', 'E-School237') }}"
            class="max-lg:hidden font-semibold tracking-tight"
        />

        <flux:navbar class="-mb-px max-lg:hidden ml-6">
            <flux:navbar.item wire:navigate icon="home"          href="{{ route('home') }}">{{ __('app.nav.home') }}</flux:navbar.item>
            <flux:navbar.item wire:navigate icon="document-text" href="{{ route('articles.index') }}">{{ __('app.nav.articles') }}</flux:navbar.item>
            <flux:navbar.item wire:navigate icon="book-open"     href="{{ route('resources.index') }}">{{ __('app.nav.resources') }}</flux:navbar.item>
            <flux:navbar.item wire:navigate icon="academic-cap"  href="{{ route('subjects.index') }}">{{ __('app.nav.subjects') }}</flux:navbar.item>
            @auth
                <flux:navbar.item wire:navigate icon="banknotes" href="{{ route('subscriptions.index') }}">{{ __('app.nav.subscriptions') }}</flux:navbar.item>
            @endauth
        </flux:navbar>

        <flux:spacer />

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

            {{-- ===== SÉLECTEUR DE LANGUE ===== --}}
            <flux:dropdown position="bottom" align="end">
                <flux:button variant="subtle" size="sm" class="rounded-full gap-1 font-medium">
                    <span>{{ $languages[$locale]['flag'] }}</span>
                    <span class="hidden sm:inline">{{ $languages[$locale]['short'] }}</span>
                    <flux:icon name="chevron-down" class="w-3 h-3 opacity-50" />
                </flux:button>

                <flux:menu>
                    @foreach($languages as $code => $lang)
                        <flux:menu.item href="{{ route('lang.switch', ['locale' => $code]) }}"
                            class="{{ $locale === $code ? 'font-semibold bg-' . $theme['primary'] . '-50 dark:bg-' . $theme['primary'] . '-900/20' : '' }}"
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

            @guest
                <div class="flex items-center gap-2">
                    <flux:button variant="{{ $theme['btn_secondary'] }}" href="{{ route('register') }}" size="sm">
                        {{ __('app.nav.register') }}
                    </flux:button>
                    <flux:button variant="{{ $theme['btn_primary'] }}" href="{{ route('login') }}" size="sm">
                        {{ __('app.nav.login') }}
                    </flux:button>
                </div>
            @else
                {{-- Notifications --}}
                <div class="relative cursor-pointer"
                    x-data="{ count: {{ $unreadNotifications }} }"
                    x-on:notif-read.window="count = Math.max(0, count - 1)"
                >
                    <flux:modal.trigger name="user-notif">
                        <flux:button icon="bell" variant="subtle" size="sm" class="rounded-full" />
                        <span
                            x-show="count > 0"
                            x-text="count > 9 ? '9+' : count"
                            class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center
                                rounded-full bg-{{ $theme['danger'] }}-500 text-white text-[10px] font-bold"
                        ></span>
                    </flux:modal.trigger>
                </div>

                {{-- Profil --}}
                <flux:dropdown position="bottom" align="end" wire:ignore>
                    <div class="cursor-pointer ring-2 ring-{{ $theme['primary'] }}-200
                        dark:ring-{{ $theme['primary'] }}-800 rounded-full
                        hover:ring-{{ $theme['primary'] }}-400 transition-all">
                        @if($user->avatar_url)
                            <flux:profile circle avatar="{{ asset('storage/' . $user->avatar_url) }}" />
                        @else
                            <flux:profile circle avatar:name="{{ $initials }}" />
                        @endif
                    </div>

                    <flux:navmenu class="w-56">
                        <div class="px-3 py-2 border-b border-zinc-100 dark:border-zinc-800">
                            <flux:text size="xs" class="text-zinc-400 uppercase tracking-wider">
                                {{ __('app.theme.connected_as') }}
                            </flux:text>
                            <flux:heading size="sm" class="mt-0.5 truncate">{{ $user->name }}</flux:heading>
                            <flux:text size="xs" class="text-zinc-400 truncate">{{ $user->email }}</flux:text>
                        </div>

                        <flux:navmenu.item icon="user" href="{{ route('user.profile') }}">
                            {{ __('app.nav.profile') }}
                        </flux:navmenu.item>

                        @if($user->role === 'admin')
                            <flux:navmenu.item icon="cog-6-tooth" href="{{ route('admin.dashboard') }}">
                                {{ __('app.nav.admin') }}
                            </flux:navmenu.item>
                        @endif

                        <flux:navmenu.item icon="information-circle" href="#">
                            {{ __('app.nav.help') }}
                        </flux:navmenu.item>

                        <flux:navmenu.separator />

                        <form method="POST" action="/auth/logout">
                            @csrf
                            <flux:navmenu.item
                                type="submit"
                                icon="arrow-right-start-on-rectangle"
                                class="text-{{ $theme['danger'] }}-500 w-full"
                            >
                                {{ __('app.nav.logout') }}
                            </flux:navmenu.item>
                        </form>
                    </flux:navmenu>
                </flux:dropdown>
            @endguest
        </div>
    </flux:header>

    {{-- ===== SIDEBAR MOBILE ===== --}}
    <flux:sidebar sticky collapsible="mobile"
        class="lg:hidden bg-white dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-800"
        wire:ignore>

        <flux:sidebar.header class="border-b border-zinc-100 dark:border-zinc-800 pb-3">
            <flux:sidebar.brand
                wire:navigate
                href="{{ route('home') }}"
                logo="{{ Vite::asset('resources/images/e-school-logo.jpg') }}"
                name="{{ config('app.name', 'E-School237') }}"
            />
            <flux:sidebar.collapse />
        </flux:sidebar.header>

        <flux:sidebar.nav class="mt-2">
            <flux:sidebar.item wire:navigate icon="home"          href="{{ route('home') }}">{{ __('app.nav.home') }}</flux:sidebar.item>
            <flux:sidebar.item wire:navigate icon="document-text" href="{{ route('articles.index') }}">{{ __('app.nav.articles') }}</flux:sidebar.item>
            <flux:sidebar.item wire:navigate icon="book-open"     href="{{ route('resources.index') }}">{{ __('app.nav.resources') }}</flux:sidebar.item>
            <flux:sidebar.item wire:navigate icon="academic-cap"  href="{{ route('subjects.index') }}">{{ __('app.nav.subjects') }}</flux:sidebar.item>
            @auth
                <flux:sidebar.item wire:navigate icon="banknotes" href="{{ route('subscriptions.index') }}">{{ __('app.nav.subscriptions') }}</flux:sidebar.item>
            @endauth

            @auth
                <flux:separator />

                {{-- Langue dans sidebar mobile --}}
                <div class="px-3 py-2">
                    <flux:text size="xs" class="text-zinc-400 uppercase tracking-wider mb-2">
                        {{ $locale === 'fr' ? 'Langue' : 'Language' }}
                    </flux:text>
                    <div class="flex gap-2">
                        @foreach($languages as $code => $lang)
                            <a href="{{ route('lang.switch', ['locale' => $code]) }}"
                               class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium transition
                                   {{ $locale === $code
                                       ? 'bg-' . $theme['primary'] . '-100 text-' . $theme['primary'] . '-700 dark:bg-' . $theme['primary'] . '-900/30'
                                       : 'text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                                <span>{{ $lang['flag'] }}</span>
                                <span>{{ $lang['short'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <flux:separator />

                <flux:sidebar.item wire:navigate icon="user" href="{{ route('user.profile') }}">
                    {{ __('app.nav.profile') }}
                </flux:sidebar.item>

                @if($user->role === 'admin')
                    <flux:sidebar.item wire:navigate icon="cog-6-tooth" href="{{ route('admin.dashboard') }}">
                        {{ __('app.nav.admin') }}
                    </flux:sidebar.item>
                @endif

                <form method="POST" action="/auth/logout">
                    @csrf
                    <flux:sidebar.item type="submit" icon="arrow-right-start-on-rectangle"
                        class="text-{{ $theme['danger'] }}-500">
                        {{ __('app.nav.logout') }}
                    </flux:sidebar.item>
                </form>
            @endauth
        </flux:sidebar.nav>
    </flux:sidebar>

    {{-- ===== CONTENU PRINCIPAL ===== --}}
    <flux:main container class="px-4 sm:px-6 lg:px-8 py-6">

        @if(session('success'))
            <flux:callout icon="check-circle" color="{{ $theme['success'] }}" class="mb-6">
                {{ session('success') }}
            </flux:callout>
        @endif

        @if(session('error'))
            <flux:callout icon="x-circle" color="{{ $theme['danger'] }}" class="mb-6">
                {{ session('error') }}
            </flux:callout>
        @endif

        {{ $slot }}
    </flux:main>

    {{-- ===== MODAL NOTIFICATIONS ===== --}}
    @auth
        <flux:modal name="user-notif" wire:ignore class="flex flex-col w-full max-w-sm">
            <div class="px-4 py-3 border-b border-zinc-100 dark:border-zinc-800">
                <flux:heading size="sm">{{ __('app.notifications.title') }}</flux:heading>
            </div>
            <div class="overflow-y-auto flex-1">
                <livewire:notifications.show />
            </div>
        </flux:modal>
    @endauth

    @fluxScripts
</body>
</html>