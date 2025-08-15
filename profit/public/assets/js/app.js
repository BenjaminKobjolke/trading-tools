document.addEventListener('DOMContentLoaded', function() {
    const periodRadios = document.querySelectorAll('input[name="period"]');
    const customDaysGroup = document.getElementById('custom-days-group');
    const customDaysInput = document.getElementById('custom_days');
    
    function toggleCustomDays() {
        const selectedPeriod = document.querySelector('input[name="period"]:checked');
        
        if (selectedPeriod && selectedPeriod.value === 'custom') {
            customDaysGroup.style.display = 'block';
            customDaysInput.setAttribute('required', 'required');
            
            setTimeout(() => {
                customDaysInput.focus();
            }, 100);
        } else {
            customDaysGroup.style.display = 'none';
            customDaysInput.removeAttribute('required');
            customDaysInput.value = '';
        }
    }
    
    periodRadios.forEach(radio => {
        radio.addEventListener('change', toggleCustomDays);
    });
    
    toggleCustomDays();
    
    const amountInput = document.getElementById('amount');
    const rateInput = document.getElementById('rate');
    
    function formatInputOnBlur(input, isGerman) {
        input.addEventListener('blur', function() {
            let value = this.value.trim();
            if (value === '') return;
            
            if (isGerman) {
                value = value.replace(/\./g, '').replace(',', '.');
            } else {
                value = value.replace(/,/g, '');
            }
            
            const numValue = parseFloat(value);
            if (!isNaN(numValue)) {
                if (this === amountInput) {
                    this.value = isGerman 
                        ? numValue.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.')
                        : numValue.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                }
            }
        });
    }
    
    const currentLang = document.documentElement.lang || 'de';
    const isGerman = currentLang === 'de';
    
    if (amountInput) {
        formatInputOnBlur(amountInput, isGerman);
    }
    
    const resultCards = document.querySelectorAll('.result-card');
    resultCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
    
    const form = document.querySelector('form');
    const submitButton = form ? form.querySelector('button[type="submit"]') : null;
    
    if (form && submitButton) {
        form.addEventListener('submit', function(e) {
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                ${submitButton.textContent}
            `;
            
            // Update form action to include #results fragment
            form.action = '#results';
        });
    }
    
    // Handle URL fragment on page load
    if (window.location.hash === '#results') {
        setTimeout(() => {
            const resultsSection = document.getElementById('results');
            if (resultsSection) {
                resultsSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }, 100);
    }
    
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 500);
        }, 5000);
    });
    
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('input-focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('input-focused');
        });
    });
    
    if (customDaysInput) {
        customDaysInput.addEventListener('input', function() {
            const value = parseInt(this.value);
            if (value > 365) {
                this.value = 365;
            } else if (value < 1 && this.value !== '') {
                this.value = 1;
            }
        });
    }
    
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});