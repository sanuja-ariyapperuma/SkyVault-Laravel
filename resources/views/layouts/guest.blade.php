<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-50 via-white to-primary-100">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%233b82f6" fill-opacity="0.4"%3E%3Ccircle cx="7" cy="7" r="7"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            </div>
            
            <!-- Login Container -->
            <div class="relative z-10 w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row items-center gap-8">
                    <!-- Left Side - Brand -->
                    <div class="flex-1 text-center lg:text-left animate-fade-in">
                        <div class="mb-8">
                            <a href="/" class="inline-flex items-center group">
                                <div class="p-3 bg-gradient-to-r from-primary-600 to-primary-700 rounded-2xl shadow-colored-primary group-hover:shadow-lg transition-all">
                                    <x-application-logo class="h-8 w-auto fill-current text-white" />
                                </div>
                                <span class="ml-3 text-3xl font-bold text-gray-900 group-hover:text-primary-700 transition-colors">SkyVault</span>
                            </a>
                        </div>
                        <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                            Welcome to <span class="text-primary-600">SkyVault</span>
                        </h1>
                        <p class="text-lg text-gray-600 mb-8 max-w-lg">
                            Your comprehensive customer management solution. Sign in to access your dashboard and manage your customers efficiently.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-5 h-5 mr-2 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Secure Authentication
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-5 h-5 mr-2 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                24/7 Support
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Login Form -->
                    <div class="flex-1 w-full max-w-md animate-slide-up">
                        <div class="bg-white/80 backdrop-blur-sm border border-white/20 rounded-2xl shadow-large p-8">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
