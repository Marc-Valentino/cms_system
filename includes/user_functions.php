<?php
require_once 'db_connection.php';

// Function to get all users
function get_all_users($limit = 100, $offset = 0) {
    return supabase_query('users', 'GET', null, [
        'select' => '*',
        'order' => 'created_at.desc',
        'limit' => $limit,
        'offset' => $offset
    ]);
}

// Function to get a specific user by ID
function get_user_by_id($user_id) {
    return supabase_query('users', 'GET', null, [
        'select' => '*',
        'id' => 'eq.' . $user_id
    ]);
}

// Function to get a user by email
function get_user_by_email($email) {
    return supabase_query('users', 'GET', null, [
        'select' => '*',
        'email' => 'eq.' . $email
    ]);
}

// Function to get a user by username
function get_user_by_username($username) {
    return supabase_query('users', 'GET', null, [
        'select' => '*',
        'username' => 'eq.' . $username
    ]);
}

// Function to check if username exists
function username_exists($username) {
    $result = get_user_by_username($username);
    return !empty($result);
}

// Function to get role ID by name
function get_role_id_by_name($role_name) {
    $result = supabase_query('roles', 'GET', null, [
        'select' => 'id',
        'name' => 'eq.' . $role_name
    ]);
    
    if (!empty($result) && isset($result[0]['id'])) {
        return $result[0]['id'];
    }
    
    return null;
}

// Function to create a new user
function create_user($user_data) {
    // Validate required fields
    $required_fields = ['email', 'username', 'password_hash', 'first_name', 'last_name', 'role_id'];
    foreach ($required_fields as $field) {
        if (!isset($user_data[$field]) || empty($user_data[$field])) {
            error_log("Missing required field: $field");
            return ['error' => "Missing required field: $field"];
        }
    }
    
    // Check if email already exists
    $existing_email = get_user_by_email($user_data['email']);
    if (!empty($existing_email)) {
        error_log("Email already exists: " . $user_data['email']);
        return ['error' => 'Email already exists'];
    }
    
    // Check if username already exists
    $existing_username = get_user_by_username($user_data['username']);
    if (!empty($existing_username)) {
        error_log("Username already exists: " . $user_data['username']);
        return ['error' => 'Username already exists'];
    }
    
    // Make the API call
    $result = supabase_query('users', 'POST', $user_data);
    
    // Log the result for debugging
    error_log("User creation result: " . json_encode($result));
    
    return $result;
}

// Function to update a user
function update_user($user_id, $user_data) {
    return supabase_query('users', 'PATCH', $user_data, [
        'id' => 'eq.' . $user_id
    ]);
}

// Function to delete a user
function delete_user($user_id) {
    return supabase_query('users', 'DELETE', null, [
        'id' => 'eq.' . $user_id
    ]);
}

// Function to create doctor profile
function create_doctor_profile($profile_data) {
    return supabase_query('doctor_profiles', 'POST', $profile_data);
}

// Function to create nurse profile
function create_nurse_profile($profile_data) {
    return supabase_query('nurse_profiles', 'POST', $profile_data);
}
// Function to get role name by ID
function get_role_name_by_id($role_id) {
    $result = supabase_query('roles', 'GET', null, [
        'select' => 'name',
        'id' => 'eq.' . $role_id
    ]);
    
    if (!empty($result) && isset($result[0]['name'])) {
        return $result[0]['name'];
    }
    
    return null;
}

// Function to authenticate user
// Function to authenticate user
function authenticate_user($email, $password) {
    global $conn;
    
    try {
        // Add debugging at the start
        error_log("Attempting to authenticate user: $email");
        
        // Prepare statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, username, email, password_hash, first_name, last_name, role_id FROM users WHERE email = ?");
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            return false;
        }
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // For debugging
            error_log("User found: " . json_encode($user));
            
            // Verify password - only use password_verify for security
            if (password_verify($password, $user['password_hash'])) {
                // Return user data with role mapped correctly
                return [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'role' => $user['role_id']
                ];
            } else {
                error_log("Password verification failed for user: " . $email);
                error_log("Provided password length: " . strlen($password));
                error_log("Stored hash: " . substr($user['password_hash'], 0, 10) . "...");
            }
        } else {
            error_log("No user found with email: " . $email);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        error_log("Exception in authenticate_user: " . $e->getMessage());
    }
    
    return false;
}
?>
