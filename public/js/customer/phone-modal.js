// Phone Modal Module
(() => {
    'use strict';
    
    // Cache DOM elements and state
    const elements = {
        modal: null,
        list: null,
        input: null,
        whatsappCheckbox: null,
        helpText: null,
        modalLoaded: false
    };
    
    let phones = [];
    
    // Constants
    const PHONE_REGEX = /^\d{10}$/;
    const API_ENDPOINT = '/customer';
    const MESSAGES = {
        LOAD_ERROR: 'Failed to load phone modal. Please try again.',
        EMPTY_PHONE: 'Please enter a phone number',
        INVALID_PHONE: 'Phone number must be exactly 10 digits',
        DUPLICATE_PHONE: 'This phone number already exists'
    };
    
    // Load modal HTML via AJAX
    async function loadModalHtml() {
        if (elements.modalLoaded) return;
        
        try {
            const customerId = window.customerData?.id;
            if (!customerId) throw new Error('Customer ID not found');
            
            const response = await fetch(`${API_ENDPOINT}/${customerId}/phone-modal`, {
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
            tempDiv.innerHTML = result.html;
            const modalElement = tempDiv.firstElementChild;
            if (modalElement) {
                document.body.appendChild(modalElement);
            }
            
            // Update phones data if provided
            if (result.data?.phones) {
                window.customerData.phones = result.data.phones;
                phones = result.data.phones;
            }
            
            elements.modalLoaded = true;
        } catch (error) {
            console.error('Error loading phone modal:', error);
            alert(MESSAGES.LOAD_ERROR);
        }
    }
    
    // Initialize DOM references
    function initializeElements() {
        elements.modal = document.getElementById('phoneModal');
        elements.list = document.getElementById('phone_list');
        elements.input = document.getElementById('new_phone');
        elements.whatsappCheckbox = document.getElementById('new_phone_whatsapp');
        elements.helpText = document.getElementById('phoneHelp');
    }
    
    // Phone validation
    function validatePhone(number) {
        const cleanNumber = number.replace(/\D/g, '');
        const isValid = PHONE_REGEX.test(cleanNumber);
        
        return {
            isValid,
            cleanNumber,
            error: !cleanNumber ? MESSAGES.EMPTY_PHONE : 
                   !isValid ? MESSAGES.INVALID_PHONE : 
                   null
        };
    }
    
    // Check for duplicate phone numbers
    function isDuplicatePhone(number, excludeIndex = -1) {
        return phones.some((phone, index) => 
            phone.number === number && index !== excludeIndex
        );
    }
    
    // Create phone item element
    function createPhoneElement(phone, index) {
        const phoneDiv = document.createElement('div');
        phoneDiv.className = 'flex items-center justify-between p-3 border rounded-md hover:bg-gray-50 transition-colors';
        phoneDiv.setAttribute('role', 'listitem');
        phoneDiv.innerHTML = `
            <div class="flex items-center space-x-3 flex-1">
                <input type="radio" name="default_phone_modal" value="${index}" ${phone.isDefault ? 'checked' : ''} 
                       class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500" 
                       onchange="PhoneModal.setDefaultPhone(${index})" aria-label="Set as default phone number">
                <span class="text-sm ${phone.isDefault ? 'font-medium text-blue-600' : 'text-gray-700'}">${phone.number}</span>
                ${phone.isDefault ? '<span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full" aria-label="Default phone number">Default</span>' : ''}
                ${phone.isWhatsapp ? '<span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full ml-1" aria-label="WhatsApp enabled">WhatsApp</span>' : ''}
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="PhoneModal.editPhone(${index})" 
                        class="text-blue-600 hover:text-blue-800 text-sm hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        aria-label="Edit phone number ${phone.number}">Edit</button>
                <button onclick="PhoneModal.removePhone(${index})" 
                        class="text-red-600 hover:text-red-800 text-sm hover:underline focus:outline-none focus:ring-2 focus:ring-red-500" 
                        aria-label="Delete phone number ${phone.number}">Delete</button>
            </div>
        `;
        return phoneDiv;
    }
    
    // Show error message
    function showError(message) {
        alert(message);
        elements.input?.focus();
    }
    
    // Public API
    window.PhoneModal = {
        async open() {
            await loadModalHtml();
            if (!elements.modal) initializeElements();
            elements.modal.classList.remove('hidden');
            elements.modal.setAttribute('aria-hidden', 'false');
            this.render();
            elements.input?.focus();
        },
        
        close() {
            if (!elements.modal) initializeElements();
            elements.modal.classList.add('hidden');
            elements.modal.setAttribute('aria-hidden', 'true');
            
            // Update the main phone display when modal closes
            updatePhoneDisplay();
        },
        
        render() {
            if (!elements.list) initializeElements();
            elements.list.innerHTML = '';
            
            if (phones.length === 0) {
                elements.list.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">No phone numbers added yet</p>';
                return;
            }
            
            // Document fragment for better performance
            const fragment = document.createDocumentFragment();
            phones.forEach((phone, index) => {
                fragment.appendChild(createPhoneElement(phone, index));
            });
            elements.list.appendChild(fragment);
        },
        
        async setDefaultPhone(index) {
            try {
                const customerId = window.customerData?.id;
                const phone = phones[index];
                
                if (!customerId) throw new Error('Customer ID not found');
                if (!phone.id) throw new Error('Phone ID not found');
                
                const response = await fetch(`${API_ENDPOINT}/${customerId}/phones/${phone.id}/default`, {
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
                    throw new Error(result.message || 'Failed to update default phone number');
                }
                
                // Update local state: remove default from all phones, set new default
                phones.forEach((phone, i) => {
                    phone.isDefault = i === index;
                });
                
                this.render();
                
            } catch (error) {
                console.error('Error setting default phone:', error);
                showError(error.message || MESSAGES.LOAD_ERROR);
                // Revert the radio button selection on error
                this.render();
            }
        },
        
        async addPhone() {
            if (!elements.input) initializeElements();
            const number = elements.input.value.trim();
            const button = elements.input.nextElementSibling;
            
            const validation = validatePhone(number);
            if (!validation.isValid) {
                showError(validation.error);
                return;
            }
            
            if (isDuplicatePhone(validation.cleanNumber)) {
                showError(MESSAGES.DUPLICATE_PHONE);
                return;
            }
            
            // Show loading state
            const originalText = button.textContent;
            button.disabled = true;
            button.textContent = 'Adding...';
            
            try {
                const customerId = window.customerData?.id;
                if (!customerId) throw new Error('Customer ID not found');
                
                const response = await fetch(`${API_ENDPOINT}/${customerId}/phones`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({
                        phone_number: validation.cleanNumber,
                        is_default: phones.length === 0, // First phone is default
                        is_whatsapp: elements.whatsappCheckbox?.checked || false
                    }),
                    credentials: 'include'
                });
                
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || `HTTP ${response.status}`);
                }
                
                const result = await response.json();
                
                if (!result.success) {
                    throw new Error(result.message || 'Failed to add phone number');
                }
                
                // Add the new phone to the local array
                phones.push({
                    id: result.data.id,
                    number: result.data.number,
                    isDefault: result.data.isDefault,
                    isWhatsapp: result.data.isWhatsapp
                });
                
                // Reset form
                elements.input.value = '';
                if (elements.whatsappCheckbox) {
                    elements.whatsappCheckbox.checked = false;
                }
                this.render();
                
            } catch (error) {
                console.error('Error adding phone:', error);
                showError(error.message || MESSAGES.LOAD_ERROR);
            } finally {
                // Restore button state
                button.disabled = false;
                button.textContent = originalText;
            }
        },
        
        editPhone(index) {
            const phone = phones[index];
            if (!elements.input) initializeElements();
            
            elements.input.value = phone.number;
            if (elements.whatsappCheckbox) {
                elements.whatsappCheckbox.checked = phone.isWhatsapp || false;
            }
            
            const button = elements.input.nextElementSibling;
            button.textContent = 'Edit';
            button.setAttribute('onclick', `PhoneModal.updatePhone(${index})`);
            button.setAttribute('aria-label', 'Update phone number');
            
            elements.input.focus();
            elements.input.scrollIntoView({ behavior: 'smooth', block: 'center' });
        },
        
        async updatePhone(index) {
            if (!elements.input) initializeElements();
            const number = elements.input.value.trim();
            const button = elements.input.nextElementSibling;
            
            const validation = validatePhone(number);
            if (!validation.isValid) {
                showError(validation.error);
                return;
            }
            
            if (isDuplicatePhone(validation.cleanNumber, index)) {
                showError(MESSAGES.DUPLICATE_PHONE);
                return;
            }
            
            // Show loading state
            const originalText = button.textContent;
            button.disabled = true;
            button.textContent = 'Updating...';
            
            try {
                const customerId = window.customerData?.id;
                const phone = phones[index];
                
                if (!customerId) throw new Error('Customer ID not found');
                if (!phone.id) throw new Error('Phone ID not found');
                
                const response = await fetch(`${API_ENDPOINT}/${customerId}/phones/${phone.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({
                        phone_number: validation.cleanNumber,
                        is_whatsapp: elements.whatsappCheckbox?.checked || false
                    }),
                    credentials: 'include'
                });
                
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || `HTTP ${response.status}`);
                }
                
                const result = await response.json();
                
                if (!result.success) {
                    throw new Error(result.message || 'Failed to update phone number');
                }
                
                // Update the phone in the local array
                phones[index].number = result.data.number;
                phones[index].isWhatsapp = result.data.isWhatsapp;
                
                this.render();
                this.resetAddButton();
                
            } catch (error) {
                console.error('Error updating phone:', error);
                showError(error.message || MESSAGES.LOAD_ERROR);
            } finally {
                // Restore button state
                button.disabled = false;
                button.textContent = originalText;
            }
        },
        
        resetAddButton() {
            if (!elements.input) return;
            elements.input.value = '';
            if (elements.whatsappCheckbox) {
                elements.whatsappCheckbox.checked = false;
            }
            const button = elements.input.nextElementSibling;
            if (button) {
                button.textContent = '+ Add';
                button.setAttribute('onclick', 'PhoneModal.addPhone()');
                button.setAttribute('aria-label', 'Add phone number');
            }
        },
        
        async removePhone(index) {
            const phone = phones[index];
            
            // Check if trying to delete default phone
            if (phone.isDefault) {
                if (phones.length <= 1) {
                    alert('Cannot delete the only phone number. Please add another phone number first.');
                    return;
                } else {
                    alert('Cannot delete the default phone number. Please set another phone number as default first, then delete this one.');
                    return;
                }
            }
            
            // Show confirmation dialog
            const confirmed = confirm(`Are you sure you want to delete the phone number ${phone.number}?`);
            if (!confirmed) {
                return;
            }
            
            try {
                const customerId = window.customerData?.id;
                
                if (!customerId) throw new Error('Customer ID not found');
                if (!phone.id) throw new Error('Phone ID not found');
                
                const response = await fetch(`${API_ENDPOINT}/${customerId}/phones/${phone.id}`, {
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
                    throw new Error(result.message || 'Failed to delete phone number');
                }
                
                // Remove the phone from the local array
                phones.splice(index, 1);
                
                this.render();
                
            } catch (error) {
                console.error('Error deleting phone:', error);
                showError(error.message || MESSAGES.LOAD_ERROR);
            }
        }
    };
    
    // Update phone display in main form
    window.updatePhoneDisplay = function() {
        const phoneDisplay = document.getElementById('phone_display');
        if (!phoneDisplay) return;
        
        if (phones.length === 0) {
            phoneDisplay.textContent = 'No phone numbers added';
            phoneDisplay.className = 'text-sm text-gray-600 mb-2';
        } else {
            const defaultPhone = phones.find(p => p.isDefault) || phones[0];
            const otherCount = phones.length - 1;
            
            if (phones.length === 1) {
                phoneDisplay.textContent = defaultPhone.number;
                phoneDisplay.className = 'text-sm text-gray-900 mb-2 font-medium';
            } else {
                phoneDisplay.textContent = `${defaultPhone.number} (+${otherCount} more)`;
                phoneDisplay.className = 'text-sm text-gray-900 mb-2 font-medium';
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
        if (e.key === 'Enter' && e.target.id === 'new_phone') {
            e.preventDefault();
            PhoneModal.addPhone();
        } else if (e.key === 'Escape' && elements.modal && !elements.modal.classList.contains('hidden')) {
            PhoneModal.close();
        }
    }
    
    // Handle clicking outside modal
    function handleModalClick(e) {
        if (elements.modal && e.target === elements.modal) {
            PhoneModal.close();
        }
    }
    
    // Initialize
    initializeOnReady();
    document.addEventListener('keypress', handleKeyPress);
    document.addEventListener('click', handleModalClick);
    
    // Initialize phones from global data
    if (window.customerData?.phones) {
        phones = window.customerData.phones;
    }
})();
