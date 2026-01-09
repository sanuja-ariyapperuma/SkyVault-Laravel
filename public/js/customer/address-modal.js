// Address Modal Module
(() => {
    'use strict';
    
    // Cache DOM elements
    const elements = {
        modal: null,
        list: null,
        inputs: {
            line1: null,
            line2: null,
            city: null,
            state: null,
            country: null
        },
        helpText: null
    };
    
    // Initialize DOM references
    function initializeElements() {
        elements.modal = document.getElementById('addressModal');
        elements.list = document.getElementById('address_list');
        elements.inputs.line1 = document.getElementById('new_address_line1');
        elements.inputs.line2 = document.getElementById('new_address_line2');
        elements.inputs.city = document.getElementById('new_city');
        elements.inputs.state = document.getElementById('new_state');
        elements.inputs.country = document.getElementById('new_country');
        elements.helpText = document.getElementById('addressHelp');
    }
    
    // Address validation
    function validateAddress(address) {
        const errors = [];
        
        if (!address.line1?.trim()) errors.push('Address line 1 is required');
        if (!address.city?.trim()) errors.push('City is required');
        if (!address.state?.trim()) errors.push('State is required');
        if (!address.country) errors.push('Country is required');
        
        return {
            isValid: errors.length === 0,
            errors: errors,
            cleanAddress: {
                line1: address.line1?.trim() || '',
                line2: address.line2?.trim() || '',
                city: address.city?.trim() || '',
                state: address.state?.trim() || '',
                country: address.country || ''
            }
        };
    }
    
    // Check for duplicate addresses
    function isDuplicateAddress(address, excludeIndex = -1) {
        return addresses.some((addr, index) => 
            index !== excludeIndex &&
            addr.line1 === address.line1 &&
            addr.line2 === address.line2 &&
            addr.city === address.city &&
            addr.state === address.state &&
            addr.country === address.country
        );
    }
    
    // Create address table row
    function createAddressRow(address, index) {
        const row = document.createElement('tr');
        row.className = 'border-b hover:bg-gray-50 transition-colors';
        row.setAttribute('role', 'listitem');
        row.innerHTML = `
            <td class="py-2 px-2">
                <input type="radio" name="default_address_modal" value="${index}" ${address.isDefault ? 'checked' : ''} 
                       class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500" 
                       onchange="AddressModal.setDefaultAddress(${index})" aria-label="Set as default address">
            </td>
            <td class="py-2 px-2">
                <div class="${address.isDefault ? 'font-medium text-blue-600' : 'text-gray-700'}">
                    <div>${address.line1}</div>
                    ${address.line2 ? `<div class="text-xs">${address.line2}</div>` : ''}
                </div>
            </td>
            <td class="py-2 px-2 ${address.isDefault ? 'font-medium text-blue-600' : 'text-gray-700'}">${address.city}</td>
            <td class="py-2 px-2 ${address.isDefault ? 'font-medium text-blue-600' : 'text-gray-700'}">${address.state}</td>
            <td class="py-2 px-2 ${address.isDefault ? 'font-medium text-blue-600' : 'text-gray-700'}">${address.country}</td>
            <td class="py-2 px-2">
                <div class="flex justify-center space-x-2">
                    <button onclick="AddressModal.editAddress(${index})" 
                            class="text-blue-600 hover:text-blue-800 text-xs hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            aria-label="Edit address">Edit</button>
                    <button onclick="AddressModal.removeAddress(${index})" 
                            class="text-red-600 hover:text-red-800 text-xs hover:underline focus:outline-none focus:ring-2 focus:ring-red-500" 
                            aria-label="Delete address">Delete</button>
                </div>
            </td>
        `;
        return row;
    }
    
    // Public API
    window.AddressModal = {
        open() {
            if (!elements.modal) initializeElements();
            elements.modal.classList.remove('hidden');
            elements.modal.setAttribute('aria-hidden', 'false');
            this.render();
            elements.inputs.line1.focus();
        },
        
        close() {
            if (!elements.modal) initializeElements();
            elements.modal.classList.add('hidden');
            elements.modal.setAttribute('aria-hidden', 'true');
        },
        
        render() {
            if (!elements.list) initializeElements();
            elements.list.innerHTML = '';
            
            if (addresses.length === 0) {
                elements.list.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">No addresses added yet</p>';
                return;
            }
            
            // Create table structure
            const table = document.createElement('table');
            table.className = 'w-full text-sm';
            table.innerHTML = `
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2 px-2">Default</th>
                        <th class="text-left py-2 px-2">Address</th>
                        <th class="text-left py-2 px-2">City</th>
                        <th class="text-left py-2 px-2">State</th>
                        <th class="text-left py-2 px-2">Country</th>
                        <th class="text-center py-2 px-2">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            `;
            
            // Document fragment for better performance
            const fragment = document.createDocumentFragment();
            addresses.forEach((address, index) => {
                fragment.appendChild(createAddressRow(address, index));
            });
            elements.list.appendChild(table);
            table.querySelector('tbody').appendChild(fragment);
        },
        
        setDefaultAddress(index) {
            addresses.forEach((address, i) => {
                address.isDefault = i === index;
            });
            this.render();
        },
        
        addAddress() {
            if (!elements.modal) initializeElements();
            
            const address = {
                line1: elements.inputs.line1.value.trim(),
                line2: elements.inputs.line2.value.trim(),
                city: elements.inputs.city.value.trim(),
                state: elements.inputs.state.value.trim(),
                country: elements.inputs.country.value
            };
            
            const validation = validateAddress(address);
            if (!validation.isValid) {
                alert(validation.errors.join('\\n'));
                elements.inputs.line1.focus();
                return;
            }
            
            if (isDuplicateAddress(address)) {
                alert('This address already exists');
                elements.inputs.line1.focus();
                return;
            }
            
            addresses.push({
                ...address,
                isDefault: addresses.length === 0
            });
            
            // Clear form
            Object.values(elements.inputs).forEach(input => input.value = '');
            this.render();
        },
        
        editAddress(index) {
            const address = addresses[index];
            if (!elements.modal) initializeElements();
            
            // Populate form
            elements.inputs.line1.value = address.line1;
            elements.inputs.line2.value = address.line2;
            elements.inputs.city.value = address.city;
            elements.inputs.state.value = address.state;
            elements.inputs.country.value = address.country;
            
            // Update button
            const addButton = Array.from(elements.list.querySelectorAll('button')).find(btn => 
                btn.textContent.includes('Add Address')
            );
            if (addButton) {
                addButton.textContent = 'Edit';
                addButton.setAttribute('onclick', `AddressModal.updateAddress(${index})`);
                addButton.setAttribute('aria-label', 'Update address');
            }
            
            elements.inputs.line1.focus();
            elements.inputs.line1.scrollIntoView({ behavior: 'smooth', block: 'center' });
        },
        
        updateAddress(index) {
            if (!elements.modal) initializeElements();
            
            const address = {
                line1: elements.inputs.line1.value.trim(),
                line2: elements.inputs.line2.value.trim(),
                city: elements.inputs.city.value.trim(),
                state: elements.inputs.state.value.trim(),
                country: elements.inputs.country.value
            };
            
            const validation = validateAddress(address);
            if (!validation.isValid) {
                alert(validation.errors.join('\\n'));
                elements.inputs.line1.focus();
                return;
            }
            
            if (isDuplicateAddress(address, index)) {
                alert('This address already exists');
                elements.inputs.line1.focus();
                return;
            }
            
            // Update address
            addresses[index] = {
                ...address,
                isDefault: addresses[index].isDefault
            };
            
            this.render();
            
            // Reset form
            Object.values(elements.inputs).forEach(input => input.value = '');
            
            // Reset button
            const addButton = Array.from(elements.list.querySelectorAll('button')).find(btn => 
                btn.textContent.includes('Edit')
            );
            if (addButton) {
                addButton.textContent = '+ Add Address';
                addButton.setAttribute('onclick', 'AddressModal.addAddress()');
                addButton.setAttribute('aria-label', 'Add address');
            }
        },
        
        removeAddress(index) {
            const wasDefault = addresses[index].isDefault;
            addresses.splice(index, 1);
            
            if (wasDefault && addresses.length > 0) {
                addresses[0].isDefault = true;
            }
            
            this.render();
        },
        
        save() {
            const selectedDefault = document.querySelector('input[name="default_address_modal"]:checked');
            if (selectedDefault) {
                addresses.forEach((address, index) => {
                    address.isDefault = index === parseInt(selectedDefault.value);
                });
            }
            
            updateAddressDisplay();
            this.close();
        }
    };
    
    // Update address display in main form
    function updateAddressDisplay() {
        const addressDisplay = document.getElementById('address_display');
        if (!addressDisplay) return;
        
        if (addresses.length === 0) {
            addressDisplay.textContent = 'No addresses added';
            addressDisplay.className = 'text-sm text-gray-600 mb-2';
        } else {
            const defaultAddress = addresses.find(a => a.isDefault) || addresses[0];
            const otherCount = addresses.length - 1;
            
            // Format address for display
            const formattedAddress = `${defaultAddress.line1}${defaultAddress.line2 ? ', ' + defaultAddress.line2 : ''}, ${defaultAddress.city}, ${defaultAddress.state}`;
            
            if (addresses.length === 1) {
                addressDisplay.textContent = formattedAddress;
                addressDisplay.className = 'text-sm text-gray-900 mb-2 font-medium';
            } else {
                addressDisplay.textContent = `${formattedAddress} (+${otherCount} more)`;
                addressDisplay.className = 'text-sm text-gray-900 mb-2 font-medium';
            }
        }
    }
    
    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeElements);
    } else {
        initializeElements();
    }
})();
