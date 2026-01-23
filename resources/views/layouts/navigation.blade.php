<nav class="bg-white shadow-large border-r border-gray-100 transition-all duration-300 ease-in-out fixed lg:relative h-screen z-40 lg:z-auto flex flex-col"
    :class="{
        'w-64': sidebarOpen && window.innerWidth >= 1024, 
        'w-16': !sidebarOpen && window.innerWidth >= 1024, 
        'w-80 inset-0': sidebarOpen && window.innerWidth < 1024,
        '-translate-x-full': !sidebarOpen && window.innerWidth < 1024,
        'lg:translate-x-0': true
    }">
    
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
        <!-- Logo -->
        <div class="flex items-center" :class="{'justify-center': !sidebarOpen}">
            <a href="{{ route('dashboard') }}" class="flex items-center group">
                <div class="p-2 bg-gradient-to-r from-primary-600 to-primary-700 rounded-xl shadow-colored-primary group-hover:shadow-lg transition-all">
                    <x-application-logo class="h-6 w-auto fill-current text-white" />
                </div>
                <span x-show="sidebarOpen" x-transition class="ml-3 text-xl font-bold text-gray-900 group-hover:text-primary-700 transition-colors">SkyVault</span>
            </a>
        </div>

        <!-- Toggle Button (Desktop) -->
        <button @click="sidebarOpen = !sidebarOpen" 
                class="hidden lg:flex items-center justify-center p-2 rounded-xl text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-200 transition-all duration-200">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 px-3 py-6 space-y-2 overflow-y-auto min-h-0">
        <!-- Dashboard Link -->
        <a href="{{ route('dashboard') }}" 
           class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200
                  {{ request()->routeIs('dashboard') 
                      ? 'bg-gradient-to-r from-primary-50 to-primary-100 text-primary-700 border border-primary-200 shadow-soft' 
                      : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:shadow-soft' }}"
           :class="{'justify-center': !sidebarOpen}">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <span x-show="sidebarOpen" x-transition class="ml-3 font-medium">Dashboard</span>
            @if(request()->routeIs('dashboard'))
                <span class="ml-auto w-2 h-2 bg-primary-500 rounded-full animate-pulse"></span>
            @endif
        </a>
    </div>

    <!-- User Section - Fixed at Bottom -->
    <div class="border-t border-gray-100 p-4 flex-shrink-0 bg-gradient-to-t from-gray-50 to-white">
        <!-- Desktop User Menu -->
        <div class="hidden lg:block">
            <div class="space-y-3">
                <!-- User Name Display -->
                <div class="px-3 py-3">
                    <div class="flex items-center p-3 bg-white rounded-xl shadow-soft" :class="{'justify-center': !sidebarOpen}">
                        <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center text-white font-bold shadow-colored-primary">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div x-show="sidebarOpen" x-transition class="ml-3">
                            <div class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                </div>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="group w-full flex items-center px-4 py-3 text-sm font-medium rounded-xl text-red-600 hover:bg-red-50 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-200 transition-all duration-200 hover:shadow-soft"
                            :class="{'justify-center': !sidebarOpen}">
                        <svg class="flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition class="ml-3 font-medium">Log Out</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Mobile User Menu -->
        <div class="lg:hidden">
            <div class="space-y-3">
                <!-- User Name Display -->
                <div class="px-3 py-3">
                    <div class="flex items-center p-3 bg-white rounded-xl shadow-soft">
                        <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center text-white font-bold shadow-colored-primary">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                </div>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="group w-full flex items-center px-4 py-3 text-sm font-medium rounded-xl text-red-600 hover:bg-red-50 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-200 transition-all duration-200 hover:shadow-soft">
                        <svg class="flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="ml-3 font-medium">Log Out</span>
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
         class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm z-30 lg:hidden"
         style="margin-left: 320px;">
    </div>
</nav>

<!-- Mobile Menu Button - Outside nav to be always visible -->
<div class="lg:hidden fixed top-4 right-4 z-50">
    <button @click="sidebarOpen = !sidebarOpen" 
            class="gradient-primary text-white p-3 rounded-xl shadow-large hover:shadow-lg transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path x-show="sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
