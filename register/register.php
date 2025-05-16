<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Management System - Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="container">
        <div class="info-section">
            <h1>Clinic Management System</h1>
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

                <div class="terms">
                    <label class="checkbox-container">
                        <input type="checkbox" id="termsCheckbox" required>
                        <span class="checkmark"></span>
                        agree to our <a href="#"> Terms of Service </a> and <a href="#">  Privacy Policy</a>
                    </label>
                </div>
                
                <button type="submit" class="btn">Register Account</button>
                
                
                
                <div class="login-link">
                    Already have an account? <a href="../login/login.php">Login here</a>
                </div>
            </form>
        </div>
    </div>
<script src="register.js"></script>
</body>
</html>