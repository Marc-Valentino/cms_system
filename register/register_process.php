<?php
// Include necessary files
require_once '../includes/db_connection.php';
require_once '../includes/user_functions.php';

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'errors' => []
];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    
    // Validate inputs
    $errors = [];
    
    if (empty($firstName)) {
        $errors['firstName'] = 'First name is required';
    }
    
    if (empty($lastName)) {
        $errors['lastName'] = 'Last name is required';
    }
    
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }
    
    if (empty($phone)) {
        $errors['phone'] = 'Phone number is required';
    }
    
    if (empty($role)) {
        $errors['role'] = 'Role is required';
    }
    
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters';
    }
    
    if ($password !== $confirmPassword) {
        $errors['confirmPassword'] = 'Passwords do not match';
    }
    
    // If no errors, proceed with registration
    if (empty($errors)) {
        // Get role ID based on role name
        $roleId = get_role_id_by_name($role);
        
        if (!$roleId) {
            $response['message'] = 'Invalid role selected';
            echo json_encode($response);
            exit;
        }
        
        // Generate username from email (before @ symbol)
        $username = explode('@', $email)[0];
        
        // Check if username exists, append numbers if needed
        $baseUsername = $username;
        $counter = 1;
        while (username_exists($username)) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        // Prepare user data
        $userData = [
            'email' => $email,
            'username' => $username,
            'password_hash' => $passwordHash,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'role_id' => $roleId,
            'phone' => $phone
        ];
        
        // Log the data being sent
        error_log("Attempting to create user with data: " . json_encode($userData));
        
        // Create user
        $result = create_user($userData);
        
        // Check for error in result
        if (isset($result['error'])) {
            $response['message'] = 'Registration failed: ' . $result['error'];
            echo json_encode($response);
            exit;
        }
        
        if ($result && isset($result[0]['id'])) {
            // Create role-specific profile if needed
            if ($role === 'doctor' || $role === 'nurse') {
                $profileData = [
                    'user_id' => $result[0]['id']
                ];
                
                if ($role === 'doctor') {
                    $profileData['specialty'] = 'General';
                    $profileData['license_number'] = 'TBD';
                    create_doctor_profile($profileData);
                } else {
                    $profileData['department'] = 'General';
                    $profileData['license_number'] = 'TBD';
                    create_nurse_profile($profileData);
                }
            }
            
            $response['success'] = true;
            $response['message'] = 'Registration successful! You can now login.';
        } else {
            // Log the actual response for debugging
            error_log("Failed to create user. Supabase response: " . json_encode($result));
            $response['message'] = 'Failed to create user. Please try again.';
        }
    } else {
        $response['errors'] = $errors;
        $response['message'] = 'Please fix the errors and try again.';
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>