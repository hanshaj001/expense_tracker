// ==========================================================================
// GLOBAL APPLICATION JAVASCRIPT
// Handles interactive UI elements, dismissals, and button animations
// ==========================================================================

document.addEventListener('DOMContentLoaded', () => {
    // 1. Auto-dismiss top flash alerts after 5 seconds
    const flashAlerts = document.querySelectorAll('.alert-dismissible');
    if (flashAlerts.length > 0) {
        setTimeout(() => {
            flashAlerts.forEach(alert => {
                alert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-8px)';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 400);
            });
        }, 5000);
    }

    // 2. Ripple animation effect on primary buttons
    const buttons = document.querySelectorAll('.btn-gradient');
    buttons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            let ripple = document.createElement('span');
            ripple.classList.add('ripple');
            this.appendChild(ripple);
            
            let rect = e.target.getBoundingClientRect();
            let x = e.clientX - rect.left;
            let y = e.clientY - rect.top;
            
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // 3. Format currency inputs on blur
    const currencyInputs = document.querySelectorAll('input[type="number"][step="0.01"]');
    currencyInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && !isNaN(this.value)) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    });
});
