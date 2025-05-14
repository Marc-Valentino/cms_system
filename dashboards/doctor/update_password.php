<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Not authorized']);
    exit();
}

// Include database connection and functions
include_once '../../includes/db_connection.php';
include_once '../../includes/user_functions.php';
include_once '../../includes/auth_functions.php';

// Initialize response array
$response = [
    'success' => false,
    'message' => ''
];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get form data
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        // Validate input
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            throw new Exception('All fields are required');
        }
        
        // Check if new password and confirm password match
        if ($new_password !== $confirm_password) {
            throw new Exception('New password and confirm password do not match');
        }
        
        // Validate password strength
        if (strlen($new_password) < 8) {
            throw new Exception('Password must be at least 8 characters long');
        }
        
        // Verify current password
        $user = get_user_by_id($_SESSION['user_id']);
        if (empty($user) || !isset($user[0])) {
            throw new Exception('User not found');
        }
        
        // Verify current password (this would need to be implemented in auth_functions.php)
        $is_valid = verify_password($current_password, $user[0]['password_hash']);
        if (!$is_valid) {
            throw new Exception('Current password is incorrect');
        }
        
        // Hash new password
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update password in database
        $result = update_user($_SESSION['user_id'], ['password_hash' => $password_hash]);
        
        if (isset($result['error'])) {
            throw new Exception($result['error']);
        }
        
        $response['success'] = true;
        $response['message'] = 'Password updated successfully';
        
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
        error_log('Password update error: ' . $e->getMessage());
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>