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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #1e90ff, #00bfff);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
        }
        
        .container {
            display: flex;
            width: 100%;
            max-width: 1000px;
            height: 600px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #00bfff, #1e90ff);
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .left-panel::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -100px;
            left: -100px;
        }
        
        .left-panel::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            bottom: -50px;
            right: -50px;
        }
        
        .clinic-logo {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }
        
        .clinic-logo i {
            margin-right: 10px;
            color: #fff;
            animation: heartbeat 1.5s ease-in-out infinite;
        }
        
        @keyframes heartbeat {
            0% { transform: scale(1); }
            15% { transform: scale(1.25); }
            30% { transform: scale(1); }
            45% { transform: scale(1.25); }
            60% { transform: scale(1); }
            100% { transform: scale(1); }
        }
        
        .welcome-text {
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }
        
        .welcome-text h2 {
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .welcome-text p {
            font-size: 16px;
            line-height: 1.6;
            opacity: 0.9;
        }
        
        .features {
            position: relative;
            z-index: 1;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .feature-item i {
            font-size: 20px;
            margin-right: 15px;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .right-panel {
            flex: 1;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .login-header h3 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #777;
            font-size: 15px;
        }
        
        .login-form .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .login-form label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            font-size: 14px;
        }
        
        .login-form .input-group {
            position: relative;
        }
        
        .login-form .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #1e90ff;
            font-size: 18px;
        }
        
        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
            background-color: #f9f9f9;
        }
        
        .login-form input[type="text"]:focus,
        .login-form input[type="password"]:focus {
            border-color: #1e90ff;
            box-shadow: 0 0 0 3px rgba(30, 144, 255, 0.1);
            background-color: #fff;
            outline: none;
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .remember-me input {
            margin-right: 8px;
            width: 16px;
            height: 16px;
            accent-color: #1e90ff;
        }
        
        .remember-me label {
            margin-bottom: 0;
            font-size: 14px;
            color: #666;
        }
        
        .forgot-password {
            color: #1e90ff;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .forgot-password:hover {
            text-decoration: underline;
        }
        
        .login-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #1e90ff, #00bfff);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(30, 144, 255, 0.3);
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 144, 255, 0.4);
        }
        
        .login-btn:active {
            transform: translateY(0);
        }
        
        .login-footer {
            text-align: center;
            margin-top: 30px;
            color: #777;
            font-size: 14px;
        }
        
        .login-footer a {
            color: #1e90ff;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        .error-message {
            background-color: rgba(255, 99, 71, 0.1);
            color: #ff6347;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: center;
            border-left: 4px solid #ff6347;
            font-size: 14px;
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .role-selector {
            display: flex;
            justify-content: center;
            margin-bottom: 25px;
        }
        
        .role-option {
            padding: 10px 20px;
            margin: 0 5px;
            background: #f0f0f0;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
            color: #555;
        }
        
        .role-option.active {
            background: linear-gradient(135deg, #1e90ff, #00bfff);
            color: white;
            box-shadow: 0 4px 10px rgba(30, 144, 255, 0.3);
        }
        
        /* Responsive adjustments */
        @media (max-width: 900px) {
            .container {
                flex-direction: column;
                height: auto;
                max-width: 500px;
            }
            
            .left-panel {
                padding: 30px;
                display: none;
            }
            
            .right-panel {
                padding: 40px 30px;
            }
        }
    </style>
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
                <p>Need help? <a href="#">Contact Support</a></p>
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