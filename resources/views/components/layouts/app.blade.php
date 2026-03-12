<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? config('app.name') }}</title>
    <meta name="description" content="@yield('description', 'Plateforme éducative avec articles, sujets d\'évaluation et supports pédagogiques')">
    
    <!-- Fonts -->
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
     
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Styles -->
    @fluxAppearance
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800 antialiased">
    
    @php
        $user = auth()->user();

        $initials = strtoupper(substr($user->first_name,0,1) . substr($user->last_name,0,1));
        $unreadNotifications = $user->notifications()
            ->where('is_read', false)
            ->count();
    @endphp
    <flux:header container class="sticky top-0 z-50 bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        <flux:brand href="{{ route('home') }}" logo="{{ Vite::asset('resources/images/e-school-logo.jpg') }}" name="{{ config('app.name', 'EduSite') }}" class="max-lg:hidden" />
        <flux:navbar class="-mb-px max-lg:hidden">
            <flux:navbar.item wire:navigate icon="home" href="{{ route('home') }}">Accueil</flux:navbar.item>
            <flux:navbar.item wire:navigate icon="document-text" href="{{ route('articles.index') }}">Articles</flux:navbar.item>
            <flux:navbar.item wire:navigate icon="book-open" href="{{ route('resources.index') }}">Supports</flux:navbar.item>
            <flux:navbar.item wire:navigate icon="academic-cap" href="{{ route('subjects.index') }}">Sujets</flux:navbar.item>
            @if(Auth::check())
                <flux:navbar.item wire:navigate icon="banknotes" href="{{ route('subscriptions.index') }}">Abonnements</flux:navbar.item>
            @endif
        </flux:navbar>
        <flux:spacer />
        <flux:button icon="moon" x-data x-on:click="$flux.dark = ! $flux.dark" variant="subtle" class="mr-2"/>
        @guest
        <div class="flex space-x-2">
            <flux:button variant="primary"  href="{{ route('login') }}" class="w-full">Connexion</flux:button>
            <flux:button href="{{ route('register') }}"  class="w-full">Inscription</flux:button>
        </div>
        @else
            <flux:modal.trigger name="user-notif">
                <flux:badge icon="bell" class="mr-2">{{ $unreadNotifications }}</flux:badge>
            </flux:modal.trigger>
            <flux:dropdown position="top" align="start" wire:ignore>
                @if($user->avatar_url)
                    <flux:profile circle avatar="{{ asset('storage/'.$user->avatar_url) }}" />
                @else
                    <flux:profile circle avatar:name="{{ $initials }}" />
                @endif

                <flux:navmenu>
                    <div class="px-2 py-1.5">
                        <flux:text size="sm">Connecté en tant que</flux:text>
                        <flux:heading class="mt-1! truncate">{{ $user->name }}</flux:heading>
                    </div>
                    <flux:navmenu.separator />
                    <flux:navmenu.item icon="user" href="{{ route('user.profile') }}">Profile</flux:navmenu.item>
                    <flux:navmenu.item icon="information-circle" href="#">Help</flux:navmenu.item>
                    <flux:navmenu.separator />
                    <form method="POST" action="/auth/logout">
                        @csrf
                        <flux:navmenu.item type="submit" icon="arrow-right-start-on-rectangle" class="text-zinc-800 dark:text-white w-full">Déconnexion</flux:navmenu.item>
                    </form>
                </flux:navmenu>
            </flux:dropdown>
        @endguest
    </flux:header>
    <flux:sidebar sticky collapsible="mobile" class="lg:hidden bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700" wire:ignore>
        <flux:sidebar.header>
            <flux:sidebar.brand wire:navigate
                href="{{ route('home') }}"
                logo="{{ Vite::asset('resources/images/e-school-logo.jpg') }}"
                name="{{ config('app.name', 'EduSite') }}"
            />
            <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
        </flux:sidebar.header>
        <flux:sidebar.nav>
            <flux:sidebar.item wire:navigate icon="home" href="{{ route('home') }}">Accueil</flux:sidebar.item>
            <flux:sidebar.item wire:navigate icon="document-text" href="{{ route('articles.index') }}">Articles</flux:sidebar.item>
            <flux:sidebar.item wire:navigate icon="book-open" href="{{ route('resources.index') }}">Supports</flux:sidebar.item>
            <flux:sidebar.item wire:navigate icon="academic-cap" href="{{ route('subjects.index') }}">Sujets</flux:sidebar.item>
            @if(Auth::check())
                <flux:navbar.item wire:navigate icon="banknotes" href="{{ route('subscriptions.index') }}">Abonnements</flux:navbar.item>
            @endif
        </flux:sidebar.nav>
    </flux:sidebar>
    <flux:main container  class="bg-zinc-50 dark:bg-zinc-900 px-4 sm:px-6 lg:px-8">
        {{ $slot }}
    </flux:main>
    <flux:modal name="user-notif" wire:ignore class="max-h-96 flex flex-col">
        <livewire:notifications.show />
    </flux:modal>
    @fluxScripts
</body>
</html>