<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and has nurse role
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'nurse') {
//     header("Location: ../login.php");
//     exit();
// }

// Sample notification data - in a real application, this would come from a database
$notifications = [
    [
        'id' => 1,
        'type' => 'vitals',
        'title' => 'New Vitals Submitted',
        'message' => 'Patient John Doe has submitted new vital readings.',
        'time' => '10 minutes ago',
        'read' => false,
        'icon' => 'bi-heart-pulse'
    ],
    [
        'id' => 2,
        'type' => 'medication',
        'title' => 'Medication Reminder',
        'message' => 'It\'s time to administer insulin to patient in Room 302.',
        'time' => '30 minutes ago',
        'read' => false,
        'icon' => 'bi-capsule'
    ],
    [
        'id' => 3,
        'type' => 'admin',
        'title' => 'Shift Announcement',
        'message' => 'There will be a staff meeting tomorrow at 9:00 AM.',
        'time' => '1 hour ago',
        'read' => true,
        'icon' => 'bi-info-circle'
    ],
    [
        'id' => 4,
        'type' => 'vitals',
        'title' => 'Abnormal Vital Signs',
        'message' => 'Patient in Room 205 has elevated blood pressure readings.',
        'time' => '2 hours ago',
        'read' => true,
        'icon' => 'bi-exclamation-triangle'
    ],
    [
        'id' => 5,
        'type' => 'medication',
        'title' => 'Medication Schedule Updated',
        'message' => 'Dr. Smith has updated the medication schedule for patient Emily Johnson.',
        'time' => '3 hours ago',
        'read' => true,
        'icon' => 'bi-calendar-check'
    ]
];

// Count unread notifications
$unread_count = 0;
foreach ($notifications as $notification) {
    if (!$notification['read']) {
        $unread_count++;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse Notifications - Clinic Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/doctor.css">
    <link rel="stylesheet" href="nurse.css">
    <link rel="stylesheet" href="nurse-notification.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Include the sidebar -->
        <?php include('nurse-sidebar.php'); ?>
        
        <div class="main-content">
            <!-- Include the navbar/header -->
            <?php include('navbar.php'); ?>

            <!-- Main Content -->
            <div class="container-fluid py-4">
                <div class="row mb-4">
                    <div class="col-12">
                        <h4 class="mb-0">
                            Notifications
                        </h4>
                    </div>
                </div>
                
                <!-- Notification Demo Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Notification Panel Demo</h5>
                                <p class="mb-4">Click the button below to see the notification panel with heartbeat loader animation:</p>
                                
                                <div class="position-relative d-inline-block">
                                    <button id="demoNotificationBtn" class="btn btn-primary">
                                        <i class="bi bi-bell me-2"></i>Show Notifications
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            <?php echo $unread_count; ?>
                                        </span>
                                    </button>
                                    
                                    <!-- Nurse Notification Panel -->
                                    <div class="nurse-notification-panel" id="nurseNotificationPanel">
                                        <div class="nurse-notification-header">
                                            <h6 class="mb-0">Notifications</h6>
                                            <span class="mark-all-read">Mark all as read</span>
                                        </div>
                                        
                                        <!-- Heartbeat Loader -->
                                        <div class="heartbeat-loader" id="heartbeatLoader">
                                            <div class="heartbeat-line">
                                                <svg viewBox="0 0 600 100" preserveAspectRatio="none">
                                                    <path d="M0,50 L100,50 L120,30 L140,70 L160,30 L180,70 L200,30 L220,70 L240,50 L300,50 L320,30 L340,70 L360,30 L380,70 L400,30 L420,70 L440,50 L500,50 L520,30 L540,70 L560,30 L580,70 L600,50" />
                                                </svg>
                                            </div>
                                        </div>
                                        
                                        <!-- Notification Content -->
                                        <div class="nurse-notification-body" id="notificationContent" style="display: none;">
                                            <?php foreach ($notifications as $notification): ?>
                                                <div class="notification-item <?php echo !$notification['read'] ? 'unread' : ''; ?>">
                                                    <div class="notification-icon <?php echo $notification['type']; ?>">
                                                        <i class="bi <?php echo $notification['icon']; ?>"></i>
                                                    </div>
                                                    <div class="notification-content">
                                                        <div class="notification-title"><?php echo $notification['title']; ?></div>
                                                        <div class="notification-text"><?php echo $notification['message']; ?></div>
                                                        <div class="notification-time"><?php echo $notification['time']; ?></div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        
                                        <div class="nurse-notification-footer">
                                            <a href="notifications.php">View all notifications</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- All Notifications Section -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">All Notifications</h5>
                                
                                <!-- Filter Options -->
                                <div class="mb-4">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-primary active">All</button>
                                        <button type="button" class="btn btn-outline-primary">Unread</button>
                                        <button type="button" class="btn btn-outline-primary">Vitals</button>
                                        <button type="button" class="btn btn-outline-primary">Medications</button>
                                        <button type="button" class="btn btn-outline-primary">Admin</button>
                                    </div>
                                </div>
                                
                                <!-- Notifications List -->
                                <div class="list-group">
                                    <?php foreach ($notifications as $notification): ?>
                                        <div class="list-group-item list-group-item-action <?php echo !$notification['read'] ? 'list-group-item-light' : ''; ?>">
                                            <div class="d-flex w-100 align-items-center">
                                                <div class="notification-icon <?php echo $notification['type']; ?> me-3">
                                                    <i class="bi <?php echo $notification['icon']; ?>"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <h6 class="mb-1"><?php echo $notification['title']; ?></h6>
                                                        <small><?php echo $notification['time']; ?></small>
                                                    </div>
                                                    <p class="mb-1"><?php echo $notification['message']; ?></p>
                                                </div>
                                                <?php if (!$notification['read']): ?>
                                                    <span class="badge bg-primary rounded-pill ms-2">New</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
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
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Notification panel toggle
            const demoNotificationBtn = document.getElementById('demoNotificationBtn');
            const nurseNotificationPanel = document.getElementById('nurseNotificationPanel');
            const heartbeatLoader = document.getElementById('heartbeatLoader');
            const notificationContent = document.getElementById('notificationContent');
            
            demoNotificationBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                nurseNotificationPanel.classList.toggle('show');
                
                // If panel is shown, simulate loading with heartbeat animation
                if (nurseNotificationPanel.classList.contains('show')) {
                    heartbeatLoader.style.display = 'flex';
                    notificationContent.style.display = 'none';
                    
                    // After 0.8 seconds, hide loader and show content
                    setTimeout(function() {
                        heartbeatLoader.style.display = 'none';
                        notificationContent.style.display = 'block';
                    }, 800);
                }
            });
            
            // Close notification panel when clicking outside
            document.addEventListener('click', function(e) {
                if (!nurseNotificationPanel.contains(e.target) && e.target !== demoNotificationBtn) {
                    nurseNotificationPanel.classList.remove('show');
                }
            });
            
            // Mark all as read functionality
            const markAllReadBtn = document.querySelector('.mark-all-read');
            markAllReadBtn.addEventListener('click', function() {
                const unreadItems = document.querySelectorAll('.notification-item.unread');
                unreadItems.forEach(item => {
                    item.classList.remove('unread');
                });
                
                // Update badge count (in a real app, this would also update the database)
                const badge = document.querySelector('.badge');
                badge.textContent = '0';
            });
            
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>