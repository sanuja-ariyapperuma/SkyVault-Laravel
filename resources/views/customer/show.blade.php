<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Customer View') }}
        </h2>
    </x-slot>
    {{-- be27d49f-f140-4f3e-b9a3-081eab4c3d22 --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-6">Customer Information</h3>
                    
                    <form class="space-y-6">
                        <!-- Submit Button -->
                        <div class="flex justify-between items-center pb-4 border-b">
                            <div>
                                <span class="text-sm text-gray-600">Assigned Staff Member:</span>
                                <span class="text-sm font-medium text-gray-900 ml-2">
                                    {{ $customer_staff_member ?? 'No staff member assigned' }}
                                </span>
                            </div>
                            <div class="flex space-x-2">
                                <button type="submit" class="px-4 py-2 text-white bg-amber-500 rounded-md hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 flex items-center justify-center relative group">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                        Edit
                                    </span>
                                </button>
                                <button type="button" class="px-4 py-2 text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 flex items-center justify-center relative group">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                        Delete
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-4">
                                <!-- Salutation -->
                                <div>
                                    <label for="salutation" class="block text-sm font-medium text-gray-700 mb-1">Salutation</label>
                                    <select id="salutation" name="salutation" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select...</option>
                                        @foreach($salutations as $salutation)
                                            <option value="{{ $salutation->value }}" @if($customer_salutations->value === $salutation->value) selected @endif>{{ $salutation->value }}.</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- First Name -->
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                    <input type="text" id="first_name" name="first_name" value="{{ $customer_first_name }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>

                                <!-- Last Name -->
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                    <input type="text" id="last_name" name="last_name" value="{{ $customer_last_name }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>

                                <!-- Phone Numbers -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Numbers</label>
                                    <div id="phone_display" class="text-sm text-gray-900 mb-2 font-medium">{{ $customer_phone ?? 'No phone numbers added' }}</div>
                                    <button type="button" onclick="PhoneModal.open()" class="text-blue-600 hover:text-blue-800 hover:underline focus:outline-none focus:text-blue-800 focus:ring-2 focus:ring-blue-500">Manage Phone Numbers</button>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-4">
                                <!-- Email Addresses -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Addresses</label>
                                    <div id="email_display" class="text-sm text-gray-900 mb-2 font-medium">{{ $customer_email ?? 'No email addresses added' }}</div>
                                    <button type="button" onclick="EmailModal.open()" class="text-sm text-blue-600 hover:text-blue-800 hover:underline focus:outline-none focus:text-blue-800 focus:ring-2 focus:ring-blue-500">Manage Email Addresses</button>
                                </div>

                                <!-- Address -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                    <div id="address_display" class="text-sm text-gray-600 mb-2">
                                        @if($customer_address)
                                            {{ $customer_address->address_line_1 }} <br/>
                                            {{ $customer_address->address_line_2 }} <br/>
                                            {{ $customer_address->city }} <br/>
                                            {{ $customer_address->state }} <br/>
                                            {{ $customer_address->postal_code }} <br/>
                                            {{ $customer_address->country->name }} <br/>
                                        @else
                                            No addresses added
                                        @endif
                                        
                                    </div>
                                    <button type="button" onclick="AddressModal.open()" class="text-sm text-blue-600 hover:text-blue-800 hover:underline focus:outline-none focus:text-blue-800">Manage Addresses</button>
                                </div>

                                <!-- Preferred Communication Method -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Communication Method</label>
                                    <div class="space-y-2">
                                        @foreach(App\Enums\CommiunicationMethod::cases() as $method)
                                            <label class="flex items-center">
                                                <input 
                                                    type="checkbox" 
                                                    name="comm_method" 
                                                    value="{{ $method->value }}" 
                                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mr-2" 
                                                    @if($customer_communication_method->value === $method->value) checked @endif>
                                                <span class="text-sm text-gray-700">{{ ucfirst($method->value) }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @include('customer.modals.phone-modal')
    @include('customer.modals.email-modal')

    <!-- Scripts -->
    <script src="{{ asset('js/customer/phone-modal.js') }}"></script>
    <script src="{{ asset('js/customer/email-modal.js') }}"></script>
    <script src="{{ asset('js/customer/address-modal.js') }}"></script>
    
    <!-- Data Initialization -->
    <script>
        // Global data storage
        window.customerData = {
            id: '{{ $customerId }}',
            phones: @json($phones ?? []),
            emails: [],
            addresses: []
        };
        
        // Initialize data from global scope
        let phones = window.customerData.phones;
        let emails = window.customerData.emails;
        let addresses = window.customerData.addresses;
        
        // Initialize modals when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                // Ensure modal APIs are available
                if (typeof PhoneModal !== 'undefined') {
                    console.log('Phone modal API loaded');
                }
                if (typeof EmailModal !== 'undefined') {
                    console.log('Email modal API loaded');
                }
                if (typeof AddressModal !== 'undefined') {
                    console.log('Address modal API loaded');
                }
            });
        } else {
            // DOM already loaded, initialize immediately
            if (typeof PhoneModal !== 'undefined') {
                console.log('Phone modal API loaded');
            }
            if (typeof EmailModal !== 'undefined') {
                console.log('Email modal API loaded');
            }
            if (typeof AddressModal !== 'undefined') {
                console.log('Address modal API loaded');
            }
        }
    </script>
</x-app-layout>
