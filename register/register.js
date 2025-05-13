function validateForm(event) {
    event.preventDefault();
    let isValid = true;
    
    // Reset error messages
    const errorElements = document.querySelectorAll('.error-message');
    errorElements.forEach(element => {
        element.style.display = 'none';
        element.textContent = '';
    });
    
    // Validate first name
    const firstName = document.getElementById('firstName').value.trim();
    if (firstName === '') {
        document.getElementById('firstNameError').textContent = 'First name is required';
        document.getElementById('firstNameError').style.display = 'block';
        isValid = false;
    }
    
    // Validate last name
    const lastName = document.getElementById('lastName').value.trim();
    if (lastName === '') {
        document.getElementById('lastNameError').textContent = 'Last name is required';
        document.getElementById('lastNameError').style.display = 'block';
        isValid = false;
    }
    
    // Validate email
    const email = document.getElementById('email').value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email === '') {
        document.getElementById('emailError').textContent = 'Email address is required';
        document.getElementById('emailError').style.display = 'block';
        isValid = false;
    } else if (!emailRegex.test(email)) {
        document.getElementById('emailError').textContent = 'Please enter a valid email address';
        document.getElementById('emailError').style.display = 'block';
        isValid = false;
    }
    
    // Validate phone
    const phone = document.getElementById('phone').value.trim();
    const phoneRegex = /^\+?[0-9]{10,15}$/;
    if (phone === '') {
        document.getElementById('phoneError').textContent = 'Phone number is required';
        document.getElementById('phoneError').style.display = 'block';
        isValid = false;
    } else if (!phoneRegex.test(phone)) {
        document.getElementById('phoneError').textContent = 'Please enter a valid phone number';
        document.getElementById('phoneError').style.display = 'block';
        isValid = false;
    }
    
    // Validate role
    const role = document.getElementById('role').value;
    if (role === '') {
        document.getElementById('roleError').textContent = 'Please select a role';
        document.getElementById('roleError').style.display = 'block';
        isValid = false;
    }
    
    // Validate password
    const password = document.getElementById('password').value;
    if (password === '') {
        document.getElementById('passwordError').textContent = 'Password is required';
        document.getElementById('passwordError').style.display = 'block';
        isValid = false;
    } else if (password.length < 8) {
        document.getElementById('passwordError').textContent = 'Password must be at least 8 characters long';
        document.getElementById('passwordError').style.display = 'block';
        isValid = false;
    }
    
    // Validate confirm password
    const confirmPassword = document.getElementById('confirmPassword').value;
    if (confirmPassword === '') {
        document.getElementById('confirmPasswordError').textContent = 'Please confirm your password';
        document.getElementById('confirmPasswordError').style.display = 'block';
        isValid = false;
    } else if (confirmPassword !== password) {
        document.getElementById('confirmPasswordError').textContent = 'Passwords do not match';
        document.getElementById('confirmPasswordError').style.display = 'block';
        isValid = false;
    }
    
    // If form is valid, show success message
    if (isValid) {
        document.getElementById('successMessage').style.display = 'block';
        document.getElementById('registrationForm').reset();
        
        // Simulate redirect after 3 seconds
        setTimeout(() => {
            window.location.href = 'dashboard.php';
        }, 3000);
    }
    
    return false;
}