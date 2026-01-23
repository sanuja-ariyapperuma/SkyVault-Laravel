window.VisaModal = {
    async open() {
        if (!window.customerData || !window.customerData.id) {
            alert('Customer data not available. Please refresh the page and try again.');
            return;
        }
        
        const customerId = window.customerData.id;
        
        try {
            // Show loading state
            this.showLoadingModal();
            
            // Fetch modal content from server
            const response = await fetch(`/customer/${customerId}/visa-modal`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });
            
            if (!response.ok) {
                throw new Error(`Failed to load visa modal: ${response.status} ${response.statusText}`);
            }
            
            const data = await response.json();
            
            // Replace loading modal with actual content
            this.replaceModalContent(data.data.html);
            
        } catch (error) {
            this.showErrorModal(error.message);
        }
    },
    
    showLoadingModal() {
        const loadingHtml = `
            <div id="visaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" role="dialog" aria-labelledby="visaModalTitle" aria-hidden="true">
                <div class="relative top-20 mx-auto p-4 border w-11/12 md:w-1/2 lg:w-2/5 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <header class="flex justify-between items-center mb-4">
                            <h3 id="visaModalTitle" class="text-lg font-medium text-gray-900">VISAs</h3>
                            <button onclick="VisaModal.close()" class="text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500" aria-label="Close modal">&times;</button>
                        </header>
                        <section class="mb-4">
                            <div class="flex justify-center items-center py-8">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                <span class="ml-3 text-gray-600">Loading...</span>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        `;
        
        // Remove any existing modal
        this.removeModal();
        
        // Add loading modal to body
        document.body.insertAdjacentHTML('beforeend', loadingHtml);
        document.body.classList.add('overflow-hidden');
    },
    
    replaceModalContent(html) {
        const existingModal = document.getElementById('visaModal');
        if (existingModal) {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;
            const newModal = tempDiv.firstElementChild;
            
            // Remove the 'hidden' class from the new modal
            if (newModal && newModal.classList.contains('hidden')) {
                newModal.classList.remove('hidden');
            }
            
            existingModal.replaceWith(newModal);
        }
    },
    
    showErrorModal(message = 'Failed to load visa information. Please try again.') {
        const existingModal = document.getElementById('visaModal');
        if (existingModal) {
            const contentSection = existingModal.querySelector('section');
            if (contentSection) {
                contentSection.innerHTML = `
                    <div class="text-red-600 text-center py-4">
                        ${message}
                    </div>
                `;
            }
        }
    },
    
    close() {
        this.removeModal();
        document.body.classList.remove('overflow-hidden');
    },
    
    removeModal() {
        const modal = document.getElementById('visaModal');
        if (modal) {
            modal.remove();
        }
    }
};
