<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user = [
    'id' => 1,
    'name' => 'Nurse Johnny Sins',
    'profile_pic' => '../assets/img/doctor-profile.jpg',
    'role' => 'Nurse 1'
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
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="nurse-notification.css">
    
</head>
<body>
    <div class="dashboard-container">
        <!-- Include the sidebar -->
        <?php include('nurse-sidebar.php'); ?>
        
        <div class="main-content">
            <?php include('navbar.php'); ?>

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
                                <!-- Lab Result Notification -->
                                <div class="card notification-card unread">
                                    <div class="card-body">
                                        <div class="notification-header">
                                            <div class="notification-title">
                                                <i class="bi bi-clipboard-data text-primary"></i> Lab Result
                                            </div>
                                            <div class="notification-time">2 hours ago</div>
                                        </div>
                                        <div class="notification-message">
                                            New lab results for patient Emily Davis
                                        </div>
                                        <div class="notification-actions">
                                            <button class="btn btn-sm btn-outline-primary">Mark as Read</button>
                                            <button class="btn btn-sm btn-light ms-2"><i class="bi bi-three-dots"></i></button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Appointment Notification -->
                                <div class="card notification-card unread">
                                    <div class="card-body">
                                        <div class="notification-header">
                                            <div class="notification-title">
                                                <i class="bi bi-calendar-check text-success"></i> Appointment
                                            </div>
                                            <div class="notification-time">3 hours ago</div>
                                        </div>
                                        <div class="notification-message">
                                            New appointment request from John Smith
                                        </div>
                                        <div class="notification-actions">
                                            <button class="btn btn-sm btn-outline-primary">Mark as Read</button>
                                            <button class="btn btn-sm btn-light ms-2"><i class="bi bi-three-dots"></i></button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Reminder Notification -->
                                <div class="card notification-card">
                                    <div class="card-body">
                                        <div class="notification-header">
                                            <div class="notification-title">
                                                <i class="bi bi-alarm text-warning"></i> Reminder
                                            </div>
                                            <div class="notification-time">1 day ago</div>
                                        </div>
                                        <div class="notification-message">
                                            Follow-up call with John Smith
                                        </div>
                                        <div class="notification-actions">
                                            <button class="btn btn-sm btn-outline-primary">Mark as Read</button>
                                            <button class="btn btn-sm btn-light ms-2"><i class="bi bi-three-dots"></i></button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- System Notification -->
                                <div class="card notification-card">
                                    <div class="card-body">
                                        <div class="notification-header">
                                            <div class="notification-title">
                                                <i class="bi bi-gear text-secondary"></i> System
                                            </div>
                                            <div class="notification-time">3 days ago</div>
                                        </div>
                                        <div class="notification-message">
                                            System maintenance scheduled for tonight
                                        </div>
                                        <div class="notification-actions">
                                            <button class="btn btn-sm btn-outline-primary">Mark as Read</button>
                                            <button class="btn btn-sm btn-light ms-2"><i class="bi bi-three-dots"></i></button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Patient Feedback Notification -->
                                <div class="card notification-card">
                                    <div class="card-body">
                                        <div class="notification-header">
                                            <div class="notification-title">
                                                <i class="bi bi-chat-square-text text-info"></i> Patient Feedback
                                            </div>
                                            <div class="notification-time">4 days ago</div>
                                        </div>
                                        <div class="notification-message">
                                            New feedback received from patient consultation
                                        </div>
                                        <div class="notification-actions">
                                            <button class="btn btn-sm btn-outline-primary">Mark as Read</button>
                                            <button class="btn btn-sm btn-light ms-2"><i class="bi bi-three-dots"></i></button>
                                        </div>
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
        // Show loader and hide content initially
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('loader');
            const content = document.getElementById('content');
            
            // Show loader
            if (loader) loader.style.display = 'flex';
            if (content) content.style.opacity = '0';
            
            // Hide loader after 1 second and show content
            setTimeout(function() {
                if (loader) loader.style.display = 'none';
                if (content) {
                    content.style.opacity = '1';
                    content.classList.add('show');
                }
            }, 1000);
            
            // Filter buttons functionality
            const filterButtons = document.querySelectorAll('.notification-filter .btn');
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    // Filter notifications based on button text
                    const filterType = this.textContent.trim();
                    const notificationCards = document.querySelectorAll('.notification-card');
                    
                    notificationCards.forEach(card => {
                        if (filterType === 'All') {
                            card.style.display = 'block';
                        } else if (filterType === 'Unread') {
                            card.style.display = card.classList.contains('unread') ? 'block' : 'none';
                        } else if (filterType === 'Read') {
                            card.style.display = !card.classList.contains('unread') ? 'block' : 'none';
                        }
                    });
                });
            });
            
            // Mark as read functionality
            const markAsReadButtons = document.querySelectorAll('.notification-actions .btn-outline-primary');
            markAsReadButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const card = this.closest('.notification-card');
                    card.classList.remove('unread');
                    this.textContent = 'Marked as Read';
                    this.disabled = true;
                });
            });
        });
    </script>
</body>
</html>