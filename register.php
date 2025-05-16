<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Management System - Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1a73e8; /* Deeper blue */
            --secondary-color: #4285f4; /* Google blue */
            --accent-color: #34a0ff; /* Light blue accent */
            --text-color: #202124; /* Darker text for better contrast */
            --light-color: #f0f6ff; /* Light blue tint for backgrounds */
            --error-color: #e74c3c;
            --success-color: #0f9d58; /* Google green */
            --shadow: 0 4px 12px rgba(26, 115, 232, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #e8f0fe 0%, #c2d7ff 100%); /* Blue gradient background */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            display: flex;
            box-shadow: var(--shadow);
            border-radius: 15px;
            overflow: hidden;
            background-color: white;
        }

        .info-section {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            padding: 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Heart Logo and Animation Styles */
        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .heart-logo {
            width: 80px;
            height: 80px;
            position: relative;
            animation: heartbeat 3s ease-in-out infinite; /* Increased from 1.5s to 3s */
        }

        @keyframes heartbeat {
            0% { transform: scale(1); }
            10% { transform: scale(1); }
            15% { transform: scale(1.12); }
            25% { transform: scale(1); }
            35% { transform: scale(1); }
            40% { transform: scale(1.12); }
            50% { transform: scale(1); }
            100% { transform: scale(1); }
        }

        .info-section h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: 700;
            text-align: center;
        }

        .info-section p {
            font-size: 1.1rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .features {
            margin-top: 30px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 15px;
        }

        .feature-text {
            font-size: 1rem;
        }

        .form-section {
            flex: 1;
            padding: 40px;
            background-color: white;
        }

        .form-section h2 {
            font-size: 1.8rem;
            margin-bottom: 30px;
            color: var(--text-color);
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-color);
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }

        .form-control {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.2);
            outline: none;
        }

        .error-message {
            color: var(--error-color);
            font-size: 0.85rem;
            margin-top: 5px;
            display: none;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .btn {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 4px 6px rgba(26, 115, 232, 0.3);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(26, 115, 232, 0.4);
            background: linear-gradient(to right, #0d62d1, #3b7deb); /* Slightly darker on hover */
        }

        .btn:active {
            transform: translateY(0);
        }

        .terms {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #777;
            text-align: center;
        }

        .terms a {
            color: var(--primary-color);
            text-decoration: none;
            cursor: pointer;
        }

        .success-message {
            display: none;
            background-color: var(--success-color);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.95rem;
            color: #666;
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        /* Modal Styles */
        .modal-overlay {
            display: none; /* Hide by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal.show {
            display: flex;
            opacity: 1;
        }

        .modal-content {
            background-color: white;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            position: relative;
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }

        .modal.show .modal-content {
            transform: translateY(0);
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.5rem;
            color: var(--text-color);
            font-weight: 600;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #777;
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .modal-close:hover {
            color: var(--error-color);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 20px;
        }

        /* Loading Animation */
        .loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px;
        }

        .heart-rate-container {
            position: relative;
            width: 100%;
            height: 100px;
            margin-bottom: 20px;
        }

        .heart-rate {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .heart-rate-line {
            width: 100%;
            height: 2px;
            background-color: #eee;
            position: relative;
            overflow: hidden;
        }

        .heart-rate-pulse {
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, transparent, var(--primary-color), transparent);
            animation: pulse-animation 2s infinite;
        }

        @keyframes pulse-animation {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .heart-rate-svg {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }

        .heart-rate-path {
            stroke: var(--primary-color);
            stroke-width: 2;
            fill: none;
            stroke-dasharray: 1000;
            stroke-dashoffset: 1000;
            animation: dash-animation 2s infinite linear;
        }

        @keyframes dash-animation {
            0% { stroke-dashoffset: 1000; }
            50% { stroke-dashoffset: 0; }
            100% { stroke-dashoffset: -1000; }
        }

        .loading-text {
            font-size: 1.1rem;
            color: var(--text-color);
            margin-top: 15px;
            animation: pulse-text 1.5s infinite;
        }

        @keyframes pulse-text {
            0% { opacity: 0.5; }
            50% { opacity: 1; }
            100% { opacity: 0.5; }
        }

        .content-container {
            display: none;
        }

        /* Responsive styles */
        @media (max-width: 992px) {
            .container {
                flex-direction: column;
                max-width: 600px;
            }
            
            .info-section, .form-section {
                padding: 30px;
            }
            
            .info-section {
                text-align: center;
            }
            
            .feature-item {
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .modal-content {
                width: 95%;
                max-height: 90vh;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="info-section">
            <div class="logo-container">
                <svg class="heart-logo" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <!-- Heart shape -->
                    <path d="M50,90 C25,65 0,50 0,25 C0,10 10,0 25,0 C35,0 45,5 50,15 C55,5 65,0 75,0 C90,0 100,10 100,25 C100,50 75,65 50,90 Z" fill="#ffffff"/>
                    <!-- Lifeline across the heart -->
                    <path d="M10,50 L30,50 L35,35 L45,65 L55,35 L65,65 L70,50 L90,50" stroke="#1a73e8" stroke-width="3" fill="none" stroke-linecap="round"/>
                </svg>
            </div>
            <h1>MediCare Clinic</h1>
            <p>Join our platform to streamline your healthcare practice. Register now to access our comprehensive clinic management tools.</p>
            
            <div class="features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="feature-text">Manage patient records efficiently</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="feature-text">Schedule appointments with ease</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-file-medical-alt"></i>
                    </div>
                    <div class="feature-text">Generate detailed medical reports</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-pills"></i>
                    </div>
                    <div class="feature-text">Track medication and prescriptions</div>
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <div class="success-message" id="successMessage">
                <i class="fas fa-check-circle"></i> Registration successful! Redirecting to dashboard...
            </div>
            
            <h2>Create an Account</h2>
            
            <form id="registrationForm" onsubmit="return validateForm(event)">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" id="firstName" class="form-control" placeholder="Enter first name">
                        </div>
                        <div class="error-message" id="firstNameError"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" id="lastName" class="form-control" placeholder="Enter last name">
                        </div>
                        <div class="error-message" id="lastNameError"></div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" class="form-control" placeholder="Enter email address">
                    </div>
                    <div class="error-message" id="emailError"></div>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <div class="input-group">
                        <i class="fas fa-phone"></i>
                        <input type="tel" id="phone" class="form-control" placeholder="Enter phone number">
                    </div>
                    <div class="error-message" id="phoneError"></div>
                </div>
                
                <div class="form-group">
                    <label for="role">Role</label>
                    <div class="input-group">
                        <i class="fas fa-user-tag"></i>
                        <select id="role" class="form-control">
                            <option value="">Select your role</option>
                            <option value="doctor">Doctor</option>
                            <option value="nurse">Nurse</option>
                            <option value="receptionist">Receptionist</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                    <div class="error-message" id="roleError"></div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" class="form-control" placeholder="Enter password">
                        </div>
                        <div class="error-message" id="passwordError"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password</label>
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="confirmPassword" class="form-control" placeholder="Confirm password">
                        </div>
                        <div class="error-message" id="confirmPasswordError"></div>
                    </div>
                </div>
                
                <button type="submit" class="btn">Register Account</button>
                
                <div class="terms">
                    By registering, you agree to our <a onclick="openModal('terms')">Terms of Service</a> and <a onclick="openModal('privacy')">Privacy Policy</a>
                </div>
                
                <div class="login-link">
                    Already have an account? <a href="login.php">Login here</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Terms of Service</h3>
                <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Loading Animation -->
                <div class="loading-container" id="loadingContainer">
                    <svg class="heart-icon" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <path d="M50,90 C25,65 0,50 0,25 C0,10 10,0 25,0 C35,0 45,5 50,15 C55,5 65,0 75,0 C90,0 100,10 100,25 C100,50 75,65 50,90 Z" fill="var(--primary-color)"/>
                    </svg>
                    
                    <div class="heart-rate-container">
                        <div class="heart-rate">
                            <div class="heart-rate-line"></div>
                            <div class="heart-rate-pulse">
                                <svg viewBox="0 0 600 100" preserveAspectRatio="none">
                                    <path d="M0,50 L100,50 L130,20 L160,80 L190,20 L220,80 L250,50 L300,50 L330,20 L360,80 L390,20 L420,80 L450,50 L600,50" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="loading-text">
                        Loading<span class="loading-dots"></span>
                    </div>
                </div>
                
                <!-- Content Containers -->
                <div class="content-container" id="termsContent">
                    <h4>Terms of Service</h4>
                    <p>Welcome to MediCare Clinic. By using our services, you agree to comply with and be bound by the following terms and conditions:</p>
                    
                    <h5>1. Acceptance of Terms</h5>
                    <p>By accessing or using the MediCare Clinic Management System, you agree to be bound by these Terms of Service and all applicable laws and regulations. If you do not agree with any of these terms, you are prohibited from using or accessing this system.</p>
                    
                    <h5>2. Use License</h5>
                    <p>Permission is granted to temporarily use the MediCare Clinic Management System for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title.</p>
                    
                    <h5>3. User Accounts</h5>
                    <p>To access certain features of the system, you will need to create a user account. You are responsible for maintaining the confidentiality of your account information and for all activities that occur under your account.</p>
                    
                    <h5>4. Privacy Policy</h5>
                    <p>Your use of the MediCare Clinic Management System is also governed by our Privacy Policy, which is incorporated into these Terms of Service by reference.</p>
                    
                    <h5>5. Limitation of Liability</h5>
                    <p>MediCare Clinic shall not be liable for any direct, indirect, incidental, special, consequential or exemplary damages resulting from your use or inability to use the service.</p>
                </div>
                
                <div class="content-container" id="privacyContent">
                    <h4>Privacy Policy</h4>
                    <p>At MediCare Clinic, we take your privacy seriously. This Privacy Policy describes how we collect, use, and share your personal information:</p>
                    
                    <h5>1. Information We Collect</h5>
                    <p>We collect information you provide directly to us, such as your name, email address, phone number, and other personal details when you register for an account or use our services.</p>
                    
                    <h5>2. How We Use Your Information</h5>
                    <p>We use the information we collect to provide, maintain, and improve our services, communicate with you, and comply with legal obligations.</p>
                    
                    <h5>3. Information Sharing</h5>
                    <p>We do not share your personal information with third parties except as described in this Privacy Policy, such as with your consent or to comply with laws.</p>
                    
                    <h5>4. Data Security</h5>
                    <p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>
                    
                    <h5>5. Your Rights</h5>
                    <p>You have the right to access, correct, or delete your personal information. You may also have the right to object to or restrict certain processing of your data.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
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

        // Modal Functions
        function openModal(type) {
            // Set modal title based on type
            const modalTitle = document.getElementById('modalTitle');
            if (type === 'terms') {
                modalTitle.textContent = 'Terms of Service';
            } else if (type === 'privacy') {
                modalTitle.textContent = 'Privacy Policy';
            }

            // Show modal
            const modalOverlay = document.getElementById('modalOverlay');
            modalOverlay.style.display = 'flex';
            
            // Show loading animation
            document.getElementById('loadingContainer').style.display = 'flex';
            document.getElementById('termsContent').style.display = 'none';
            document.getElementById('privacyContent').style.display = 'none';
            
            // Simulate loading and then show content
            setTimeout(() => {
                document.getElementById('loadingContainer').style.display = 'none';
                
                if (type === 'terms') {
                    document.getElementById('termsContent').style.display = 'block';
                } else if (type === 'privacy') {
                    document.getElementById('privacyContent').style.display = 'block';
                }
            }, 2000); // 2 seconds loading animation
        }

        function closeModal() {
            document.getElementById('modalOverlay').style.display = 'none';
        }

        // Close modal when clicking outside of it
        document.getElementById('modalOverlay').addEventListener('click', function(event) {
            if (event.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
