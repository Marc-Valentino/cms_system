<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
require_once '../../includes/db_connection.php';
require_once '../../includes/user_functions.php';

// Check if user is logged in and has nurse role
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: ../../login/login.php");
    exit();
}

// Get nurse information
$nurse_id = $_SESSION['user_id'];

// Use Supabase query instead of direct MySQL
$nurse_info = supabase_query('users', 'GET', null, [
    'select' => '*',
    'id' => 'eq.' . $nurse_id
]);

// Get the first result
$nurse = $nurse_info[0] ?? null;

// Get current date and time
$current_date = date('Y-m-d');
$current_time = date('H:i:s');
$current_datetime = date('Y-m-d H:i:s');
$day_of_week = date('l');

// Initialize counters with real data from database
$assigned_patients_count = 0;
$medications_today_count = 0;
$vitals_logged_count = 0;

// Get assigned patients count
$patient_assignments = supabase_query('patient_assignments', 'GET', null, [
    'select' => 'count',
    'nurse_id' => 'eq.' . $nurse_id,
    'count' => 'exact'
]);
$assigned_patients_count = $patient_assignments['count'] ?? 0;

// Get medications count for today
$medication_schedules = supabase_query('medication_schedules', 'GET', null, [
    'select' => 'count',
    'nurse_id' => 'eq.' . $nurse_id,
    'scheduled_time' => 'gte.' . $current_date,
    'scheduled_time' => 'lt.' . date('Y-m-d', strtotime('+1 day')),
    'count' => 'exact'
]);
$medications_today_count = $medication_schedules['count'] ?? 0;

// Get vitals logged count for today
$vitals_records = supabase_query('vitals_records', 'GET', null, [
    'select' => 'count',
    'recorded_by' => 'eq.' . $nurse_id,
    'date_time' => 'gte.' . $current_date,
    'date_time' => 'lt.' . date('Y-m-d', strtotime('+1 day')),
    'count' => 'exact'
]);
$vitals_logged_count = $vitals_records['count'] ?? 0;

// Get nurse shift information
$shift_start = "";
$shift_end = "";
$tomorrow_shift_start = "";
$tomorrow_shift_end = "";
$has_shift_today = false;
$has_shift_tomorrow = false;

// Get today's shift
$today_shift = supabase_query('nurse_schedules', 'GET', null, [
    'select' => '*',
    'nurse_id' => 'eq.' . $nurse_id,
    'date' => 'eq.' . $current_date
]);

// Fix for line 85 - Check if today_shift is not null and has elements
if (!empty($today_shift) && isset($today_shift[0]) && is_array($today_shift[0])) {
    $shift_start = date('h:i A', strtotime($today_shift[0]['start_time']));
    $shift_end = date('h:i A', strtotime($today_shift[0]['end_time']));
    $has_shift_today = true;
}

// Get tomorrow's shift
$tomorrow = date('Y-m-d', strtotime('+1 day'));
$tomorrow_shift = supabase_query('nurse_schedules', 'GET', null, [
    'select' => '*',
    'nurse_id' => 'eq.' . $nurse_id,
    'date' => 'eq.' . $tomorrow
]);

// Fix for lines 98-99 - Check if tomorrow_shift is not null and has elements
if (!empty($tomorrow_shift) && isset($tomorrow_shift[0]) && is_array($tomorrow_shift[0])) {
    $tomorrow_shift_start = date('h:i A', strtotime($tomorrow_shift[0]['start_time']));
    $tomorrow_shift_end = date('h:i A', strtotime($tomorrow_shift[0]['end_time']));
    $has_shift_tomorrow = true;
}

// Get appointments for this nurse
$appointments = supabase_query('appointments', 'GET', null, [
    'select' => '*,patients(*)',
    'nurse_id' => 'eq.' . $nurse_id,
    'order' => 'appointment_date.asc,appointment_time.asc'
]);

// Get today's appointments - Fix for line 114 - Use $current_date instead of $today
$today_appointments = supabase_query('appointments', 'GET', null, [
    'select' => '*,patients(*)',
    'nurse_id' => 'eq.' . $nurse_id,
    'appointment_date' => 'eq.' . $current_date,
    'order' => 'appointment_time.asc'
]);

// Get recent patients
$recent_patients = supabase_query('patients', 'GET', null, [
    'select' => '*',
    'order' => 'created_at.desc',
    'limit' => 5
]);

