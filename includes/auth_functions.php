<?php
require_once 'db_connection.php';

// Function to register a new user
function register_user($email, $password, $user_data) {
    global $supabase_url, $supabase_key;
    
    // First, create the auth user
    $auth_url = $supabase_url . '/auth/v1/signup';
    $headers = [
        'apikey: ' . $supabase_key,
        'Content-Type: application/json'
    ];
    
    $auth_data = [
        'email' => $email,
        'password' => $password
    ];
    
    $ch = curl_init($auth_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($auth_data));
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return ['error' => $error];
    }
    
    $auth_response = json_decode($response, true);
    
    // Handle error responses
    if ($http_code >= 400) {
        return ['error' => $auth_response['message'] ?? 'Unknown error', 'status' => $http_code];
    }
    
    if (isset($auth_response['id'])) {
        // Now create the user profile in the users table
        $user_data['id'] = $auth_response['id'];
        return supabase_query('users', 'POST', $user_data);
    }
    
    return $auth_response;
}

// Function to login a user
function login_user($email, $password) {
    global $supabase_url, $supabase_key;
    
    $auth_url = $supabase_url . '/auth/v1/token?grant_type=password';
    $headers = [
        'apikey: ' . $supabase_key,
        'Content-Type: application/json'
    ];
    
    $auth_data = [
        'email' => $email,
        'password' => $password
    ];
    
    $ch = curl_init($auth_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($auth_data));
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return ['error' => $error];
    }
    
    $auth_response = json_decode($response, true);
    
    // Handle error responses
    if ($http_code >= 400) {
        return ['error' => $auth_response['message'] ?? 'Unknown error', 'status' => $http_code];
    }
    
    return $auth_response;
}

// Function to get user data by ID
function get_user_by_id($user_id) {
    return supabase_query('users', 'GET', null, ['select' => '*', 'id' => 'eq.' . $user_id]);
}

// Function to get user data by email
function get_user_by_email($email) {
    return supabase_query('users', 'GET', null, ['select' => '*', 'email' => 'eq.' . $email]);
}

// Function to update user data
function update_user($user_id, $user_data) {
    return supabase_query('users', 'PATCH', $user_data, ['id' => 'eq.' . $user_id]);
}

// Function to logout a user
function logout_user($access_token) {
    global $supabase_url, $supabase_key;
    
    $auth_url = $supabase_url . '/auth/v1/logout';
    $headers = [
        'apikey: ' . $supabase_key,
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json'
    ];
    
    $ch = curl_init($auth_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return ['error' => $error];
    }
    
    if ($http_code >= 400) {
        $result = json_decode($response, true);
        return ['error' => $result['message'] ?? 'Unknown error', 'status' => $http_code];
    }
    
    return ['success' => true];
}

// Function to reset password
function reset_password($email) {
    global $supabase_url, $supabase_key;
    
    $auth_url = $supabase_url . '/auth/v1/recover';
    $headers = [
        'apikey: ' . $supabase_key,
        'Content-Type: application/json'
    ];
    
    $auth_data = [
        'email' => $email
    ];
    
    $ch = curl_init($auth_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($auth_data));
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return ['error' => $error];
    }
    
    if ($http_code >= 400) {
        $result = json_decode($response, true);
        return ['error' => $result['message'] ?? 'Unknown error', 'status' => $http_code];
    }
    
    return ['success' => true];
}

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user']) && isset($_SESSION['access_token']);
}

// Function to get current user data
function get_current_user() {
    return $_SESSION['user'] ?? null;
}

// Function to get current user role
function get_user_role() {
    $user = get_current_user();
    return $user['role_id'] ?? null;
}

// Function to check if user has specific role
function has_role($role_id) {
    $user_role = get_user_role();
    return $user_role == $role_id;
}

// Function to verify password
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}
?>