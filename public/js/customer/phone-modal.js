// Phone Modal Script - This should appear in console if script loads
console.log('phone-modal.js script loaded successfully!');

// Modal functionality
window.PhoneModal = {
    phones: [], // Store phone data
    
    // Initialize method to ensure everything is ready
    init: function() {
        console.log('PhoneModal initializing...');
        const modal = document.getElementById('phoneModal');
        const content = document.getElementById('phoneModalContent');
        
        if (!modal) {
            console.error('Phone modal element not found in the DOM');
            return false;
        }
        
        if (!content) {
            console.error('Phone modal content element not found in the DOM');
            return false;
        }
        
        console.log('PhoneModal initialized successfully');
        return true;
    },
    
    open: function() {
        console.log('PhoneModal.open() called');
        
        // Ensure modal is initialized
        if (!this.init()) {
            console.error('PhoneModal initialization failed');
            return;
        }
        
        const modal = document.getElementById('phoneModal');
        const content = document.getElementById('phoneModalContent');
        
        if (!modal || !content) {
            console.error('Phone modal not found in the DOM');
            return;
        }
        
        console.log('Opening phone modal...');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
            console.log('Modal animation classes applied');
        }, 10);
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
        
        // Load phones from global data
        this.loadPhones();
        this.render();
        
        // Focus on input
        const phoneInput = document.getElementById('new_phone');
        if (phoneInput) {
            phoneInput.focus();
        }
    },
    
    close: function() {
        console.log('PhoneModal.close() called');
        const modal = document.getElementById('phoneModal');
        const content = document.getElementById('phoneModalContent');
        
        if (!modal || !content) {
            console.error('Phone modal not found in the DOM');
            return;
        }
        
        console.log('Closing phone modal...');
        modal.classList.remove('opacity-100');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            console.log('Phone modal closed');
        }, 300);
    },
    
    loadPhones: function() {
        // Load phones from global customer data
        if (window.customerData && window.customerData.phones) {
            this.phones = window.customerData.phones;
        } else {
            // Fallback sample data for demonstration
            this.phones = [
                {
                    id: 1,
                    number: '(555) 123-4567',
                    isDefault: true,
                    isWhatsapp: true
                },
                {
                    id: 2,
                    number: '(555) 987-6543',
                    isDefault: false,
                    isWhatsapp: false
                },
                {
                    id: 3,
                    number: '(555) 456-7890',
                    isDefault: false,
                    isWhatsapp: true
                }
            ];
        }
    },
    
    render: function() {
        const phoneList = document.getElementById('phone_list');
        if (!phoneList) return;
        
        phoneList.innerHTML = '';
        
        // Update default phone display first
        this.updateDefaultPhoneDisplay();
        
        if (this.phones.length === 0) {
            phoneList.innerHTML = `
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <p class="text-gray-500 text-sm">No phone numbers added yet</p>
                    <p class="text-gray-400 text-xs mt-1">Add your first phone number above</p>
                </div>
            `;
            return;
        }
        
        // Render each phone
        this.phones.forEach((phone, index) => {
            const phoneElement = this.createPhoneElement(phone, index);
            phoneList.appendChild(phoneElement);
        });
    },
    
    updateDefaultPhoneDisplay: function() {
        const defaultPhoneElement = document.getElementById('default_phone_number');
        const defaultBadgesElement = document.getElementById('default_phone_badges');
        
        if (!defaultPhoneElement || !defaultBadgesElement) return;
        
        const defaultPhone = this.phones.find(phone => phone.isDefault);
        
        if (defaultPhone) {
            defaultPhoneElement.textContent = defaultPhone.number;
            
            // Create badges
            let badgesHTML = '';
            if (defaultPhone.isWhatsapp) {
                badgesHTML += `
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.149-.67.149-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        WhatsApp Available
                    </span>
                `;
            }
            
            defaultBadgesElement.innerHTML = badgesHTML;
        } else {
            defaultPhoneElement.textContent = 'No primary phone set';
            defaultBadgesElement.innerHTML = '';
        }
    },
    
    createPhoneElement: function(phone, index) {
        const phoneDiv = document.createElement('div');
        phoneDiv.className = 'bg-gray-50 rounded-xl p-4 border border-gray-200 hover:border-gray-300 transition-all duration-200 group';
        phoneDiv.setAttribute('role', 'listitem');
        
        phoneDiv.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 flex-1">
                    <div class="p-2 ${phone.isDefault ? 'bg-primary-100' : 'bg-green-100'} rounded-lg">
                        <svg class="w-5 h-5 ${phone.isDefault ? 'text-primary-600' : 'text-green-600'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">${phone.number}</p>
                        <div class="flex items-center space-x-2 text-sm mt-1">
                            ${phone.isDefault ? `
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Primary
                                </span>
                            ` : `
                                <button onclick="PhoneModal.setDefaultPhone(${index})" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 hover:bg-primary-100 hover:text-primary-800 transition-colors">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Set as Primary
                                </button>
                            `}
                            ${phone.isWhatsapp ? `
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.149-.67.149-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                    </svg>
                                    WhatsApp
                                </span>
                            ` : ''}
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button onclick="PhoneModal.editPhone(${index})" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" aria-label="Edit phone number">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="PhoneModal.removePhone(${index})" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" aria-label="Delete phone number">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;
        
        return phoneDiv;
    },
    
    addPhone: async function() {
        const input = document.getElementById('new_phone');
        const whatsappCheckbox = document.getElementById('new_phone_whatsapp');
        
        if (!input || !input.value.trim()) {
            this.showErrorMessage('Please enter a phone number');
            return;
        }
        
        // Simple validation - 10 digits
        const cleanNumber = input.value.replace(/\D/g, '');
        if (cleanNumber.length !== 10) {
            this.showErrorMessage('Phone number must be exactly 10 digits');
            return;
        }
        
        // Format phone number
        const formattedNumber = this.formatPhoneNumber(cleanNumber);
        
        // Check for duplicate
        const isDuplicate = this.phones.some(phone => phone.number === formattedNumber);
        if (isDuplicate) {
            this.showErrorMessage('This phone number already exists');
            return;
        }
        
        try {
            const customerId = window.customerData?.id;
            if (!customerId) throw new Error('Customer ID not found');
            
            // Show loading state
            this.showLoadingMessage('Adding phone number...');
            
            // Make API call to add phone
            const response = await fetch(`/customer/${customerId}/phones`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    phone_number: cleanNumber,
                    is_default: this.phones.length === 0, // First phone is default
                    is_whatsapp: whatsappCheckbox ? whatsappCheckbox.checked : false
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
            const newPhone = {
                id: result.data.id,
                number: formattedNumber,
                isDefault: result.data.isDefault,
                isWhatsapp: result.data.isWhatsapp
            };
            
            this.phones.push(newPhone);
            
            // Clear form
            input.value = '';
            if (whatsappCheckbox) {
                whatsappCheckbox.checked = false;
            }
            
            // Hide loading message
            this.hideLoadingMessage();
            
            // Update default phone display and re-render
            this.updateDefaultPhoneDisplay();
            this.render();
            
            // Show success message
            this.showSuccessMessage('Phone number added successfully!');
            
        } catch (error) {
            console.error('Error adding phone:', error);
            this.showErrorMessage(error.message || 'Failed to add phone number');
        }
    },
    
    setDefaultPhone: async function(index) {
        try {
            const phone = this.phones[index];
            const customerId = window.customerData?.id;
            
            if (!customerId) throw new Error('Customer ID not found');
            if (!phone.id) throw new Error('Phone ID not found');
            
            // Show loading state
            this.showLoadingMessage('Updating primary phone...');
            
            // Make API call to update default phone
            const response = await fetch(`/customer/${customerId}/phones/${phone.id}/default`, {
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
            this.phones.forEach((phone, i) => {
                phone.isDefault = i === index;
            });
            
            // Hide loading message
            this.hideLoadingMessage();
            
            // Update the default phone display
            this.updateDefaultPhoneDisplay();
            
            // Re-render the phone list
            this.render();
            
            // Show success message
            this.showSuccessMessage('Primary phone number updated!');
            
        } catch (error) {
            console.error('Error setting default phone:', error);
            this.showErrorMessage(error.message || 'Failed to update primary phone number');
            // Revert the UI state on error
            this.render();
        }
    },
    
    editPhone: function(index) {
        const phone = this.phones[index];
        const input = document.getElementById('new_phone');
        const whatsappCheckbox = document.getElementById('new_phone_whatsapp');
        
        if (!input) return;
        
        // Populate form with phone data
        input.value = phone.number.replace(/\D/g, '');
        if (whatsappCheckbox) {
            whatsappCheckbox.checked = phone.isWhatsapp;
        }
        
        // Change button to update
        const addButton = input.nextElementSibling;
        if (addButton) {
            addButton.textContent = 'Update';
            addButton.setAttribute('onclick', `PhoneModal.updatePhone(${index})`);
        }
        
        // Focus input
        input.focus();
        input.scrollIntoView({ behavior: 'smooth', block: 'center' });
    },
    
    updatePhone: function(index) {
        const input = document.getElementById('new_phone');
        const whatsappCheckbox = document.getElementById('new_phone_whatsapp');
        
        if (!input || !input.value.trim()) {
            alert('Please enter a phone number');
            return;
        }
        
        // Simple validation - 10 digits
        const cleanNumber = input.value.replace(/\D/g, '');
        if (cleanNumber.length !== 10) {
            alert('Phone number must be exactly 10 digits');
            return;
        }
        
        // Format phone number
        const formattedNumber = this.formatPhoneNumber(cleanNumber);
        
        // Check for duplicate (excluding current phone)
        const isDuplicate = this.phones.some((phone, i) => i !== index && phone.number === formattedNumber);
        if (isDuplicate) {
            alert('This phone number already exists');
            return;
        }
        
        // Update phone
        this.phones[index].number = formattedNumber;
        this.phones[index].isWhatsapp = whatsappCheckbox ? whatsappCheckbox.checked : false;
        
        // Reset form
        this.resetAddButton();
        
        // Re-render
        this.render();
        
        // Show success message
        this.showSuccessMessage('Phone number updated successfully!');
    },
    
    removePhone: async function(index) {
        const phone = this.phones[index];
        
        // Don't allow deleting if it's the only phone
        if (this.phones.length === 1) {
            this.showErrorMessage('Cannot delete the only phone number. Please add another phone number first.');
            return;
        }
        
        // Don't allow deleting default phone if there are other phones
        if (phone.isDefault) {
            this.showErrorMessage('Cannot delete the primary phone number. Please set another phone number as primary first.');
            return;
        }
        
        // Confirm deletion
        const confirmed = confirm(`Are you sure you want to delete the phone number ${phone.number}?`);
        if (!confirmed) {
            return;
        }
        
        try {
            const customerId = window.customerData?.id;
            
            if (!customerId) throw new Error('Customer ID not found');
            if (!phone.id) throw new Error('Phone ID not found');
            
            // Show loading state
            this.showLoadingMessage('Deleting phone number...');
            
            // Make API call to delete phone
            const response = await fetch(`/customer/${customerId}/phones/${phone.id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token']')?.getAttribute('content')
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
            this.phones.splice(index, 1);
            
            // Hide loading message
            this.hideLoadingMessage();
            
            // Update default phone display and re-render
            this.updateDefaultPhoneDisplay();
            this.render();
            
            // Show success message
            this.showSuccessMessage('Phone number deleted successfully!');
            
        } catch (error) {
            console.error('Error deleting phone:', error);
            this.showErrorMessage(error.message || 'Failed to delete phone number');
        }
    },
    
    resetAddButton: function() {
        const input = document.getElementById('new_phone');
        if (!input) return;
        
        input.value = '';
        const whatsappCheckbox = document.getElementById('new_phone_whatsapp');
        if (whatsappCheckbox) {
            whatsappCheckbox.checked = false;
        }
        
        const addButton = input.nextElementSibling;
        if (addButton) {
            addButton.textContent = 'Add';
            addButton.setAttribute('onclick', 'PhoneModal.addPhone()');
        }
    },
    
    formatPhoneNumber: function(number) {
        // Format as (XXX) XXX-XXXX
        const cleaned = number.replace(/\D/g, '');
        if (cleaned.length === 10) {
            return `(${cleaned.slice(0, 3)}) ${cleaned.slice(3, 6)}-${cleaned.slice(6)}`;
        }
        return cleaned;
    },
    
    showSuccessMessage: function(message) {
        // Create a temporary success message
        const successDiv = document.createElement('div');
        successDiv.className = 'fixed top-4 right-4 bg-success-500 text-white px-4 py-2 rounded-lg shadow-lg z-[10000] animate-slide-up';
        successDiv.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                ${message}
            </div>
        `;
        
        document.body.appendChild(successDiv);
        
        // Remove after 3 seconds
        setTimeout(() => {
            successDiv.remove();
        }, 3000);
    },
    
    showErrorMessage: function(message) {
        // Create a temporary error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'fixed top-4 right-4 bg-danger-500 text-white px-4 py-2 rounded-lg shadow-lg z-[10000] animate-slide-up';
        errorDiv.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                ${message}
            </div>
        `;
        
        document.body.appendChild(errorDiv);
        
        // Remove after 5 seconds
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    },
    
    showLoadingMessage: function(message) {
        // Create a temporary loading message
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'fixed top-4 right-4 bg-primary-500 text-white px-4 py-2 rounded-lg shadow-lg z-[10000] animate-slide-up';
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
    }
};

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        PhoneModal.close();
    }
});

// Close modal on background click
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('phoneModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                PhoneModal.close();
            }
        });
    }
    
    // Initialize PhoneModal when DOM is ready
    console.log('DOM ready, initializing PhoneModal...');
    if (window.PhoneModal && typeof window.PhoneModal.init === 'function') {
        window.PhoneModal.init();
        console.log('Full PhoneModal object is now available!');
    } else {
        console.error('PhoneModal not available for initialization');
    }
});
