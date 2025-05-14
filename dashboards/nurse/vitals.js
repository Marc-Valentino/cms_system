// Toast notification function
function showToast(type, title, message) {
    const toastContainer = document.querySelector('.toast-container');
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    
    // Set icon based on type
    let icon = '';
    switch(type) {
        case 'success':
            icon = 'bi-check-circle-fill';
            break;
        case 'warning':
            icon = 'bi-exclamation-triangle-fill';
            break;
        case 'danger':
            icon = 'bi-x-circle-fill';
            break;
        case 'info':
        default:
            icon = 'bi-info-circle-fill';
            break;
    }
    
    // Create toast content
    toast.innerHTML = `
        <div class="toast-header">
            <i class="bi ${icon} toast-icon"></i>
            <strong class="toast-title">${title}</strong>
            <button type="button" class="toast-close" onclick="this.parentElement.parentElement.remove()">
                <i class="bi bi-x"></i>
            </button>
        </div>
        <div class="toast-body">
            ${message}
        </div>
    `;
    
    // Add to container
    toastContainer.appendChild(toast);
    
    // Show toast with animation
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 5000);
}

// Save new vitals
document.getElementById('saveVitals').addEventListener('click', function() {
    // In a real application, this would send data to the server
    // For this demo, we'll just show a toast notification and close the modal
    showToast('success', 'Vitals Saved', 'Patient vitals have been saved successfully!');
    const modal = bootstrap.Modal.getInstance(document.getElementById('addVitalsModal'));
    modal.hide();
    
    // Reset form
    document.getElementById('addVitalsForm').reset();
});

// Add toast notifications to view vitals buttons
document.querySelectorAll('.view-vitals').forEach(button => {
    button.addEventListener('click', function() {
        const vitalId = this.getAttribute('data-vital-id');
        const vital = vitalsData.find(v => v.id == vitalId);
        
        if (vital) {
            // Show info toast when viewing patient vitals
            showToast('info', 'Viewing Patient', `Displaying vitals for ${vital.patient_name}`);
            
            // Rest of your existing code for displaying vitals
            // ... existing code ...
        }
    });
});