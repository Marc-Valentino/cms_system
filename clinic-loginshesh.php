<?php
session_start();
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // This is a simple authentication check - in a real system, you'd check against a database
    if ($username == 'doctor' && $password == 'clinic123') {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'doctor';
        header("location: dashboard.php");
        exit;
    } elseif ($username == 'nurse' && $password == 'clinic123') {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'nurse';
        header("location: dashboard.php");
        exit;
    } elseif ($username == 'admin' && $password == 'clinic123') {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'admin';
        header("location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
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
    <link rel="stylesheet" href="css/clinic-login.css">
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
            
            <form action="" method="post" class="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
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
                <p>No account yet? <a href="#">Register</a></p>
            </div>
        </div>
    </div>
    
    <script>
        // Role selector functionality
        document.querySelectorAll('.role-option').forEach(option => {
            option.addEventListener('click', function() {
                // Remove active class from all options
                document.querySelectorAll('.role-option').forEach(opt => {
                    opt.classList.remove('active');
                });
                
                // Add active class to clicked option
                this.classList.add('active');
                
                // Set username field based on role
                const role = this.getAttribute('data-role');
                document.getElementById('username').value = role;
            });
        });
    </script>
</body>
</html>
