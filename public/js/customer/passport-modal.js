// Modal functionality
window.PassportModal = {
    open: function() {
        const modal = document.getElementById('passportModal');
        const content = document.getElementById('passportModalContent');
        
        if (!modal || !content) {
            console.error('Passport modal not found in the DOM');
            return;
        }
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    },
    
    close: function() {
        const modal = document.getElementById('passportModal');
        const content = document.getElementById('passportModalContent');
        
        if (!modal || !content) {
            console.error('Passport modal not found in the DOM');
            return;
        }
        
        modal.classList.remove('opacity-100');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }
};

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        PassportModal.close();
    }
});

// Close modal on background click
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('passportModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                PassportModal.close();
            }
        });
    }
});
