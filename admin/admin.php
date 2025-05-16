<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and has admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    header("Location: ../login/login.php");
    exit();
}

// Include database connection and functions
include_once '../includes/db_connection.php';
include_once '../includes/user_functions.php';
include_once '../includes/cache_config.php';

// Clear cache if refresh button was clicked
if (isset($_GET['refresh']) && $_GET['refresh'] == 'true') {
    $cache->clear();
    header("Location: admin.php");
    exit();
}

// Generate a unique cache key for this admin's dashboard
$cache_key = 'admin_dashboard_' . $_SESSION['user_id'];
$dashboard_data = $cache->get($cache_key);

// If we have cached data, use it
if ($dashboard_data !== false && is_array($dashboard_data) && $GLOBALS['CACHE_ENABLED']) {
    extract($dashboard_data);
} else {
    // Otherwise, fetch fresh data
    
    // Get system statistics
    $total_users = 0;
    $total_doctors = 0;
    $total_nurses = 0;
    $total_patients = 0;

    // Get user counts from database
    try {
        // Total users - simplified query
        $users_result = supabase_query('users', 'GET');
        $total_users = is_array($users_result) ? count($users_result) : 0;
        
        // Doctors (role_id = 1)
        $doctors_result = supabase_query('users', 'GET', null, [
            'role_id' => 'eq.1'
        ]);
        $total_doctors = is_array($doctors_result) ? count($doctors_result) : 0;
        
        // Nurses (role_id = 2)
        $nurses_result = supabase_query('users', 'GET', null, [
            'role_id' => 'eq.2'
        ]);
        $total_nurses = is_array($nurses_result) ? count($nurses_result) : 0;
        
        // Patients
        $patients_result = supabase_query('patients', 'GET');
        $total_patients = is_array($patients_result) ? count($patients_result) : 0;
        
        // Log the results for debugging
        error_log("Admin Dashboard - Users: $total_users, Doctors: $total_doctors, Nurses: $total_nurses, Patients: $total_patients");
        
    } catch (Exception $e) {
        // Handle error
        error_log("Error fetching statistics: " . $e->getMessage());
    }

    // Get recent system activities
    $recent_activities = supabase_query('system_logs', 'GET', null, [
        'select' => 'id,user_id,action,created_at',
        'order' => 'created_at.desc',
        'limit' => 5
    ]) ?: [];

    // Get system activity data for chart - specifically user logins
    $login_data = supabase_query('system_logs', 'GET', null, [
        'select' => 'created_at,action',
        'order' => 'created_at.asc',
        'limit' => 1000,
        'action' => 'ilike.%login%'
    ]) ?: [];

    // Process data for chart
    $chart_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $chart_values = array_fill(0, 12, 0); // Initialize with zeros

    // Count login activities per month
    foreach ($login_data as $activity) {
        if (isset($activity['created_at'])) {
            $month = date('n', strtotime($activity['created_at'])) - 1; // 0-based index
            $chart_values[$month]++;
        }
    }

    // If no data, provide sample data for demonstration
    if (array_sum($chart_values) == 0) {
        $chart_values = [65, 59, 80, 81, 56, 55, 40, 45, 60, 70, 75, 80];
    }

    // Add to dashboard data
    $dashboard_data['chart_data'] = [
        'labels' => $chart_labels,
        'values' => $chart_values
    ];

    // Cache the dashboard data
    if (isset($cache) && isset($cache_key)) {
        $cache->set($cache_key, $dashboard_data);
    }
}
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
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Include the sidebar -->
        <?php include('admin-sidebar.php'); ?>
        
        <div class="main-content">
            <!-- Include the navbar/header -->
            <?php include('admin-navbar.php'); ?>
            
            <div class="container-fluid p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="mb-0">Admin Dashboard</h1>
                    <a href="admin.php?refresh=true" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-clockwise me-1"></i> Refresh Data
                    </a>
                </div>
                
                <!-- Stats Cards Row -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stats-card primary h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="stats-card-title">Total Users</div>
                                        <div class="stats-card-value"><?php echo $total_users; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-people stats-card-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stats-card success h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="stats-card-title">Doctors</div>
                                        <div class="stats-card-value"><?php echo $total_doctors; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-clipboard2-pulse stats-card-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stats-card warning h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="stats-card-title">Nurses</div>
                                        <div class="stats-card-value"><?php echo $total_nurses; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-heart-pulse stats-card-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stats-card danger h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="stats-card-title">Patients</div>
                                        <div class="stats-card-value"><?php echo $total_patients; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-person-vcard stats-card-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Charts Row -->
                <div class="row mb-4">
                    <div class="col-lg-8 mb-4">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">System Overview</h5>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="chartTimeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        This Month
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="chartTimeDropdown">
                                        <li><a class="dropdown-item" href="#">Today</a></li>
                                        <li><a class="dropdown-item" href="#">This Week</a></li>
                                        <li><a class="dropdown-item" href="#">This Month</a></li>
                                        <li><a class="dropdown-item" href="#">This Year</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="systemOverviewChart"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 mb-4">
                        <!-- Recent Activities Card -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Recent Activities</h5>
                            </div>
                            <div class="card-body">
                                <div class="activity-list">
                                    <?php if (!empty($dashboard_data['recent_activities'])): ?>
                                        <?php foreach ($dashboard_data['recent_activities'] as $activity): ?>
                                            <div class="activity-item">
                                                <div class="activity-icon bg-info">
                                                    <?php 
                                                    // Choose icon based on action type
                                                    $icon = 'bi-activity';
                                                    if (isset($activity['action'])) {
                                                        if (strpos($activity['action'], 'login') !== false) {
                                                            $icon = 'bi-box-arrow-in-right';
                                                        } elseif (strpos($activity['action'], 'create') !== false) {
                                                            $icon = 'bi-plus-circle';
                                                        } elseif (strpos($activity['action'], 'update') !== false) {
                                                            $icon = 'bi-pencil-square';
                                                        } elseif (strpos($activity['action'], 'delete') !== false) {
                                                            $icon = 'bi-trash';
                                                        }
                                                    }
                                                    ?>
                                                    <i class="bi <?php echo $icon; ?>"></i>
                                                </div>
                                                <div class="activity-content">
                                                    <p class="mb-0">
                                                        <?php 
                                                        // Format the action to be more readable
                                                        $action_text = isset($activity['action']) ? $activity['action'] : 'System activity';
                                                        
                                                        // Make it more user-friendly
                                                        $action_text = str_replace('_', ' ', $action_text);
                                                        $action_text = ucfirst($action_text);
                                                        
                                                        echo htmlspecialchars($action_text);
                                                        
                                                        if (isset($activity['user_id'])) {
                                                            // Try to get user name instead of just ID
                                                            $user_name = "User #" . htmlspecialchars($activity['user_id']);
                                                            
                                                            // Optionally, you could fetch the user's name from the database
                                                            // $user = supabase_query('users', 'GET', $activity['user_id']);
                                                            // if ($user && isset($user['first_name'])) {
                                                            //     $user_name = htmlspecialchars($user['first_name'] . ' ' . $user['last_name']);
                                                            // }
                                                            
                                                            echo ' by ' . $user_name;
                                                        }
                                                        ?>
                                                    </p>
                                                    <small class="text-muted">
                                                        <?php 
                                                        if (isset($activity['created_at'])) {
                                                            $timestamp = strtotime($activity['created_at']);
                                                            $now = time();
                                                            $diff = $now - $timestamp;
                                                            
                                                            if ($diff < 60) {
                                                                echo 'Just now';
                                                            } elseif ($diff < 3600) {
                                                                echo floor($diff / 60) . ' minutes ago';
                                                            } elseif ($diff < 86400) {
                                                                echo floor($diff / 3600) . ' hours ago';
                                                            } else {
                                                                echo date('M d, Y h:i A', $timestamp);
                                                            }
                                                        }
                                                        ?>
                                                    </small>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="text-center text-muted">No recent activities found</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Remove the old Recent Activities section that was here -->
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Admin Dashboard JS -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle refresh button click
        const refreshBtn = document.getElementById('refreshDashboard');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                // Show loading state
                refreshBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Refreshing...';
                refreshBtn.disabled = true;
                
                // Send AJAX request to clear cache
                fetch('clear_cache.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'clear_dashboard_cache',
                        key: 'admin_dashboard_<?php echo $_SESSION['user_id']; ?>'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload the page to show fresh data
                        window.location.reload();
                    } else {
                        alert('Failed to refresh data: ' + data.message);
                        refreshBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i> Refresh Data';
                        refreshBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while refreshing data');
                    refreshBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i> Refresh Data';
                    refreshBtn.disabled = false;
                });
            });
        }
        
        // Initialize charts
        initializeCharts();
    });
    
    function initializeCharts() {
        // System Overview Chart
        const systemCtx = document.getElementById('systemOverviewChart');
        if (systemCtx) {
            new Chart(systemCtx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($dashboard_data['chart_data']['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']); ?>,
                    datasets: [{
                        label: 'Total Users',
                        data: <?php echo json_encode($dashboard_data['chart_data']['values'] ?? [3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3]); ?>,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        tension: 0.3,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: '#0d6efd'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'center'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false
                            }
                        }
                    }
                }
            });
        }
        
        // User Distribution Chart
        const userCtx = document.getElementById('userDistributionChart');
        if (userCtx) {
            new Chart(userCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Doctors', 'Nurses', 'Patients', 'Admins'],
                    datasets: [{
                        data: [
                            <?php echo $total_doctors; ?>, 
                            <?php echo $total_nurses; ?>, 
                            <?php echo $total_patients; ?>, 
                            <?php echo $total_users - $total_doctors - $total_nurses; ?>
                        ],
                        backgroundColor: [
                            '#198754',  // success
                            '#ffc107',  // warning
                            '#dc3545',  // danger
                            '#0d6efd'   // primary
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });
        }
    }
    
    // Handle chart time filter dropdown
    const chartTimeDropdown = document.querySelectorAll('#chartTimeDropdown + .dropdown-menu .dropdown-item');
    if (chartTimeDropdown.length > 0) {
        chartTimeDropdown.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const timeFrame = this.textContent.trim();
                document.getElementById('chartTimeDropdown').textContent = timeFrame;
                
                // Update chart based on selected time frame
                let url = 'ajax/get_chart_data.php?timeframe=' + encodeURIComponent(timeFrame.toLowerCase());
                
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.labels && data.values) {
                            // Update chart
                            const chart = Chart.getChart(systemCtx);
                            if (chart) {
                                chart.data.labels = data.labels;
                                chart.data.datasets[0].data = data.values;
                                chart.update();
                            }
                        }
                    })
                    .catch(error => console.error('Error fetching chart data:', error));
            });
        });
    }
    </script>
