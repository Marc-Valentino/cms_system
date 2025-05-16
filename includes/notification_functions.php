<?php
require_once 'db_connection.php';

// Function to get all notifications for a user
function get_user_notifications($user_id, $limit = 20, $offset = 0) {
    return supabase_query('notifications', 'GET', null, [
        'select' => '*',
        'user_id' => 'eq.' . $user_id,
        'order' => 'created_at.desc',
        'limit' => $limit,
        'offset' => $offset
    ]);
}

// Function to get unread notifications count for a user
function get_unread_notification_count($user_id) {
    $result = supabase_query('notifications', 'GET', null, [
        'select' => 'count',
        'user_id' => 'eq.' . $user_id,
        'is_read' => 'eq.false',
        'count' => 'exact'
    ]);
    
    return $result['count'] ?? 0;
}

// Function to create a new notification
function create_notification($notification_data) {
    return supabase_query('notifications', 'POST', $notification_data);
}

// Function to mark a notification as read
function mark_notification_as_read($notification_id) {
    return supabase_query('notifications', 'PATCH', ['is_read' => true], [
        'id' => 'eq.' . $notification_id
    ]);
}

// Function to mark all notifications as read for a user
function mark_all_notifications_as_read($user_id) {
    return supabase_query('notifications', 'PATCH', ['is_read' => true], [
        'user_id' => 'eq.' . $user_id,
        'is_read' => 'eq.false'
    ]);
}

// Function to delete a notification
function delete_notification($notification_id) {
    return supabase_query('notifications', 'DELETE', null, [
        'id' => 'eq.' . $notification_id
    ]);
}

// Function to delete all notifications for a user
function delete_all_notifications($user_id) {
    return supabase_query('notifications', 'DELETE', null, [
        'user_id' => 'eq.' . $user_id
    ]);
}
?>