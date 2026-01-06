<nav class="bg-white shadow-lg border-r border-gray-200 transition-all duration-300 ease-in-out fixed lg:relative h-screen z-40 lg:z-auto flex flex-col"
    :class="{
        'w-64': sidebarOpen && window.innerWidth >= 1024, 
        'w-16': !sidebarOpen && window.innerWidth >= 1024, 
        'w-80 inset-0': sidebarOpen && window.innerWidth < 1024,
        '-translate-x-full': !sidebarOpen && window.innerWidth < 1024,
        'lg:translate-x-0': true
    }">
    
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between p-4 border-b border-gray-200">
        <!-- Logo -->
        <div class="flex items-center" :class="{'justify-center': !sidebarOpen}">
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <x-application-logo class="h-8 w-auto fill-current text-gray-800" />
                <span x-show="sidebarOpen" x-transition class="ml-3 text-xl font-semibold text-gray-800">SkyVault</span>
            </a>
        </div>

        <!-- Toggle Button (Desktop) -->
        <button @click="sidebarOpen = !sidebarOpen" 
                class="hidden lg:flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 px-2 py-6 space-y-2 overflow-y-auto min-h-0">
        <!-- Dashboard Link -->
        <a href="{{ route('dashboard') }}" 
           class="flex items-center px-3 py-3 text-sm font-medium rounded-md transition-colors duration-150
                  {{ request()->routeIs('dashboard') 
                      ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' 
                      : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
           :class="{'justify-center': !sidebarOpen}">
            <svg class="flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span x-show="sidebarOpen" x-transition class="ml-3">Dashboard</span>
        </a>
    </div>

    <!-- User Section - Fixed at Bottom -->
    <div class="border-t border-gray-200 p-4 flex-shrink-0">
        <!-- Desktop User Menu -->
        <div class="hidden lg:block">
            <div class="space-y-2">
                <!-- User Name Display -->
                <div class="px-3 py-2">
                    <div class="flex items-center" :class="{'justify-center': !sidebarOpen}">
                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div x-show="sidebarOpen" x-transition class="ml-3">
                            <div class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                </div>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center px-3 py-2 text-sm font-medium rounded-md text-red-600 hover:bg-red-50 hover:text-red-700 focus:outline-none focus:bg-red-50 transition duration-150 ease-in-out"
                            :class="{'justify-center': !sidebarOpen}">
                        <svg class="flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition class="ml-3">Log Out</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Mobile User Menu -->
        <div class="lg:hidden">
            <div class="space-y-2">
                <!-- User Name Display -->
                <div class="px-3 py-2">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                </div>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center px-3 py-2 text-sm font-medium rounded-md text-red-600 hover:bg-red-50 hover:text-red-700 focus:outline-none focus:bg-red-50 transition duration-150 ease-in-out">
                        <svg class="flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="ml-3">Log Out</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div x-show="sidebarOpen && window.innerWidth < 1024" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-gray-600 bg-opacity-75 z-30 lg:hidden"
         style="margin-left: 320px;">
    </div>
</nav>

<!-- Mobile Menu Button - Outside nav to be always visible -->
<div class="lg:hidden fixed top-4 right-4 z-50">
    <button @click="sidebarOpen = !sidebarOpen" 
            class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-md shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path x-show="sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
