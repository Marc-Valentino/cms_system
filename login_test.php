<?php
require_once 'includes/db_connection.php';
require_once 'includes/user_functions.php';

echo '<h1>Login Test</h1>';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    echo "<p>Testing login for: " . htmlspecialchars($email) . "</p>";
    
    $user = authenticate_user($email, $password);
    
    if ($user) {
        echo "<p style='color:green'>Login successful!</p>";
        echo "<pre>" . print_r($user, true) . "</pre>";
    } else {
        echo "<p style='color:red'>Login failed!</p>";
    }
}
?>

<form method="post">
    <div>
        <label>Email:</label>
        <input type="text" name="email" required>
    </div>
    <div>
        <label>Password:</label>
        <input type="password" name="password" required>
    </div>
    <button type="submit">Test Login</button>
</form>