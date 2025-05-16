<?php
// Supabase Configuration
define('SUPABASE_URL', 'https://zdqaglewecydgxffsjqp.supabase.co');
define('SUPABASE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InpkcWFnbGV3ZWN5ZGd4ZmZzanFwIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDcxMjYwODYsImV4cCI6MjA2MjcwMjA4Nn0.yY1hT0rjN0GPFCXtQZI_flfKvNoDT1jrq8KZzX0gPCg');

// Database Tables
define('TABLE_USERS', 'users');
define('TABLE_ROLES', 'roles');
define('TABLE_DOCTOR_PROFILES', 'doctor_profiles');
define('TABLE_NURSE_PROFILES', 'nurse_profiles');
define('TABLE_PATIENTS', 'patients');
define('TABLE_APPOINTMENTS', 'appointments');
define('TABLE_MEDICAL_NOTES', 'medical_notes');
define('TABLE_LAB_RESULTS', 'lab_results');
define('TABLE_NOTIFICATIONS', 'notifications');
define('TABLE_PATIENT_VISITS', 'patient_visits');
define('TABLE_PATIENT_FEEDBACK', 'patient_feedback');
define('TABLE_SYSTEM_SETTINGS', 'system_settings');

// Role IDs
define('ROLE_DOCTOR', 1);
define('ROLE_NURSE', 2);
define('ROLE_ADMIN', 3);

// Other Constants
define('ITEMS_PER_PAGE', 10);
?>