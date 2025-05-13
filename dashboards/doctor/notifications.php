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
    'profile_pic' => '../assets/img/doctor-profile.jpg',
    'role' => 'Doctor - Cardiologist'
];

// Placeholder for notifications - replace with actual data
$notifications = [
    ['type' => 'Lab Result', 'message' => 'New lab results for patient Emily Davis', 'time' => '2 hours ago', 'read' => false],
    ['type' => 'Appointment', 'message' => 'New appointment request from John Smith', 'time' => '3 hours ago', 'read' => false],
    ['type' => 'Reminder', 'message' => 'Follow-up call with John Smith', 'time' => '1 day ago', 'read' => true],
    ['type' => 'System', 'message' => 'System maintenance scheduled for tonight', 'time' => '3 days ago', 'read' => true],
    ['type' => 'Patient Feedback', 'message' => 'New feedback submitted by Linda Williams', 'time' => '4 days ago', 'read' => true],
    ['type' => 'Lab Result', 'message' => 'Lab results available for Thomas Anderson', 'time' => '1 week ago', 'read' => true],
    ['type' => 'Appointment', 'message' => 'Appointment rescheduled by Michael Brown', 'time' => '1 week ago', 'read' => true]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Clinic Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/doctor.css">
    
    <link rel="stylesheet" href="css/notification.css">
    
</head>
<body>
    <div class="dashboard-container">
        <!-- Include the sidebar -->
        <?php include('sidebar.php'); ?>
        
        <div class="main-content">
            <!-- Include the navbar/header -->
            <?php include('navbar.php'); ?>

            <!-- REMOVED DUPLICATED HEADER SECTION -->

            <!-- Loader Container -->
            <div class="loader-container" id="loader">
                <div class="lifeline-loader">
                    <div class="lifeline"></div>
                </div>
            </div>
            
            <!-- Content Container -->
            <div class="content-container" id="content">
                <div class="container-fluid py-4">
                    <!-- Page Title and Filter -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0"><i class="bi bi-bell me-2"></i>Notifications</h4>
                                <div class="notification-filter">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-primary active">All</button>
                                        <button type="button" class="btn btn-outline-primary">Unread</button>
                                        <button type="button" class="btn btn-outline-primary">Read</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notifications List -->
                    <div class="row">
                        <div class="col-12">
                            <div class="notification-list">
                                <!-- Loop through notifications -->
                                <?php foreach ($notifications as $index => $notification): ?>
                                <div class="card notification-card <?php echo $notification['read'] ? '' : 'unread'; ?>" data-notification-id="<?php echo $index; ?>">
                                    <div class="card-body">
                                        <div class="notification-header">
                                            <div class="notification-title">
                                                <?php if ($notification['type'] == 'Lab Result'): ?>
                                                    <i class="bi bi-clipboard2-pulse text-primary"></i>
                                                <?php elseif ($notification['type'] == 'Appointment'): ?>
                                                    <i class="bi bi-calendar-check text-success"></i>
                                                <?php elseif ($notification['type'] == 'Reminder'): ?>
                                                    <i class="bi bi-alarm text-warning"></i>
                                                <?php elseif ($notification['type'] == 'System'): ?>
                                                    <i class="bi bi-gear text-secondary"></i>
                                                <?php elseif ($notification['type'] == 'Patient Feedback'): ?>
                                                    <i class="bi bi-chat-left-text text-info"></i>
                                                <?php else: ?>
                                                    <i class="bi bi-info-circle text-dark"></i>
                                                <?php endif; ?>
                                                <?php echo $notification['type']; ?>
                                            </div>
                                            <div class="notification-time"><?php echo $notification['time']; ?></div>
                                        </div>
                                        <div class="notification-message"><?php echo $notification['message']; ?></div>
                                        <div class="notification-actions">
                                            <?php if (!$notification['read']): ?>
                                            <button class="btn btn-sm btn-outline-primary me-2 mark-read-btn">
                                                <i class="bi bi-check-circle me-1"></i> Mark as Read
                                            </button>
                                            <?php endif; ?>
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                        </div>
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
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Show content after a short delay to simulate loading
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.getElementById('loader').style.display = 'none';
                document.getElementById('content').classList.add('show');
            }, 1000);
            
            // Toggle sidebar on mobile
            document.getElementById('toggle-sidebar').addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('show');
            });
            
            // Mark notification as read
            const markReadBtns = document.querySelectorAll('.mark-read-btn');
            markReadBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const notificationCard = this.closest('.notification-card');
                    notificationCard.classList.remove('unread');
                    this.parentElement.removeChild(this);
                    
                    // Here you would also make an AJAX call to update the notification status in the database
                    console.log('Marked notification as read: ' + notificationCard.dataset.notificationId);
                });
            });
            
            // Notification card click
            const notificationCards = document.querySelectorAll('.notification-card');
            notificationCards.forEach(card => {
                card.addEventListener('click', function() {
                    // Here you would handle the notification click, e.g., navigate to related content
                    console.log('Clicked notification: ' + this.dataset.notificationId);
                });
            });
        });
    </script>
</body>
</html>