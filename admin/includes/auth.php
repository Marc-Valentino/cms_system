<?php
// ... existing code ...

/**
 * Check if the current user is an admin
 * 
 * @return bool True if user is logged in and has admin role, false otherwise
 */
function is_admin() {
    // Check if session is started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check if user is logged in and has admin role (role_id = 3)
    return isset($_SESSION['user_id']) && isset($_SESSION['role_id']) && $_SESSION['role_id'] == 3;
}

// ... existing code ...