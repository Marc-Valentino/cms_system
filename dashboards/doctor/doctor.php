<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if doctor is logged in
// if (!isset($_SESSION['doctor_id'])) {
//     header("Location: ../login.php");
//     exit();
// }

// Placeholder doctor data - replace with actual data from your database
$doctor = [
    'id' => 1,
    'name' => 'Dr. Sarah Johnson',
    'profile_pic' => '../assets/img/doctor-profile.jpg',
    'specialty' => 'Cardiologist'
];

// Placeholder for today's appointments - replace with actual data
$todaysAppointments = [
    ['time' => '09:00 AM', 'patient' => 'John Smith', 'purpose' => 'Annual Checkup'],
    ['time' => '10:30 AM', 'patient' => 'Emily Davis', 'purpose' => 'Follow-up'],
    ['time' => '01:15 PM', 'patient' => 'Michael Brown', 'purpose' => 'Consultation'],
    ['time' => '03:45 PM', 'patient' => 'Jessica Wilson', 'purpose' => 'Test Results Review']
];

// Placeholder for recent patients - replace with actual data
$recentPatients = [
    ['id' => 'P10045', 'name' => 'Robert Johnson', 'last_visit' => '2023-06-15'],
    ['id' => 'P10089', 'name' => 'Linda Williams', 'last_visit' => '2023-06-10'],
    ['id' => 'P10023', 'name' => 'Thomas Anderson', 'last_visit' => '2023-06-05']
];

// Placeholder for notifications - replace with actual data
$notifications = [
    ['type' => 'Lab Result', 'message' => 'New lab results for patient Emily Davis', 'time' => '2 hours ago'],
    ['type' => 'Reminder', 'message' => 'Follow-up call with John Smith', 'time' => '1 day ago'],
    ['type' => 'System', 'message' => 'System maintenance scheduled for tonight', 'time' => '3 days ago']
];

// Placeholder for medical notes - replace with actual data
$medicalNotes = [
    ['patient' => 'Emily Davis', 'date' => '2023-06-15', 'summary' => 'Patient reported improvement in symptoms after medication change.'],
    ['patient' => 'John Smith', 'date' => '2023-06-12', 'summary' => 'Prescribed new medication for hypertension. Follow-up in 2 weeks.']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - Clinic Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
    
</head>
<body>
    <div class="dashboard-container">
        <!-- Include the sidebar -->
        <?php include('sidebar.php'); ?>
        
        <div class="main-content">
            <!-- Include the navbar/header -->
            <?php include('navbar.php'); ?>

            <!-- Search Bar -->
            <div class="search-bar">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Search for patients by name or ID...">
                
            </div>

            <div class="row">
                <!-- Today's Appointments - Adjusted to full width -->
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Today's Appointments</h5>
                            <a href="appointments.php" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Time</th>
                                            <th>Patient</th>
                                            <th>Purpose</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($todaysAppointments as $appointment): ?>
                                        <tr>
                                            <td><span class="badge bg-light text-dark"><?php echo $appointment['time']; ?></span></td>
                                            <td><strong><?php echo $appointment['patient']; ?></strong></td>
                                            <td><?php echo $appointment['purpose']; ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-eye"></i> View</button>
                                                <button class="btn btn-sm btn-outline-success"><i class="bi bi-check-circle"></i> Complete</button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Patient Records Quick Access - Now full width -->
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Patient Records Quick Access</h5>
                            <a href="patients.php" class="btn btn-sm btn-primary">View All Patients</a>
                        </div>
                        <div class="card-body p-0">
                            <?php foreach ($recentPatients as $patient): ?>
                            <div class="patient-card">
                                <div class="patient-avatar">
                                    <?php echo substr($patient['name'], 0, 1); ?>
                                </div>
                                <div class="patient-info">
                                    <p class="patient-name"><?php echo $patient['name']; ?></p>
                                    <p class="patient-details">ID: <?php echo $patient['id']; ?> | Last Visit: <?php echo $patient['last_visit']; ?></p>
                                </div>
                                <div class="patient-actions">
                                    <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-outline-success"><i class="bi bi-journal-plus"></i></button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle sidebar on mobile
        document.getElementById('toggle-sidebar').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.main-content').classList.toggle('active');
        });
    </script>
</body>
</html>

<!-- View Appointment Modal -->
<div class="modal fade" id="viewAppointmentModal" tabindex="-1" aria-labelledby="viewAppointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAppointmentModalLabel">
                    <i class="bi bi-calendar-check me-2"></i>Appointment Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="avatar-container mb-3">
                        <i class="bi bi-person-circle" style="font-size: 4rem; color: #6c757d;"></i>
                    </div>
                    <h4 id="modalPatientName">Patient Name</h4>
                    <p class="text-muted">Patient ID: <span id="modalPatientId">P10045</span></p>
                </div>
                
                <div class="patient-details">
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Purpose:</div>
                        <div class="col-md-8" id="modalPurpose">Annual Checkup</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Date:</div>
                        <div class="col-md-8" id="modalDate">2023-07-15</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Time:</div>
                        <div class="col-md-8" id="modalTime">09:00 AM</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Contact:</div>
                        <div class="col-md-8" id="modalContact">(555) 123-4567</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Medical History:</div>
                        <div class="col-md-8" id="modalMedicalHistory">No significant medical history</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Complete Appointment Modal -->
