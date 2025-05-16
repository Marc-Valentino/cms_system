<?php
require_once 'includes/db_connection.php';

// Test the connection by fetching roles
$roles = supabase_query('roles', 'GET', null, [
    'select' => '*'
]);

echo '<pre>';
echo 'Testing Supabase Connection...<br>';
echo 'URL: ' . SUPABASE_URL . '<br>';
echo 'Key: ' . substr(SUPABASE_KEY, 0, 10) . '...<br><br>';

if ($roles === false) {
    echo 'Connection failed!';
} else {
    echo 'Connection successful!<br><br>';
    echo 'Roles:<br>';
    print_r($roles);
}
echo '</pre>';
?>