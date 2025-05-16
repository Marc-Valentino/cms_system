<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Set content type to JSON
header('Content-Type: application/json');

// Connect to database (replace with your actual database connection code)
// $conn = new mysqli($servername, $username, $password, $dbname);

// For demonstration, we'll use sample data
// In a real implementation, you would query your database for these values

$stats = [
    'total_users' => 125,
    'total_doctors' => 45,
    'total_nurses' => 60,
    'total_patients' => 850
];

// Return the stats as JSON
echo json_encode([
    'success' => true,
    'stats' => $stats
]);