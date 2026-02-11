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
    let isLoading = false;
    let loadingTimeout = null;
    
    // Constants
    const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const API_ENDPOINT = '/customer';
    const MESSAGES = {
        LOAD_ERROR: 'Failed to load email modal. Please try again.',
        EMPTY_EMAIL: 'Please enter an email address',
        INVALID_EMAIL: 'Please enter a valid email address',
        DUPLICATE_EMAIL: 'This email address already exists'
    };
    
    // Get skeleton loading HTML
    function getSkeletonHTML() {
        return `
            <!-- Email Addresses Modal Skeleton -->
            <div id="emailModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full z-[9999] transition-opacity duration-300 flex items-center justify-center p-4" role="dialog" aria-labelledby="emailModalTitle" aria-hidden="true">
                <div class="relative min-h-screen p-4">
                    <div class="relative bg-white rounded-2xl shadow-large max-w-2xl w-full transform transition-all duration-300 scale-95 opacity-0" id="emailModalContent">
                        <!-- Modal Header Skeleton -->
                        <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white rounded-t-2xl">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-colored-blue animate-pulse">
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
                            <!-- Current Default Email Skeleton -->
                            <div class="bg-gradient-to-r from-primary-50 to-primary-100 rounded-xl p-4 border border-primary-200">
                                <div class="h-5 bg-primary-200 rounded-lg animate-pulse w-40 mb-3"></div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="p-2 bg-primary-500 rounded-lg animate-pulse">
                                            <div class="w-5 h-5 bg-white/30 rounded"></div>
                                        </div>
                                        <div>
                                            <div class="h-5 bg-primary-300 rounded animate-pulse w-32 mb-2"></div>
                                            <div class="h-4 bg-primary-200 rounded animate-pulse w-24"></div>
                                        </div>
                                    </div>
                                    <div class="h-6 bg-primary-200 rounded-full animate-pulse w-16"></div>
                                </div>
                            </div>

                            <!-- Add New Email Address Skeleton -->
                            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                                <div class="h-5 bg-blue-200 rounded-lg animate-pulse w-40 mb-4"></div>
                                
                                <div class="space-y-4">
                                    <div class="flex gap-3">
                                        <div class="flex-1 relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <div class="h-5 w-5 bg-gray-300 rounded animate-pulse"></div>
                                            </div>
                                            <div class="w-full h-12 bg-gray-100 rounded-xl animate-pulse pl-10"></div>
                                        </div>
                                        <div class="h-12 bg-blue-100 rounded-xl animate-pulse w-20"></div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-3">
                                        <div class="h-4 w-4 bg-gray-200 rounded animate-pulse"></div>
                                        <div class="h-4 bg-gray-100 rounded animate-pulse w-24"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Existing Email Addresses Skeleton -->
                            <div class="space-y-4">
                                <div class="h-5 bg-gray-200 rounded-lg animate-pulse w-48"></div>
                                
                                <div class="space-y-3">
                                    <!-- Skeleton email item 1 -->
                                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 animate-pulse">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3 flex-1">
                                                <div class="p-2 bg-blue-100 rounded-lg">
                                                    <div class="w-5 h-5 bg-blue-200 rounded animate-pulse"></div>
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
                                    
                                    <!-- Skeleton email item 2 -->
                                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 animate-pulse">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3 flex-1">
                                                <div class="p-2 bg-primary-100 rounded-lg">
                                                    <div class="w-5 h-5 bg-primary-200 rounded animate-pulse"></div>
                                                </div>
                                                <div class="flex-1">
                                                    <div class="h-5 bg-gray-200 rounded animate-pulse w-36 mb-2"></div>
                                                    <div class="h-4 bg-gray-100 rounded animate-pulse w-16"></div>
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
    }
    
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
        
        emailDiv.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 flex-1">
                    <div class="p-2 ${email.isDefault ? 'bg-primary-100' : 'bg-blue-100'} rounded-lg">
                        <svg class="w-5 h-5 ${email.isDefault ? 'text-primary-600' : 'text-blue-600'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">${email.email}</p>
                        <div class="flex items-center space-x-2 text-sm mt-1">
                            ${email.isDefault ? `
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Primary
                                </span>
                            ` : `
                                <button onclick="EmailModal.setDefaultEmail(${index}, event)" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 hover:bg-primary-100 hover:text-primary-800 transition-colors">
                                    Set as Primary
                                </button>
                            `}
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Verified
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
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
        toast.error(message);
        elements.input?.focus();
    }
    
    // Show success message
    function showSuccess(message) {
        toast.success(message);
    }
    
    // Show loading message for CRUD operations
    function showLoadingMessage(message) {
        toast.info(message);
    }
    
    // Hide loading message
    function hideLoadingMessage() {
        // Toast messages auto-hide, so no action needed
    }
    
    // Public API
    window.EmailModal = {
        async open() {
            // Prevent multiple simultaneous loads
            if (isLoading) {
                console.log('Email modal is already loading...');
                return;
            }
            
            isLoading = true;
            
            // Show skeleton loading immediately
            this.showSkeleton();
            
            // Load modal content via AJAX
            this.loadModalContent();
        },
        
        showSkeleton() {
            const container = document.getElementById('emailModalContainer');
            if (!container) {
                console.error('Email modal container not found in the DOM');
                isLoading = false;
                return;
            }
            
            container.innerHTML = getSkeletonHTML();
            
            // Animate skeleton in
            setTimeout(() => {
                const modal = document.getElementById('emailModal');
                const content = document.getElementById('emailModalContent');
                
                if (modal && content) {
                    modal.classList.add('opacity-100');
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }
            }, 10);
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
            
            // Set timeout for loading
            loadingTimeout = setTimeout(() => {
                if (isLoading) {
                    this.showLoadingTimeout();
                }
            }, 5000);
        },
        
        showLoadingTimeout() {
            toast.warning('Loading is taking longer than expected. Please wait...');
        },
        
        async loadModalContent() {
            try {
                const customerId = window.customerData?.id;
                if (!customerId) throw new Error('Customer ID not found');
                
                // Make AJAX call to get modal content
                const response = await fetch(`/customer/${customerId}/email-modal`, {
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
                    throw new Error(result.message || 'Failed to load email modal');
                }
                
                // Clear timeout
                if (loadingTimeout) {
                    clearTimeout(loadingTimeout);
                    loadingTimeout = null;
                }
                
                // Update emails data from server - check both possible structures
                emails = result.emails || result.data?.emails || [];
                
                // Get HTML from response - check both possible structures
                const modalHtml = result.html || result.data?.html || '';
                
                if (!modalHtml) {
                    throw new Error('No modal HTML received from server');
                }
                
                // Replace skeleton with actual content
                this.replaceWithRealContent(modalHtml);
                
            } catch (error) {
                console.error('Error loading email modal:', error);
                this.handleLoadError(error);
            } finally {
                isLoading = false;
            }
        },
        
        replaceWithRealContent(html) {
            const container = document.getElementById('emailModalContainer');
            
            if (!container) {
                console.error('Email modal container not found!');
                return;
            }
            
            // Fade out skeleton
            const modal = document.getElementById('emailModal');
            const content = document.getElementById('emailModalContent');
            
            if (modal && content) {
                content.classList.add('scale-95', 'opacity-0');
                content.classList.remove('scale-100', 'opacity-100');
                
                setTimeout(() => {
                    // Replace content
                    container.innerHTML = html;
                    
                    // Remove hidden class from the new modal and ensure it's visible
                    const newModal = document.getElementById('emailModal');
                    if (newModal) {
                        newModal.classList.remove('hidden');
                        newModal.classList.add('opacity-100');
                        // Remove aria-hidden when modal is visible to fix accessibility warning
                        newModal.removeAttribute('aria-hidden');
                    }
                    
                    // Fade in real content
                    const newContent = document.getElementById('emailModalContent');
                    
                    if (newModal && newContent) {
                        newContent.classList.remove('scale-95', 'opacity-0');
                        newContent.classList.add('scale-100', 'opacity-100');
                        
                        // Initialize elements after content is loaded
                        initializeElements();
                        
                        // Render the email data in the list
                        this.render();
                        
                        // Focus on input
                        const emailInput = document.getElementById('new_email');
                        if (emailInput) {
                            emailInput.focus();
                        }
                    } else {
                        console.error('New modal elements not found after replacement!');
                    }
                }, 200);
            } else {
                console.error('Skeleton modal elements not found!');
            }
        },
        
        handleLoadError(error) {
            const container = document.getElementById('emailModalContainer');
            container.innerHTML = `
                <div id="emailModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full z-[9999] flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl shadow-large max-w-md w-full p-6 text-center">
                        <div class="mb-4">
                            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Failed to Load</h3>
                            <p class="text-gray-600 mb-4">${error.message || 'Unable to load email modal. Please try again.'}</p>
                        </div>
                        <div class="flex gap-3 justify-center">
                            <button onclick="EmailModal.retryLoad()" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                                Retry
                            </button>
                            <button onclick="EmailModal.close()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            toast.error('Failed to load email modal: ' + error.message);
        },
        
        retryLoad() {
            isLoading = false;
            this.open();
        },
        
        close() {
            const modal = document.getElementById('emailModal');
            const content = document.getElementById('emailModalContent');
            
            if (!modal || !content) {
                console.error('Email modal not found in the DOM');
                return;
            }
            
            modal.classList.remove('opacity-100');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                // Clear the modal container
                const container = document.getElementById('emailModalContainer');
                if (container) {
                    container.innerHTML = '';
                }
                document.body.style.overflow = 'auto';
                
                // Reset loading state
                isLoading = false;
                if (loadingTimeout) {
                    clearTimeout(loadingTimeout);
                    loadingTimeout = null;
                }
                
                // Reset button to Add state when modal is closed
                this.resetAddButton();
            }, 300);
            
            modal.setAttribute('aria-hidden', 'true');
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
            
            // Update the email display in the main view
            if (typeof window.updateEmailDisplay === 'function') {
                window.updateEmailDisplay();
            }
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
                
                // Update the email display in the main view
                if (typeof window.updateEmailDisplay === 'function') {
                    window.updateEmailDisplay();
                }
                
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
                
                // Update the email display in the main view
                if (typeof window.updateEmailDisplay === 'function') {
                    window.updateEmailDisplay();
                }
                
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
                
                // Update the email display in the main view
                if (typeof window.updateEmailDisplay === 'function') {
                    window.updateEmailDisplay();
                }
                
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
                
                // Update the email display in the main view
                if (typeof window.updateEmailDisplay === 'function') {
                    window.updateEmailDisplay();
                }
                
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
            
            emailDisplay.textContent = defaultEmail.email;
            emailDisplay.className = 'text-sm text-gray-900 mb-2 font-medium';
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
    
    // Initialize emails from global data
    initializeEmails();
    
    // Also try to initialize after a short delay in case data loads later
    setTimeout(initializeEmails, 100);
})();
