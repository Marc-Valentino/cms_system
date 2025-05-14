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

// Placeholder admin data - replace with actual data from your database
$admin = [
    'id' => 1,
    'name' => 'Admin User',
    'profile_pic' => '../assets/img/admin-profile.jpg',
    'role' => 'System Administrator'
];

// Placeholder for system overview data - replace with actual data
$systemOverview = [
    'total_patients' => 1250,
    'total_doctors' => 45,
    'total_nurses' => 78,
    'total_receptionists' => 12,
    'appointments_today' => 87,
    'monthly_revenue' => 125000
];

// Placeholder for recent users - replace with actual data
$recentUsers = [
    ['id' => 'U10045', 'name' => 'Dr. Sarah Johnson', 'role' => 'Doctor', 'date_added' => '2023-06-15', 'status' => 'Active'],
    ['id' => 'U10089', 'name' => 'Nurse Linda Williams', 'role' => 'Nurse', 'date_added' => '2023-06-10', 'status' => 'Active'],
    ['id' => 'U10023', 'name' => 'Thomas Anderson', 'role' => 'Receptionist', 'date_added' => '2023-06-05', 'status' => 'Inactive']
];

// Placeholder for system logs - replace with actual data
$systemLogs = [
    ['user' => 'Dr. Sarah Johnson', 'action' => 'Updated patient record', 'timestamp' => '2023-07-15 09:45:22'],
    ['user' => 'Admin User', 'action' => 'Added new staff member', 'timestamp' => '2023-07-15 08:30:15'],
    ['user' => 'System', 'action' => 'Backup completed', 'timestamp' => '2023-07-15 01:00:00']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Clinic Management System</title>
    
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

            <!-- Dashboard Content -->
            <div class="container-fluid py-4">
                <!-- Overview Cards Row -->
                <div class="row mb-4">
                    <!-- Total Patients Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card dashboard-card h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="icon-circle bg-primary">
                                            <i class="bi bi-person"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="card-info">
                                            <h5 class="card-title">Total Patients</h5>
                                            <h2 class="card-value"><?php echo $systemOverview['total_patients']; ?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total Staff Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card dashboard-card h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="icon-circle bg-success">
                                            <i class="bi bi-people"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="card-info">
                                            <h5 class="card-title">Total Staff</h5>
                                            <h2 class="card-value"><?php echo $systemOverview['total_doctors'] + $systemOverview['total_nurses'] ?></h2>
                                            <div class="staff-breakdown">
                                                <small>Doctors: <?php echo $systemOverview['total_doctors']; ?> | Nurses: <?php echo $systemOverview['total_nurses']; ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Appointments Today Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card dashboard-card h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="icon-circle bg-info">
                                            <i class="bi bi-calendar-check"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="card-info">
                                            <h5 class="card-title">Appointments Today</h5>
                                            <h2 class="card-value"><?php echo $systemOverview['appointments_today']; ?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Billing Summary Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card dashboard-card h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="icon-circle bg-warning">
                                            <i class="bi bi-currency-dollar"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="card-info">
                                            <h5 class="card-title">Monthly Revenue</h5>
                                            <h2 class="card-value">$<?php echo number_format($systemOverview['monthly_revenue']); ?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- System Overview Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="bi bi-clipboard-data me-2"></i>System Overview</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="chart-container">
                                            <canvas id="userRoleChart"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="chart-container">
                                            <canvas id="appointmentChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- User Management and System Logs Section -->
                <div class="row">
                    <!-- User Management Section -->
                    <div class="col-lg-7 mb-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5><i class="bi bi-people me-2"></i>User Management</h5>
                                <button class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>Add User
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Role</th>
                                                <th>Date Added</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recentUsers as $user): ?>
                                            <tr>
                                                <td><?php echo $user['id']; ?></td>
                                                <td><?php echo $user['name']; ?></td>
                                                <td><span class="badge bg-<?php echo $user['role'] == 'Doctor' ? 'primary' : ($user['role'] == 'Nurse' ? 'success' : 'info'); ?>"><?php echo $user['role']; ?></span></td>
                                                <td><?php echo $user['date_added']; ?></td>
                                                <td><span class="badge bg-<?php echo $user['status'] == 'Active' ? 'success' : 'danger'; ?>"><?php echo $user['status']; ?></span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-eye"></i></button>
                                                    <button class="btn btn-sm btn-outline-success me-1"><i class="bi bi-pencil"></i></button>
                                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="#" class="btn btn-outline-primary btn-sm">View All Users</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- System Logs Section -->
                    <div class="col-lg-5 mb-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5><i class="bi bi-journal-text me-2"></i>System Logs</h5>
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-download me-1"></i>Export
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="logs-container">
                                    <?php foreach ($systemLogs as $log): ?>
                                    <div class="log-item">
                                        <div class="log-time">
                                            <?php echo date('H:i', strtotime($log['timestamp'])); ?>
                                        </div>
                                        <div class="log-content">
                                            <p class="log-action"><strong><?php echo $log['user']; ?></strong> <?php echo $log['action']; ?></p>
                                            <p class="log-timestamp"><?php echo date('M d, Y H:i:s', strtotime($log['timestamp'])); ?></p>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="#" class="btn btn-outline-primary btn-sm">View All Logs</a>
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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Custom JavaScript -->
<script src="admin.js"></script>
</body>
</html>