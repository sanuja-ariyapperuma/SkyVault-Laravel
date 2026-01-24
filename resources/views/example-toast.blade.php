<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toast Component Example</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen p-8">
    
    <!-- Toast Component -->
    <x-toast />
    
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Toast Component Examples</h1>
        
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Global Toast Methods</h2>
            <p class="text-gray-600 mb-4">Use these methods anywhere in your JavaScript:</p>
            
            <div class="space-y-4">
                <button onclick="toast.success('Operation completed successfully!')" 
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                    Show Success Toast
                </button>
                
                <button onclick="toast.warning('Please review your input before proceeding.')" 
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition-colors">
                    Show Warning Toast
                </button>
                
                <button onclick="toast.error('An error occurred while processing your request.')" 
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                    Show Error Toast
                </button>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Alpine Component Methods</h2>
            <p class="text-gray-600 mb-4">Direct component access (useful in Alpine components):</p>
            
            <div x-data="toastExample" class="space-y-4">
                <button @click="showSuccess" 
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                    Alpine Success Toast
                </button>
                
                <button @click="showWarning" 
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition-colors">
                    Alpine Warning Toast
                </button>
                
                <button @click="showError" 
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                    Alpine Error Toast
                </button>
            </div>
        </div>
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
            <h3 class="font-semibold text-blue-800 mb-2">Usage Notes:</h3>
            <ul class="text-blue-700 space-y-1 text-sm">
                <li>• Toasts auto-hide after 3 seconds (customizable)</li>
                <li>• Click the X button to dismiss immediately</li>
                <li>• Multiple toasts stack vertically</li>
                <li>• Smooth fade in/out animations</li>
            </ul>
        </div>
    </div>

    <script>
        // Alpine component example
        document.addEventListener('alpine:init', () => {
            Alpine.data('toastExample', () => ({
                showSuccess() {
                    this.$dispatch('toast-success', { message: 'Success from Alpine component!' });
                    toast.success('Success from Alpine component!');
                },
                showWarning() {
                    toast.warning('Warning from Alpine component!');
                },
                showError() {
                    toast.error('Error from Alpine component!');
                }
            }));
        });
    </script>
</body>
</html>
