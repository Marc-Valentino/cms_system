<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and has admin role
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header("Location: ../login/login.php");
//     exit();
// }

// Include database configuration
include('../includes/config.php');

// Placeholder for system settings - replace with actual data from your database
$systemSettings = [
    'site_name' => 'MediCare Clinic',
    'site_email' => 'admin@medicare-clinic.com',
    'appointment_interval' => 30,
    'working_hours_start' => '08:00',
    'working_hours_end' => '18:00',
    'maintenance_mode' => false,
    'enable_notifications' => true,
    'enable_sms' => false,
    'enable_email' => true,
    'default_language' => 'English',
    'timezone' => 'UTC',
    'date_format' => 'MM/DD/YYYY',
    'time_format' => '12h'
];

// Placeholder for cache settings
$cacheSettings = [
    'enable_cache' => true,
    'cache_lifetime' => 3600,
    'clear_on_update' => true
];

// Handle form submissions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_system_settings'])) {
        // Process system settings update
        // In a real application, you would update the database here
        $message = 'System settings updated successfully!';
        $messageType = 'success';
    } elseif (isset($_POST['update_cache_settings'])) {
        // Process cache settings update
        // In a real application, you would update the database here
        $message = 'Cache settings updated successfully!';
        $messageType = 'success';
    } elseif (isset($_POST['clear_cache'])) {
        // Process cache clearing
        // In a real application, you would clear the cache here
        $message = 'Cache cleared successfully!';
        $messageType = 'success';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings - Clinic Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Include the sidebar -->
        <?php include('admin-sidebar.php'); ?>
        
        <div class="main-content">
            <!-- Include the navbar/header -->
            <?php include('admin-navbar.php'); ?>

            <!-- Settings Content -->
            <div class="container-fluid py-4">
                <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="bi bi-gear me-2"></i>System Settings</h5>
                            </div>
                            <div class="card-body">
                                <!-- Settings Tabs -->
                                <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">
                                            <i class="bi bi-sliders"></i> General Settings
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="notification-tab" data-bs-toggle="tab" data-bs-target="#notification" type="button" role="tab" aria-controls="notification" aria-selected="false">
                                            <i class="bi bi-bell"></i> Notification Settings
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="cache-tab" data-bs-toggle="tab" data-bs-target="#cache" type="button" role="tab" aria-controls="cache" aria-selected="false">
                                            <i class="bi bi-hdd-stack"></i> Cache Settings
                                        </button>
                                    </li>
                                </ul>
                                
                                <!-- Tab Content -->
                                <div class="tab-content" id="settingsTabContent">
                                    <!-- General Settings Tab -->
                                    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                        <form id="systemSettingsForm" method="post">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="siteName" class="form-label">Site Name</label>
                                                    <input type="text" class="form-control" id="siteName" name="site_name" value="<?php echo htmlspecialchars($systemSettings['site_name']); ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="siteEmail" class="form-label">Site Email</label>
                                                    <input type="email" class="form-control" id="siteEmail" name="site_email" value="<?php echo htmlspecialchars($systemSettings['site_email']); ?>" required>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="appointmentInterval" class="form-label">Appointment Interval (minutes)</label>
                                                    <input type="number" class="form-control" id="appointmentInterval" name="appointment_interval" value="<?php echo $systemSettings['appointment_interval']; ?>" min="5" max="60" step="5" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="maintenanceMode" class="form-label">Maintenance Mode</label>
                                                    <div class="form-check form-switch mt-2">
                                                        <input class="form-check-input" type="checkbox" id="maintenanceMode" name="maintenance_mode" <?php echo $systemSettings['maintenance_mode'] ? 'checked' : ''; ?>>
                                                        <label class="form-check-label" for="maintenanceMode">Enable Maintenance Mode</label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="workingHoursStart" class="form-label">Working Hours Start</label>
                                                    <input type="time" class="form-control" id="workingHoursStart" name="working_hours_start" value="<?php echo $systemSettings['working_hours_start']; ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="workingHoursEnd" class="form-label">Working Hours End</label>
                                                    <input type="time" class="form-control" id="workingHoursEnd" name="working_hours_end" value="<?php echo $systemSettings['working_hours_end']; ?>" required>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="defaultLanguage" class="form-label">Default Language</label>
                                                    <select class="form-select" id="defaultLanguage" name="default_language">
                                                        <option value="English" <?php echo $systemSettings['default_language'] === 'English' ? 'selected' : ''; ?>>English</option>
                                                        <option value="Spanish" <?php echo $systemSettings['default_language'] === 'Spanish' ? 'selected' : ''; ?>>Spanish</option>
                                                        <option value="French" <?php echo $systemSettings['default_language'] === 'French' ? 'selected' : ''; ?>>French</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="timezone" class="form-label">Timezone</label>
                                                    <select class="form-select" id="timezone" name="timezone">
                                                        <option value="UTC" <?php echo $systemSettings['timezone'] === 'UTC' ? 'selected' : ''; ?>>UTC</option>
                                                        <option value="America/New_York" <?php echo $systemSettings['timezone'] === 'America/New_York' ? 'selected' : ''; ?>>Eastern Time (ET)</option>
                                                        <option value="America/Chicago" <?php echo $systemSettings['timezone'] === 'America/Chicago' ? 'selected' : ''; ?>>Central Time (CT)</option>
                                                        <option value="America/Denver" <?php echo $systemSettings['timezone'] === 'America/Denver' ? 'selected' : ''; ?>>Mountain Time (MT)</option>
                                                        <option value="America/Los_Angeles" <?php echo $systemSettings['timezone'] === 'America/Los_Angeles' ? 'selected' : ''; ?>>Pacific Time (PT)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="dateFormat" class="form-label">Date Format</label>
                                                    <select class="form-select" id="dateFormat" name="date_format">
                                                        <option value="MM/DD/YYYY" <?php echo $systemSettings['date_format'] === 'MM/DD/YYYY' ? 'selected' : ''; ?>>MM/DD/YYYY</option>
                                                        <option value="DD/MM/YYYY" <?php echo $systemSettings['date_format'] === 'DD/MM/YYYY' ? 'selected' : ''; ?>>DD/MM/YYYY</option>
                                                        <option value="YYYY-MM-DD" <?php echo $systemSettings['date_format'] === 'YYYY-MM-DD' ? 'selected' : ''; ?>>YYYY-MM-DD</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="timeFormat" class="form-label">Time Format</label>
                                                    <select class="form-select" id="timeFormat" name="time_format">
                                                        <option value="12h" <?php echo $systemSettings['time_format'] === '12h' ? 'selected' : ''; ?>>12-hour (AM/PM)</option>
                                                        <option value="24h" <?php echo $systemSettings['time_format'] === '24h' ? 'selected' : ''; ?>>24-hour</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-4">
                                                <button type="submit" name="update_system_settings" class="btn btn-primary">
                                                    <i class="bi bi-save me-1"></i> Save General Settings
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <!-- Notification Settings Tab -->
                                    <div class="tab-pane fade" id="notification" role="tabpanel" aria-labelledby="notification-tab">
                                        <form id="notificationSettingsForm" method="post">
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="enableNotifications" name="enable_notifications" <?php echo $systemSettings['enable_notifications'] ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="enableNotifications">Enable System Notifications</label>
                                                </div>
                                                <small class="text-muted">Enable or disable all system notifications</small>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="enableSMS" name="enable_sms" <?php echo $systemSettings['enable_sms'] ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="enableSMS">Enable SMS Notifications</label>
                                                </div>
                                                <small class="text-muted">Send SMS notifications for appointments and important updates</small>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="enableEmail" name="enable_email" <?php echo $systemSettings['enable_email'] ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="enableEmail">Enable Email Notifications</label>
                                                </div>
                                                <small class="text-muted">Send email notifications for appointments and important updates</small>
                                            </div>
                                            
                                            <div class="mt-4">
                                                <button type="submit" name="update_system_settings" class="btn btn-primary">
                                                    <i class="bi bi-save me-1"></i> Save Notification Settings
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <!-- Cache Settings Tab -->
                                    <div class="tab-pane fade" id="cache" role="tabpanel" aria-labelledby="cache-tab">
                                        <form id="cacheSettingsForm" method="post">
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="enableCache" name="enable_cache" <?php echo $cacheSettings['enable_cache'] ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="enableCache">Enable System Cache</label>
                                                </div>
                                                <small class="text-muted">Enabling cache can improve system performance</small>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="cacheLifetime" class="form-label">Cache Lifetime (seconds)</label>
                                                <input type="number" class="form-control" id="cacheLifetime" name="cache_lifetime" value="<?php echo $cacheSettings['cache_lifetime']; ?>" min="60" step="60">
                                                <small class="text-muted">How long cached items should be stored before refreshing</small>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="clearOnUpdate" name="clear_on_update" <?php echo $cacheSettings['clear_on_update'] ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="clearOnUpdate">Clear Cache on Content Update</label>
                                                </div>
                                                <small class="text-muted">Automatically clear cache when content is updated</small>
                                            </div>
                                            
                                            <div class="mt-4 d-flex">
                                                <button type="submit" name="update_cache_settings" class="btn btn-primary me-2">
                                                    <i class="bi bi-save me-1"></i> Save Cache Settings
                                                </button>
                                                <button type="submit" name="clear_cache" class="btn btn-warning" id="clearCacheBtn">
                                                    <i class="bi bi-trash me-1"></i> Clear Cache
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- System Information Card -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="bi bi-info-circle me-2"></i>System Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
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
                                                    <th>Database Type</th>
                                                    <td>PostgreSQL (Supabase)</td>
                                                </tr>
                                                <tr>
                                                    <th>System Time</th>
                                                    <td><?php echo date('Y-m-d H:i:s'); ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>CMS Version</th>
                                                    <td>1.0.0</td>
                                                </tr>
                                                <tr>
                                                    <th>Last System Update</th>
                                                    <td>2023-07-15</td>
                                                </tr>
                                                <tr>
                                                    <th>Memory Limit</th>
                                                    <td><?php echo ini_get('memory_limit'); ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Upload Max Size</th>
                                                    <td><?php echo ini_get('upload_max_filesize'); ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Settings Toast -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast" id="settingsToast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <strong class="me-auto">Success</strong>
                <small>Just now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Settings updated successfully!
            </div>
        </div>
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="../cache/admin-settings.js"></script>
</body>
</html>