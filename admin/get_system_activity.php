<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and has admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Include database connection and functions
include_once '../includes/db_connection.php';
include_once '../includes/user_functions.php';
include_once '../includes/cache_config.php';

// Set content type to JSON
header('Content-Type: application/json');

// Generate a unique cache key for this data
$cache_key = 'system_activity_data_' . $_SESSION['user_id'];
$activity_data = $cache->get($cache_key);

// If we have cached data, use it
if ($activity_data !== false && is_array($activity_data) && $GLOBALS['CACHE_ENABLED']) {
    echo json_encode(['success' => true, 'activity_data' => $activity_data]);
    exit();
}

// Otherwise, fetch fresh data
try {
    // Get the last 12 months
    $months = [];
    $values = [];
    
    for ($i = 11; $i >= 0; $i--) {
        $month = date('M', strtotime("-$i months"));
        $year = date('Y', strtotime("-$i months"));
        $months[] = "$month $year";
        
        // Get the first day of the month
        $start_date = date('Y-m-01', strtotime("-$i months"));
        // Get the last day of the month
        $end_date = date('Y-m-t', strtotime("-$i months"));
        
        // Query system logs for this month
        $logs_result = cached_supabase_query('system_logs', 'GET', null, [
            'select' => 'count',
            'created_at' => "gte.$start_date,lte.$end_date",
            'count' => 'exact'
        ]);
        
        $values[] = $logs_result['count'] ?? 0;
    }
    
    $activity_data = [
        'labels' => $months,
        'values' => $values
    ];
    
    // Cache the data
    $cache->set($cache_key, $activity_data, 3600); // Cache for 1 hour
    
    echo json_encode(['success' => true, 'activity_data' => $activity_data]);
} catch (Exception $e) {
    error_log("Error fetching system activity data: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error fetching data']);
}
?>