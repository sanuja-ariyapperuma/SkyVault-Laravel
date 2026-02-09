// Email Modal Module

(() => {
    'use strict';
    
    // Cache DOM elements and state
    const elements = {
        modal: null,
        list: null,
        input: null,
        helpText: null,
        modalLoaded: false
    };
    
    let emails = [];
    
    // Constants
    const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const API_ENDPOINT = '/customer';
    const MESSAGES = {
        LOAD_ERROR: 'Failed to load email modal. Please try again.',
        EMPTY_EMAIL: 'Please enter an email address',
        INVALID_EMAIL: 'Please enter a valid email address',
        DUPLICATE_EMAIL: 'This email address already exists'
    };
    
    // Load modal HTML via AJAX (not needed since modal is included in main view)
    async function loadModalHtml() {
        // Modal is already included in the main view, so we just need to initialize
        elements.modalLoaded = true;
        
        // Initialize emails from global data if available
        if (window.customerData?.emails) {
            emails = window.customerData.emails;
        } else {
            emails = [];
        }
    }
    
    // Initialize DOM references
    function initializeElements() {
        elements.modal = document.getElementById('emailModal');
        elements.list = document.getElementById('email_list');
        elements.input = document.getElementById('new_email');
        elements.helpText = document.getElementById('emailHelp');
    }
    
    // Email validation
    function validateEmail(email) {
        const cleanEmail = email.trim();
        const isValid = EMAIL_REGEX.test(cleanEmail);
        
        return {
            isValid,
            cleanEmail,
            error: !cleanEmail ? MESSAGES.EMPTY_EMAIL : 
                   !isValid ? MESSAGES.INVALID_EMAIL : 
                   null
        };
    }
    
    // Check for duplicate email addresses
    function isDuplicateEmail(email, excludeIndex = -1) {
        return emails.some((emailItem, index) => 
            emailItem.email === email && index !== excludeIndex
        );
    }
    
    // Create email item element
    function createEmailElement(email, index) {
        const emailDiv = document.createElement('div');
        emailDiv.className = 'bg-gray-50 rounded-xl p-4 border border-gray-200 hover:border-gray-300 transition-colors group';
        emailDiv.setAttribute('role', 'listitem');
        
        const badges = [];
        if (email.isDefault) {
            badges.push(`
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Primary
                </span>
            `);
        }
        
        badges.push(`
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Verified
            </span>
        `);
        
        emailDiv.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">${email.email}</p>
                        <div class="flex items-center space-x-2 text-sm mt-1">
                            ${badges.join('')}
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    ${!email.isDefault ? `
                        <button onclick="EmailModal.setDefaultEmail(${index}, event)" 
                                class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" 
                                aria-label="Set as default email address ${email.email}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </button>
                    ` : ''}
                    <button onclick="EmailModal.editEmail(${index}, event)" 
                            class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                            aria-label="Edit email address ${email.email}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    ${!email.isDefault ? `
                        <button onclick="EmailModal.removeEmail(${index}, event)" 
                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                aria-label="Delete email address ${email.email}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    ` : ''}
                </div>
            </div>
        `;
        return emailDiv;
    }
    
    // Update default email display
    function updateDefaultEmailDisplay() {
        const defaultEmailElement = document.getElementById('default_email_address');
        const defaultBadgesElement = document.getElementById('default_email_badges');
        const currentDefaultSection = document.getElementById('current_default_email');
        
        if (!defaultEmailElement || !defaultBadgesElement || !currentDefaultSection) return;
        
        const defaultEmail = emails.find(email => email.isDefault);
        
        if (defaultEmail) {
            defaultEmailElement.textContent = defaultEmail.email;
            defaultBadgesElement.innerHTML = `
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Verified
                </span>
            `;
            currentDefaultSection.style.display = 'block';
        } else {
            defaultEmailElement.textContent = 'No primary email set';
            defaultBadgesElement.innerHTML = '';
            if (emails.length === 0) {
                currentDefaultSection.style.display = 'none';
            } else {
                currentDefaultSection.style.display = 'block';
            }
        }
    }
    
    // Show error message with better UI
    function showError(message) {
        // Create toast notification instead of alert
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-[10000] flex items-center space-x-3 transform translate-x-full transition-transform duration-300';
        toast.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>${message}</span>
        `;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
        
        elements.input?.focus();
    }
    
    // Show success message
    function showSuccess(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-[10000] flex items-center space-x-3 transform translate-x-full transition-transform duration-300';
        toast.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>${message}</span>
        `;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }
    
    // Public API
    window.EmailModal = {
        async open() {
            // Initialize elements if needed
            if (!elements.modal) {
                elements.modal = document.getElementById('emailModal');
                elements.list = document.getElementById('email_list');
                elements.input = document.getElementById('new_email');
                elements.helpText = document.getElementById('emailHelp');
            }
            
            // Always try to load the latest emails from global data
            if (window.customerData?.emails) {
                emails = window.customerData.emails;
            } else {
                emails = [];
            }
            
            if (!elements.modal) {
                console.error('Email modal not found in the DOM');
                return;
            }
            
            elements.modal.classList.remove('hidden');
            elements.modal.classList.add('opacity-100');
            
            // Animate content
            const content = document.getElementById('emailModalContent');
            if (content) {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
            
            elements.modal.setAttribute('aria-hidden', 'false');
            this.render();
            elements.input?.focus();
        },
        
        close() {
            // Initialize elements if needed
            if (!elements.modal) {
                elements.modal = document.getElementById('emailModal');
            }
            
            if (!elements.modal) {
                console.error('Email modal not found in the DOM');
                return;
            }
            
            elements.modal.classList.remove('opacity-100');
            
            // Animate content
            const content = document.getElementById('emailModalContent');
            if (content) {
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');
            }
            
            setTimeout(() => {
                elements.modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
            
            elements.modal.setAttribute('aria-hidden', 'true');
        },
        
        render() {
            if (!elements.list) initializeElements();
            elements.list.innerHTML = '';
            
            if (emails.length === 0) {
                elements.list.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">No email addresses added yet</p>';
            } else {
                // Document fragment for better performance
                const fragment = document.createDocumentFragment();
                emails.forEach((email, index) => {
                    fragment.appendChild(createEmailElement(email, index));
                });
                elements.list.appendChild(fragment);
            }
            
            // Update default email display
            updateDefaultEmailDisplay();
        },
        
        async setDefaultEmail(index, e) {
            // Prevent default form submission
            if (e) {
                e.preventDefault();
            }
            
            try {
                const customerId = window.customerData?.id;
                const email = emails[index];
                
                if (!customerId) throw new Error('Customer ID not found');
                if (!email.id) throw new Error('Email ID not found');
                
                const response = await fetch(`${API_ENDPOINT}/${customerId}/emails/${email.id}/default`, {
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
                    throw new Error(result.message || 'Failed to update default email address');
                }
                
                // Update local state: remove default from all emails, set new default
                emails.forEach((email, i) => {
                    email.isDefault = i === index;
                });
                
                this.render();
                showSuccess('Default email address updated successfully');
                
            } catch (error) {
                console.error('Error setting default email:', error);
                showError(error.message || MESSAGES.LOAD_ERROR);
                // Revert the radio button selection on error
                this.render();
            }
        },
        
        async addEmail(e) {
            // Prevent default form submission
            if (e) {
                e.preventDefault();
            }
            
            if (!elements.input) initializeElements();
            const email = elements.input.value.trim();
            
            // Find the button more reliably - it's the button with onclick="EmailModal.addEmail()"
            const button = document.querySelector('button[onclick*="EmailModal.addEmail"]');
            
            const validation = validateEmail(email);
            if (!validation.isValid) {
                showError(validation.error);
                return;
            }
            
            if (isDuplicateEmail(validation.cleanEmail)) {
                showError(MESSAGES.DUPLICATE_EMAIL);
                return;
            }
            
            // Show loading state
            const originalHTML = button.innerHTML;
            button.disabled = true;
            button.innerHTML = `
                <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>Adding...</span>
            `;
            
            try {
                const customerId = window.customerData?.id;
                if (!customerId) throw new Error('Customer ID not found');
                
                const response = await fetch(`${API_ENDPOINT}/${customerId}/emails`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({
                        email: validation.cleanEmail
                    }),
                    credentials: 'include'
                });
                
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || `HTTP ${response.status}`);
                }
                
                const result = await response.json();
                
                if (!result.success) {
                    throw new Error(result.message || 'Failed to add email address');
                }
                
                // Add the new email to the local array
                emails.push({
                    id: result.data.id,
                    email: result.data.email,
                    isDefault: emails.length === 0 // First email is default
                });
                
                // Reset form
                elements.input.value = '';
                this.render();
                showSuccess('Email address added successfully');
                
            } catch (error) {
                console.error('Error adding email:', error);
                showError(error.message || MESSAGES.LOAD_ERROR);
            } finally {
                // Restore button state
                button.disabled = false;
                button.innerHTML = originalHTML;
            }
        },
        
        editEmail(index, e) {
            // Prevent default form submission
            if (e) {
                e.preventDefault();
            }
            
            const email = emails[index];
            if (!elements.input) initializeElements();
            
            elements.input.value = email.email;
            
            // Find the button more reliably
            const button = document.querySelector('button[onclick*="EmailModal.addEmail"], button[onclick*="EmailModal.updateEmail"]');
            button.innerHTML = `
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span>Update</span>
            `;
            button.setAttribute('onclick', `EmailModal.updateEmail(${index}, event)`);
            button.setAttribute('aria-label', 'Update email address');
            
            elements.input.focus();
            elements.input.scrollIntoView({ behavior: 'smooth', block: 'center' });
        },
        
        async updateEmail(index, e) {
            // Prevent default form submission
            if (e) {
                e.preventDefault();
            }
            
            if (!elements.input) initializeElements();
            const email = elements.input.value.trim();
            
            // Find the button more reliably
            const button = document.querySelector('button[onclick*="EmailModal.addEmail"], button[onclick*="EmailModal.updateEmail"]');
            
            const validation = validateEmail(email);
            if (!validation.isValid) {
                showError(validation.error);
                return;
            }
            
            if (isDuplicateEmail(validation.cleanEmail, index)) {
                showError(MESSAGES.DUPLICATE_EMAIL);
                return;
            }
            
            // Show loading state
            const originalHTML = button.innerHTML;
            button.disabled = true;
            button.innerHTML = `
                <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>Updating...</span>
            `;
            
            try {
                const customerId = window.customerData?.id;
                const emailItem = emails[index];
                
                if (!customerId) throw new Error('Customer ID not found');
                if (!emailItem.id) throw new Error('Email ID not found');
                
                const response = await fetch(`${API_ENDPOINT}/${customerId}/emails/${emailItem.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({
                        email: validation.cleanEmail
                    }),
                    credentials: 'include'
                });
                
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || `HTTP ${response.status}`);
                }
                
                const result = await response.json();
                
                if (!result.success) {
                    throw new Error(result.message || 'Failed to update email address');
                }
                
                // Update the email in the local array
                emails[index].email = result.data.email;
                
                this.render();
                this.resetAddButton();
                showSuccess('Email address updated successfully');
                
            } catch (error) {
                console.error('Error updating email:', error);
                showError(error.message || MESSAGES.LOAD_ERROR);
            } finally {
                // Restore button state
                button.disabled = false;
                button.innerHTML = originalHTML;
            }
        },
        
        resetAddButton() {
            if (!elements.input) return;
            elements.input.value = '';
            // Find the button more reliably
            const button = document.querySelector('button[onclick*="EmailModal.addEmail"], button[onclick*="EmailModal.updateEmail"]');
            if (button) {
                button.innerHTML = `
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Add</span>
                `;
                button.setAttribute('onclick', 'EmailModal.addEmail(event)');
                button.setAttribute('aria-label', 'Add email address');
            }
        },
        
        async removeEmail(index, e) {
            // Prevent default form submission
            if (e) {
                e.preventDefault();
            }
            
            const email = emails[index];
            
            // Check if trying to delete default email
            if (email.isDefault) {
                if (emails.length <= 1) {
                    showError('Cannot delete the only email address. Please add another email address first.');
                    return;
                } else {
                    showError('Cannot delete the default email address. Please set another email address as default first, then delete this one.');
                    return;
                }
            }
            
            // Show confirmation dialog
            const confirmed = confirm(`Are you sure you want to delete the email address ${email.email}?`);
            if (!confirmed) {
                return;
            }
            
            try {
                const customerId = window.customerData?.id;
                
                if (!customerId) throw new Error('Customer ID not found');
                if (!email.id) throw new Error('Email ID not found');
                
                const response = await fetch(`${API_ENDPOINT}/${customerId}/emails/${email.id}`, {
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
                    throw new Error(result.message || 'Failed to delete email address');
                }
                
                // Remove the email from the local array
                emails.splice(index, 1);
                
                this.render();
                showSuccess('Email address deleted successfully');
                
            } catch (error) {
                console.error('Error deleting email:', error);
                showError(error.message || MESSAGES.LOAD_ERROR);
            }
        }
    };
    
    // Update email display in main form
    window.updateEmailDisplay = function() {
        const emailDisplay = document.getElementById('email_display');
        if (!emailDisplay) return;
        
        if (emails.length === 0) {
            emailDisplay.textContent = 'No email addresses added';
            emailDisplay.className = 'text-sm text-gray-600 mb-2';
        } else {
            const defaultEmail = emails.find(e => e.isDefault) || emails[0];
            const otherCount = emails.length - 1;
            
            if (emails.length === 1) {
                emailDisplay.textContent = defaultEmail.email;
                emailDisplay.className = 'text-sm text-gray-900 mb-2 font-medium';
            } else {
                emailDisplay.textContent = `${defaultEmail.email} (+${otherCount} more)`;
                emailDisplay.className = 'text-sm text-gray-900 mb-2 font-medium';
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
        if (e.key === 'Enter' && e.target.id === 'new_email') {
            e.preventDefault();
            EmailModal.addEmail();
        } else if (e.key === 'Escape' && elements.modal && !elements.modal.classList.contains('hidden')) {
            EmailModal.close();
        }
    }
    
    // Handle clicking outside modal
    function handleModalClick(e) {
        if (elements.modal && e.target === elements.modal) {
            EmailModal.close();
        }
    }
    
    // Initialize emails from global data
    function initializeEmails() {
        if (window.customerData?.emails) {
            emails = window.customerData.emails;
        } else {
            emails = [];
        }
    }
    
    // Initialize
    initializeOnReady();
    document.addEventListener('keypress', handleKeyPress);
    document.addEventListener('click', handleModalClick);
    
    // Initialize emails from global data
    initializeEmails();
    
    // Also try to initialize after a short delay in case data loads later
    setTimeout(initializeEmails, 100);
})();
