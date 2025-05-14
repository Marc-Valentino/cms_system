document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading indicator
            const loginBtn = document.querySelector('.login-btn');
            const originalBtnText = loginBtn.innerHTML;
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing In...';
            loginBtn.disabled = true;
            
            const formData = new FormData(loginForm);
            
            // Debug
            console.log('Submitting form with email:', formData.get('email'));
            
            fetch('login_process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json().catch(error => {
                    console.error('Error parsing JSON:', error);
                    throw new Error('Invalid JSON response');
                });
            })
            .then(data => {
                console.log('Login response:', data);
                
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    // Show error message
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message';
                    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${data.message}`;
                    
                    // Remove any existing error message
                    const existingError = document.querySelector('.error-message');
                    if (existingError) {
                        existingError.remove();
                    }
                    
                    // Insert error before the form
                    loginForm.parentNode.insertBefore(errorDiv, loginForm);
                }
                
                // Reset button
                loginBtn.innerHTML = originalBtnText;
                loginBtn.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Show generic error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> An error occurred. Please try again.';
                
                // Remove any existing error message
                const existingError = document.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }
                
                // Insert error before the form
                loginForm.parentNode.insertBefore(errorDiv, loginForm);
                
                // Reset button
                loginBtn.innerHTML = originalBtnText;
                loginBtn.disabled = false;
            });
        });
    }
});