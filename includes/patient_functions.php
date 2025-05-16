<?php
require_once 'db_connection.php';

// Function to get all patients
function get_all_patients($limit = 100, $offset = 0) {
    return supabase_query('patients', 'GET', null, [
        'select' => '*',
        'order' => 'created_at.desc',
        'limit' => $limit,
        'offset' => $offset
    ]);
}

// Function to get a specific patient by ID
function get_patient_by_id($patient_id) {
    return supabase_query('patients', 'GET', null, [
        'select' => '*',
        'id' => 'eq.' . $patient_id
    ]);
}

// Function to get a specific patient by patient_id (custom ID)
function get_patient_by_custom_id($custom_patient_id) {
    return supabase_query('patients', 'GET', null, [
        'select' => '*',
        'patient_id' => 'eq.' . $custom_patient_id
    ]);
}

// Function to search patients
function search_patients($search_term) {
    // Search in first_name, last_name, email, and patient_id
    return supabase_query('patients', 'GET', null, [
        'select' => '*',
        'or' => '(first_name.ilike.%' . $search_term . '%,last_name.ilike.%' . $search_term . '%,email.ilike.%' . $search_term . '%,patient_id.ilike.%' . $search_term . '%)'
    ]);
}

// Function to create a new patient
function create_patient($patient_data) {
    // Generate a unique patient_id if not provided
    if (!isset($patient_data['patient_id'])) {
        $patient_data['patient_id'] = 'P' . str_pad(mt_rand(10000, 99999), 5, '0', STR_PAD_LEFT);
    }
    
    return supabase_query('patients', 'POST', $patient_data);
}

// Function to update a patient
function update_patient($patient_id, $patient_data) {
    return supabase_query('patients', 'PATCH', $patient_data, [
        'id' => 'eq.' . $patient_id
    ]);
}

// Function to delete a patient
function delete_patient($patient_id) {
    return supabase_query('patients', 'DELETE', null, [
        'id' => 'eq.' . $patient_id
    ]);
}

// Function to get patient count
function get_patient_count() {
    $result = supabase_query('patients', 'GET', null, [
        'select' => 'count',
        'count' => 'exact'
    ]);
    
    return $result['count'] ?? 0;
}
?>