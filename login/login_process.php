<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection and user functions
require_once '../includes/db_connection.php';
require_once '../includes/user_functions.php';

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'redirect' => ''
];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate inputs
    if (empty($email) || empty($password)) {
        $response['message'] = 'Email and password are required';
        echo json_encode($response);
        exit;
    }
    
    try {
        // Debug
        error_log("Login attempt: Email = $email, Password length = " . strlen($password));
        
        // Use the authenticate_user function
        $user = authenticate_user($email, $password);
        
        // Debug the user result
        error_log("Authentication result: " . ($user ? "Success" : "Failed"));
        if ($user) {
            error_log("User data: " . json_encode($user));
        }
        
        if ($user) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'] ?? '';
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'] ?? '';
            $_SESSION['last_name'] = $user['last_name'] ?? '';
            $_SESSION['role'] = (int)$user['role']; // Ensure role is an integer
            
            error_log("User authenticated successfully. Role ID: " . $_SESSION['role'] . " (Type: " . gettype($_SESSION['role']) . ")");
            
            // Redirect based on role - use == instead of === for comparison
            if ($_SESSION['role'] == 1) {
                $response['redirect'] = '../dashboards/doctor/doctor.php';
                error_log("Redirecting to doctor dashboard");
            } elseif ($_SESSION['role'] == 2) {
                $response['redirect'] = '../dashboards/nurse/nurse.php';
                error_log("Redirecting to nurse dashboard");
            } elseif ($_SESSION['role'] == 3) {
                $response['redirect'] = '../admin/admin.php';
                error_log("Redirecting to admin dashboard");
            } else {
                $response['redirect'] = '../index.php';
                error_log("Unknown role: " . $_SESSION['role'] . ", redirecting to index");
            }
            
            $response['success'] = true;
            $response['message'] = 'Login successful!';
        } else {
            error_log("Authentication failed for user: $email");
            $response['message'] = 'Invalid email or password';
        }
    } catch (Exception $e) {
        error_log("Exception during login: " . $e->getMessage());
        $response['message'] = 'An error occurred during login. Please try again.';
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>