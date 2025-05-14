<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'auth_functions.php';

// Function to set user session
function set_user_session($user_data, $access_token, $refresh_token = null) {
    $_SESSION['user'] = $user_data;
    $_SESSION['access_token'] = $access_token;
    
    if ($refresh_token) {
        $_SESSION['refresh_token'] = $refresh_token;
    }
    
    // Set session expiry time (e.g., 1 hour)
    $_SESSION['expires_at'] = time() + 3600;
}

// Function to clear user session
function clear_user_session() {
    // If there's an access token, try to logout from Supabase
    if (isset($_SESSION['access_token'])) {
        logout_user($_SESSION['access_token']);
    }
    
    // Unset all session variables
    $_SESSION = [];
    
    // If it's desired to kill the session, also delete the session cookie.
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Finally, destroy the session
    session_destroy();
}

// Function to check if session is expired
function is_session_expired() {
    return isset($_SESSION['expires_at']) && $_SESSION['expires_at'] < time();
}

// Function to refresh session
function refresh_session() {
    if (isset($_SESSION['refresh_token'])) {
        // Implement token refresh logic here
        // This would involve calling Supabase to get a new access token using the refresh token
        
        // For now, just extend the session
        $_SESSION['expires_at'] = time() + 3600;
    }
}

// Check if session needs refresh
if (isset($_SESSION['user']) && isset($_SESSION['expires_at'])) {
    // If session is about to expire (less than 5 minutes left), refresh it
    if ($_SESSION['expires_at'] - time() < 300) {
        refresh_session();
    }
    
    // If session is expired, clear it
    if (is_session_expired()) {
        clear_user_session();
    }
}
?>