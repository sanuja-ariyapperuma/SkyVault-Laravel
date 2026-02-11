// Address Modal Script

// Modal functionality
window.AddressModal = {
    addresses: [], // Store address data
    countries: {}, // Store countries data
    isLoading: false,
    loadingTimeout: null,
    
    // Initialize method to ensure everything is ready
    init: function() {
        // Check if modal container exists
        const container = document.getElementById('addressModalContainer');
        if (!container) {
            console.error('Address modal container not found in the DOM');
            return false;
        }
        return true;
    },
    
    // Get skeleton loading HTML
    getSkeletonHTML: function() {
        return `
            <!-- Address Modal Skeleton -->
            <div id="addressModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full z-[9999] transition-opacity duration-300 flex items-start justify-center p-4" role="dialog" aria-labelledby="addressModalTitle" aria-hidden="true">
                <!-- Toast Component will be added when real content loads -->
                
                <div class="relative w-full max-w-2xl my-4 p-4">
                    <div class="relative bg-white rounded-2xl shadow-large w-full transform transition-all duration-300 scale-95 opacity-0" id="addressModalContent">
                        <!-- Modal Header Skeleton -->
                        <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-white rounded-t-2xl">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-colored-purple animate-pulse">
                                    <div class="w-5 h-5 bg-white/30 rounded"></div>
                                </div>
                                <div>
                                    <div class="h-6 bg-gray-200 rounded-lg animate-pulse w-48 mb-2"></div>
                                    <div class="h-4 bg-gray-100 rounded animate-pulse w-32"></div>
                                </div>
                            </div>
                            <div class="p-2 bg-gray-100 rounded-lg animate-pulse">
                                <div class="w-5 h-5 bg-gray-300 rounded"></div>
                            </div>
                        </div>

                        <!-- Modal Body Skeleton -->
                        <div class="p-6 space-y-6">
                            <!-- Current Default Address Skeleton -->
                            <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                                <div class="h-5 bg-purple-200 rounded-lg animate-pulse w-40 mb-3"></div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="p-2 bg-purple-500 rounded-lg animate-pulse">
                                            <div class="w-5 h-5 bg-white/30 rounded"></div>
                                        </div>
                                        <div>
                                            <div class="h-5 bg-purple-300 rounded animate-pulse w-32 mb-2"></div>
                                            <div class="h-4 bg-purple-200 rounded animate-pulse w-24"></div>
                                        </div>
                                    </div>
                                    <div class="h-6 bg-purple-200 rounded-full animate-pulse w-16"></div>
                                </div>
                            </div>

                            <!-- Add New Address Skeleton -->
                            <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                                <div class="h-5 bg-purple-200 rounded-lg animate-pulse w-40 mb-4"></div>
                                
                                <div class="space-y-4">
                                    <div class="space-y-3">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <div class="h-5 w-5 bg-gray-300 rounded animate-pulse"></div>
                                            </div>
                                            <div class="w-full h-12 bg-gray-100 rounded-xl animate-pulse pl-11"></div>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <div class="h-5 w-5 bg-gray-300 rounded animate-pulse"></div>
                                                </div>
                                                <div class="w-full h-12 bg-gray-100 rounded-xl animate-pulse pl-11"></div>
                                            </div>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <div class="h-5 w-5 bg-gray-300 rounded animate-pulse"></div>
                                                </div>
                                                <div class="w-full h-12 bg-gray-100 rounded-xl animate-pulse pl-11"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="h-10 bg-purple-100 rounded-xl animate-pulse w-20"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Existing Addresses Skeleton -->
                            <div class="space-y-4">
                                <div class="h-5 bg-gray-200 rounded-lg animate-pulse w-48"></div>
                                
                                <div class="space-y-3">
                                    <!-- Skeleton address item 1 -->
                                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 animate-pulse">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3 flex-1">
                                                <div class="p-2 bg-purple-100 rounded-lg">
                                                    <div class="w-5 h-5 bg-purple-200 rounded animate-pulse"></div>
                                                </div>
                                                <div class="flex-1">
                                                    <div class="h-5 bg-gray-200 rounded animate-pulse w-32 mb-2"></div>
                                                    <div class="h-4 bg-gray-100 rounded animate-pulse w-20"></div>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <div class="h-8 w-8 bg-gray-100 rounded-lg animate-pulse"></div>
                                                <div class="h-8 w-8 bg-gray-100 rounded-lg animate-pulse"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer Skeleton -->
                        <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                            <div class="h-10 bg-white border border-gray-300 rounded-xl animate-pulse w-16"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    },
    
    open: function() {
        // Ensure modal is initialized
        if (!this.init()) {
            console.error('AddressModal initialization failed');
            return;
        }
        
        // Prevent multiple simultaneous loads
        if (this.isLoading) {
            console.log('Address modal is already loading...');
            return;
        }
        
        this.isLoading = true;
        
        // Show skeleton loading immediately
        this.showSkeleton();
        
        // Load modal content via AJAX
        this.loadModalContent();
    },
    
    showSkeleton: function() {
        const container = document.getElementById('addressModalContainer');
        container.innerHTML = this.getSkeletonHTML();
        
        // Animate skeleton in
        setTimeout(() => {
            const modal = document.getElementById('addressModal');
            const content = document.getElementById('addressModalContent');
            
            if (modal && content) {
                modal.classList.add('opacity-100');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }
        }, 10);
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
        
        // Set timeout for loading
        this.loadingTimeout = setTimeout(() => {
            if (this.isLoading) {
                this.showLoadingTimeout();
            }
        }, 5000);
    },
    
    showLoadingTimeout: function() {
        toast.warning('Loading is taking longer than expected. Please wait...');
    },
    
    loadModalContent: async function() {
        try {
            const customerId = window.customerData?.id;
            if (!customerId) throw new Error('Customer ID not found');
            
            // Make AJAX call to get modal content
            const response = await fetch(`/customer/${customerId}/address-modal`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                credentials: 'include'
            });
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            
            const result = await response.json();
            
            if (!result.success) {
                throw new Error(result.message || 'Failed to load address modal');
            }
            
            // Clear timeout
            if (this.loadingTimeout) {
                clearTimeout(this.loadingTimeout);
                this.loadingTimeout = null;
            }
            
            // Update addresses data from server - check both possible structures
            this.addresses = result.addresses || result.data?.addresses || [];
            
            // Store countries data from server response
            this.countries = result.countries || result.data?.countries || {};
            
            // Get HTML from response - check both possible structures
            const modalHtml = result.html || result.data?.html || '';
            
            if (!modalHtml) {
                throw new Error('No modal HTML received from server');
            }
            
            // Replace skeleton with actual content
            this.replaceWithRealContent(modalHtml);
            
        } catch (error) {
            console.error('Error loading address modal:', error);
            this.handleLoadError(error);
        } finally {
            this.isLoading = false;
        }
    },
    
    replaceWithRealContent: function(html) {
        const container = document.getElementById('addressModalContainer');
        
        if (!container) {
            console.error('Address modal container not found!');
            return;
        }
        
        // Fade out skeleton
        const modal = document.getElementById('addressModal');
        const content = document.getElementById('addressModalContent');
        
        if (modal && content) {
            content.classList.add('scale-95', 'opacity-0');
            content.classList.remove('scale-100', 'opacity-100');
            
            setTimeout(() => {
                // Replace content
                container.innerHTML = html;
                
                // Remove hidden class from the new modal and ensure it's visible
                const newModal = document.getElementById('addressModal');
                if (newModal) {
                    newModal.classList.remove('hidden');
                    newModal.classList.add('opacity-100');
                    // Remove aria-hidden when modal is visible to fix accessibility warning
                    newModal.removeAttribute('aria-hidden');
                }
                
                // Fade in real content
                const newContent = document.getElementById('addressModalContent');
                
                if (newModal && newContent) {
                    newContent.classList.remove('scale-95', 'opacity-0');
                    newContent.classList.add('scale-100', 'opacity-100');
                    
                    // Render the address data in the list
                    this.render();
                    
                    // Focus on input
                    const addressInput = document.getElementById('new_address_line1');
                    if (addressInput) {
                        addressInput.focus();
                    }
                } else {
                    console.error('New modal elements not found after replacement!');
                }
            }, 200);
        } else {
            console.error('Skeleton modal elements not found!');
        }
    },
    
    handleLoadError: function(error) {
        const container = document.getElementById('addressModalContainer');
        container.innerHTML = `
            <div id="addressModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full z-[9999] transition-opacity duration-300 flex items-start justify-center p-4">
                <div class="relative w-full max-w-2xl my-4 p-4">
                    <div class="bg-white rounded-2xl shadow-large max-w-md w-full p-6 text-center">
                        <div class="mb-4">
                            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Failed to Load</h3>
                            <p class="text-gray-600 mb-4">${error.message || 'Unable to load address modal. Please try again.'}</p>
                        </div>
                        <div class="flex gap-3 justify-center">
                            <button onclick="AddressModal.retryLoad()" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                Retry
                            </button>
                            <button onclick="AddressModal.close()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        toast.error('Failed to load address modal: ' + error.message);
    },
    
    retryLoad: function() {
        this.isLoading = false;
        this.open();
    },
    
    close: function() {
        const modal = document.getElementById('addressModal');
        const content = document.getElementById('addressModalContent');
        
        if (!modal || !content) {
            console.error('Address modal not found in the DOM');
            return;
        }
        
        modal.classList.remove('opacity-100');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            // Clear the modal container
            const container = document.getElementById('addressModalContainer');
            if (container) {
                container.innerHTML = '';
            }
            document.body.style.overflow = 'auto';
            
            // Reset loading state
            this.isLoading = false;
            if (this.loadingTimeout) {
                clearTimeout(this.loadingTimeout);
                this.loadingTimeout = null;
            }
            
            // Reset button to Add state when modal is closed
            this.resetAddForm();
        }, 300);
    },
    
    loadAddresses: function() {
        // Addresses are now loaded from server via AJAX in loadModalContent()
        // This method is kept for compatibility but no longer needs to do anything
    },
    
    render: function() {
        const addressList = document.getElementById('address_list');
        if (!addressList) return;
        
        addressList.innerHTML = '';
        
        // Update default address display first
        this.updateDefaultAddressDisplay();
        
        if (this.addresses.length === 0) {
            addressList.innerHTML = `
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <p class="text-gray-500 text-sm">No addresses added yet</p>
                    <p class="text-gray-400 text-xs mt-1">Add your first address above</p>
                </div>
            `;
            return;
        }
        
        // Render each address
        this.addresses.forEach((address, index) => {
            const addressElement = this.createAddressElement(address, index);
            addressList.appendChild(addressElement);
        });
    },
    
    updateDefaultAddressDisplay: function() {
        const defaultAddressElement = document.getElementById('default_address_text');
        const defaultBadgesElement = document.getElementById('default_address_badges');
        
        if (!defaultAddressElement || !defaultBadgesElement) return;
        
        const defaultAddress = this.addresses.find(address => address.isDefault);
        
        if (defaultAddress) {
            const addressText = [
                defaultAddress.address_line_1,
                defaultAddress.address_line_2,
                defaultAddress.city,
                defaultAddress.state,
                defaultAddress.postal_code,
                this.getCountryName(defaultAddress.country_id)
            ].filter(Boolean).join(', ');
            
            defaultAddressElement.textContent = addressText;
            
            // Create badges
            let badgesHTML = '';
            if (defaultAddress.isDefault) {
                badgesHTML += `
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Primary
                    </span>
                `;
            }
            
            defaultBadgesElement.innerHTML = badgesHTML;
        } else {
            defaultAddressElement.textContent = 'No primary address set';
            defaultBadgesElement.innerHTML = '';
        }
    },
    
    // Country mapping to convert IDs to names
    getCountryName: function(countryId) {
        return this.countries[countryId] || countryId;
    },

    createAddressElement: function(address, index) {
        const addressDiv = document.createElement('div');
        addressDiv.className = 'bg-gray-50 rounded-xl p-4 border border-gray-200 hover:border-gray-300 transition-all duration-200 group';
        addressDiv.setAttribute('role', 'listitem');
        
        const addressText = [
            address.address_line_1,
            address.address_line_2,
            address.city,
            address.state,
            address.postal_code,
            this.getCountryName(address.country_id)
        ].filter(Boolean).join(', ');
        
        addressDiv.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 flex-1">
                    <div class="p-2 ${address.isDefault ? 'bg-purple-100' : 'bg-gray-100'} rounded-lg">
                        <svg class="w-5 h-5 ${address.isDefault ? 'text-purple-600' : 'text-gray-600'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">${addressText}</p>
                        <div class="flex items-center space-x-2 text-sm mt-1">
                            ${address.isDefault ? `
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Primary
                                </span>
                            ` : `
                                <button onclick="AddressModal.setDefaultAddress(${index}, event)" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 hover:bg-purple-100 hover:text-purple-800 transition-colors">
                                    Set as Primary
                                </button>
                            `}
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button onclick="AddressModal.editAddress(${index}, event)" class="p-2 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" aria-label="Edit address">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828L8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="AddressModal.removeAddress(${index}, event)" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" aria-label="Delete address">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;
        
        return addressDiv;
    },
    
    async addAddress(e) {
        // Prevent default form submission
        if (e) {
            e.preventDefault();
        }
        
        const addressLine1 = document.getElementById('new_address_line1');
        const addressLine2 = document.getElementById('new_address_line2');
        const city = document.getElementById('new_city');
        const state = document.getElementById('new_state');
        const postalCode = document.getElementById('new_postal_code');
        const country = document.getElementById('new_country');
        
        if (!addressLine1 || !addressLine1.value.trim()) {
            toast.error('Please enter address line 1');
            return;
        }
        
        if (!city || !city.value.trim()) {
            toast.error('Please enter a city');
            return;
        }
        
        if (!country || !country.value) {
            toast.error('Please select a country');
            return;
        }
        
        const addressData = {
            address_line_1: addressLine1.value.trim(),
            address_line_2: addressLine2 ? addressLine2.value.trim() : '',
            city: city.value.trim(),
            state: state ? state.value.trim() : '',
            postal_code: postalCode ? postalCode.value.trim() : '',
            country_id: country.value
        };
        
        // Check for duplicate
        const isDuplicate = this.addresses.some(address => 
            address.address_line_1 === addressData.address_line_1 &&
            address.city === addressData.city &&
            address.state === addressData.state &&
            address.postal_code === addressData.postal_code &&
            address.country_id === addressData.country_id
        );
        
        if (isDuplicate) {
            toast.error('This address already exists');
            return;
        }
        
        try {
            const customerId = window.customerData?.id;
            if (!customerId) throw new Error('Customer ID not found');
            
            // Show loading state
            this.showLoadingMessage('Adding address...');
            
            // Make API call to add address
            const response = await fetch(`/customer/${customerId}/addresses`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify(addressData),
                credentials: 'include'
            });
            
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.message || `HTTP ${response.status}`);
            }
            
            const result = await response.json();
            
            if (!result.success) {
                throw new Error(result.message || 'Failed to add address');
            }
            
            // Add the new address to the local array
            const newAddress = {
                id: result.data.id,
                address_line_1: result.data.address_line_1,
                address_line_2: result.data.address_line_2,
                city: result.data.city,
                state: result.data.state,
                postal_code: result.data.postal_code,
                country_id: result.data.country_id,
                isDefault: this.addresses.length === 0 // First address is default
            };
            
            this.addresses.push(newAddress);
            
            // Clear form
            this.resetAddForm();
            
            // Hide loading message
            this.hideLoadingMessage();
            
            // Update default address display and re-render
            this.updateDefaultAddressDisplay();
            this.render();
            
            // Update the main page address display if this is the first address (default)
            if (this.addresses.length === 1) {
                this.updateMainPageAddressDisplay(newAddress);
            }
            
            // Show success message
            toast.success('Address added successfully!');
            
        } catch (error) {
            console.error('❌ Error adding address:', error);
            toast.error(error.message || 'Failed to add address');
        }
    },
    
    async setDefaultAddress(index, e) {
        // Prevent default form submission
        if (e) {
            e.preventDefault();
        }
        
        try {
            const address = this.addresses[index];
            const customerId = window.customerData?.id;
            
            if (!customerId) throw new Error('Customer ID not found');
            if (!address.id) throw new Error('Address ID not found');
            
            // Show loading state
            this.showLoadingMessage('Updating primary address...');
            
            // Make API call to update default address
            const response = await fetch(`/customer/${customerId}/addresses/${address.id}/default`, {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                credentials: 'include'
            });
            
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.message || `HTTP ${response.status}`);
            }
            
            const result = await response.json();
            
            if (!result.success) {
                throw new Error(result.message || 'Failed to update default address');
            }
            
            // Update local state: remove default from all addresses, set new default
            this.addresses.forEach((address, i) => {
                address.isDefault = i === index;
            });
            
            // Hide loading message
            this.hideLoadingMessage();
            
            // Update the default address display and re-render
            this.updateDefaultAddressDisplay();
            this.render();
            
            // Update the main page address display
            this.updateMainPageAddressDisplay(address);
            
            // Show success message
            toast.success('Primary address updated!');
            
        } catch (error) {
            console.error('Error setting default address:', error);
            toast.error(error.message || 'Failed to update primary address');
            // Revert the UI state on error
            this.render();
        }
    },
    
    editAddress: function(index, e) {
        // Prevent default form submission
        if (e) {
            e.preventDefault();
        }
        
        const address = this.addresses[index];
        const addressLine1 = document.getElementById('new_address_line1');
        const addressLine2 = document.getElementById('new_address_line2');
        const city = document.getElementById('new_city');
        const state = document.getElementById('new_state');
        const postalCode = document.getElementById('new_postal_code');
        const country = document.getElementById('new_country');
        const addButton = document.getElementById('add_address_button');
        
        if (!addressLine1) {
            console.error('Address line 1 input not found');
            toast.error('Form elements not available. Please try again.');
            return;
        }
        
        if (!addButton) {
            console.error('Add button not found');
            toast.error('Form elements not available. Please try again.');
            return;
        }
        
        // Populate form with address data
        addressLine1.value = address.address_line_1;
        if (addressLine2) {
            addressLine2.value = address.address_line_2 || '';
        }
        if (city) {
            city.value = address.city;
        }
        if (state) {
            state.value = address.state || '';
        }
        if (postalCode) {
            postalCode.value = address.postal_code || '';
        }
        if (country) {
            country.value = address.country_id || '';
        }
        
        // Change button to Update state
        const buttonSpan = addButton.querySelector('span');
        if (buttonSpan) {
            buttonSpan.textContent = 'Update';
        }
        
        // Change the onclick handler to updateAddress
        addButton.setAttribute('onclick', `AddressModal.updateAddress(${index}, event)`);
        addButton.setAttribute('data-editing-index', index); // Add debugging attribute
        
        // Focus input
        addressLine1.focus();
        addressLine1.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        console.log('Edit address mode activated for index:', index, 'Address data:', address);
    },
    
    async updateAddress(index, e) {
        // Prevent default form submission
        if (e) {
            e.preventDefault();
        }
        
        const addressLine1 = document.getElementById('new_address_line1');
        const addressLine2 = document.getElementById('new_address_line2');
        const city = document.getElementById('new_city');
        const state = document.getElementById('new_state');
        const postalCode = document.getElementById('new_postal_code');
        const country = document.getElementById('new_country');
        const addButton = document.getElementById('add_address_button');
        
        if (!addressLine1) {
            console.error('Address line 1 input not found');
            toast.error('Form elements not available. Please try again.');
            return;
        }
        
        if (!addButton) {
            console.error('Add button not found');
            toast.error('Form elements not available. Please try again.');
            return;
        }
        
        if (!addressLine1 || !addressLine1.value.trim()) {
            toast.error('Please enter address line 1');
            return;
        }
        
        if (!city || !city.value.trim()) {
            toast.error('Please enter a city');
            return;
        }
        
        if (!country || !country.value) {
            toast.error('Please select a country');
            return;
        }
        
        const addressData = {
            address_line_1: addressLine1.value.trim(),
            address_line_2: addressLine2 ? addressLine2.value.trim() : '',
            city: city.value.trim(),
            state: state ? state.value.trim() : '',
            postal_code: postalCode ? postalCode.value.trim() : '',
            country_id: country.value
        };
        
        // Check for duplicate (excluding current address)
        const isDuplicate = this.addresses.some((address, i) => 
            i !== index &&
            address.address_line_1 === addressData.address_line_1 &&
            address.city === addressData.city &&
            address.state === addressData.state &&
            address.postal_code === addressData.postal_code &&
            address.country_id === addressData.country_id
        );
        if (isDuplicate) {
            toast.error('This address already exists');
            return;
        }
        
        try {
            const address = this.addresses[index];
            const customerId = window.customerData?.id;
            
            if (!customerId) throw new Error('Customer ID not found');
            if (!address.id) throw new Error('Address ID not found');
            
            // Show loading state
            this.showLoadingMessage('Updating address...');
            
            // Make API call to update address
            const response = await fetch(`/customer/${customerId}/addresses/${address.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify(addressData),
                credentials: 'include'
            });
            
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.message || `HTTP ${response.status}`);
            }
            
            const result = await response.json();
            
            if (!result.success) {
                throw new Error(result.message || 'Failed to update address');
            }
            
            // Update address in local array
            this.addresses[index].address_line_1 = result.data.address_line_1;
            this.addresses[index].address_line_2 = result.data.address_line_2;
            this.addresses[index].city = result.data.city;
            this.addresses[index].state = result.data.state;
            this.addresses[index].postal_code = result.data.postal_code;
            this.addresses[index].country_id = result.data.country_id; // Keep as ID for data structure
            
            // Hide loading message
            this.hideLoadingMessage();
            
            // Reset form and button to Add state
            this.resetAddForm();
            
            // Update default address display and re-render
            this.updateDefaultAddressDisplay();
            this.render();
            
            // Show success message
            toast.success('Address updated successfully!');
            
            console.log('Address updated successfully:', result.data);
            
        } catch (error) {
            console.error('Error updating address:', error);
            toast.error(error.message || 'Failed to update address');
        }
    },
    
    async removeAddress(index, e) {
        // Prevent default form submission
        if (e) {
            e.preventDefault();
        }
        
        const address = this.addresses[index];
        
        // Don't allow deleting if it's the only address
        if (this.addresses.length === 1) {
            toast.error('Cannot delete the only address. Please add another address first.');
            return;
        }
        
        // Don't allow deleting default address if there are other addresses
        if (address.isDefault) {
            toast.error('Cannot delete the primary address. Please set another address as primary first.');
            return;
        }
        
        // Confirm deletion
        const addressText = [
            address.address_line_1,
            address.address_line_2,
            address.city,
            address.state,
            address.postal_code
        ].filter(Boolean).join(', ');
        
        const confirmed = confirm(`Are you sure you want to delete this address?\n${addressText}`);
        if (!confirmed) {
            return;
        }
        
        try {
            const customerId = window.customerData?.id;
            
            if (!customerId) throw new Error('Customer ID not found');
            if (!address.id) throw new Error('Address ID not found');
            
            // Show loading state
            this.showLoadingMessage('Deleting address...');
            
            // Make API call to delete address
            const response = await fetch(`/customer/${customerId}/addresses/${address.id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                credentials: 'include'
            });
            
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.message || `HTTP ${response.status}`);
            }
            
            const result = await response.json();
            
            if (!result.success) {
                throw new Error(result.message || 'Failed to delete address');
            }
            
            // Remove the address from the local array
            this.addresses.splice(index, 1);
            
            // Hide loading message
            this.hideLoadingMessage();
            
            // Update default address display and re-render
            this.updateDefaultAddressDisplay();
            this.render();
            
            // Show success message
            toast.success('Address deleted successfully!');
            
        } catch (error) {
            console.error('Error deleting address:', error);
            toast.error(error.message || 'Failed to delete address');
        }
    },
    
    resetAddForm: function() {
        const addressLine1 = document.getElementById('new_address_line1');
        const addressLine2 = document.getElementById('new_address_line2');
        const city = document.getElementById('new_city');
        const state = document.getElementById('new_state');
        const postalCode = document.getElementById('new_postal_code');
        const country = document.getElementById('new_country');
        const addButton = document.getElementById('add_address_button');
        
        if (addressLine1) {
            addressLine1.value = '';
        }
        if (addressLine2) {
            addressLine2.value = '';
        }
        if (city) {
            city.value = '';
        }
        if (state) {
            state.value = '';
        }
        if (postalCode) {
            postalCode.value = '';
        }
        if (country) {
            country.value = '';
        }
        
        if (addButton) {
            const buttonSpan = addButton.querySelector('span');
            if (buttonSpan) {
                buttonSpan.textContent = 'Add Address';
            }
            // Reset the onclick handler
            addButton.setAttribute('onclick', 'AddressModal.addAddress(event)');
        }
    },
    
    showLoadingMessage: function(message) {
        // Create a temporary loading message
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'fixed top-4 right-4 bg-purple-600 text-white px-4 py-2 rounded-lg shadow-lg z-[10000] animate-slide-up';
        loadingDiv.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                ${message}
            </div>
        `;
        
        document.body.appendChild(loadingDiv);
        
        // Store reference so we can remove it later
        this.currentLoadingMessage = loadingDiv;
    },
    
    hideLoadingMessage: function() {
        if (this.currentLoadingMessage) {
            this.currentLoadingMessage.remove();
            this.currentLoadingMessage = null;
        }
    },

    // Update the main page address display when primary address changes
    updateMainPageAddressDisplay: function(address) {
        const addressDisplayElement = document.getElementById('main-address-display');
        
        if (addressDisplayElement) {
            // Debug: log current and target structure
            console.log('Current address element HTML:', addressDisplayElement.innerHTML);
            console.log('Address data:', address);
            
            // Match exact format from Blade template
            let addressHtml = '';
            
            if (address.address_line_1) {
                addressHtml += address.address_line_1;
            }
            
            if (address.address_line_2) {
                addressHtml += '<br>' + address.address_line_2;
            }
            
            // City, State, Postal Code on same line
            if (address.city || address.state || address.postal_code) {
                const cityStatePostal = [
                    address.city,
                    address.state,
                    address.postal_code
                ].filter(Boolean).join(' ');
                if (addressHtml) {
                    addressHtml += '<br>' + cityStatePostal;
                } else {
                    addressHtml += cityStatePostal;
                }
            }
            
            // Country on new line - use country_name if available, fallback to getCountryName
            if (address.country_id) {
                const countryName = address.country_name || this.getCountryName(address.country_id);
                if (addressHtml) {
                    addressHtml += '<br>' + countryName;
                } else {
                    addressHtml = countryName;
                }
            }
            
            console.log('Generated address HTML:', addressHtml);
            addressDisplayElement.innerHTML = addressHtml || 'No address added';
        }
    }
};

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        AddressModal.close();
    }
});

// Handle Enter key in address input - only when modal is open and focused
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('addressModal');
    const addressInput = document.getElementById('new_address_line1');
    
    // Only handle Enter key if modal is visible and not hidden
    if (e.key === 'Enter' && modal && !modal.classList.contains('hidden') && addressInput && document.activeElement === addressInput) {
        e.preventDefault();
        e.stopPropagation();
        AddressModal.addAddress();
    }
});

// Handle clicking outside modal - simple approach like email modal
function handleModalClick(e) {
    const modal = document.getElementById('addressModal');
    if (modal && e.target === modal) {
        AddressModal.close();
    }
}

// Initialize event listeners
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', handleModalClick);
    
    // Initialize AddressModal when DOM is ready
    if (window.AddressModal && typeof window.AddressModal.init === 'function') {
        window.AddressModal.init();
    } else {
        console.error('AddressModal not available for initialization');
    }
});
