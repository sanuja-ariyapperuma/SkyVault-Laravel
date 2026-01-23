<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                Dashboard
            </h2>
            <div class="flex items-center space-x-4">
                <x-primary-button>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Customer
                </x-primary-button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl p-6 text-white shadow-large animate-scale-in">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-primary-100 text-sm font-medium">Total Customers</p>
                            <p class="text-3xl font-bold mt-2">1,234</p>
                            <p class="text-primary-100 text-sm mt-2">+12% from last month</p>
                        </div>
                        <div class="bg-white/20 p-3 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-success-500 to-success-600 rounded-2xl p-6 text-white shadow-large animate-scale-in" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-success-100 text-sm font-medium">Active Bookings</p>
                            <p class="text-3xl font-bold mt-2">89</p>
                            <p class="text-success-100 text-sm mt-2">+5% from last week</p>
                        </div>
                        <div class="bg-white/20 p-3 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-warning-500 to-warning-600 rounded-2xl p-6 text-white shadow-large animate-scale-in" style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-warning-100 text-sm font-medium">Revenue</p>
                            <p class="text-3xl font-bold mt-2">$45.2k</p>
                            <p class="text-warning-100 text-sm mt-2">+18% from last month</p>
                        </div>
                        <div class="bg-white/20 p-3 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-danger-500 to-danger-600 rounded-2xl p-6 text-white shadow-large animate-scale-in" style="animation-delay: 0.3s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-danger-100 text-sm font-medium">Pending Tasks</p>
                            <p class="text-3xl font-bold mt-2">23</p>
                            <p class="text-danger-100 text-sm mt-2">8 due today</p>
                        </div>
                        <div class="bg-white/20 p-3 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Section -->
            <div class="animate-slide-up" style="animation-delay: 0.4s">
                <x-card class="relative overflow-visible">
                    <div class="max-w-2xl mx-auto">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Customer Search</h3>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                id="customer-search" 
                                name="term"
                                placeholder="Search customers by name, email, or phone..."
                                class="input-professional pl-12 relative z-10"
                                autocomplete="off"
                            >
                            <div id="search-suggestions" class="absolute top-full left-0 right-0 z-[9999] w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-large hidden max-h-80 overflow-y-auto">
                                <!-- Suggestions will be populated here -->
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <x-card title="Recent Customers" class="animate-slide-up" style="animation-delay: 0.5s">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center text-white font-bold">
                                    JD
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">John Doe</p>
                                    <p class="text-sm text-gray-500">Added 2 hours ago</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-r from-success-500 to-success-600 flex items-center justify-center text-white font-bold">
                                    AS
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Alice Smith</p>
                                    <p class="text-sm text-gray-500">Added 5 hours ago</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </x-card>

                <x-card title="Quick Actions" class="animate-slide-up" style="animation-delay: 0.6s">
                    <div class="space-y-3">
                        <button class="w-full text-left px-4 py-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors flex items-center justify-between group">
                            <span class="font-medium text-gray-700">Generate Reports</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <button class="w-full text-left px-4 py-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors flex items-center justify-between group">
                            <span class="font-medium text-gray-700">View Analytics</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <button class="w-full text-left px-4 py-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors flex items-center justify-between group">
                            <span class="font-medium text-gray-700">System Settings</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('customer-search');
    const suggestionsDiv = document.getElementById('search-suggestions');
    let searchTimeout;

    searchInput.addEventListener('input', function(e) {
        const term = e.target.value.trim();
        
        // Clear previous timeout
        clearTimeout(searchTimeout);
        
        if (term.length < 2) {
            suggestionsDiv.classList.add('hidden');
            return;
        }
        
        // Debounce search requests
        searchTimeout = setTimeout(() => {
            performSearch(term);
        }, 300);
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
            suggestionsDiv.classList.add('hidden');
        }
    });

    // Also hide suggestions when pressing Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            suggestionsDiv.classList.add('hidden');
        }
    });

    function performSearch(term) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        fetch('/customer/search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                term: term
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Search response:', data);
            if (data.success && data.data.length > 0) {
                displaySuggestions(data.data);
            } else {
                suggestionsDiv.innerHTML = '<div class="p-4 text-gray-500 text-sm">No customers found</div>';
                suggestionsDiv.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            suggestionsDiv.innerHTML = '<div class="p-4 text-red-500 text-sm">Search failed</div>';
            suggestionsDiv.classList.remove('hidden');
        });
    }

    function displaySuggestions(customers) {
        console.log('Displaying suggestions:', customers); // Debug log
        suggestionsDiv.innerHTML = '';
        
        if (customers.length === 0) {
            suggestionsDiv.innerHTML = `
                <div class="p-4 text-center text-gray-500 text-sm">
                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    No customers found
                </div>
            `;
        } else {
            customers.forEach(customer => {
                const suggestionItem = document.createElement('div');
                suggestionItem.className = 'px-4 py-3 hover:bg-primary-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition-all duration-200 group';
                suggestionItem.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="h-8 w-8 rounded-lg bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                ${customer.first_name ? customer.first_name.charAt(0).toUpperCase() : 'C'}
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 group-hover:text-primary-700">
                                    ${customer.salutation ? customer.salutation + '. ' : ''}${customer.first_name} ${customer.last_name}
                                </div>
                                <div class="text-sm text-gray-500">
                                    ${customer.email || 'No email'}
                                </div>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                `;
                
                suggestionItem.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Clicked customer:', customer);
                    console.log('Navigating to:', `/customer/${customer.id}`);
                    window.location.href = `/customer/${customer.id}`;
                });
                
                suggestionsDiv.appendChild(suggestionItem);
            });
        }
        
        // Force show the suggestions
        suggestionsDiv.classList.remove('hidden');
        suggestionsDiv.style.display = 'block';
        console.log('Suggestions should be visible now'); // Debug log
    }
});
</script>
