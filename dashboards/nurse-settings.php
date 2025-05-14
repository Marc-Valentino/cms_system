<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
// if (!isset($_SESSION['user_id'])) {
//     header("Location: ../login.php");
//     exit();
// }

// Placeholder user data - replace with actual data from your database
$user = [
    'id' => 1,
    'name' => 'Dr. Sarah Johnson',
    'email' => 'sarah.johnson@example.com',
    'profile_pic' => '../assets/img/doctor-profile.jpg',
    'role' => 'Doctor', // Changed to 'Doctor' for doctor view
    'specialty' => 'Cardiologist',
    'phone' => '(555) 123-4567',
    'address' => '123 Medical Center Dr, Healthcare City, HC 12345'
];

// Placeholder for notifications - replace with actual data
$notifications = [
    ['type' => 'Lab Result', 'message' => 'New lab results for patient Emily Davis', 'time' => '2 hours ago'],
    ['type' => 'Reminder', 'message' => 'Follow-up call with John Smith', 'time' => '1 day ago'],
    ['type' => 'System', 'message' => 'System maintenance scheduled for tonight', 'time' => '3 days ago']
];

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="nurse-settings.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Include the sidebar -->
        <?php include('nurse-sidebar.php'); ?>
        
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
                                                <div class="profile-pic-container">
                                                    <img src="<?php echo $user['profile_pic']; ?>" alt="Profile Picture" class="profile-pic" onerror="this.src='https://via.placeholder.com/150'">
                                                    <div class="profile-pic-edit" title="Change Profile Picture">
                                                        <i class="bi bi-camera"></i>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <h5 class="mb-1"><?php echo $user['name']; ?></h5>
                                                    <p class="text-muted"><?php echo $user['role']; ?> - <?php echo $user['specialty']; ?></p>
                                                </div>
                                                <input type="file" id="profile-pic-input" class="d-none">
                                            </div>
                                            <div class="col-md-8">
                                                <form>
                                                    <div class="mb-3">
                                                        <label for="fullName" class="form-label">Full Name</label>
                                                        <input type="text" class="form-control" id="fullName" value="<?php echo $user['name']; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">Email Address</label>
                                                        <input type="email" class="form-control" id="email" value="<?php echo $user['email']; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="specialty" class="form-label">Specialty</label>
                                                        <input type="text" class="form-control" id="specialty" value="<?php echo $user['specialty']; ?>" readonly>
                                                        <small class="text-muted">Contact administration to update specialty information.</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="phone" class="form-label">Phone Number</label>
                                                        <input type="text" class="form-control" id="phone" value="<?php echo $user['phone']; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="address" class="form-label">Address</label>
                                                        <textarea class="form-control" id="address" rows="3"><?php echo $user['address']; ?></textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Security Tab -->
                                    <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                                        <h4 class="mb-4">Change Password</h4>
                                        <form>
                                            <div class="mb-3">
                                                <label for="currentPassword" class="form-label">Current Password</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="currentPassword">
                                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="newPassword" class="form-label">New Password</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="newPassword">
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
                                                    <input type="password" class="form-control" id="confirmPassword">
                                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
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
        const toggleButtons = document.querySelectorAll('.toggle-password');
        toggleButtons.forEach(button => {
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
        
        // Simple password strength meter
        document.getElementById('newPassword').addEventListener('input', function() {
            const password = this.value;
            const progressBar = document.querySelector('.password-strength .progress-bar');
            const strengthText = document.getElementById('password-strength-text');
            
            let strength = 0;
            
            if (password.length >= 8) strength += 25;
            if (password.match(/[A-Z]/)) strength += 25;
            if (password.match(/[0-9]/)) strength += 25;
            if (password.match(/[^A-Za-z0-9]/)) strength += 25;
            
            progressBar.style.width = strength + '%';
            
            if (strength <= 25) {
                progressBar.className = 'progress-bar bg-danger';
                strengthText.textContent = 'Too weak';
            } else if (strength <= 50) {
                progressBar.className = 'progress-bar bg-warning';
                strengthText.textContent = 'Could be stronger';
            } else if (strength <= 75) {
                progressBar.className = 'progress-bar bg-info';
                strengthText.textContent = 'Good';
            } else {
                progressBar.className = 'progress-bar bg-success';
                strengthText.textContent = 'Strong';
            }
        });
    </script>
</body>
</html>