<?php
// Include necessary files
require_once '../includes/db_connection.php';
require_once '../includes/user_functions.php';

// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    
    // Authenticate user
    $user = authenticate_user($email, $password);
    
    if ($user) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['role_id'] = $user['role_id'];
        
        // Get role name
        $roles = supabase_query('roles', 'GET', null, [
            'select' => 'name',
            'id' => 'eq.' . $user['role_id']
        ]);
        
        if (!empty($roles) && isset($roles[0]['name'])) {
            $_SESSION['role'] = $roles[0]['name'];
        }
        
        $response['success'] = true;
        $response['message'] = 'Login successful!';
        $response['redirect'] = '../dashboards/doctor/doctor.php';
    } else {
        $response['message'] = 'Invalid email or password';
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>