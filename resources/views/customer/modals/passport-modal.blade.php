<!-- Passport Modal -->
<div id="passportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50" role="dialog" aria-labelledby="passportModalTitle" aria-hidden="true">
    <div class="relative top-20 mx-auto p-4 border w-11/12 md:w-1/2 lg:w-2/5 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <header class="flex justify-between items-center mb-4">
                <h3 id="passportModalTitle" class="text-lg font-medium text-gray-900">Passports</h3>
                <button onclick="PassportModal.close()" class="text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500" aria-label="Close modal">&times;</button>
            </header>
            
            <section class="mb-4">
                <div class="text-gray-600">
                    Passport information will be displayed here.
                </div>
            </section>
        </div>
    </div>
</div>
