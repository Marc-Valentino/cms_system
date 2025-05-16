<?php
session_start();
header('Content-Type: application/json');

// Include database connection
include_once '../includes/db_connection.php';
include_once '../includes/user_functions.php';

// Initialize response array
$response = ['success' => false, 'message' => '', 'redirect' => ''];

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log function for debugging
function logDebug($message) {
    error_log("[LOGIN DEBUG] " . $message);
}

logDebug("Login process started");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'doctor'; // Default to doctor if not specified
    
    // Log received data for debugging
    logDebug("Login attempt - Email: $email, Role: $role");
    
    // Basic validation
    if (empty($email) || empty($password)) {
        $response['message'] = 'Please enter both email and password';
        echo json_encode($response);
        exit;
    }
    
    // Map role names to role_id values
    $role_map = [
        'doctor' => 1,
        'nurse' => 2,
        'admin' => 3
    ];
    
    $role_id = $role_map[$role] ?? 1; // Default to doctor (1) if role not found
    
    try {
        // First try to find the user by email only (without role restriction)
        logDebug("Querying database for user with email: $email");
        
        // Use direct SQL query for debugging
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            logDebug("No user found with email: $email");
            $response['message'] = 'No account found with this email';
            echo json_encode($response);
            exit;
        }
        
        // Get all matching users
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        
        logDebug("Found " . count($users) . " users with email: $email");
        
        // Check if any of the returned users match the role
        $user_found = false;
        $user = null;
        
        foreach ($users as $potential_user) {
            logDebug("Checking user ID: " . $potential_user['id'] . " with role_id: " . $potential_user['role_id']);
            
            // Check if role_id matches
            if ($potential_user['role_id'] == $role_id) {
                $user = $potential_user;
                $user_found = true;
                logDebug("Found matching user with correct role");
                break;
            }
        }
        
        // If no user with matching role was found, use the first user
        if (!$user_found) {
            $user = $users[0];
            logDebug("User found but with different role. Requested: $role_id, Found: {$user['role_id']}");
            $response['message'] = 'Your account has a different role. Please select the correct role.';
            echo json_encode($response);
            exit;
        }
        
        // Debug log for password verification
        logDebug("Attempting to verify password for user: {$user['email']}");
        
        // Verify password
        if (password_verify($password, $user['password_hash'])) {
            logDebug("Password verified successfully");
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['role_id'] = $user['role_id'];
            
            // Set role string based on role_id for backward compatibility
            switch ($user['role_id']) {
                case 1:
                    $_SESSION['role'] = 'doctor';
                    $response['redirect'] = '../dashboards/doctor/doctor.php';
                    break;
                case 2:
                    $_SESSION['role'] = 'nurse';
                    $response['redirect'] = '../dashboards/nurse/nurse.php';
                    break;
                case 3:
                    $_SESSION['role'] = 'admin';
                    $response['redirect'] = '../admin/admin.php';
                    break;
                default:
                    $_SESSION['role'] = 'unknown';
                    $response['redirect'] = '../index.php';
                    break;
            }
            
            // Set additional session variables if available
            if (isset($user['profile_pic_url'])) $_SESSION['profile_pic_url'] = $user['profile_pic_url'];
            if (isset($user['phone'])) $_SESSION['phone'] = $user['phone'];
            if (isset($user['address'])) $_SESSION['address'] = $user['address'];
            if (isset($user['created_at'])) $_SESSION['created_at'] = $user['created_at'];
            if (isset($user['updated_at'])) $_SESSION['updated_at'] = $user['updated_at'];
            
            $response['success'] = true;
            $response['message'] = 'Login successful!';
            
            // Log successful login
            logDebug("User {$user['email']} logged in successfully with role {$_SESSION['role']}");
        } else {
            logDebug("Password verification failed");
            $response['message'] = 'Invalid password';
        }
    } catch (Exception $e) {
        logDebug("Exception during login: " . $e->getMessage());
        $response['message'] = 'An error occurred during login: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Invalid request method';
    logDebug("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
}

logDebug("Login process completed. Response: " . json_encode($response));
echo json_encode($response);
exit;
?>