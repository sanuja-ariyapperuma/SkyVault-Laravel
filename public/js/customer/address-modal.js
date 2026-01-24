// Address Modal Module

(() => {
    'use strict';
    
    // Cache DOM elements and state
    const elements = {
        modal: null,
        list: null,
        addressLine1: null,
        addressLine2: null,
        city: null,
        state: null,
        postalCode: null,
        country: null,
        helpText: null,
        modalLoaded: false
    };
    
    let addresses = [];
    
    // Constants
    const API_ENDPOINT = '/customer';
    const MESSAGES = {
        LOAD_ERROR: 'Failed to load address modal. Please try again.',
        EMPTY_ADDRESS_LINE1: 'Please enter address line 1',
        EMPTY_CITY: 'Please enter a city',
        EMPTY_COUNTRY: 'Please select a country',
        DUPLICATE_ADDRESS: 'This address already exists'
    };
    
    // Load modal HTML via AJAX
    async function loadModalHtml() {
        if (elements.modalLoaded) return;
        
        try {
            const customerId = window.customerData?.id;
            if (!customerId) throw new Error('Customer ID not found');
            
            const url = `${API_ENDPOINT}/${customerId}/address-modal`;
            
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                credentials: 'include'
            });
            
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            
            const result = await response.json();
            
            if (!result.success) {
                throw new Error(result.message || 'Failed to load modal');
            }
            
            // Append modal HTML to body
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = result.data.html;
            const modalElement = tempDiv.firstElementChild;
            if (modalElement) {
                document.body.appendChild(modalElement);
            }
            
            // Update addresses data if provided
            if (result.data.addresses) {
                window.customerData.addresses = result.data.addresses;
                addresses = result.data.addresses;
            }
            
            elements.modalLoaded = true;
        } catch (error) {
            console.error('Error loading address modal:', error);
            alert(MESSAGES.LOAD_ERROR);
        }
    }
    
    // Initialize DOM references
    function initializeElements() {
        elements.modal = document.getElementById('addressModal');
        elements.list = document.getElementById('address_list');
        elements.addressLine1 = document.getElementById('new_address_line1');
        elements.addressLine2 = document.getElementById('new_address_line2');
        elements.city = document.getElementById('new_city');
        elements.state = document.getElementById('new_state');
        elements.postalCode = document.getElementById('new_postal_code');
        elements.country = document.getElementById('new_country');
        elements.helpText = document.getElementById('addressHelp');
    }
    
    // Address validation
    function validateAddress(addressData) {
        const errors = [];
        
        if (!addressData.address_line_1 || addressData.address_line_1.trim() === '') {
            errors.push(MESSAGES.EMPTY_ADDRESS_LINE1);
        }
        
        if (!addressData.city || addressData.city.trim() === '') {
            errors.push(MESSAGES.EMPTY_CITY);
        }
        
        if (!addressData.country_id || addressData.country_id === '') {
            errors.push(MESSAGES.EMPTY_COUNTRY);
        }
        
        return {
            isValid: errors.length === 0,
            errors
        };
    }
    
    // Check for duplicate addresses
    function isDuplicateAddress(addressData, excludeIndex = -1) {
        return addresses.some((address, index) => 
            address.address_line_1 === addressData.address_line_1 &&
            address.city === addressData.city &&
            address.state === addressData.state &&
            address.postal_code === addressData.postal_code &&
            address.country_id === addressData.country_id &&
            index !== excludeIndex
        );
    }
    
    // Create address item element
    function createAddressElement(address, index) {
        const addressDiv = document.createElement('div');
        addressDiv.className = 'flex items-center justify-between p-3 border rounded-md hover:bg-gray-50 transition-colors';
        addressDiv.setAttribute('role', 'listitem');
        
        const addressText = [
            address.address_line_1,
            address.address_line_2,
            address.city,
            address.state,
            address.postal_code,
            address.country_id ? `Country: ${address.country_id}` : ''
        ].filter(Boolean).join(', ');
        
        addressDiv.innerHTML = `
            <div class="flex items-center space-x-3 flex-1">
                <input type="radio" name="default_address_modal" value="${index}" ${address.isDefault ? 'checked' : ''} 
                       class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500" 
                       onchange="AddressModal.setDefaultAddress(${index}, event)" aria-label="Set as default address">
                <span class="text-sm ${address.isDefault ? 'font-medium text-blue-600' : 'text-gray-700'}">${addressText}</span>
                ${address.isDefault ? '<span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full" aria-label="Default address">Default</span>' : ''}
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="AddressModal.editAddress(${index}, event)" 
                        class="text-blue-600 hover:text-blue-800 text-sm hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        aria-label="Edit address">Edit</button>
                <button onclick="AddressModal.removeAddress(${index}, event)" 
                        class="text-red-600 hover:text-red-800 text-sm hover:underline focus:outline-none focus:ring-2 focus:ring-red-500" 
                        aria-label="Delete address">Delete</button>
            </div>
        `;
        return addressDiv;
    }
    
    // Show error message
    function showError(errors) {
        const errorText = errors.join('\n');
        alert(errorText);
        elements.addressLine1?.focus();
    }
    
    // Public API
    window.AddressModal = {
        async open() {
            // Initialize elements if needed
            if (!elements.modal) {
                elements.modal = document.getElementById('addressModal');
                elements.list = document.getElementById('address_list');
                elements.addressLine1 = document.getElementById('new_address_line1');
                elements.addressLine2 = document.getElementById('new_address_line2');
                elements.city = document.getElementById('new_city');
                elements.state = document.getElementById('new_state');
                elements.postalCode = document.getElementById('new_postal_code');
                elements.country = document.getElementById('new_country');
                elements.helpText = document.getElementById('addressHelp');
            }
            
            if (!elements.modal) {
                console.error('Address modal not found in the DOM');
                return;
            }
            
            elements.modal.classList.remove('hidden');
            elements.modal.classList.add('opacity-100');
            
            // Animate content
            const content = document.getElementById('addressModalContent');
            if (content) {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
            
            elements.modal.setAttribute('aria-hidden', 'false');
            this.render();
            elements.addressLine1?.focus();
        },
        
        close() {
            // Initialize elements if needed
            if (!elements.modal) {
                elements.modal = document.getElementById('addressModal');
            }
            
            if (!elements.modal) {
                console.error('Address modal not found in the DOM');
                return;
            }
            
            elements.modal.classList.remove('opacity-100');
            
            // Animate content
            const content = document.getElementById('addressModalContent');
            if (content) {
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');
            }
            
            setTimeout(() => {
                elements.modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
            
            elements.modal.setAttribute('aria-hidden', 'true');
            
            // Update the main address display when modal closes
            updateAddressDisplay();
        },
        
        render() {
            if (!elements.list) initializeElements();
            elements.list.innerHTML = '';
            
            if (addresses.length === 0) {
                elements.list.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">No addresses added yet</p>';
                return;
            }
            
            // Document fragment for better performance
            const fragment = document.createDocumentFragment();
            addresses.forEach((address, index) => {
                fragment.appendChild(createAddressElement(address, index));
            });
            elements.list.appendChild(fragment);
        },
        
        async setDefaultAddress(index, e) {
            // Prevent default form submission
            if (e) {
                e.preventDefault();
            }
            
            try {
                const customerId = window.customerData?.id;
                const address = addresses[index];
                
                if (!customerId) throw new Error('Customer ID not found');
                if (!address.id) throw new Error('Address ID not found');
                
                const response = await fetch(`${API_ENDPOINT}/${customerId}/addresses/${address.id}/default`, {
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
                addresses.forEach((address, i) => {
                    address.isDefault = i === index;
                });
                
                this.render();
                
            } catch (error) {
                console.error('Error setting default address:', error);
                showError([error.message || MESSAGES.LOAD_ERROR]);
                // Revert the radio button selection on error
                this.render();
            }
        },
        
        async addAddress(e) {
            // Prevent default form submission
            if (e) {
                e.preventDefault();
            }
            
            if (!elements.addressLine1) initializeElements();
            
            const addressData = {
                address_line_1: elements.addressLine1.value.trim(),
                address_line_2: elements.addressLine2.value.trim(),
                city: elements.city.value.trim(),
                state: elements.state.value.trim(),
                postal_code: elements.postalCode.value.trim(),
                country_id: elements.country.value
            };
            
            const validation = validateAddress(addressData);
            if (!validation.isValid) {
                showError(validation.errors);
                return;
            }
            
            if (isDuplicateAddress(addressData)) {
                showError([MESSAGES.DUPLICATE_ADDRESS]);
                return;
            }
            
            // Show loading state
            const button = document.querySelector('button[onclick="AddressModal.addAddress()"]');
            const originalText = button.textContent;
            button.disabled = true;
            button.textContent = 'Adding...';
            
            try {
                const customerId = window.customerData?.id;
                if (!customerId) throw new Error('Customer ID not found');
                
                const response = await fetch(`${API_ENDPOINT}/${customerId}/addresses`, {
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
                addresses.push({
                    id: result.data.id,
                    address_line_1: result.data.address_line_1,
                    address_line_2: result.data.address_line_2,
                    city: result.data.city,
                    state: result.data.state,
                    postal_code: result.data.postal_code,
                    country_id: result.data.country_id,
                    isDefault: addresses.length === 0 // First address is default
                });
                
                // Reset form
                this.resetAddForm();
                this.render();
                
            } catch (error) {
                console.error('Error adding address:', error);
                showError([error.message || MESSAGES.LOAD_ERROR]);
            } finally {
                // Restore button state
                button.disabled = false;
                button.textContent = originalText;
            }
        },
        
        editAddress(index, e) {
            // Prevent default form submission
            if (e) {
                e.preventDefault();
            }
            
            const address = addresses[index];
            if (!elements.addressLine1) initializeElements();
            
            elements.addressLine1.value = address.address_line_1;
            elements.addressLine2.value = address.address_line_2 || '';
            elements.city.value = address.city;
            elements.state.value = address.state || '';
            elements.postalCode.value = address.postal_code || '';
            elements.country.value = address.country_id || '';
            
            const button = document.querySelector('button[onclick="AddressModal.addAddress()"]');
            button.textContent = 'Edit';
            button.setAttribute('onclick', `AddressModal.updateAddress(${index}, event)`);
            button.setAttribute('aria-label', 'Update address');
            
            elements.addressLine1.focus();
            elements.addressLine1.scrollIntoView({ behavior: 'smooth', block: 'center' });
        },
        
        async updateAddress(index, e) {
            // Prevent default form submission
            if (e) {
                e.preventDefault();
            }
            
            if (!elements.addressLine1) initializeElements();
            
            const addressData = {
                address_line_1: elements.addressLine1.value.trim(),
                address_line_2: elements.addressLine2.value.trim(),
                city: elements.city.value.trim(),
                state: elements.state.value.trim(),
                postal_code: elements.postalCode.value.trim(),
                country_id: elements.country.value
            };
            
            const validation = validateAddress(addressData);
            if (!validation.isValid) {
                showError(validation.errors);
                return;
            }
            
            if (isDuplicateAddress(addressData, index)) {
                showError([MESSAGES.DUPLICATE_ADDRESS]);
                return;
            }
            
            // Show loading state
            const button = document.querySelector('button[onclick="AddressModal.updateAddress(${index})"]');
            const originalText = button.textContent;
            button.disabled = true;
            button.textContent = 'Updating...';
            
            try {
                const customerId = window.customerData?.id;
                const address = addresses[index];
                
                if (!customerId) throw new Error('Customer ID not found');
                if (!address.id) throw new Error('Address ID not found');
                
                const response = await fetch(`${API_ENDPOINT}/${customerId}/addresses/${address.id}`, {
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
                
                // Update the address in the local array
                addresses[index].address_line_1 = result.data.address_line_1;
                addresses[index].address_line_2 = result.data.address_line_2;
                addresses[index].city = result.data.city;
                addresses[index].state = result.data.state;
                addresses[index].postal_code = result.data.postal_code;
                addresses[index].country_id = result.data.country_id;
                
                this.render();
                this.resetAddForm();
                
            } catch (error) {
                console.error('Error updating address:', error);
                showError([error.message || MESSAGES.LOAD_ERROR]);
            } finally {
                // Restore button state
                button.disabled = false;
                button.textContent = originalText;
            }
        },
        
        resetAddForm() {
            if (!elements.addressLine1) return;
            elements.addressLine1.value = '';
            elements.addressLine2.value = '';
            elements.city.value = '';
            elements.state.value = '';
            elements.postalCode.value = '';
            elements.country.value = '';
            
            const button = document.querySelector('button[onclick="AddressModal.addAddress()"]');
            if (button) {
                button.textContent = '+ Add Address';
                button.setAttribute('onclick', 'AddressModal.addAddress(event)');
                button.setAttribute('aria-label', 'Add address');
            }
        },
        
        async removeAddress(index, e) {
            // Prevent default form submission
            if (e) {
                e.preventDefault();
            }
            
            const address = addresses[index];
            
            // Check if trying to delete default address
            if (address.isDefault) {
                if (addresses.length <= 1) {
                    alert('Cannot delete the only address. Please add another address first.');
                    return;
                } else {
                    alert('Cannot delete the default address. Please set another address as default first, then delete this one.');
                    return;
                }
            }
            
            // Show confirmation dialog
            const confirmed = confirm(`Are you sure you want to delete this address?\n${address.address_line_1}\n${address.city}, ${address.state} ${address.postal_code}`);
            if (!confirmed) {
                return;
            }
            
            try {
                const customerId = window.customerData?.id;
                
                if (!customerId) throw new Error('Customer ID not found');
                if (!address.id) throw new Error('Address ID not found');
                
                const response = await fetch(`${API_ENDPOINT}/${customerId}/addresses/${address.id}`, {
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
                addresses.splice(index, 1);
                
                this.render();
                
            } catch (error) {
                console.error('Error deleting address:', error);
                showError([error.message || MESSAGES.LOAD_ERROR]);
            }
        }
    };
    
    // Update address display in main form
    window.updateAddressDisplay = function() {
        const addressDisplay = document.getElementById('address_display');
        if (!addressDisplay) return;
        
        if (addresses.length === 0) {
            addressDisplay.textContent = 'No addresses added';
            addressDisplay.className = 'text-sm text-gray-600 mb-2';
        } else {
            const defaultAddress = addresses.find(a => a.isDefault) || addresses[0];
            const otherCount = addresses.length - 1;
            
            if (addresses.length === 1) {
                const addressText = [
                    defaultAddress.address_line_1,
                    defaultAddress.address_line_2,
                    defaultAddress.city,
                    defaultAddress.state,
                    defaultAddress.postal_code
                ].filter(Boolean).join(', ');
                addressDisplay.textContent = addressText;
                addressDisplay.className = 'text-sm text-gray-900 mb-2 font-medium';
            } else {
                const addressText = [
                    defaultAddress.address_line_1,
                    defaultAddress.address_line_2,
                    defaultAddress.city,
                    defaultAddress.state,
                    defaultAddress.postal_code
                ].filter(Boolean).join(', ');
                addressDisplay.textContent = `${addressText} (+${otherCount} more)`;
                addressDisplay.className = 'text-sm text-gray-900 mb-2 font-medium';
            }
        }
    };
    
    // Initialize on DOM ready
    function initializeOnReady() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeElements);
        } else {
            initializeElements();
        }
    }
    
    // Handle Enter key
    function handleKeyPress(e) {
        if (e.key === 'Enter' && e.target.id === 'new_address_line1') {
            e.preventDefault();
            AddressModal.addAddress();
        } else if (e.key === 'Escape' && elements.modal && !elements.modal.classList.contains('hidden')) {
            AddressModal.close();
        }
    }
    
    // Handle clicking outside modal
    function handleModalClick(e) {
        if (elements.modal && e.target === elements.modal) {
            AddressModal.close();
        }
    }
    
    // Initialize
    initializeOnReady();
    document.addEventListener('keypress', handleKeyPress);
    document.addEventListener('click', handleModalClick);
    
    // Initialize addresses from global data
    if (window.customerData?.addresses) {
        addresses = window.customerData.addresses;
    }
})();
