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
    <link rel="stylesheet" href="styles.css">
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
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Filter buttons functionality
            const filterButtons = document.querySelectorAll('.btn-group .btn');
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    // Get filter type
                    const filterType = this.textContent.trim().toLowerCase();
                    
                    // Filter notifications
                    const notificationItems = document.querySelectorAll('.list-group-item');
                    notificationItems.forEach(item => {
                        if (filterType === 'all') {
                            item.style.display = 'block';
                        } else if (filterType === 'unread') {
                            if (item.classList.contains('list-group-item-light')) {
                                item.style.display = 'block';
                            } else {
                                item.style.display = 'none';
                            }
                        } else {
                            // Filter by notification type
                            const iconDiv = item.querySelector('.notification-icon');
                            if (iconDiv && iconDiv.classList.contains(filterType)) {
                                item.style.display = 'block';
                            } else {
                                item.style.display = 'none';
                            }
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>