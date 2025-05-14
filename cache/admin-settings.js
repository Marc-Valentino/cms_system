/**
 * Admin Settings JavaScript
 * Handles interactive functionality for the admin settings interface
 */

document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const settingsForm = document.getElementById('settingsForm');
    const cacheSettingsForm = document.getElementById('cacheSettingsForm');
    const systemSettingsForm = document.getElementById('systemSettingsForm');
    const clearCacheBtn = document.getElementById('clearCacheBtn');
    const settingsToast = document.getElementById('settingsToast');
    
    // Bootstrap Toast initialization
    let toastInstance;
    if (typeof bootstrap !== 'undefined' && settingsToast) {
        toastInstance = new bootstrap.Toast(settingsToast);
    }
    
    // Show success toast message
    function showToast(message, type = 'success') {
        if (settingsToast) {
            const toastBody = settingsToast.querySelector('.toast-body');
            const toastHeader = settingsToast.querySelector('.toast-header');
            
            if (toastBody) toastBody.textContent = message;
            
            // Set toast color based on type
            settingsToast.className = 'toast';
            if (type === 'success') {
                settingsToast.classList.add('bg-success', 'text-white');
                if (toastHeader) toastHeader.classList.add('bg-success', 'text-white');
            } else if (type === 'danger') {
                settingsToast.classList.add('bg-danger', 'text-white');
                if (toastHeader) toastHeader.classList.add('bg-danger', 'text-white');
            }
            
            if (toastInstance) {
                toastInstance.show();
            }
        }
    }
    
    // Handle cache settings form submission
    if (cacheSettingsForm) {
        cacheSettingsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(cacheSettingsForm);
            
            // Send form data to server
            fetch('update_cache_settings.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Cache settings updated successfully!');
                } else {
                    showToast('Failed to update cache settings.', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while updating settings.', 'danger');
            });
        });
    }
    
    // Handle system settings form submission
    if (systemSettingsForm) {
        systemSettingsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(systemSettingsForm);
            
            // Send form data to server
            fetch('update_system_settings.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('System settings updated successfully!');
                } else {
                    showToast('Failed to update system settings.', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while updating settings.', 'danger');
            });
        });
    }
    
    // Handle clear cache button click
    if (clearCacheBtn) {
        clearCacheBtn.addEventListener('click', function() {
            // Show loading state
            const originalText = clearCacheBtn.innerHTML;
            clearCacheBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Clearing...';
            clearCacheBtn.disabled = true;
            
            // Send request to clear cache
            fetch('clear_cache.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ action: 'clear_cache' })
            })
            .then(response => response.json())
            .then(data => {
                // Reset button state
                clearCacheBtn.innerHTML = originalText;
                clearCacheBtn.disabled = false;
                
                if (data.success) {
                    showToast('Cache cleared successfully!');
                } else {
                    showToast('Failed to clear cache.', 'danger');
                }
            })
            .catch(error => {
                // Reset button state
                clearCacheBtn.innerHTML = originalText;
                clearCacheBtn.disabled = false;
                
                console.error('Error:', error);
                showToast('An error occurred while clearing cache.', 'danger');
            });
        });
    }
    
    // Toggle settings sections
    const settingsTabs = document.querySelectorAll('.settings-tab');
    const settingsSections = document.querySelectorAll('.settings-section');
    
    if (settingsTabs.length > 0) {
        settingsTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const targetSection = this.getAttribute('data-section');
                
                // Remove active class from all tabs and hide all sections
                settingsTabs.forEach(t => t.classList.remove('active'));
                settingsSections.forEach(s => s.classList.remove('active'));
                
                // Add active class to clicked tab and show target section
                this.classList.add('active');
                document.getElementById(targetSection).classList.add('active');
            });
        });
    }
    
    // Initialize form validation
    function validateForm(form) {
        let isValid = true;
        const inputs = form.querySelectorAll('input[required], select[required]');
        
        inputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });
        
        return isValid;
    }
    
    // Add event listeners to remove validation styling on input
    const formInputs = document.querySelectorAll('input, select, textarea');
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
});