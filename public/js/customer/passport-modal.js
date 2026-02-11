// Passport Modal Script

// Modal functionality
window.PassportModal = {
    passports: [], // Store passport data
    isLoading: false,
    loadingTimeout: null,
    
    init: function() {
        const container = document.getElementById('passportModalContainer');
        if (!container) {
            console.error('Passport modal container not found in the DOM');
            return false;
        }
        return true;
    },
    
    getSkeletonHTML: function() {
        return `
            <div id="passportModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full z-[9999] transition-opacity duration-300">
                <div class="relative min-h-screen flex items-center justify-center p-4">
                    <div class="relative bg-white rounded-2xl shadow-large max-w-2xl w-full transform transition-all duration-300 scale-95 opacity-0" id="passportModalContent">
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
                        <!-- Modal Body Skeleton with loading animation -->
                        <div class="p-6 space-y-6">
                            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                                <div class="h-5 bg-blue-200 rounded-lg animate-pulse w-32 mb-3"></div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="h-20 bg-blue-50 rounded-lg animate-pulse"></div>
                                    <div class="h-20 bg-blue-50 rounded-lg animate-pulse"></div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="h-5 bg-gray-200 rounded-lg animate-pulse w-40"></div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="h-12 bg-gray-100 rounded-xl animate-pulse"></div>
                                    <div class="h-12 bg-gray-100 rounded-xl animate-pulse"></div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Footer Skeleton -->
                        <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                            <div class="h-10 bg-white border border-gray-300 rounded-xl animate-pulse w-16"></div>
                            <div class="h-10 bg-primary-600 rounded-xl animate-pulse w-24"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    },
    
    open: function(e) {
        if (e) e.preventDefault();
        if (!this.init()) return;
        if (this.isLoading) return;
        
        this.isLoading = true;
        this.showSkeleton();
        this.loadModalContent();
    },
    
    showSkeleton: function() {
        const container = document.getElementById('passportModalContainer');
        container.innerHTML = this.getSkeletonHTML();
        
        setTimeout(() => {
            const modal = document.getElementById('passportModal');
            const content = document.getElementById('passportModalContent');
            if (modal && content) {
                modal.classList.add('opacity-100');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }
        }, 10);
        
        document.body.style.overflow = 'hidden';
        this.loadingTimeout = setTimeout(() => {
            if (this.isLoading) this.showLoadingTimeout();
        }, 5000);
    },
    
    showLoadingTimeout: function() {
        toast.warning('Loading is taking longer than expected. Please wait...');
    },
    
    loadModalContent: async function() {
        try {
            const customerId = window.customerData?.id;
            if (!customerId) throw new Error('Customer ID not found');
            
            const response = await fetch(`/customer/${customerId}/passport-modal`, {
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
            if (!result.success) throw new Error(result.message || 'Failed to load passport modal');
            
            if (this.loadingTimeout) {
                clearTimeout(this.loadingTimeout);
                this.loadingTimeout = null;
            }
            
            this.passports = result.passports || result.data?.passports || [];
            const modalHtml = result.html || result.data?.html || '';
            
            if (!modalHtml) throw new Error('No modal HTML received from server');
            
            this.replaceWithRealContent(modalHtml);
        } catch (error) {
            console.error('Error loading passport modal:', error);
            this.handleLoadError(error);
        } finally {
            this.isLoading = false;
        }
    },
    
    replaceWithRealContent: function(html) {
        const container = document.getElementById('passportModalContainer');
        if (!container) return;
        
        const modal = document.getElementById('passportModal');
        const content = document.getElementById('passportModalContent');
        
        if (modal && content) {
            content.classList.add('scale-95', 'opacity-0');
            content.classList.remove('scale-100', 'opacity-100');
            
            setTimeout(() => {
                container.innerHTML = html;
                const newModal = document.getElementById('passportModal');
                if (newModal) {
                    newModal.classList.remove('hidden');
                    newModal.classList.add('opacity-100');
                    newModal.removeAttribute('aria-hidden');
                }
                
                const newContent = document.getElementById('passportModalContent');
                if (newModal && newContent) {
                    newContent.classList.remove('scale-95', 'opacity-0');
                    newContent.classList.add('scale-100', 'opacity-100');
                    
                    const firstInput = newContent.querySelector('input, select, textarea');
                    if (firstInput) firstInput.focus();
                }
            }, 200);
        }
    },
    
    handleLoadError: function(error) {
        const container = document.getElementById('passportModalContainer');
        container.innerHTML = `
            <div id="passportModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full z-[9999] flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-large max-w-md w-full p-6 text-center">
                    <div class="mb-4">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Failed to Load</h3>
                        <p class="text-gray-600 mb-4">${error.message || 'Unable to load passport modal. Please try again.'}</p>
                    </div>
                    <div class="flex gap-3 justify-center">
                        <button onclick="PassportModal.retryLoad()" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">Retry</button>
                        <button onclick="PassportModal.close()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Close</button>
                    </div>
                </div>
            </div>
        `;
        toast.error('Failed to load passport modal: ' + error.message);
    },
    
    retryLoad: function() {
        this.isLoading = false;
        this.open();
    },
    
    close: function(e) {
        if (e) e.preventDefault();
        
        const modal = document.getElementById('passportModal');
        const content = document.getElementById('passportModalContent');
        
        if (!modal || !content) return;
        
        modal.classList.remove('opacity-100');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            const container = document.getElementById('passportModalContainer');
            if (container) container.innerHTML = '';
            document.body.style.overflow = 'auto';
            
            this.isLoading = false;
            if (this.loadingTimeout) {
                clearTimeout(this.loadingTimeout);
                this.loadingTimeout = null;
            }
        }, 300);
    }
};

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && window.PassportModal && typeof window.PassportModal.close === 'function') {
        PassportModal.close(e);
    }
});
