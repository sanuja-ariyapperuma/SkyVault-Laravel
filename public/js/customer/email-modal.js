// Email Modal Module
(() => {
    'use strict';
    
    // Cache DOM elements
    const elements = {
        modal: null,
        list: null,
        input: null,
        helpText: null
    };
    
    // Initialize DOM references
    function initializeElements() {
        elements.modal = document.getElementById('emailModal');
        elements.list = document.getElementById('email_list');
        elements.input = document.getElementById('new_email');
        elements.helpText = document.getElementById('emailHelp');
    }
    
    // Email validation
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return {
            isValid: emailRegex.test(email),
            error: !email ? 'Please enter an email address' : 
                   !emailRegex.test(email) ? 'Please enter a valid email address' : 
                   null
        };
    }
    
    // Check for duplicate email addresses
    function isDuplicateEmail(email, excludeIndex = -1) {
        return emails.some((emailItem, index) => 
            emailItem.address === email && index !== excludeIndex
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
                       onchange="EmailModal.setDefaultEmail(${index})" aria-label="Set as default email">
                <span class="text-sm ${email.isDefault ? 'font-medium text-blue-600' : 'text-gray-700'}">${email.address}</span>
                ${email.isDefault ? '<span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full" aria-label="Default email address">Default</span>' : ''}
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="EmailModal.editEmail(${index})" 
                        class="text-blue-600 hover:text-blue-800 text-sm hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        aria-label="Edit email address ${email.address}">Edit</button>
                <button onclick="EmailModal.removeEmail(${index})" 
                        class="text-red-600 hover:text-red-800 text-sm hover:underline focus:outline-none focus:ring-2 focus:ring-red-500" 
                        aria-label="Delete email address ${email.address}">Delete</button>
            </div>
        `;
        return emailDiv;
    }
    
    // Public API
    window.EmailModal = {
        open() {
            if (!elements.modal) initializeElements();
            elements.modal.classList.remove('hidden');
            elements.modal.setAttribute('aria-hidden', 'false');
            this.render();
            elements.input.focus();
        },
        
        close() {
            if (!elements.modal) initializeElements();
            elements.modal.classList.add('hidden');
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
        
        setDefaultEmail(index) {
            emails.forEach((email, i) => {
                email.isDefault = i === index;
            });
            this.render();
        },
        
        addEmail() {
            if (!elements.input) initializeElements();
            const address = elements.input.value.trim();
            
            const validation = validateEmail(address);
            if (!validation.isValid) {
                alert(validation.error);
                elements.input.focus();
                return;
            }
            
            if (isDuplicateEmail(address)) {
                alert('This email address already exists');
                elements.input.focus();
                return;
            }
            
            emails.push({
                address: address,
                isDefault: emails.length === 0
            });
            
            elements.input.value = '';
            this.render();
        },
        
        editEmail(index) {
            const email = emails[index];
            if (!elements.input) initializeElements();
            
            elements.input.value = email.address;
            const button = elements.input.nextElementSibling;
            button.textContent = 'Edit';
            button.setAttribute('onclick', `EmailModal.updateEmail(${index})`);
            button.setAttribute('aria-label', 'Update email address');
            
            elements.input.focus();
            elements.input.scrollIntoView({ behavior: 'smooth', block: 'center' });
        },
        
        updateEmail(index) {
            if (!elements.input) initializeElements();
            const address = elements.input.value.trim();
            
            const validation = validateEmail(address);
            if (!validation.isValid) {
                alert(validation.error);
                elements.input.focus();
                return;
            }
            
            if (isDuplicateEmail(address, index)) {
                alert('This email address already exists');
                elements.input.focus();
                return;
            }
            
            if (address !== emails[index].address) {
                emails[index].address = address;
                this.render();
            }
            
            elements.input.value = '';
            const button = elements.input.nextElementSibling;
            button.textContent = '+ Add';
            button.setAttribute('onclick', 'EmailModal.addEmail()');
            button.setAttribute('aria-label', 'Add email address');
        },
        
        removeEmail(index) {
            const wasDefault = emails[index].isDefault;
            emails.splice(index, 1);
            
            if (wasDefault && emails.length > 0) {
                emails[0].isDefault = true;
            }
            
            this.render();
        },
        
        save() {
            const selectedDefault = document.querySelector('input[name="default_email_modal"]:checked');
            if (selectedDefault) {
                emails.forEach((email, index) => {
                    email.isDefault = index === parseInt(selectedDefault.value);
                });
            }
            
            updateEmailDisplay();
            this.close();
        }
    };
    
    // Update email display in main form
    function updateEmailDisplay() {
        const emailDisplay = document.getElementById('email_display');
        if (!emailDisplay) return;
        
        if (emails.length === 0) {
            emailDisplay.textContent = 'No email addresses added';
            emailDisplay.className = 'text-sm text-gray-600 mb-2';
        } else {
            const defaultEmail = emails.find(e => e.isDefault) || emails[0];
            const otherCount = emails.length - 1;
            
            if (emails.length === 1) {
                emailDisplay.textContent = defaultEmail.address;
                emailDisplay.className = 'text-sm text-gray-900 mb-2 font-medium';
            } else {
                emailDisplay.textContent = `${defaultEmail.address} (+${otherCount} more)`;
                emailDisplay.className = 'text-sm text-gray-900 mb-2 font-medium';
            }
        }
    }
    
    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeElements);
    } else {
        initializeElements();
    }
    
    // Handle Enter key
    document.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && e.target.id === 'new_email') {
            e.preventDefault();
            EmailModal.addEmail();
        }
    });
})();
