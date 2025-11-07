<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name') . ' - Client Hunting SaaS')</title>
        <meta name="description" content="@yield('description', 'Discover, organize, and convert high-value clients with GrowPath AI.')">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="@yield('og_title', config('app.name'))">
        <meta property="og:description" content="@yield('og_description', 'Discover, organize, and convert high-value clients with GrowPath AI.')">
        <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url()->current() }}">
        <meta property="twitter:title" content="@yield('twitter_title', config('app.name'))">
        <meta property="twitter:description" content="@yield('twitter_description', 'Discover, organize, and convert high-value clients with GrowPath AI.')">
        <meta property="twitter:image" content="@yield('twitter_image', asset('images/og-default.jpg'))">

        <!-- Canonical URL -->
        <link rel="canonical" href="{{ url()->current() }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-white text-primary-brand">
        <!-- Landing Navigation -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-neutral-200">
            <div class="max-w-full-width mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2">
                            <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gradient-to-br from-primary-accent to-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-5 h-5 text-white">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <span class="text-2xl font-bold text-primary-brand">GrowPath AI</span>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex md:items-center md:space-x-8">
                        <a href="{{ route('home') }}" class="text-neutral-700 hover:text-primary-accent transition-colors duration-200">Home</a>
                        <a href="#features" class="text-neutral-700 hover:text-primary-accent transition-colors duration-200">Features</a>
                        <a href="#pricing" class="text-neutral-700 hover:text-primary-accent transition-colors duration-200">Pricing</a>
                        <a href="#contact" class="text-neutral-700 hover:text-primary-accent transition-colors duration-200">Contact</a>
                    </div>

                    <!-- Auth Links -->
                    <div class="hidden md:flex md:items-center md:space-x-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-neutral-700 hover:text-primary-accent transition-colors duration-200">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-neutral-700 hover:text-primary-accent transition-colors duration-200">Login</a>
                            <a href="{{ route('register') }}" class="px-6 py-2 bg-primary-accent text-white rounded-md hover:bg-blue-600 transition-colors duration-200 font-semibold">
                                Get Started
                            </a>
                        @endauth
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="text-neutral-700 hover:text-primary-accent">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-neutral-200 bg-white">
                <div class="px-4 pt-2 pb-3 space-y-1">
                    <a href="{{ route('home') }}" class="block px-3 py-2 text-neutral-700 hover:text-primary-accent hover:bg-neutral-50 rounded-md">Home</a>
                    <a href="#features" class="block px-3 py-2 text-neutral-700 hover:text-primary-accent hover:bg-neutral-50 rounded-md">Features</a>
                    <a href="#pricing" class="block px-3 py-2 text-neutral-700 hover:text-primary-accent hover:bg-neutral-50 rounded-md">Pricing</a>
                    <a href="#contact" class="block px-3 py-2 text-neutral-700 hover:text-primary-accent hover:bg-neutral-50 rounded-md">Contact</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-neutral-700 hover:text-primary-accent hover:bg-neutral-50 rounded-md">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="block px-3 py-2 text-neutral-700 hover:text-primary-accent hover:bg-neutral-50 rounded-md">Login</a>
                        <a href="{{ route('register') }}" class="block px-3 py-2 bg-primary-accent text-white hover:bg-blue-600 rounded-md font-semibold text-center">Get Started</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="pt-16">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-primary-brand text-white mt-24">
            <div class="max-w-full-width mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Brand -->
                    <div class="col-span-1">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-primary-accent to-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-6 h-6 text-white">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold">GrowPath AI</h3>
                        </div>
                        <p class="text-neutral-300">Modern CRM solution designed to help businesses grow faster and manage customer relationships effectively.</p>
                    </div>

                    <!-- Product -->
                    <div>
                        <h4 class="font-semibold mb-4">Product</h4>
                        <ul class="space-y-2">
                            <li><a href="#features" class="text-neutral-300 hover:text-white transition-colors">Features</a></li>
                            <li><a href="#pricing" class="text-neutral-300 hover:text-white transition-colors">Pricing</a></li>
                            <li><a href="#" class="text-neutral-300 hover:text-white transition-colors">Updates</a></li>
                        </ul>
                    </div>

                    <!-- Company -->
                    <div>
                        <h4 class="font-semibold mb-4">Company</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-neutral-300 hover:text-white transition-colors">About</a></li>
                            <li><a href="#contact" class="text-neutral-300 hover:text-white transition-colors">Contact</a></li>
                            <li><a href="#" class="text-neutral-300 hover:text-white transition-colors">Blog</a></li>
                        </ul>
                    </div>

                    <!-- Legal -->
                    <div>
                        <h4 class="font-semibold mb-4">Legal</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-neutral-300 hover:text-white transition-colors">Privacy Policy</a></li>
                            <li><a href="#" class="text-neutral-300 hover:text-white transition-colors">Terms of Service</a></li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-neutral-700 mt-8 pt-8 text-center text-neutral-300">
                    <p>&copy; {{ date('Y') }} GrowPath AI. All rights reserved.</p>
                </div>
            </div>
        </footer>

        @stack('scripts')

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('landing', () => ({
                    mobileMenuOpen: false
                }))
            })
        </script>
    </body>
</html>