</body>
</html>

// Get user count data for chart
$user_data = supabase_query('users', 'GET', null, [
    'select' => 'id,created_at',
    'order' => 'created_at.asc'
]) ?: [];

// Process data for chart - track user growth over time
$chart_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$chart_values = array_fill(0, 12, 0); // Initialize with zeros

// Count cumulative users per month
$user_count = 0;
foreach ($user_data as $user) {
    if (isset($user['created_at'])) {
        $month = date('n', strtotime($user['created_at'])) - 1; // 0-based index
        $user_count++;
        
        // Update all months from user creation to current
        $current_month = date('n') - 1;
        $current_year = date('Y');
        $user_year = date('Y', strtotime($user['created_at']));
        
        // Only count for current year
        if ($user_year == $current_year) {
            for ($i = $month; $i <= $current_month; $i++) {
                $chart_values[$i] = max($chart_values[$i], $user_count);
            }
        } else if ($user_year < $current_year) {
            // For users from previous years, count them in all months
            for ($i = 0; $i <= $current_month; $i++) {
                $chart_values[$i] = max($chart_values[$i], $user_count);
            }
        }
    }
}

// If no data, provide sample data for demonstration
if (array_sum($chart_values) == 0) {
    $chart_values = [3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3]; // Show constant 3 users
}

// Add to dashboard data
$dashboard_data['chart_data'] = [
    'labels' => $chart_labels,
    'values' => $chart_values
];
