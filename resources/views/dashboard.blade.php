<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-96">
                <div class="p-6 text-gray-900 h-full">
                    <div class="max-w-md mx-auto h-full flex flex-col">
                        <div class="relative flex-shrink-0">
                            <input 
                                type="text" 
                                id="customer-search" 
                                name="term"
                                placeholder="Search customers..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                                autocomplete="off"
                            >
                            <div id="search-suggestions" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-80 overflow-y-auto">
                                <!-- Suggestions will be populated here -->
                            </div>
                        </div>
                    </div>
                </div>
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
            if (data.success && data.data.length > 0) {
                displaySuggestions(data.data);
            } else {
                suggestionsDiv.innerHTML = '<div class="p-3 text-gray-500 text-sm">No customers found</div>';
                suggestionsDiv.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            suggestionsDiv.innerHTML = '<div class="p-3 text-red-500 text-sm">Search failed</div>';
            suggestionsDiv.classList.remove('hidden');
        });
    }

    function displaySuggestions(customers) {
        suggestionsDiv.innerHTML = '';
        
        customers.forEach(customer => {
            const suggestionItem = document.createElement('div');
            suggestionItem.className = 'px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0';
            suggestionItem.innerHTML = `
                <div class="font-medium text-gray-900"> ${customer.salutation}. ${customer.first_name} ${customer.last_name}</div>
            `;
            
            suggestionItem.addEventListener('click', function() {
                // Navigate to customer page (adjust route as needed)
                window.location.href = `/customer/${customer.id}`;
            });
            
            suggestionsDiv.appendChild(suggestionItem);
        });
        
        suggestionsDiv.classList.remove('hidden');
    }
});
</script>
