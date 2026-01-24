import './bootstrap';

import Alpine from 'alpinejs';

// Toast component
Alpine.data('toast', () => ({
    show: false,
    message: '',
    type: 'success',
    timeout: null,
    
    showNotification(message, type = 'success', duration = 3000) {
        this.message = message;
        this.type = type;
        this.show = true;
        
        // Clear any existing timeout
        if (this.timeout) {
            clearTimeout(this.timeout);
        }
        
        // Auto-hide after duration
        this.timeout = setTimeout(() => {
            this.show = false;
        }, duration);
    },
    
    hide() {
        this.show = false;
        if (this.timeout) {
            clearTimeout(this.timeout);
        }
    },
    
    // Convenience methods
    success(message, duration = 3000) {
        this.showNotification(message, 'success', duration);
    },
    
    warning(message, duration = 3000) {
        this.showNotification(message, 'warning', duration);
    },
    
    error(message, duration = 3000) {
        this.showNotification(message, 'error', duration);
    }
}));

// Global toast helper
window.toast = {
    success: (message, duration = 3000) => {
        window.Alpine.$data(document.querySelector('[x-data~=\"toast\"]')).success(message, duration);
    },
    warning: (message, duration = 3000) => {
        window.Alpine.$data(document.querySelector('[x-data~=\"toast\"]')).warning(message, duration);
    },
    error: (message, duration = 3000) => {
        window.Alpine.$data(document.querySelector('[x-data~=\"toast\"]')).error(message, duration);
    }
};

window.Alpine = Alpine;

Alpine.start();
