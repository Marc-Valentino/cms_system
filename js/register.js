function openModal(content) {
    // Create modal elements
    const modalOverlay = document.createElement('div');
    modalOverlay.className = 'modal-overlay';
    
    const modalContainer = document.createElement('div');
    modalContainer.className = 'modal-container';
    
    const modalHeader = document.createElement('div');
    modalHeader.className = 'modal-header';
    
    const modalTitle = document.createElement('h3');
    modalTitle.textContent = content === 'terms' ? 'Terms of Service' : 'Privacy Policy';
    
    const closeButton = document.createElement('button');
    closeButton.className = 'modal-close';
    closeButton.innerHTML = '&times;';
    closeButton.onclick = closeModal;
    
    const modalBody = document.createElement('div');
    modalBody.className = 'modal-body';
    
    // Set content based on what was clicked
    if (content === 'terms') {
        modalBody.innerHTML = `
            <h4>1. Acceptance of Terms</h4>
            <p>By accessing or using the MediCare Clinic Management System, you agree to be bound by these Terms of Service and all applicable laws and regulations.</p>
            
            <h4>2. Use License</h4>
            <p>Permission is granted to temporarily access the materials within the MediCare Clinic Management System for personal, non-commercial use.</p>
            
            <h4>3. User Accounts</h4>
            <p>To access certain features of the system, you will need to create an account. You are responsible for maintaining the confidentiality of your account information.</p>
            
            <h4>4. Patient Data and Privacy</h4>
            <p>All healthcare professionals using this system must comply with applicable healthcare privacy laws and regulations.</p>
        `;
    } else {
        modalBody.innerHTML = `
            <h4>1. Information We Collect</h4>
            <p>We collect personal information such as name, contact details, date of birth, and medical history to provide healthcare services.</p>
            
            <h4>2. How We Use Your Information</h4>
            <p>We use your information to provide healthcare services, communicate with you about appointments, maintain medical records, improve our services, and for billing purposes.</p>
            
            <h4>3. Data Security</h4>
            <p>We implement appropriate technical and organizational measures to protect your personal information against unauthorized access.</p>
            
            <h4>4. Your Rights</h4>
            <p>You have the right to access your personal information, request corrections, request deletion where applicable, and withdraw consent for certain processing activities.</p>
        `;
    }
    
    // Assemble modal
    modalHeader.appendChild(modalTitle);
    modalHeader.appendChild(closeButton);
    
    modalContainer.appendChild(modalHeader);
    modalContainer.appendChild(modalBody);
    
    modalOverlay.appendChild(modalContainer);
    document.body.appendChild(modalOverlay);
    
    // Prevent body scrolling when modal is open
    document.body.style.overflow = 'hidden';
    
    // Close modal when clicking outside
    modalOverlay.addEventListener('click', function(e) {
        if (e.target === modalOverlay) {
            closeModal();
        }
    });
}

function closeModal() {
    const modalOverlay = document.querySelector('.modal-overlay');
    if (modalOverlay) {
        document.body.removeChild(modalOverlay);
        document.body.style.overflow = 'auto';
    }
}

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