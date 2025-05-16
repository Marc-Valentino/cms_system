<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login/login.php");
    exit();
}

// Include database connection and functions
include_once '../../includes/db_connection.php';
include_once '../../includes/user_functions.php';
include_once '../../includes/cache_config.php';

// Handle cache clear request
$cache_message = '';
if (isset($_POST['clear_cache']) && $_POST['clear_cache'] == 1) {
    if (clear_all_cache()) {
        $cache_message = 'Cache cleared successfully!';
    } else {
        $cache_message = 'Failed to clear cache.';
    }
}

// Get user data with error handling
try {
    $user_result = cached_supabase_query('users', 'GET', null, [
        'select' => '*', 
        'id' => 'eq.' . $_SESSION['user_id']
    ], 600); // Cache for 10 minutes
    
    if (!empty($user_result) && isset($user_result[0])) {
        $user = $user_result[0];
    } else {
        // Log the error
        error_log("Failed to fetch user data for ID: " . $_SESSION['user_id']);
        $user = [];
    }
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    $user = [];
}

// Get notifications with error handling
try {
    $notifications = cached_supabase_query('notifications', 'GET', null, [
        'user_id' => 'eq.' . $_SESSION['user_id'],
        'order' => 'created_at.desc',
        'limit' => 5
    ], 60); // Cache for 1 minute
    
    if (!is_array($notifications)) {
        $notifications = [];
        error_log("Failed to fetch notifications for user ID: " . $_SESSION['user_id']);
    }
} catch (Exception $e) {
    error_log("Notification fetch error: " . $e->getMessage());
    $notifications = [];
}

// Set default values for user if not found
if (empty($user)) {
    $user = [
        'id' => $_SESSION['user_id'] ?? '',
        'username' => $_SESSION['username'] ?? '',
        'email' => $_SESSION['email'] ?? '',
        'first_name' => $_SESSION['first_name'] ?? '',
        'last_name' => $_SESSION['last_name'] ?? '',
        'profile_pic_url' => $_SESSION['profile_pic_url'] ?? '../assets/img/default-profile.jpg',
        'phone' => $_SESSION['phone'] ?? '',
        'address' => $_SESSION['address'] ?? '',
        'specialty' => $_SESSION['specialty'] ?? '',
        'created_at' => $_SESSION['created_at'] ?? '',
        'updated_at' => $_SESSION['updated_at'] ?? ''
    ];
}

// Prepare user display name
$user_name = (!empty($user['first_name']) && !empty($user['last_name'])) 
    ? $user['first_name'] . ' ' . $user['last_name'] 
    : ($user['username'] ?? 'User');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Clinic Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <!-- In the head section of your document -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/doctor.css">
    <link rel="stylesheet" href="css/settings.css">
    
