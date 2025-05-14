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
    'name' => 'Nurse Johnny Sins',
    'profile_pic' => '../assets/img/doctor-profile.jpg',
    'role' => 'Nurse 1'
];

// Sample data for demonstration
$current_shift = [
    'start_time' => '07:00:00',
    'end_time' => '15:00:00'
];

$next_shift = [
    'date' => '2023-08-16',
    'start_time' => '07:00:00',
    'end_time' => '15:00:00'
];

// Format time function
function formatTime($time) {
    return date('h:i A', strtotime($time));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse Dashboard - Clinic Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="nurse.css">
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
                            Nurse Dashboard
                        </h4>
                    </div>
                </div>
                
                <!-- Current Shift Overview -->
                <div class="card shift-info-card mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="shift-icon-container me-3">
                                        <i class="bi bi-clock"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">You're on shift</h5>
                                        <h3 class="mb-0"><?php echo formatTime($current_shift['start_time']); ?> – <?php echo formatTime($current_shift['end_time']); ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                <div class="current-date" id="currentDateTime">
                                    <div class="current-day mb-1" id="currentDay"></div>
                                    <div class="current-time" id="currentTime"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Summary Cards -->
                <div class="row">
                    <!-- Total Assigned Patients Card -->
                    <div class="col-md-4 mb-4">
                        <div class="card summary-card h-100">
                            <div class="card-body">
                                <div class="summary-icon">
                                    <i class="bi bi-person-lines-fill"></i>
                                </div>
                                <h5 class="card-title">Total Assigned Patients</h5>
                                <h2 class="summary-value">12</h2>
                                <p class="summary-label">Patients</p>
                                <a href="nurse-patients.php" class="btn btn-outline-primary btn-sm mt-2">
                                    <i class="bi bi-eye me-1"></i>View All
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Medications Today Card -->
                    <div class="col-md-4 mb-4">
                        <div class="card summary-card h-100">
                            <div class="card-body">
                                <div class="summary-icon">
                                    <i class="bi bi-capsule"></i>
                                </div>
                                <h5 class="card-title">Medications Today</h5>
                                <h2 class="summary-value">8</h2>
                                <p class="summary-label">Tasks</p>
                                <a href="medications.php" class="btn btn-outline-primary btn-sm mt-2">
                                    <i class="bi bi-eye me-1"></i>View All
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Vitals Logged Card -->
                    <div class="col-md-4 mb-4">
                        <div class="card summary-card h-100">
                            <div class="card-body">
                                <div class="summary-icon">
                                    <i class="bi bi-clipboard-pulse"></i>
                                </div>
                                <h5 class="card-title">Vitals Logged</h5>
                                <h2 class="summary-value">5</h2>
                                <p class="summary-label">Records Today</p>
                                <a href="vitals.php" class="btn btn-outline-primary btn-sm mt-2">
                                    <i class="bi bi-eye me-1"></i>View All
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Next Shift Preview -->
                <div class="card next-shift-card mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="next-shift-icon me-3">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Tomorrow's Shift</h6>
                                <h5 class="mb-0"><?php echo formatTime($next_shift['start_time']); ?> – <?php echo formatTime($next_shift['end_time']); ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container for Notifications -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    trigger: 'hover'
                });
            });
            
            // Update current date and time
            function updateDateTime() {
                const now = new Date();
                const dayElement = document.getElementById('currentDay');
                const timeElement = document.getElementById('currentTime');
                
                dayElement.textContent = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                timeElement.textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            }
            
            // Initial call and set interval
            updateDateTime();
            setInterval(updateDateTime, 60000); // Update every minute
            
            // Add hover animation to cards
            const summaryCards = document.querySelectorAll('.summary-card');
            summaryCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.classList.add('card-hover');
                });
                card.addEventListener('mouseleave', function() {
                    this.classList.remove('card-hover');
                });
            });
        });
    </script>
</body>
</html>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle sidebar on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('toggle-sidebar');
            if (toggleButton) {
                toggleButton.addEventListener('click', function() {
                    document.querySelector('.sidebar').classList.toggle('active');
                    document.querySelector('.main-content').classList.toggle('active');
                });
            }
        });
    </script>