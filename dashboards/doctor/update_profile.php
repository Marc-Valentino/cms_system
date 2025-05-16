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

// Initialize response array
$response = [
    'success' => false,
    'message' => ''
];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    
    // Validate inputs
    if (empty($first_name) || empty($last_name) || empty($email)) {
        $response['message'] = 'First name, last name, and email are required';
    } else {
        // Prepare user data for update
        $user_data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Update user in database
        $result = update_user($_SESSION['user_id'], $user_data);
        
        if (isset($result['error'])) {
            $response['message'] = 'Error updating profile: ' . $result['error'];
        } else {
            // Update session variables
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['email'] = $email;
            $_SESSION['phone'] = $phone;
            $_SESSION['address'] = $address;
            
            $response['success'] = true;
            $response['message'] = 'Profile updated successfully!';
        }
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>