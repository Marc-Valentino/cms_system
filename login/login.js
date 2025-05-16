document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const roleOptions = document.querySelectorAll('.role-option');
    let selectedRole = 'doctor'; // Default role
    
    // Add event listeners to role options
    roleOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all options
            roleOptions.forEach(opt => opt.classList.remove('active'));
            
            // Add active class to selected option
            this.classList.add('active');
            
            // Update selected role
            selectedRole = this.getAttribute('data-role');
            console.log('Selected role:', selectedRole);
        });
    });
    
    // Add submit event listener to form
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Form submitted');
            
            // Show loading state
            const loginBtn = document.querySelector('.login-btn');
            const originalBtnText = loginBtn.innerHTML;
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing In...';
            loginBtn.disabled = true;
            
            // Get form data
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            // Create FormData object
            const formData = new FormData();
            formData.append('email', email);
            formData.append('password', password);
            formData.append('role', selectedRole);
            
            // Log form data for debugging
            console.log('Email:', email);
            console.log('Role:', selectedRole);
            
            // Submit form
            fetch('login_process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Login response:', data);
                
                if (data.success) {
                    // Show success toast
                    showToast('Login successful! Redirecting...', 'success');
                    
                    // Redirect to dashboard after a short delay
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                } else {
                    // Show error toast
                    showToast(data.message || 'Login failed. Please try again.', 'error');
                    
                    // Reset button
                    loginBtn.innerHTML = originalBtnText;
                    loginBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Show error toast
                showToast('An error occurred: ' + error.message, 'error');
                
                // Reset button
                loginBtn.innerHTML = originalBtnText;
                loginBtn.disabled = false;
            });
        });
    }
    
    // Toast notification function
    window.showToast = function(message, type = 'info') {
        // Remove existing toasts
        const existingToasts = document.querySelectorAll('.toast');
        existingToasts.forEach(toast => toast.remove());
        
        // Create toast container if it doesn't exist
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container';
            document.body.appendChild(toastContainer);
        }
        
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        // Add icon based on type
        let icon = '';
        switch (type) {
            case 'success':
                icon = '<i class="fas fa-check-circle"></i>';
                break;
            case 'error':
                icon = '<i class="fas fa-exclamation-circle"></i>';
                break;
            case 'warning':
                icon = '<i class="fas fa-exclamation-triangle"></i>';
                break;
            default:
                icon = '<i class="fas fa-info-circle"></i>';
                break;
        }
        
        toast.innerHTML = `
            <div class="toast-content">
                <div class="toast-icon">${icon}</div>
                <div class="toast-message">${message}</div>
                <button class="toast-close"><i class="fas fa-times"></i></button>
            </div>
        `;
        
        // Add toast to container
        toastContainer.appendChild(toast);
        
        // Add close button functionality
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', function() {
            toast.classList.add('toast-hiding');
            setTimeout(() => {
                toast.remove();
            }, 300);
        });
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.classList.add('toast-hiding');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 5000);
        
        // Animate in
        setTimeout(() => {
            toast.classList.add('toast-visible');
        }, 10);
    }
});