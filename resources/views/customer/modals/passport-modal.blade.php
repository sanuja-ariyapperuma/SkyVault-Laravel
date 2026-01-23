<!-- Passport Modal -->
<div id="passportModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm hidden overflow-y-auto h-full w-full z-[9999] transition-opacity duration-300" role="dialog" aria-labelledby="passportModalTitle" aria-hidden="true">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-large max-w-2xl w-full transform transition-all duration-300 scale-95 opacity-0" id="passportModalContent">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white rounded-t-2xl">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-colored-blue">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 id="passportModalTitle" class="text-xl font-bold text-gray-900">Passport Information</h3>
                        <p class="text-sm text-gray-500">Manage customer passport details</p>
                    </div>
                </div>
                <button onclick="PassportModal.close()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-200" aria-label="Close modal">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-6">
                <!-- Passport Overview -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-semibold text-blue-900">Current Passport</h4>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">
                            Valid
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-blue-600 font-medium">Passport Number</p>
                            <p class="text-gray-900 font-semibold">A12345678</p>
                        </div>
                        <div>
                            <p class="text-sm text-blue-600 font-medium">Issue Date</p>
                            <p class="text-gray-900 font-semibold">Jan 15, 2020</p>
                        </div>
                        <div>
                            <p class="text-sm text-blue-600 font-medium">Expiry Date</p>
                            <p class="text-gray-900 font-semibold">Jan 15, 2030</p>
                        </div>
                        <div>
                            <p class="text-sm text-blue-600 font-medium">Place of Issue</p>
                            <p class="text-gray-900 font-semibold">New York, USA</p>
                        </div>
                    </div>
                </div>

                <!-- Passport Details Form -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-900">Update Passport Details</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Passport Number</label>
                            <input type="text" class="input-professional" placeholder="Enter passport number">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Country of Issue</label>
                            <select class="input-professional">
                                <option>United States</option>
                                <option>United Kingdom</option>
                                <option>Canada</option>
                                <option>Australia</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Issue Date</label>
                            <input type="date" class="input-professional">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                            <input type="date" class="input-professional">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Place of Issue</label>
                        <input type="text" class="input-professional" placeholder="City, Country">
                    </div>
                </div>

                <!-- Document Upload -->
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 transition-colors">
                    <div class="flex flex-col items-center">
                        <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-gray-600 mb-2">Upload passport scan</p>
                        <p class="text-sm text-gray-500">PDF, JPG or PNG (max. 5MB)</p>
                        <button type="button" class="mt-3 px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors font-medium">
                            Choose File
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                <button onclick="PassportModal.close()" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors font-medium">
                    Cancel
                </button>
                <x-primary-button class="px-6 py-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Changes
                </x-primary-button>
            </div>
        </div>
    </div>
</div>
