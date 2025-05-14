cms_system/
├── login/
│   └── login.php
├── dashboards/
│   ├── doctor/
│   │   ├── doctor.php
│   │   ├── patients.php
│   │   ├── appointments.php
│   │   ├── medical-notes.php
│   │   ├── notifications.php
│   │   ├── settings.php
│   │   ├── logout.php
│   │   ├── header.php
│   │   └── sidebar.php
│   └── nurse/
│       ├── nurse-patients.php
│       ├── vitals.php
│       ├── medications.php
│       ├── schedule.php
│       ├── nurse-notification.php
│       ├── logout.php
│       └── nurse-sidebar.php
└── admin/
    └── admin.php<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If user is already logged in, redirect to appropriate dashboard
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 1) {
        header("Location: ../dashboards/doctor/doctor.php");
        exit();
    } elseif ($_SESSION['role'] == 2) {
        header("Location: ../dashboards/nurse/nurse.php");
        exit();
    } elseif ($_SESSION['role'] == 3) {
        header("Location: ../admin/admin.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MediCare Clinic</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2196F3;
            --primary-light: #E3F2FD;
            --secondary-color: #FFC107;
            --text-dark: #333;
            --text-light: #767676;
            --white: #FFFFFF;
            --border-color: #E0E0E0;
            --border-radius: 10px;
            --box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        
        .login-container {
            display: flex;
            max-width: 1000px;
            background: var(--white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
        }
        
        .login-sidebar {
            background: linear-gradient(135deg, #2196F3 0%, #1E88E5 100%);
            color: var(--white);
            padding: 40px;
            width: 40%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-sidebar h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .login-sidebar p {
            font-size: 1rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .feature-icon {
            background: rgba(255, 255, 255, 0.2);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        
        .login-form-container {
            padding: 40px;
            width: 60%;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h2 {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        .role-selector {
            display: flex;
            justify-content: center;
            margin-bottom: 25px;
        }
        
        .role-option {
            padding: 10px 20px;
            border-radius: 30px;
            margin: 0 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.9rem;
            background-color: #f5f5f5;
        }
        
        .role-option.active {
            background-color: var(--primary-color);
            color: var(--white);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-dark);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 0.9rem;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }
        
        .input-with-icon {
            padding-left: 40px;
        }
        
        .login-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .remember-me input {
            margin-right: 8px;
        }
        
        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .login-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 30px;
        }
        
        .login-btn:hover {
            background-color: #1E88E5;
        }
        
        .error-message {
            background-color: #FFEBEE;
            color: #D32F2F;
            padding: 12px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }
        
        .error-message i {
            margin-right: 10px;
        }
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                max-width: 400px;
            }
            
            .login-sidebar, .login-form-container {
                width: 100%;
            }
            
            .login-sidebar {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-sidebar">
            <h1>MediCare Clinic</h1>
            <p>Welcome Back! Access your clinic management dashboard to manage appointments, patient records, and more.</p>
            
            <ul class="feature-list">
                <li class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <span>Manage appointments efficiently</span>
                </li>
                <li class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <span>Access patient medical records</span>
                </li>
                <li class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-pills"></i>
                    </div>
                    <span>Track medication and prescriptions</span>
                </li>
                <li class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <span>View clinic analytics and reports</span>
                </li>
            </ul>
        </div>
        
        <div class="login-form-container">
            <div class="login-header">
                <h2>Sign In to MediCare</h2>
                <p>Please enter your credentials to access the system</p>
            </div>
            
            <div class="role-selector">
                <div class="role-option active" data-role="doctor">Doctor</div>
                <div class="role-option" data-role="nurse">Nurse</div>
                <div class="role-option" data-role="admin">Admin</div>
            </div>
            
            <form id="loginForm">
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-group">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email" class="form-control input-with-icon" placeholder="Enter your email" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password" name="password" class="form-control input-with-icon" placeholder="Enter your password" required>
                    </div>
                </div>
                
                <div class="login-footer">
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
</body>
</html>
