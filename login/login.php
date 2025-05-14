<?php
session_start();
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <div class="clinic-logo">
                <i class="fas fa-heartbeat"></i> MediCare Clinic
            </div>
            <div class="welcome-text">
                <h2>Welcome Back!</h2>
                <p>Access your clinic management dashboard to manage appointments, patient records, and more.</p>
            </div>
            <div class="features">
                <div class="feature-item">
                    <i class="fas fa-calendar-check"></i>
                    <span>Manage appointments efficiently</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-user-md"></i>
                    <span>Access patient medical records</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-pills"></i>
                    <span>Track medication and prescriptions</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-chart-line"></i>
                    <span>View clinic analytics and reports</span>
                </div>
            </div>
        </div>
        <div class="right-panel">
            <div class="login-header">
                <h3>Sign In to MediCare</h3>
                <p>Please enter your credentials to access the system</p>
            </div>
            
            <?php if($error != ''): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <div class="role-selector">
                <div class="role-option active" data-role="doctor">Doctor</div>
                <div class="role-option" data-role="nurse">Nurse</div>
                <div class="role-option" data-role="admin">Admin</div>
            </div>
            
            <form id="loginForm" action="login_process.php" method="post" class="login-form">
                <div class="form-group">
                    <label for="username">Email</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                </div>
                
                <div class="remember-forgot">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>
                
                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>
            
            <div class="login-footer">
                <p>No account yet? <a href="../register/register.php">Register</a></p>
            </div>
        </div>
    </div>
    
<script src="login.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(loginForm);
            
            fetch('login_process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
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
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    }
});
</script>
</body>
</html>
