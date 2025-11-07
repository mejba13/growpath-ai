<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')

        <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>

        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-neutral-50">
        <div class="min-h-screen flex" x-data="{ sidebarOpen: false }">
            <!-- Sidebar -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                   class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-neutral-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
                <div class="flex flex-col h-full">
                    <!-- Logo -->
                    <div class="flex items-center justify-between h-16 px-6 border-b border-neutral-200">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-primary-accent to-blue-600 rounded-xl shadow-lg group-hover:scale-105 transition-transform duration-300">
                                <x-app-logo-icon class="size-6 text-white" />
                            </div>
                            <span class="text-xl font-bold bg-gradient-to-r from-primary-accent to-secondary-accent bg-clip-text text-transparent">
                                GrowPath AI
                            </span>
                        </a>
                        <button @click="sidebarOpen = false" class="lg:hidden text-neutral-500 hover:text-neutral-700">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-primary-accent text-white' : 'text-neutral-700 hover:bg-neutral-100' }} transition-colors">
                            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>

                        @can('view-prospects')
                        <a href="{{ route('prospects.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('prospects.*') ? 'bg-primary-accent text-white' : 'text-neutral-700 hover:bg-neutral-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Prospects
                        </a>
                        @endcan

                        @can('view-clients')
                        <a href="{{ route('clients.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('clients.*') ? 'bg-primary-accent text-white' : 'text-neutral-700 hover:bg-neutral-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Clients
                        </a>
                        @endcan

                        @can('view-follow-ups')
                        <a href="{{ route('follow-ups.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('follow-ups.*') ? 'bg-primary-accent text-white' : 'text-neutral-700 hover:bg-neutral-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            Follow-Ups
                        </a>
                        @endcan

                        @can('view-pipeline')
                        <a href="{{ route('pipeline.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('pipeline.*') ? 'bg-primary-accent text-white' : 'text-neutral-700 hover:bg-neutral-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Pipeline
                        </a>
                        @endcan

                        @can('view-reports')
                        <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('reports.*') ? 'bg-primary-accent text-white' : 'text-neutral-700 hover:bg-neutral-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Reports
                        </a>
                        @endcan

                        <a href="{{ route('contact-messages.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('contact-messages.*') ? 'bg-primary-accent text-white' : 'text-neutral-700 hover:bg-neutral-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Contact Messages
                            @if(\App\Models\ContactMessage::where('status', 'new')->count() > 0)
                                <span class="ml-auto bg-warning text-white text-xs font-semibold px-2 py-1 rounded-full">
                                    {{ \App\Models\ContactMessage::where('status', 'new')->count() }}
                                </span>
                            @endif
                        </a>

                        <a href="{{ route('blog-posts.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('blog-posts.*') ? 'bg-primary-accent text-white' : 'text-neutral-700 hover:bg-neutral-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                            Blog Posts
                        </a>

                        <!-- Multi-Tenancy -->
                        <div class="pt-4 mt-4 border-t border-neutral-200">
                            <a href="{{ route('companies.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('companies.*') ? 'bg-primary-accent text-white' : 'text-neutral-700 hover:bg-neutral-100' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Companies
                                <span class="ml-auto bg-primary-accent/10 text-primary-accent text-xs font-semibold px-2 py-1 rounded-full">
                                    {{ auth()->user()->companies()->count() }}
                                </span>
                            </a>
                        </div>

                        @can('manage-team')
                        <div class="pt-4 mt-4 border-t border-neutral-200">
                            <a href="{{ route('team.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('team.*') ? 'bg-primary-accent text-white' : 'text-neutral-700 hover:bg-neutral-100' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Team
                            </a>
                        </div>
                        @endcan

                        @can('manage-settings')
                        <div class="pt-4 mt-4 border-t border-neutral-200">
                            <a href="{{ route('settings.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('settings.*') ? 'bg-primary-accent text-white' : 'text-neutral-700 hover:bg-neutral-100' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Settings
                            </a>
                        </div>
                        @endcan
                    </nav>

                    <!-- User Profile -->
                    <div class="p-4 border-t border-neutral-200" x-data="{ userMenuOpen: false }">
                        <div class="relative">
                            <button @click="userMenuOpen = !userMenuOpen" class="flex items-center w-full px-4 py-3 text-sm font-medium text-neutral-700 rounded-md hover:bg-neutral-100 transition-colors">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary-accent text-white flex items-center justify-center font-semibold">
                                    {{ auth()->user()->initials() }}
                                </div>
                                <div class="ml-3 text-left flex-1">
                                    <p class="text-sm font-medium text-neutral-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-neutral-500 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="userMenuOpen" @click.away="userMenuOpen = false" x-cloak
                                 class="absolute bottom-full left-0 right-0 mb-2 bg-white rounded-md shadow-lg border border-neutral-200 overflow-hidden">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">Profile</a>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">Account Settings</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Top Bar -->
                <header class="bg-white border-b border-neutral-200 sticky top-0 z-40">
                    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                        <!-- Mobile menu button -->
                        <button @click="sidebarOpen = true" class="lg:hidden text-neutral-500 hover:text-neutral-700">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <!-- Page Title -->
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-primary-brand">@yield('page-title', 'Dashboard')</h1>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-4">
                            <!-- Company Switcher -->
                            <x-company.switcher />

                            <!-- Notifications -->
                            <button class="text-neutral-500 hover:text-neutral-700 hidden sm:block">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto bg-neutral-50">
                    <div class="max-w-dashboard mx-auto px-4 sm:px-6 lg:px-8 py-8">
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>

        <!-- Overlay for mobile sidebar -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
             class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>

        @stack('scripts')
    </body>
</html>
