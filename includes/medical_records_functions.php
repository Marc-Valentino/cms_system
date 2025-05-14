<?php
require_once 'db_connection.php';

// Function to get all medical notes for a patient
function get_patient_medical_notes($patient_id, $limit = 100, $offset = 0) {
    return supabase_query('medical_notes', 'GET', null, [
        'select' => '*, users(first_name, last_name)',
        'patient_id' => 'eq.' . $patient_id,
        'order' => 'note_date.desc',
        'limit' => $limit,
        'offset' => $offset
    ]);
}

// Function to get a specific medical note by ID
function get_medical_note_by_id($note_id) {
    return supabase_query('medical_notes', 'GET', null, [
        'select' => '*, users(first_name, last_name), patients(first_name, last_name, patient_id)',
        'id' => 'eq.' . $note_id
    ]);
}

// Function to create a new medical note
function create_medical_note($note_data) {
    return supabase_query('medical_notes', 'POST', $note_data);
}

// Function to update a medical note
function update_medical_note($note_id, $note_data) {
    return supabase_query('medical_notes', 'PATCH', $note_data, [
        'id' => 'eq.' . $note_id
    ]);
}

// Function to delete a medical note
function delete_medical_note($note_id) {
    return supabase_query('medical_notes', 'DELETE', null, [
        'id' => 'eq.' . $note_id
    ]);
}

// Function to get all lab results for a patient
function get_patient_lab_results($patient_id, $limit = 100, $offset = 0) {
    return supabase_query('lab_results', 'GET', null, [
        'select' => '*, users(first_name, last_name)',
        'patient_id' => 'eq.' . $patient_id,
        'order' => 'test_date.desc',
        'limit' => $limit,
        'offset' => $offset
    ]);
}

// Function to get a specific lab result by ID
function get_lab_result_by_id($result_id) {
    return supabase_query('lab_results', 'GET', null, [
        'select' => '*, users(first_name, last_name), patients(first_name, last_name, patient_id)',
        'id' => 'eq.' . $result_id
    ]);
}

// Function to create a new lab result
function create_lab_result($result_data) {
    return supabase_query('lab_results', 'POST', $result_data);
}

// Function to update a lab result
function update_lab_result($result_id, $result_data) {
    return supabase_query('lab_results', 'PATCH', $result_data, [
        'id' => 'eq.' . $result_id
    ]);
}

// Function to delete a lab result
function delete_lab_result($result_id) {
    return supabase_query('lab_results', 'DELETE', null, [
        'id' => 'eq.' . $result_id
    ]);
}

// Function to get all patient visits
function get_patient_visits($patient_id, $limit = 100, $offset = 0) {
    return supabase_query('patient_visits', 'GET', null, [
        'select' => '*, users(first_name, last_name)',
        'patient_id' => 'eq.' . $patient_id,
        'order' => 'visit_date.desc',
        'limit' => $limit,
        'offset' => $offset
    ]);
}

// Function to get a specific patient visit by ID
function get_patient_visit_by_id($visit_id) {
    return supabase_query('patient_visits', 'GET', null, [
        'select' => '*, users(first_name, last_name), patients(first_name, last_name, patient_id)',
        'id' => 'eq.' . $visit_id
    ]);
}

// Function to create a new patient visit
function create_patient_visit($visit_data) {
    return supabase_query('patient_visits', 'POST', $visit_data);
}

// Function to update a patient visit
function update_patient_visit($visit_id, $visit_data) {
    return supabase_query('patient_visits', 'PATCH', $visit_data, [
        'id' => 'eq.' . $visit_id
    ]);
}

// Function to delete a patient visit
function delete_patient_visit($visit_id) {
    return supabase_query('patient_visits', 'DELETE', null, [
        'id' => 'eq.' . $visit_id
    ]);
}
?>