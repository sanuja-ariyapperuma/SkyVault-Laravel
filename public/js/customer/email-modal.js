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
    
    // Load modal HTML via AJAX
    async function loadModalHtml() {
        if (elements.modalLoaded) return;
        
        try {
            const customerId = window.customerData?.id;
            if (!customerId) throw new Error('Customer ID not found');
            
            const response = await fetch(`${API_ENDPOINT}/${customerId}/email-modal`, {
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
            
            // Update emails data if provided
            if (result.data?.emails) {
                window.customerData.emails = result.data.emails;
                emails = result.data.emails;
            }
            
            elements.modalLoaded = true;
        } catch (error) {
            console.error('Error loading email modal:', error);
            alert(MESSAGES.LOAD_ERROR);
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
        emailDiv.className = 'flex items-center justify-between p-3 border rounded-md hover:bg-gray-50 transition-colors';
        emailDiv.setAttribute('role', 'listitem');
        emailDiv.innerHTML = `
            <div class="flex items-center space-x-3 flex-1">
                <input type="radio" name="default_email_modal" value="${index}" ${email.isDefault ? 'checked' : ''} 
                       class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500" 
                       onchange="EmailModal.setDefaultEmail(${index}, event)" aria-label="Set as default email address">
                <span class="text-sm ${email.isDefault ? 'font-medium text-blue-600' : 'text-gray-700'}">${email.email}</span>
                ${email.isDefault ? '<span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full" aria-label="Default email address">Default</span>' : ''}
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="EmailModal.editEmail(${index}, event)" 
                        class="text-blue-600 hover:text-blue-800 text-sm hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        aria-label="Edit email address ${email.email}">Edit</button>
                <button onclick="EmailModal.removeEmail(${index}, event)" 
                        class="text-red-600 hover:text-red-800 text-sm hover:underline focus:outline-none focus:ring-2 focus:ring-red-500" 
                        aria-label="Delete email address ${email.email}">Delete</button>
            </div>
        `;
        return emailDiv;
    }
    
    // Show error message
    function showError(message) {
        alert(message);
        elements.input?.focus();
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
                return;
            }
            
            // Document fragment for better performance
            const fragment = document.createDocumentFragment();
            emails.forEach((email, index) => {
                fragment.appendChild(createEmailElement(email, index));
            });
            elements.list.appendChild(fragment);
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
            const button = elements.input.nextElementSibling;
            
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
            const originalText = button.textContent;
            button.disabled = true;
            button.textContent = 'Adding...';
            
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
                
            } catch (error) {
                console.error('Error adding email:', error);
                showError(error.message || MESSAGES.LOAD_ERROR);
            } finally {
                // Restore button state
                button.disabled = false;
                button.textContent = originalText;
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
            
            const button = elements.input.nextElementSibling;
            button.textContent = 'Edit';
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
            const button = elements.input.nextElementSibling;
            
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
            const originalText = button.textContent;
            button.disabled = true;
            button.textContent = 'Updating...';
            
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
                
            } catch (error) {
                console.error('Error updating email:', error);
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
            const button = elements.input.nextElementSibling;
            if (button) {
                button.textContent = '+ Add';
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
                    alert('Cannot delete the only email address. Please add another email address first.');
                    return;
                } else {
                    alert('Cannot delete the default email address. Please set another email address as default first, then delete this one.');
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
    
    // Initialize
    initializeOnReady();
    document.addEventListener('keypress', handleKeyPress);
    document.addEventListener('click', handleModalClick);
    
    // Initialize emails from global data
    if (window.customerData?.emails) {
        emails = window.customerData.emails;
    }
})();
