<!-- Phone Numbers Modal -->
<div id="phoneModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50" role="dialog" aria-labelledby="phoneModalTitle" aria-hidden="true">
    <div class="relative top-20 mx-auto p-4 border w-11/12 md:w-1/2 lg:w-2/5 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <header class="flex justify-between items-center mb-4">
                <h3 id="phoneModalTitle" class="text-lg font-medium text-gray-900">Manage Phone Numbers</h3>
                <button onclick="PhoneModal.close()" class="text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500" aria-label="Close modal">&times;</button>
            </header>
            
            <section class="border-b pb-4 mb-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Add New Phone Number</h4>
                <div class="space-y-3">
                    <div class="flex gap-2">
                        <input type="tel" id="new_phone" placeholder="Enter phone number" maxlength="10" pattern="[0-9]{10}" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" aria-describedby="phoneHelp">
                        <button onclick="PhoneModal.addPhone()" class="text-blue-600 hover:text-blue-800 hover:underline focus:outline-none focus:text-blue-800 focus:ring-2 focus:ring-blue-500" aria-label="Add phone number">+ Add</button>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="new_phone_whatsapp" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500 mr-2">
                        <label for="new_phone_whatsapp" class="text-sm text-gray-700">WhatsApp available</label>
                    </div>
                    <p id="phoneHelp" class="text-xs text-gray-500 mt-1">Enter exactly 10 digits (numbers only)</p>
                </div>
            </section>
            
            <section class="mb-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Existing Phone Numbers</h4>
                <div id="phone_list" class="space-y-3 max-h-60 overflow-y-auto" role="list" aria-label="Phone numbers list">
                    <!-- Phone numbers will be added here dynamically -->
                </div>
            </section>
        </div>
    </div>
</div>
