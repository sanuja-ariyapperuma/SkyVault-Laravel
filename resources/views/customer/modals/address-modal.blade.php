<!-- Addresses Modal -->
<div id="addressModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50" role="dialog" aria-labelledby="addressModalTitle" aria-hidden="true">
    <div class="relative top-20 mx-auto p-4 border w-11/12 md:w-2/3 lg:w-3/5 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <header class="flex justify-between items-center mb-4">
                <h3 id="addressModalTitle" class="text-lg font-medium text-gray-900">Manage Addresses</h3>
                <button onclick="AddressModal.close()" class="text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500" aria-label="Close modal">&times;</button>
            </header>
            
            <section class="border-b pb-4 mb-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Add New Address</h4>
                <div class="space-y-2">
                    <div class="space-y-1">
                        <input type="text" id="new_address_line1" placeholder="Address Line 1" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" aria-describedby="addressHelp">
                        <input type="text" id="new_address_line2" placeholder="Address Line 2 (Optional)" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" id="new_city" placeholder="City" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <input type="text" id="new_state" placeholder="State/Province" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <input type="text" id="new_postal_code" placeholder="Postal Code (Optional)" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <select id="new_country" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" aria-describedby="addressHelp">
                        <option value="">Select Country...</option>
                        <option value="us">United States</option>
                        <option value="uk">United Kingdom</option>
                        <option value="ca">Canada</option>
                        <option value="au">Australia</option>
                        <option value="other">Other</option>
                    </select>
                    <button onclick="AddressModal.addAddress()" class="text-blue-600 hover:text-blue-800 hover:underline focus:outline-none focus:text-blue-800 focus:ring-2 focus:ring-blue-500" aria-label="Add address">+ Add Address</button>
                </div>
                <p id="addressHelp" class="text-xs text-gray-500 mt-1">Address Line 1, City, and Country are required. State and Postal Code are optional.</p>
            </section>
            
            <section class="mb-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Existing Addresses</h4>
                <div id="address_list" class="space-y-3 max-h-60 overflow-y-auto" role="list" aria-label="Addresses list">
                    <!-- Addresses will be added here dynamically -->
                </div>
            </section>
        </div>
    </div>
</div>