</head>
<body>
    <div class="dashboard-container">
        <!-- Include the sidebar -->
        <?php include('sidebar.php'); ?>
        
        <div class="main-content">
            <!-- Include the navbar/header -->
            <?php include('navbar.php'); ?>

            <!-- Settings Content -->
            <div class="container-fluid py-4">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="bi bi-gear me-2"></i>Settings</h5>
                            </div>
                            <div class="card-body">
                                <!-- Settings Tabs -->
                                <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">
                                            <i class="bi bi-person"></i> Profile Settings
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" aria-controls="security" aria-selected="false">
                                            <i class="bi bi-shield-lock"></i> Security
                                        </button>
                                    </li>
                                </ul>
                                
                                <!-- Tab Content -->
                                <div class="tab-content" id="settingsTabContent">
                                    <!-- Profile Settings Tab -->
                                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                        <div class="row">
                                            <div class="col-md-4 mb-4">
                                                <!-- Replace this profile picture section -->
                                                <div class="profile-pic-container">
                                                    <i class="bi bi-person-circle profile-icon"></i>
                                                    <h4 class="mt-3"><?php echo htmlspecialchars($user_name); ?></h4>
                                                    <p class="text-muted"><?php echo htmlspecialchars($user['specialty'] ?? 'doctor'); ?></p>
                                                </div>
                                                <!-- Remove the profile-pic-edit div since we're not using profile pictures anymore -->
                                                <!-- Removing this unnecessary div that's causing confusion -->
                                                
                                            </div>
                                            <div class="col-md-8">
                                                <form id="profile-form" method="post" action="update_profile.php">
                                                    <div class="mb-3">
                                                        <label for="fullName" class="form-label">Full Name</label>
                                                        <div class="row">
                                                            <div class="col">
                                                                <input type="text" class="form-control" id="firstName" name="first_name" 
                                                                       value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" placeholder="First Name">
                                                            </div>
                                                            <div class="col">
                                                                <input type="text" class="form-control" id="lastName" name="last_name" 
                                                                       value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" placeholder="Last Name">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">Email Address</label>
                                                        <input type="email" class="form-control" id="email" name="email" 
                                                               value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="specialty" class="form-label">Specialty</label>
                                                        <input type="text" class="form-control" id="specialty" 
                                                               value="<?php echo htmlspecialchars($user['specialty'] ?? ''); ?>" readonly>
                                                        <small class="text-muted">Contact administration to update specialty information.</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="phone" class="form-label">Phone Number</label>
                                                        <input type="text" class="form-control" id="phone" name="phone" 
                                                               value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="address" class="form-label">Address</label>
                                                        <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                                                    </div>
                                                    <div id="profile-update-message"></div>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Security Tab -->
                                    <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                                        <h4 class="mb-4">Change Password</h4>
                                        <form id="password-form" method="post" action="update_password.php">
                                            <div class="mb-3">
                                                <label for="currentPassword" class="form-label">Current Password</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="currentPassword" name="current_password" required>
                                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="newPassword" class="form-label">New Password</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="newPassword" name="new_password" required>
                                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                                <div class="password-strength mt-2">
                                                    <div class="progress" style="height: 5px;">
                                                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="text-muted">Password strength: <span id="password-strength-text">Too weak</span></small>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div id="password-update-message"></div>
                                            <button type="submit" class="btn btn-primary">Update Password</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Toggle sidebar on mobile
        document.getElementById('toggle-sidebar').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });
        
        // Profile picture upload
        document.querySelector('.profile-pic-edit').addEventListener('click', function() {
            document.getElementById('profile-pic-input').click();
        });
        
        document.getElementById('profile-pic-input').addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                const file = e.target.files[0];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    document.querySelector('.profile-pic').src = e.target.result;
                }
                
                reader.readAsDataURL(file);
            }
        });
        
        // Password visibility toggle
        const passwordVisibilityButtons = document.querySelectorAll('.toggle-password');
        passwordVisibilityButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });
        });
        
        // Profile form submission with error handling
        document.getElementById('profile-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const messageDiv = document.getElementById('profile-update-message');
            
            // Show loading state
            messageDiv.innerHTML = '<div class="alert alert-info">Updating profile...</div>';
            
            fetch('update_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    messageDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                    // Refresh page after 2 seconds to show updated data
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    messageDiv.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                messageDiv.innerHTML = '<div class="alert alert-danger">An error occurred. Please try again later.</div>';
            });
        });
        
        // Simple password strength meter
        document.getElementById('newPassword').addEventListener('input', function() {
            const password = this.value;
            const progressBar = document.querySelector('.password-strength .progress-bar');
            const strengthText = document.getElementById('password-strength-text');
            
            // Password strength logic
            let strength = 0;
            if (password.length >= 8) strength += 25;
            if (password.match(/[A-Z]/)) strength += 25;
            if (password.match(/[0-9]/)) strength += 25;
            if (password.match(/[^A-Za-z0-9]/)) strength += 25;
            
            // Update UI
            progressBar.style.width = strength + '%';
            
            // Update color based on strength
            if (strength < 25) {
                progressBar.className = 'progress-bar bg-danger';
                strengthText.textContent = 'Too weak';
            } else if (strength < 50) {
                progressBar.className = 'progress-bar bg-warning';
                strengthText.textContent = 'Weak';
            } else if (strength < 75) {
                progressBar.className = 'progress-bar bg-info';
                strengthText.textContent = 'Medium';
            } else {
                progressBar.className = 'progress-bar bg-success';
                strengthText.textContent = 'Strong';
            }
        });
        
        // Password form submission with error handling
        document.getElementById('password-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const messageDiv = document.getElementById('password-update-message');
            
            // Show loading state
            messageDiv.innerHTML = '<div class="alert alert-info">Updating password...</div>';
            
            fetch('update_password.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    messageDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                    // Clear form fields
                    document.getElementById('currentPassword').value = '';
                    document.getElementById('newPassword').value = '';
                    document.getElementById('confirmPassword').value = '';
                } else {
                    messageDiv.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                messageDiv.innerHTML = '<div class="alert alert-danger">An error occurred. Please try again later.</div>';
            });
        });
    </script>
</body>
</html>

<div class="tab-pane fade" id="system" role="tabpanel" aria-labelledby="system-tab">
    <h4 class="mb-4">System Settings</h4>
    
    <!-- Cache Management -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Cache Management</h5>
        </div>
        <div class="card-body">
            <p>Clearing the cache can help resolve display issues and ensure you're seeing the most up-to-date information.</p>
            
            <?php if (!empty($cache_message)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($cache_message); ?></div>
            <?php endif; ?>
            
            <form method="post" action="">
                <input type="hidden" name="clear_cache" value="1">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-trash"></i> Clear System Cache
                </button>
            </form>
        </div>
    </div>
    
    <!-- System Information -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">System Information</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <tbody>
                    <tr>
                        <th>PHP Version</th>
                        <td><?php echo phpversion(); ?></td>
                    </tr>
                    <tr>
                        <th>Server Software</th>
                        <td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
                    </tr>
                    <tr>
                        <th>Cache Status</th>
                        <td><?php echo CACHE_ENABLED ? 'Enabled' : 'Disabled'; ?></td>
                    </tr>
                    <tr>
                        <th>Cache Lifetime</th>
                        <td><?php echo CACHE_LIFETIME; ?> seconds (<?php echo CACHE_LIFETIME / 60; ?> minutes)</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