<div class="modal fade" id="completeAppointmentModal" tabindex="-1" aria-labelledby="completeAppointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completeAppointmentModalLabel">
                    <i class="bi bi-check-circle me-2"></i>Complete Appointment
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You are about to mark this appointment as completed:</p>
                <div class="alert alert-info">
                    <strong>Patient:</strong> <span id="completePatientName">Patient Name</span><br>
                    <strong>Purpose:</strong> <span id="completePurpose">Annual Checkup</span><br>
                    <strong>Date/Time:</strong> <span id="completeDateTime">2023-07-15, 09:00 AM</span>
                </div>
                
                <div class="mb-3">
                    <label for="appointmentNotes" class="form-label">Appointment Notes</label>
                    <textarea class="form-control" id="appointmentNotes" rows="4" placeholder="Enter notes about this appointment..."></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="followUpRequired" class="form-label">Follow-up Required?</label>
                    <select class="form-select" id="followUpRequired">
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select>
                </div>
                
                <div id="followUpOptions" class="d-none">
                    <div class="mb-3">
                        <label for="followUpDate" class="form-label">Follow-up Date</label>
                        <input type="date" class="form-control" id="followUpDate">
                    </div>
                    <div class="mb-3">
                        <label for="followUpReason" class="form-label">Follow-up Reason</label>
                        <input type="text" class="form-control" id="followUpReason" placeholder="Reason for follow-up">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmCompleteBtn">
                    <i class="bi bi-check-lg me-1"></i> Mark as Completed
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="appointmentToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="bi bi-check-circle me-2"></i>
            <strong class="me-auto">Success</strong>
            <small>Just now</small>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toastMessage">
            Appointment has been marked as completed!
        </div>
    </div>
</div>

<!-- Add custom styles for the modals -->
<style>
    /* Avatar styling */
    .avatar-container {
        display: inline-block;
        position: relative;
    }
    
    /* Responsive icon sizing */
    @media (max-width: 576px) {
        .avatar-container .bi-person-circle {
            font-size: 3rem !important;
        }
    }
    
    /* Row styling for better mobile display */
    .patient-details .row {
        margin-bottom: 0.5rem;
        padding: 0.5rem;
        background-color: #f8f9fa;
        border-radius: 0.25rem;
    }
    
    /* Responsive text adjustments */
    @media (max-width: 767px) {
        .patient-details .col-md-4 {
            margin-bottom: 0.25rem;
        }
    }
</style>

