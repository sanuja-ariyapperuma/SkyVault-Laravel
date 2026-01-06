<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SkyVault') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ 
            sidebarOpen: window.innerWidth >= 1024,
            init() {
                // Set initial state based on screen size
                this.updateSidebarState();
                // Listen for window resize
                window.addEventListener('resize', () => this.updateSidebarState());
            },
            updateSidebarState() {
                if (window.innerWidth < 1024) {
                    // On small screens, start closed but allow full-screen overlay
                    if (this.sidebarOpen === true) {
                        // Only close if it was opened on desktop and then resized
                        this.sidebarOpen = false;
                    }
                } else {
                    // Auto-expand on large screens
                    this.sidebarOpen = true;
                }
            }
        }" class="min-h-screen bg-gray-100 flex">
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col lg:ml-0">
                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
