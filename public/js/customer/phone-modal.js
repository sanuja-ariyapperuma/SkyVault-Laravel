// Phone Modal Module
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
        elements.modal = document.getElementById('phoneModal');
        elements.list = document.getElementById('phone_list');
        elements.input = document.getElementById('new_phone');
        elements.helpText = document.getElementById('phoneHelp');
    }
    
    // Phone validation
    function validatePhone(number) {
        const cleanNumber = number.replace(/\D/g, '');
        return {
            isValid: /^\d{10}$/.test(cleanNumber),
            cleanNumber: cleanNumber,
            error: !cleanNumber ? 'Please enter a phone number' : 
                   !/^\d{10}$/.test(cleanNumber) ? 'Phone number must be exactly 10 digits' : 
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
    
    // Public API
    window.PhoneModal = {
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
        
        setDefaultPhone(index) {
            phones.forEach((phone, i) => {
                phone.isDefault = i === index;
            });
            this.render();
        },
        
        addPhone() {
            if (!elements.input) initializeElements();
            const number = elements.input.value.trim();
            
            const validation = validatePhone(number);
            if (!validation.isValid) {
                alert(validation.error);
                elements.input.focus();
                return;
            }
            
            if (isDuplicatePhone(number)) {
                alert('This phone number already exists');
                elements.input.focus();
                return;
            }
            
            phones.push({
                number: validation.cleanNumber,
                isDefault: phones.length === 0
            });
            
            elements.input.value = '';
            this.render();
        },
        
        editPhone(index) {
            const phone = phones[index];
            if (!elements.input) initializeElements();
            
            elements.input.value = phone.number;
            const button = elements.input.nextElementSibling;
            button.textContent = 'Edit';
            button.setAttribute('onclick', `PhoneModal.updatePhone(${index})`);
            button.setAttribute('aria-label', 'Update phone number');
            
            elements.input.focus();
            elements.input.scrollIntoView({ behavior: 'smooth', block: 'center' });
        },
        
        updatePhone(index) {
            if (!elements.input) initializeElements();
            const number = elements.input.value.trim();
            
            const validation = validatePhone(number);
            if (!validation.isValid) {
                alert(validation.error);
                elements.input.focus();
                return;
            }
            
            if (isDuplicatePhone(number, index)) {
                alert('This phone number already exists');
                elements.input.focus();
                return;
            }
            
            if (validation.cleanNumber !== phones[index].number) {
                phones[index].number = validation.cleanNumber;
                this.render();
            }
            
            elements.input.value = '';
            const button = elements.input.nextElementSibling;
            button.textContent = '+ Add';
            button.setAttribute('onclick', 'PhoneModal.addPhone()');
            button.setAttribute('aria-label', 'Add phone number');
        },
        
        removePhone(index) {
            const wasDefault = phones[index].isDefault;
            phones.splice(index, 1);
            
            if (wasDefault && phones.length > 0) {
                phones[0].isDefault = true;
            }
            
            this.render();
        },
        
        save() {
            const selectedDefault = document.querySelector('input[name="default_phone_modal"]:checked');
            if (selectedDefault) {
                phones.forEach((phone, index) => {
                    phone.isDefault = index === parseInt(selectedDefault.value);
                });
            }
            
            updatePhoneDisplay();
            this.close();
        }
    };
    
    // Update phone display in main form
    function updatePhoneDisplay() {
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
    }
    
    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeElements);
    } else {
        initializeElements();
    }
    
    // Handle Enter key
    document.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && e.target.id === 'new_phone') {
            e.preventDefault();
            PhoneModal.addPhone();
        }
    });
})();
