<?php
require_once 'db_connection.php';

// Function to get all appointments
function get_all_appointments($limit = 100, $offset = 0) {
    return supabase_query('appointments', 'GET', null, [
        'select' => '*, patients(first_name, last_name, patient_id), users(first_name, last_name)',
        'order' => 'appointment_date.asc,appointment_time.asc',
        'limit' => $limit,
        'offset' => $offset
    ]);
}

// Function to get appointments for a specific doctor
function get_doctor_appointments($doctor_id, $limit = 100, $offset = 0) {
    return supabase_query('appointments', 'GET', null, [
        'select' => '*, patients(first_name, last_name, patient_id)',
        'doctor_id' => 'eq.' . $doctor_id,
        'order' => 'appointment_date.asc,appointment_time.asc',
        'limit' => $limit,
        'offset' => $offset
    ]);
}

// Function to get appointments for a specific patient
function get_patient_appointments($patient_id, $limit = 100, $offset = 0) {
    return supabase_query('appointments', 'GET', null, [
        'select' => '*, users(first_name, last_name)',
        'patient_id' => 'eq.' . $patient_id,
        'order' => 'appointment_date.asc,appointment_time.asc',
        'limit' => $limit,
        'offset' => $offset
    ]);
}

// Function to get appointments for a specific date
function get_appointments_by_date($date, $doctor_id = null) {
    $query_params = [
        'select' => '*, patients(first_name, last_name, patient_id)',
        'appointment_date' => 'eq.' . $date,
        'order' => 'appointment_time.asc'
    ];
    
    // Add doctor_id filter if provided
    if ($doctor_id) {
        $query_params['doctor_id'] = 'eq.' . $doctor_id;
    }
    
    $appointments = supabase_query('appointments', 'GET', null, $query_params);
    
    // Format the appointments for display
    $formatted_appointments = [];
    if (!empty($appointments)) {
        foreach ($appointments as $appointment) {
            $patient_name = '';
            if (isset($appointment['patients']) && is_array($appointment['patients'])) {
                $patient_name = $appointment['patients']['first_name'] . ' ' . $appointment['patients']['last_name'];
            }
            
            $formatted_appointments[] = [
                'id' => $appointment['id'],
                'time' => $appointment['appointment_time'],
                'patient' => $patient_name,
                'purpose' => $appointment['purpose'] ?? 'General Checkup',
                'status' => $appointment['status'] ?? 'scheduled'
            ];
        }
    }
    
    return $formatted_appointments;
}

// Function to get a specific appointment by ID
function get_appointment_by_id($appointment_id) {
    return supabase_query('appointments', 'GET', null, [
        'select' => '*, patients(first_name, last_name, patient_id), users(first_name, last_name)',
        'id' => 'eq.' . $appointment_id
    ]);
}

// Function to create a new appointment
function create_appointment($appointment_data) {
    return supabase_query('appointments', 'POST', $appointment_data);
}

// Function to update an appointment
function update_appointment($appointment_id, $appointment_data) {
    return supabase_query('appointments', 'PATCH', $appointment_data, [
        'id' => 'eq.' . $appointment_id
    ]);
}

// Function to delete an appointment
function delete_appointment($appointment_id) {
    return supabase_query('appointments', 'DELETE', null, [
        'id' => 'eq.' . $appointment_id
    ]);
}

// Function to check appointment availability
function check_appointment_availability($doctor_id, $date, $time) {
    $result = supabase_query('appointments', 'GET', null, [
        'select' => 'count',
        'doctor_id' => 'eq.' . $doctor_id,
        'appointment_date' => 'eq.' . $date,
        'appointment_time' => 'eq.' . $time,
        'count' => 'exact'
    ]);
    
    return ($result['count'] ?? 0) == 0;
}

// Function to get appointment count
function get_appointment_count($doctor_id = null) {
    $params = [
        'select' => 'count',
        'count' => 'exact'
    ];
    
    if ($doctor_id) {
        $params['doctor_id'] = 'eq.' . $doctor_id;
    }
    
    $result = supabase_query('appointments', 'GET', null, $params);
    
    return $result['count'] ?? 0;
}
?>