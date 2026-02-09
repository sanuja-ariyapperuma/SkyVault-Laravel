<div x-data="toast" 
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-2"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform translate-y-2"
     class="fixed top-4 right-4 z-[10000] max-w-sm w-full"
     style="display: none;">
    
    <div class="rounded-lg shadow-lg p-4 mb-2 flex items-center space-x-3"
         :class="{
             'bg-green-50 border border-green-200': type === 'success',
             'bg-yellow-50 border border-yellow-200': type === 'warning', 
             'bg-red-50 border border-red-200': type === 'error'
         }">
        
        <!-- Icon -->
        <div class="flex-shrink-0">
            <svg x-show="type === 'success'" class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <svg x-show="type === 'warning'" class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <svg x-show="type === 'error'" class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        
        <!-- Message -->
        <div class="flex-1">
            <p x-text="message" 
               :class="{
                   'text-green-800': type === 'success',
                   'text-yellow-800': type === 'warning',
                   'text-red-800': type === 'error'
               }"
               class="text-sm font-medium">
            </p>
        </div>
        
        <!-- Close button -->
        <button @click="show = false" 
                class="flex-shrink-0 p-1 rounded-md hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>
