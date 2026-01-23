<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                Customer Management
            </h2>
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-500">Customer ID: #{{ $customerId }}</span>
                <div class="h-6 w-px bg-gray-300"></div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-success-100 text-success-800">
                    Active
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Customer Header Card -->
            <div class="bg-gradient-to-r from-primary-600 to-primary-700 rounded-2xl shadow-large text-white p-8 animate-fade-in">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">
                            {{ $customer_salutations->value }}. {{ $customer_first_name }} {{ $customer_last_name }}
                        </h1>
                        <p class="text-primary-100">
                            Assigned Staff: {{ $customer_staff_member ?? 'Unassigned' }}
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <x-primary-button class="bg-white/20 hover:bg-white/30 text-white border-white/30">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Customer
                        </x-primary-button>
                        <x-danger-button class="bg-white/20 hover:bg-white/30 text-white border-white/30">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </x-danger-button>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Customer Information -->
                <div class="lg:col-span-2 space-y-6">
                    <x-card title="Customer Information" class="animate-slide-up">
                        <form class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="salutation" class="block text-sm font-medium text-gray-700 mb-2">Salutation</label>
                                    <select id="salutation" name="salutation" class="input-professional">
                                        <option value="">Select...</option>
                                        @foreach($salutations as $salutation)
                                            <option value="{{ $salutation->value }}" @if($customer_salutations->value === $salutation->value) selected @endif>{{ $salutation->value }}.</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                    <input type="text" id="first_name" name="first_name" value="{{ $customer_first_name }}" class="input-professional">
                                </div>

                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                    <input type="text" id="last_name" name="last_name" value="{{ $customer_last_name }}" class="input-professional">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Communication Method</label>
                                    <div class="space-y-2">
                                        @foreach(App\Enums\CommiunicationMethod::cases() as $method)
                                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                                <input 
                                                    type="checkbox" 
                                                    name="comm_method" 
                                                    value="{{ $method->value }}" 
                                                    class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 mr-3" 
                                                    @if($customer_communication_method->value === $method->value) checked @endif>
                                                <span class="text-sm font-medium text-gray-700">{{ ucfirst($method->value) }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </form>
                    </x-card>

                    <!-- Contact Information -->
                    <x-card title="Contact Information" class="animate-slide-up" style="animation-delay: 0.1s">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                    <div>
                                        <h4 class="font-medium text-gray-900">Phone Numbers</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ $customer_phone ?? 'No phone numbers added' }}</p>
                                    </div>
                                    <button type="button" onclick="PhoneModal.open()" class="px-4 py-2 bg-primary-100 text-primary-700 rounded-lg hover:bg-primary-200 transition-colors font-medium text-sm">
                                        Manage
                                    </button>
                                </div>

                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                    <div>
                                        <h4 class="font-medium text-gray-900">Email Addresses</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ $customer_email ?? 'No email addresses added' }}</p>
                                    </div>
                                    <button type="button" onclick="EmailModal.open()" class="px-4 py-2 bg-primary-100 text-primary-700 rounded-lg hover:bg-primary-200 transition-colors font-medium text-sm">
                                        Manage
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                    <div>
                                        <h4 class="font-medium text-gray-900">Address</h4>
                                    <div class="text-sm text-gray-600 mt-1">
                                            @if($customer_address)
                                                {{ $customer_address->address_line_1 }}<br>
                                                {{ $customer_address->city }}, {{ $customer_address->state }} {{ $customer_address->postal_code }}<br>
                                                {{ $customer_address->country->name }}
                                            @else
                                                No address added
                                            @endif
                                        </div>
                                    </div>
                                    <button type="button" onclick="AddressModal.open()" class="px-4 py-2 bg-primary-100 text-primary-700 rounded-lg hover:bg-primary-200 transition-colors font-medium text-sm">
                                        Manage
                                    </button>
                                </div>
                            </div>
                        </div>
                    </x-card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Travel Documents -->
                    <x-card title="Travel Documents" class="animate-slide-up" style="animation-delay: 0.3s">
                        <div class="space-y-3">
                            <button type="button" onclick="PassportModal.open()" class="w-full text-left px-4 py-3 bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg hover:shadow-medium transition-all group">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="p-2 bg-blue-500 text-white rounded-lg group-hover:scale-110 transition-transform">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="font-medium text-blue-900">Passport</span>
                                            <p class="text-xs text-blue-600">Manage passport info</p>
                                        </div>
                                    </div>
                                    <svg class="w-4 h-4 text-blue-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </button>

                            <button type="button" onclick="VisaModal.open()" class="w-full text-left px-4 py-3 bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg hover:shadow-medium transition-all group">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="p-2 bg-green-500 text-white rounded-lg group-hover:scale-110 transition-transform">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="font-medium text-green-900">VISA</span>
                                            <p class="text-xs text-green-600">Manage visa documents</p>
                                        </div>
                                    </div>
                                    <svg class="w-4 h-4 text-green-400 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </button>

                            <button type="button" onclick="FrequentFlyerModal.open()" class="w-full text-left px-4 py-3 bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-lg hover:shadow-medium transition-all group">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="p-2 bg-purple-500 text-white rounded-lg group-hover:scale-110 transition-transform">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="font-medium text-purple-900">Frequent Flyer</span>
                                            <p class="text-xs text-purple-600">Loyalty programs</p>
                                        </div>
                                    </div>
                                    <svg class="w-4 h-4 text-purple-400 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </button>
                        </div>
                    </x-card>

                    <!-- Quick Actions -->
                    <x-card title="Quick Actions" class="animate-slide-up" style="animation-delay: 0.4s">
                        <div class="space-y-3">
                            <button class="w-full text-left px-4 py-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors flex items-center justify-between group">
                                <span class="font-medium text-gray-700">Send Email</span>
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <button class="w-full text-left px-4 py-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors flex items-center justify-between group">
                                <span class="font-medium text-gray-700">View History</span>
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <button class="w-full text-left px-4 py-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors flex items-center justify-between group">
                                <span class="font-medium text-gray-700">Generate Report</span>
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </x-card>

                    <!-- Status -->
                    <x-card title="Account Status" class="animate-slide-up" style="animation-delay: 0.4s">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Account Status</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">
                                    Active
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Member Since</span>
                                <span class="text-sm text-gray-600">Jan 2024</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Last Updated</span>
                                <span class="text-sm text-gray-600">2 days ago</span>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals and Scripts -->
    @include('customer.modals.phone-modal')
    @include('customer.modals.email-modal')
    @include('customer.modals.address-modal')
    @include('customer.modals.passport-modal')
    @include('customer.modals.visa-modal')
    @include('customer.modals.frequent-flyer-modal')

    <script src="{{ asset('js/customer/phone-modal.js') }}"></script>
    <script src="{{ asset('js/customer/email-modal.js') }}"></script>
    <script src="{{ asset('js/customer/address-modal.js') }}"></script>
    <script src="{{ asset('js/customer/passport-modal.js') }}"></script>
    <script src="{{ asset('js/customer/visa-modal.js') }}"></script>
    <script src="{{ asset('js/customer/frequent-flyer-modal.js') }}"></script>
    
    <script>
        window.customerData = {
            id: '{{ $customerId }}',
            phones: @json($phones ?? []),
            emails: [],
            addresses: []
        };
        
        // Wait for all scripts to load before setting up event handlers
        window.addEventListener('load', function() {
            console.log('All scripts loaded - checking PhoneModal...');
            console.log('PhoneModal object:', window.PhoneModal);
            console.log('Modal element:', document.getElementById('phoneModal'));
            console.log('Modal content element:', document.getElementById('phoneModalContent'));
            
            // Test if PhoneModal exists and has the full functionality
            if (typeof window.PhoneModal === 'undefined' || !window.PhoneModal.init) {
                console.error('Full PhoneModal is not defined! Using fallback...');
                // Create fallback PhoneModal
                window.PhoneModal = {
                    phones: [],
                    open: function() {
                        console.log('Fallback PhoneModal.open() called');
                        const modal = document.getElementById('phoneModal');
                        const content = document.getElementById('phoneModalContent');
                        console.log('Modal element:', modal);
                        console.log('Modal content element:', content);
                        
                        if (modal) {
                            // Remove hidden class and add opacity
                            modal.classList.remove('hidden');
                            modal.classList.add('opacity-100');
                            console.log('Modal classes after show:', modal.className);
                            
                            // Animate content
                            if (content) {
                                content.classList.remove('scale-95', 'opacity-0');
                                content.classList.add('scale-100', 'opacity-100');
                                console.log('Content classes after show:', content.className);
                            }
                            
                            // Prevent body scroll
                            document.body.style.overflow = 'hidden';
                            
                            // Ensure proper centering by removing any conflicting styles
                            modal.style.removeProperty('display');
                            modal.style.removeProperty('visibility');
                            modal.style.removeProperty('opacity');
                            
                            console.log('Fallback modal opened with animations and proper centering');
                            
                            // Check if modal is actually visible
                            setTimeout(() => {
                                const rect = modal.getBoundingClientRect();
                                console.log('Modal dimensions:', rect);
                                console.log('Modal is visible:', rect.width > 0 && rect.height > 0);
                                console.log('Modal classes final:', modal.className);
                            }, 100);
                        } else {
                            alert('Modal not found. Please refresh the page.');
                        }
                    },
                    close: function() {
                        const modal = document.getElementById('phoneModal');
                        const content = document.getElementById('phoneModalContent');
                        if (modal) {
                            modal.classList.add('hidden');
                            modal.classList.remove('opacity-100');
                            if (content) {
                                content.classList.add('scale-95', 'opacity-0');
                                content.classList.remove('scale-100', 'opacity-100');
                            }
                            document.body.style.overflow = 'auto';
                        }
                    },
                    loadPhones: function() {
                        if (window.customerData && window.customerData.phones) {
                            this.phones = window.customerData.phones;
                        }
                    },
                    render: function() {
                        // Basic render - just show that phones exist
                        console.log('Fallback render called with phones:', this.phones);
                    }
                };
            } else {
                console.log('Full PhoneModal is available');
            }
            
            // Test if EmailModal exists and has functionality
            if (typeof window.EmailModal === 'undefined' || !window.EmailModal.open) {
                console.error('EmailModal is not defined! Using fallback...');
                // Create fallback EmailModal
                window.EmailModal = {
                    emails: [],
                    open: function() {
                        console.log('Fallback EmailModal.open() called');
                        const modal = document.getElementById('emailModal');
                        const content = document.getElementById('emailModalContent');
                        console.log('Email modal element:', modal);
                        console.log('Email modal content element:', content);
                        
                        if (modal) {
                            // Remove hidden class and add opacity
                            modal.classList.remove('hidden');
                            modal.classList.add('opacity-100');
                            console.log('Email modal classes after show:', modal.className);
                            
                            // Animate content
                            if (content) {
                                content.classList.remove('scale-95', 'opacity-0');
                                content.classList.add('scale-100', 'opacity-100');
                                console.log('Email content classes after show:', content.className);
                            }
                            
                            // Prevent body scroll
                            document.body.style.overflow = 'hidden';
                            
                            // Ensure proper centering by removing any conflicting styles
                            modal.style.removeProperty('display');
                            modal.style.removeProperty('visibility');
                            modal.style.removeProperty('opacity');
                            
                            console.log('Fallback email modal opened with animations and proper centering');
                            
                            // Check if modal is actually visible
                            setTimeout(() => {
                                const rect = modal.getBoundingClientRect();
                                console.log('Email modal dimensions:', rect);
                                console.log('Email modal is visible:', rect.width > 0 && rect.height > 0);
                                console.log('Email modal classes final:', modal.className);
                            }, 100);
                        } else {
                            alert('Email modal not found. Please refresh the page.');
                        }
                    },
                    close: function() {
                        const modal = document.getElementById('emailModal');
                        const content = document.getElementById('emailModalContent');
                        if (modal) {
                            modal.classList.add('hidden');
                            modal.classList.remove('opacity-100');
                            if (content) {
                                content.classList.add('scale-95', 'opacity-0');
                                content.classList.remove('scale-100', 'opacity-100');
                            }
                            document.body.style.overflow = 'auto';
                        }
                    },
                    loadEmails: function() {
                        if (window.customerData && window.customerData.emails) {
                            this.emails = window.customerData.emails;
                        }
                    },
                    render: function() {
                        console.log('Fallback email render called with emails:', this.emails);
                    }
                };
            } else {
                console.log('EmailModal is available');
            }
            
            // Test if AddressModal exists and has functionality
            if (typeof window.AddressModal === 'undefined' || !window.AddressModal.open) {
                console.error('AddressModal is not defined! Using fallback...');
                // Create fallback AddressModal
                window.AddressModal = {
                    addresses: [],
                    open: function() {
                        console.log('Fallback AddressModal.open() called');
                        const modal = document.getElementById('addressModal');
                        const content = document.getElementById('addressModalContent');
                        console.log('Address modal element:', modal);
                        console.log('Address modal content element:', content);
                        
                        if (modal) {
                            // Remove hidden class and add opacity
                            modal.classList.remove('hidden');
                            modal.classList.add('opacity-100');
                            console.log('Address modal classes after show:', modal.className);
                            
                            // Animate content
                            if (content) {
                                content.classList.remove('scale-95', 'opacity-0');
                                content.classList.add('scale-100', 'opacity-100');
                                console.log('Address content classes after show:', content.className);
                            }
                            
                            // Prevent body scroll
                            document.body.style.overflow = 'hidden';
                            
                            // Ensure proper centering by removing any conflicting styles
                            modal.style.removeProperty('display');
                            modal.style.removeProperty('visibility');
                            modal.style.removeProperty('opacity');
                            
                            console.log('Fallback address modal opened with animations and proper centering');
                            
                            // Check if modal is actually visible
                            setTimeout(() => {
                                const rect = modal.getBoundingClientRect();
                                console.log('Address modal dimensions:', rect);
                                console.log('Address modal is visible:', rect.width > 0 && rect.height > 0);
                                console.log('Address modal classes final:', modal.className);
                            }, 100);
                        } else {
                            alert('Address modal not found. Please refresh the page.');
                        }
                    },
                    close: function() {
                        const modal = document.getElementById('addressModal');
                        const content = document.getElementById('addressModalContent');
                        if (modal) {
                            modal.classList.add('hidden');
                            modal.classList.remove('opacity-100');
                            if (content) {
                                content.classList.add('scale-95', 'opacity-0');
                                content.classList.remove('scale-100', 'opacity-100');
                            }
                            document.body.style.overflow = 'auto';
                        }
                    },
                    loadAddresses: function() {
                        if (window.customerData && window.customerData.addresses) {
                            this.addresses = window.customerData.addresses;
                        }
                    },
                    render: function() {
                        console.log('Fallback address render called with addresses:', this.addresses);
                    }
                };
            } else {
                console.log('AddressModal is available');
            }
            
            setupPhoneModalButton();
            setupEmailModalButton();
            setupAddressModalButton();
        });
        
        function setupPhoneModalButton() {
            // Fix button click handler if PhoneModal exists
            const phoneManageBtn = document.querySelector('button[onclick*="PhoneModal.open"]');
            if (phoneManageBtn) {
                console.log('Found phone manage button, fixing click handler');
                phoneManageBtn.removeAttribute('onclick');
                phoneManageBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Phone manage button clicked');
                    if (window.PhoneModal && typeof window.PhoneModal.open === 'function') {
                        console.log('Calling PhoneModal.open()');
                        window.PhoneModal.open();
                    } else {
                        console.error('PhoneModal.open is not available');
                        alert('Phone modal is not available. Please refresh the page and try again.');
                    }
                });
            } else {
                console.log('Phone manage button not found');
            }
        }
        
        function setupEmailModalButton() {
            // Fix button click handler if EmailModal exists
            const emailManageBtn = document.querySelector('button[onclick*="EmailModal.open"]');
            console.log('Looking for email manage button...');
            console.log('Email manage button found:', emailManageBtn);
            
            if (emailManageBtn) {
                console.log('Found email manage button, fixing click handler');
                emailManageBtn.removeAttribute('onclick');
                emailManageBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Email manage button clicked');
                    console.log('EmailModal object:', window.EmailModal);
                    console.log('EmailModal.open function:', typeof window.EmailModal?.open);
                    
                    if (window.EmailModal && typeof window.EmailModal.open === 'function') {
                        console.log('Calling EmailModal.open()');
                        window.EmailModal.open();
                    } else {
                        console.error('EmailModal.open is not available');
                        alert('Email modal is not available. Please refresh the page and try again.');
                    }
                });
            } else {
                console.log('Email manage button not found');
                // Try to find any button with "EmailModal" text
                const allButtons = document.querySelectorAll('button');
                console.log('All buttons on page:', allButtons.length);
                allButtons.forEach((btn, index) => {
                    if (btn.textContent.includes('Manage') && btn.textContent.includes('Email')) {
                        console.log(`Found potential email button at index ${index}:`, btn);
                    }
                });
            }
        }
        
        function setupAddressModalButton() {
            // Fix button click handler if AddressModal exists
            const addressManageBtn = document.querySelector('button[onclick*="AddressModal.open"]');
            console.log('Looking for address manage button...');
            console.log('Address manage button found:', addressManageBtn);
            
            if (addressManageBtn) {
                console.log('Found address manage button, fixing click handler');
                addressManageBtn.removeAttribute('onclick');
                addressManageBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Address manage button clicked');
                    console.log('AddressModal object:', window.AddressModal);
                    console.log('AddressModal.open function:', typeof window.AddressModal?.open);
                    
                    if (window.AddressModal && typeof window.AddressModal.open === 'function') {
                        console.log('Calling AddressModal.open()');
                        window.AddressModal.open();
                    } else {
                        console.error('AddressModal.open is not available');
                        alert('Address modal is not available. Please refresh the page and try again.');
                    }
                });
            } else {
                console.log('Address manage button not found');
                // Try to find any button with "AddressModal" text
                const allButtons = document.querySelectorAll('button');
                console.log('All buttons on page:', allButtons.length);
                allButtons.forEach((btn, index) => {
                    if (btn.textContent.includes('Manage') && btn.textContent.includes('Address')) {
                        console.log(`Found potential address button at index ${index}:`, btn);
                    }
                });
            }
        }
    </script>
</x-app-layout>
