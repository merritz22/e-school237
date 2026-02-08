<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', config('app.name', 'EduSite'))</title>
    <meta name="description" content="@yield('description', 'Plateforme éducative avec articles, sujets d\'évaluation et supports pédagogiques')">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div id="app">
        <!-- Navigation -->
        <nav class="bg-red shadow-sm border-b border-gray-100 ">
            <div class="px-2 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center">
                            <div class="flex-shrink-0 flex items-center">
                                <img src="{{ Vite::asset('resources/images/e-school-logo.jpg') }}" alt="" class="w-5 h-5 md:w-10 md:h-10 rounded bg-blue" srcset="">
                                <h1 class="text-sm md:text-xl font-bold text-primary">{{ config('app.name', 'EduSite') }}</h1>
                            </div>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <a href="{{ route('home') }}" class="@if(request()->routeIs('home')) text-primary border-b-2 border-[#03386a] @else text-gray-600 hover:text-primary @endif px-3 py-2 text-sm font-medium transition-colors">
                                Accueil
                            </a>
                            <a href="{{ route('articles.index') }}" class="@if(request()->routeIs('articles.*')) text-primary border-b-2 border-[#03386a] @else text-gray-600 hover:text-primary @endif px-3 py-2 text-sm font-medium transition-colors">
                                Articles
                            </a>
                            <a href="{{ route('resources.index') }}" class="@if(request()->routeIs('resources.*')) text-primary border-b-2 border-[#03386a] @else text-gray-600 hover:text-primary @endif px-3 py-2 text-sm font-medium transition-colors">
                                Supports
                            </a>
                            <a href="{{ route('subjects.index') }}" class="@if(request()->routeIs('subjects.*')) text-primary border-b-2 border-[#03386a] @else text-gray-600 hover:text-primary @endif px-3 py-2 text-sm font-medium transition-colors">
                                Sujets
                            </a>
                            @if(Auth::check())
                                <a href="{{ route('subscriptions.index') }}" class="@if(request()->routeIs('subscriptions.*')) text-primary border-b-2 border-[#03386a] @else text-gray-600 hover:text-primary @endif px-3 py-2 text-sm font-medium transition-colors">
                                    Abonnements
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Auth Links -->
                    <div class="flex items-center space-x-4">
                        @guest
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-primary px-2 py-2 text-sm font-medium">
                                Connexion
                            </a>
                            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-2 rounded-lg text-sm font-medium transition-colors">
                                Inscription
                            </a>
                        @else
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=3b82f6&color=fff' }}" alt="{{ Auth::user()->name }}">
                                    <span class="ml-2 text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                                    <svg class="ml-1 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                    <a href="{{ route('user.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Profil
                                    </a>
                                    @if(Auth::user()->hasRole(['admin']))
                                        <div class="border-t border-gray-100"></div>
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                            <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                            </svg>
                                            Administration
                                        </a>
                                    @endif
                                    <div class="border-t border-gray-100"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Déconnexion
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button type="button" class="text-gray-600 hover:text-primary focus:outline-none focus:text-primary" x-data @click="$dispatch('toggle-mobile-menu')">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div class="md:hidden" x-data="{ open: false }" @toggle-mobile-menu.window="open = !open" x-show="open" x-transition>
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 border-t border-gray-200">
                    <a href="{{ route('home') }}" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary">Accueil</a>
                    <a href="{{ route('articles.index') }}" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary">Articles</a>
                    <a href="{{ route('subjects.index') }}" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary">Sujets</a>
                    <a href="{{ route('resources.index') }}" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary">Supports</a>
                    @if(Auth::check())
                        <a href="{{ route('subscriptions.index') }}" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary">Abonnements</a>
                    @endif
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-lg mx-4 mt-4" x-data x-init="setTimeout(() => $el.style.display = 'none', 5000)">
                <div class="flex">
                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mx-4 mt-4" x-data x-init="setTimeout(() => $el.style.display = 'none', 5000)">
                <div class="flex">
                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <main class="min-h-screen">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="col-span-1 md:col-span-2">
                        <h3 class="text-lg font-semibold mb-4">{{ config('app.name', 'E-School237') }}</h3>
                        <p class="text-gray-300 mb-4">Plateforme éducative dédiée au partage d'articles, de sujets d'évaluation et de supports pédagogiques.</p>
                        <div class="flex space-x-4">
                            <!-- Social links -->
                            <a href="https://www.facebook.com/share/1DGRporPoN" class="text-gray-400 hover:text-white">Facebook</a>
                            <a href="#" class="text-gray-400 hover:text-white">Twitter</a>
                            <a href="https://www.youtube.com/@E-School237" class="text-gray-400 hover:text-white">Youtube</a>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold mb-4">Navigation</h4>
                        <ul class="space-y-2 text-gray-300">
                            <li><a href="{{ route('articles.index') }}" class="hover:text-white">Articles</a></li>
                            <li><a href="{{ route('resources.index') }}" class="hover:text-white">Supports pédagogiques</a></li>
                            <li><a href="{{ route('subjects.index') }}" class="hover:text-white">Sujets</a></li>
                            {{-- <li><a href="{{ route('blog.index') }}" class="hover:text-white">Blog</a></li> --}}
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold mb-4">Support</h4>
                        <ul class="space-y-2 text-gray-300">
                            <li><a href="#" class="hover:text-white">FAQ</a></li>
                            <li><a href="#" class="hover:text-white">Contact</a></li>
                            <li><a href="#" class="hover:text-white">Conditions d'utilisation</a></li>
                            <li><a href="#" class="hover:text-white">Politique de confidentialité</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                    <p>&copy; {{ date('Y') }} {{ config('app.name', 'EduSite') }}. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Additional Scripts -->
    @stack('scripts')
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>