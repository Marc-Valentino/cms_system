<?php
session_start();
require_once 'includes/db_connection.php';

// Verify CSRF token
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die('CSRF token validation failed');
}

// Sanitize and validate input
$firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
$lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
$role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
$password = $_POST['password']; // Don't sanitize password before hashing

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Invalid email format';
    header('Location: register.php');
    exit;
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert user into database
try {
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, phone, role, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $firstName, $lastName, $email, $phone, $role, $hashedPassword);
    $stmt->execute();
    
    $_SESSION['success'] = 'Registration successful! You can now log in.';
    header('Location: login.php');
    exit;
} catch (Exception $e) {
    $_SESSION['error'] = 'Registration failed: ' . $e->getMessage();
    header('Location: register.php');
    exit;
}
?>