<!-- JavaScript for modal functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Get all patient view buttons in the Patient Records section
        const patientViewButtons = document.querySelectorAll('.patient-actions .btn-outline-primary');
        patientViewButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Get the patient data from the parent container
                const patientCard = this.closest('.patient-card');
                const patientName = patientCard.querySelector('.patient-name').textContent.trim();
                const patientDetails = patientCard.querySelector('.patient-details').textContent.trim();
                
                // Extract patient ID and last visit from the details text
                const patientId = patientDetails.split('|')[0].replace('ID:', '').trim();
                const lastVisit = patientDetails.split('|')[1].replace('Last Visit:', '').trim();
                
                // Populate the modal with data
                document.getElementById('patientModalName').textContent = patientName;
                document.getElementById('patientModalId').textContent = patientId;
                document.getElementById('patientModalLastVisit').textContent = lastVisit;
                
                // Show the modal
                const viewPatientModal = new bootstrap.Modal(document.getElementById('viewPatientModal'));
                viewPatientModal.show();
            });
        });
        
        // Get all add note buttons in the Patient Records section
        const addNoteButtons = document.querySelectorAll('.patient-actions .btn-outline-success');
        addNoteButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Get the patient data from the parent container
                const patientCard = this.closest('.patient-card');
                const patientName = patientCard.querySelector('.patient-name').textContent.trim();
                const patientDetails = patientCard.querySelector('.patient-details').textContent.trim();
                
                // Extract patient ID from the details text
                const patientId = patientDetails.split('|')[0].replace('ID:', '').trim();
                
                // Populate the modal with data
                document.getElementById('notePatientName').textContent = patientName;
                document.getElementById('notePatientId').textContent = patientId;
                document.getElementById('noteDate').value = new Date().toISOString().split('T')[0]; // Set today's date
                
                // Show the modal
                const addNoteModal = new bootstrap.Modal(document.getElementById('addPatientNoteModal'));
                addNoteModal.show();
            });
        });
        
        // Handle save note button
        document.getElementById('saveNoteBtn').addEventListener('click', function() {
            // Here you would normally send the data to the server
            // For now, just close the modal and show a success message
            
            const addNoteModal = bootstrap.Modal.getInstance(document.getElementById('addPatientNoteModal'));
            addNoteModal.hide();
            
            // Show success toast
            const toast = new bootstrap.Toast(document.getElementById('appointmentToast'));
            document.getElementById('toastMessage').textContent = 'Medical note has been saved successfully!';
            toast.show();
        });
        
        // Get all view buttons
        const viewButtons = document.querySelectorAll('.btn-outline-primary');
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Get the appointment data from the row
                const row = this.closest('tr');
                const patientName = row.cells[1].textContent.trim(); // Changed from cells[0] to cells[1]
                const purpose = row.cells[2].textContent.trim();
                const time = row.cells[0].textContent.trim();
                
                // Populate the modal with data
                document.getElementById('modalPatientName').textContent = patientName;
                document.getElementById('modalPurpose').textContent = purpose;
                document.getElementById('modalTime').textContent = time; // Added to display time
                
                // Show the modal
                const viewModal = new bootstrap.Modal(document.getElementById('viewAppointmentModal'));
                viewModal.show();
            });
        });
        
        // Get all complete buttons
        const completeButtons = document.querySelectorAll('.btn-outline-success');
        completeButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Get the appointment data from the row
                const row = this.closest('tr');
                const patientName = row.cells[1].textContent.trim(); // Changed from cells[0] to cells[1]
                const purpose = row.cells[2].textContent.trim();
                const time = row.cells[0].textContent.trim();
                
                // Populate the modal with data
                document.getElementById('completePatientName').textContent = patientName;
                document.getElementById('completePurpose').textContent = purpose;
                document.getElementById('completeDateTime').textContent = time;
                
                // Show the modal
                const completeModal = new bootstrap.Modal(document.getElementById('completeAppointmentModal'));
                completeModal.show();
            });
        });
        
        // Toggle follow-up options visibility
        document.getElementById('followUpRequired').addEventListener('change', function() {
            const followUpOptions = document.getElementById('followUpOptions');
            if (this.value === 'yes') {
                followUpOptions.classList.remove('d-none');
            } else {
                followUpOptions.classList.add('d-none');
            }
        });
        
        // Handle complete appointment confirmation
        document.getElementById('confirmCompleteBtn').addEventListener('click', function() {
            // Here you would normally send the data to the server
            // For now, just close the modal and show a success message
            
            const completeModal = bootstrap.Modal.getInstance(document.getElementById('completeAppointmentModal'));
            completeModal.hide();
            
            // Show success toast
            const toast = new bootstrap.Toast(document.getElementById('appointmentToast'));
            
            // Set toast message based on follow-up selection
            const followUpRequired = document.getElementById('followUpRequired').value;
            if (followUpRequired === 'yes') {
                document.getElementById('toastMessage').textContent = 'Appointment completed and follow-up scheduled!';
            } else {
                document.getElementById('toastMessage').textContent = 'Appointment has been marked as completed!';
            }
            
            toast.show();
            
            // In a real application, you would update the UI to reflect the completed status
            // For example, changing the status badge in the table
        });
    });
</script>
<!-- View Patient Modal -->
<div class="modal fade" id="viewPatientModal" tabindex="-1" aria-labelledby="viewPatientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewPatientModalLabel">
                    <i class="bi bi-person-badge me-2"></i>Patient Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="avatar-container mb-3">
                        <i class="bi bi-person-circle" style="font-size: 4rem; color: #6c757d;"></i>
                    </div>
                    <h4 id="patientModalName">Patient Name</h4>
                    <p class="text-muted">Patient ID: <span id="patientModalId">P10045</span></p>
                </div>
                
                <div class="patient-details">
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Last Visit:</div>
                        <div class="col-md-8" id="patientModalLastVisit">2023-06-15</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Contact:</div>
                        <div class="col-md-8" id="patientModalContact">(555) 123-4567</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Email:</div>
                        <div class="col-md-8" id="patientModalEmail">patient@example.com</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Address:</div>
                        <div class="col-md-8" id="patientModalAddress">123 Main St, Anytown, USA</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Medical History:</div>
                        <div class="col-md-8" id="patientModalMedicalHistory">No significant medical history</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Note Modal -->
<div class="modal fade" id="addPatientNoteModal" tabindex="-1" aria-labelledby="addPatientNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPatientNoteModalLabel">
                    <i class="bi bi-journal-plus me-2"></i>Add Medical Note
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <strong>Patient:</strong> <span id="notePatientName">Patient Name</span><br>
                    <strong>ID:</strong> <span id="notePatientId">P10045</span>
                </div>
                
                <div class="mb-3">
                    <label for="noteTitle" class="form-label">Note Title</label>
                    <input type="text" class="form-control" id="noteTitle" placeholder="Enter note title">
                </div>
                
                <div class="mb-3">
                    <label for="noteDate" class="form-label">Date</label>
                    <input type="date" class="form-control" id="noteDate" value="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <div class="mb-3">
                    <label for="noteSummary" class="form-label">Note Summary</label>
                    <textarea class="form-control" id="noteSummary" rows="4" placeholder="Enter medical note details..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="saveNoteBtn">
                    <i class="bi bi-save me-1"></i> Save Note
                </button>
            </div>
        </div>
    </div>
</div>