// Get total patients count
$patients_count = supabase_query('patients', 'GET', null, [
    'select' => 'count',
    'count' => 'exact'
]);
$total_patients = $patients_count['count'] ?? 0;

// Get total appointments count
$appointments_count = supabase_query('appointments', 'GET', null, [
    'select' => 'count',
    'nurse_id' => 'eq.' . $nurse_id,
    'count' => 'exact'
]);
$total_appointments = $appointments_count['count'] ?? 0;

// Get completed appointments count
$completed_count = supabase_query('appointments', 'GET', null, [
    'select' => 'count',
    'nurse_id' => 'eq.' . $nurse_id,
    'status' => 'eq.completed',
    'count' => 'exact'
]);
$completed_appointments = $completed_count['count'] ?? 0;
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
    <link rel="stylesheet" href="css/nurse.css">
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
                <h1 class="mb-4">Nurse Dashboard</h1>
                
                <!-- Shift Information -->
                <?php if ($has_shift_today): ?>
                <div class="card mb-4 bg-light border-0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                <i class="bi bi-clock text-primary fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">You're on shift</h5>
                                <h3 class="mb-0"><?php echo $shift_start; ?> — <?php echo $shift_end; ?></h3>
                            </div>
                            <div class="ms-auto text-end">
                                <p class="text-muted mb-0"><?php echo date('l, F j, Y'); ?></p>
                                <h5 class="mb-0"><?php echo date('h:i A'); ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="card mb-4 bg-light border-0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-secondary bg-opacity-10 p-3 me-3">
                                <i class="bi bi-calendar-check text-secondary fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">No shift scheduled for today</h5>
                                <p class="mb-0">Check your schedule for upcoming shifts</p>
                            </div>
                            <div class="ms-auto text-end">
                                <p class="text-muted mb-0"><?php echo date('l, F j, Y'); ?></p>
                                <h5 class="mb-0"><?php echo date('h:i A'); ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Dashboard Stats -->
                <div class="row">
                    <!-- Total Assigned Patients -->
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0">
                            <div class="card-body">
                                <h5 class="card-title">Total Assigned Patients</h5>
                                <div class="d-flex align-items-center">
                                    <h1 class="display-4 mb-0"><?php echo $assigned_patients_count; ?></h1>
                                    <p class="ms-2 mb-0 text-muted">Patients</p>
                                    <div class="ms-auto">
                                        <i class="bi bi-people text-primary opacity-25 fs-1"></i>
                                    </div>
                                </div>
                                <a href="patients.php" class="btn btn-outline-primary btn-sm mt-3">
                                    <i class="bi bi-eye"></i> View All
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Medications Today -->
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0">
                            <div class="card-body">
                                <h5 class="card-title">Medications Today</h5>
                                <div class="d-flex align-items-center">
                                    <h1 class="display-4 mb-0"><?php echo $medications_today_count; ?></h1>
                                    <p class="ms-2 mb-0 text-muted">Tasks</p>
                                    <div class="ms-auto">
                                        <i class="bi bi-capsule text-info opacity-25 fs-1"></i>
                                    </div>
                                </div>
                                <a href="medications.php" class="btn btn-outline-primary btn-sm mt-3">
                                    <i class="bi bi-eye"></i> View All
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Vitals Logged -->
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0">
                            <div class="card-body">
                                <h5 class="card-title">Vitals Logged</h5>
                                <div class="d-flex align-items-center">
                                    <h1 class="display-4 mb-0"><?php echo $vitals_logged_count; ?></h1>
                                    <p class="ms-2 mb-0 text-muted">Records Today</p>
                                    <div class="ms-auto">
                                        <i class="bi bi-heart-pulse text-danger opacity-25 fs-1"></i>
                                    </div>
                                </div>
                                <a href="vitals.php" class="btn btn-outline-primary btn-sm mt-3">
                                    <i class="bi bi-eye"></i> View All
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tomorrow's Shift -->
                <?php if ($has_shift_tomorrow): ?>
                <div class="card mb-4 border-0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                                <i class="bi bi-calendar-check text-success fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Tomorrow's Shift</h5>
                                <h3 class="mb-0"><?php echo $tomorrow_shift_start; ?> — <?php echo $tomorrow_shift_end; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Additional content can be added here -->
                
            </div>
        </div>
    </div>

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
</body>
</html>