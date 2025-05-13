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

// Placeholder for appointments - replace with actual data
$appointments = [
    ['patient' => 'John Smith', 'date' => '2023-07-15', 'time' => '09:00 AM', 'doctor' => 'Dr. Sarah Johnson', 'status' => 'Pending'],
    ['patient' => 'Emily Davis', 'date' => '2023-07-16', 'time' => '10:30 AM', 'doctor' => 'Dr. Sarah Johnson', 'status' => 'Confirmed'],
    ['patient' => 'Michael Brown', 'date' => '2023-07-14', 'time' => '01:15 PM', 'doctor' => 'Dr. Robert Wilson', 'status' => 'Completed'],
    ['patient' => 'Jessica Wilson', 'date' => '2023-07-13', 'time' => '03:45 PM', 'doctor' => 'Dr. Sarah Johnson', 'status' => 'Canceled']
];

// Placeholder for notifications - replace with actual data
$notifications = [
    ['type' => 'Lab Result', 'message' => 'New lab results for patient Emily Davis', 'time' => '2 hours ago'],
    ['type' => 'Reminder', 'message' => 'Follow-up call with John Smith', 'time' => '1 day ago'],
    ['type' => 'System', 'message' => 'System maintenance scheduled for tonight', 'time' => '3 days ago']
];

// Format appointments for FullCalendar
$calendarEvents = [];
foreach ($appointments as $appointment) {
    // Convert date and time to FullCalendar format
    $dateTime = date('Y-m-d', strtotime($appointment['date'])) . 'T' . date('H:i:s', strtotime($appointment['time']));
    
    // Set color based on status
    $color = '';
    switch ($appointment['status']) {
        case 'Pending':
            $color = '#ffc107'; // warning yellow
            break;
        case 'Confirmed':
            $color = '#28a745'; // success green
            break;
        case 'Completed':
            $color = '#6c757d'; // secondary gray
            break;
        case 'Canceled':
            $color = '#dc3545'; // danger red
            break;
        default:
            $color = '#3498db'; // primary blue
    }
    
    $calendarEvents[] = [
        'title' => $appointment['patient'],
        'start' => $dateTime,
        'color' => $color,
        'extendedProps' => [
            'doctor' => $appointment['doctor'],
            'status' => $appointment['status']
        ]
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments - Clinic Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="doctor.css">
    
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
                <input type="text" placeholder="Search for appointments...">
            </div>

            <div class="row mb-4">
                <!-- Manage Appointments Section -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Manage Appointments</h5>
                            <div class="d-flex">

                                <button class="btn btn-primary" data-action="schedule">
                                    <i class="bi bi-calendar-plus"></i> Schedule New Appointment
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Patient Name</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Doctor</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($appointments as $index => $appointment): ?>
                                        <tr>
                                            <td><?php echo $appointment['patient']; ?></td>
                                            <td><?php echo $appointment['date']; ?></td>
                                            <td><?php echo $appointment['time']; ?></td>
                                            <td><?php echo $appointment['doctor']; ?></td>
                                            <td>
                                                <?php 
                                                $statusClass = '';
                                                switch($appointment['status']) {
                                                    case 'Pending':
                                                        $statusClass = 'bg-warning';
                                                        break;
                                                    case 'Confirmed':
                                                        $statusClass = 'bg-primary';
                                                        break;
                                                    case 'Completed':
                                                        $statusClass = 'bg-success';
                                                        break;
                                                    case 'Canceled':
                                                        $statusClass = 'bg-danger';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge <?php echo $statusClass; ?>"><?php echo $appointment['status']; ?></span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button 
                                                        class="btn btn-sm btn-outline-primary view-appointment-btn" 
                                                        title="View Details" 
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewModal"
                                                        data-id="<?php echo $index; ?>"
                                                        onclick="openViewModal(this)"
                                                    >
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button 
                                                        class="btn btn-sm btn-outline-success edit-appointment-btn" 
                                                        title="Edit Appointment" 
                                                        data-id="<?php echo $index; ?>"
                                                        onclick="openEditModal(this)"
                                                    >
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <?php if($appointment['status'] != 'Canceled' && $appointment['status'] != 'Completed'): ?>
                                                    <button class="btn btn-sm btn-outline-danger delete-appointment-btn" title="Delete Appointment" data-id="<?php echo $index; ?>">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                    <?php endif; ?>
                                                </div>
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
            
            <!-- Bootstrap Bundle with Popper -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            
            <!-- FullCalendar JS -->
            <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
            
            <script>
                // Initialize any JavaScript functionality
                document.addEventListener('DOMContentLoaded', function() {
                    // Handle save appointment button
                    document.getElementById('saveAppointment').addEventListener('click', function() {
                        // Validate form
                        const form = document.getElementById('appointmentForm');
                        if (!form.checkValidity()) {
                            form.reportValidity();
                            return;
                        }
                        
                        // Here you would normally send the data to the server
                        // For now, just close the modal and show a success message
                        const modal = bootstrap.Modal.getInstance(document.getElementById('scheduleAppointmentModal'));
                        modal.hide();
                        
                        // Show success alert (you can implement this)
                        alert('Appointment scheduled successfully!');
                    });
                });
            </script>
        </div>
    </div>

</body>
</html>

<!-- JavaScript for appointment modal functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the button that opens the modal
        const scheduleBtn = document.querySelector('button[data-action="schedule"]');
        if (!scheduleBtn) {
            // If the button doesn't exist yet, let's create one
            const headerActions = document.querySelector('.card-header .btn-primary');
            if (headerActions) {
                headerActions.setAttribute('data-action', 'schedule');
            }
        }
        
        // Get all buttons that should open the modal
        const allScheduleBtns = document.querySelectorAll('[data-action="schedule"]');
        
        // Add click event to all schedule buttons
        allScheduleBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Create a new Bootstrap modal instance
                const appointmentModal = new bootstrap.Modal(document.getElementById('scheduleAppointmentModal'));
                appointmentModal.show();
            });
        });
        
        // Set default date to today
        const dateInput = document.getElementById('appointmentDate');
        if (dateInput) {
            const today = new Date();
            const year = today.getFullYear();
            let month = today.getMonth() + 1;
            let day = today.getDate();
            
            // Add leading zeros if needed
            month = month < 10 ? '0' + month : month;
            day = day < 10 ? '0' + day : day;
            
            dateInput.value = `${year}-${month}-${day}`;
            dateInput.min = `${year}-${month}-${day}`; // Prevent selecting past dates
        }
        
        // Form validation
        const form = document.getElementById('appointmentForm');
        const saveBtn = document.getElementById('saveAppointmentBtn');
        
        if (saveBtn) {
            saveBtn.addEventListener('click', function() {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    form.classList.add('was-validated');
                } else {
                    // Form is valid, show success message
                    const appointmentModal = bootstrap.Modal.getInstance(document.getElementById('scheduleAppointmentModal'));
                    appointmentModal.hide();
                    
                    // Show success toast
                    const toast = new bootstrap.Toast(document.getElementById('appointmentToast'));
                    toast.show();
                    
                    // Reset form
                    form.classList.remove('was-validated');
                    form.reset();
                    
                    // Set default date again
                    const today = new Date();
                    const year = today.getFullYear();
                    let month = today.getMonth() + 1;
                    let day = today.getDate();
                    
                    month = month < 10 ? '0' + month : month;
                    day = day < 10 ? '0' + day : day;
                    
                    dateInput.value = `${year}-${month}-${day}`;
                    
                    // In a real application, you would send the form data to the server here
                    console.log('Appointment scheduled!');
                }
            });
        }
        
        // Reset form when modal is closed
        const appointmentModal = document.getElementById('scheduleAppointmentModal');
        if (appointmentModal) {
            appointmentModal.addEventListener('hidden.bs.modal', function() {
                form.classList.remove('was-validated');
                form.reset();
                
                // Set default date again
                const today = new Date();
                const year = today.getFullYear();
                let month = today.getMonth() + 1;
                let day = today.getDate();
                
                month = month < 10 ? '0' + month : month;
                day = day < 10 ? '0' + day : day;
                
                dateInput.value = `${year}-${month}-${day}`;
            });
        }
    });
</script>

<!-- Schedule Appointment Modal -->
<div class="modal fade" id="scheduleAppointmentModal" tabindex="-1" aria-labelledby="scheduleAppointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleAppointmentModalLabel">
                    <i class="bi bi-calendar-plus me-2"></i>Schedule New Appointment
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="appointmentForm">
                    <div class="mb-3">
                        <label for="patientName" class="form-label">Patient Name</label>
                        <input type="text" class="form-control" id="patientName" placeholder="Enter patient name" required>
                    </div>
                    <div class="mb-3">
                        <label for="appointmentDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="appointmentDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="appointmentTime" class="form-label">Time</label>
                        <input type="time" class="form-control" id="appointmentTime" required>
                    </div>
                    <div class="mb-3">
                        <label for="doctorSelect" class="form-label">Doctor</label>
                        <select class="form-select" id="doctorSelect" required>
                            <option value="">Select a doctor</option>
                            <option value="1">Dr. Sarah Johnson</option>
                            <option value="2">Dr. Robert Wilson</option>
                            <option value="3">Dr. Emily Chen</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="appointmentPurpose" class="form-label">Purpose</label>
                        <textarea class="form-control" id="appointmentPurpose" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveAppointment">Save Appointment</button>
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
        <div class="toast-body">
            Appointment has been scheduled successfully!
        </div>
    </div>
</div>
<!-- View Appointment Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">
                    <i class="bi bi-calendar-check me-2"></i>Appointment Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="avatar-container mb-3">
                        <i class="bi bi-person-circle" style="font-size: 4rem; color: #6c757d;"></i>
                    </div>
                    <h4 id="patientName">Patient Name</h4>
                    <p class="text-muted">Patient ID: <span id="patientId">P10045</span></p>
                </div>
                
                <div class="patient-details">
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Age:</div>
                        <div class="col-md-8" id="patientAge">45</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Gender:</div>
                        <div class="col-md-8" id="patientGender">Male</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Contact:</div>
                        <div class="col-md-8" id="patientContact">(555) 123-4567</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Email:</div>
                        <div class="col-md-8" id="patientEmail">patient@example.com</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Address:</div>
                        <div class="col-md-8" id="patientAddress">123 Main St, Anytown, USA</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Medical History:</div>
                        <div class="col-md-8" id="patientMedicalHistory">No significant medical history</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Add custom styles for the modal -->
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
<!-- Edit Appointment Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel"><i class="bi bi-pencil-square me-2"></i>Edit Appointment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="edit-appointment-form">
          <input type="hidden" id="edit-appointment-id" name="appointment_id">
          <div class="mb-3">
            <label for="edit-patient-name" class="form-label">Patient Name</label>
            <input type="text" class="form-control" id="edit-patient-name" name="patient_name" required>
          </div>
          <div class="mb-3">
            <label for="edit-appointment-date" class="form-label">Date</label>
            <input type="date" class="form-control" id="edit-appointment-date" name="appointment_date" required>
          </div>
          <div class="mb-3">
            <label for="edit-appointment-time" class="form-label">Time</label>
            <input type="time" class="form-control" id="edit-appointment-time" name="appointment_time" required>
          </div>
          <div class="mb-3">
            <label for="edit-doctor" class="form-label">Doctor</label>
            <select class="form-select" id="edit-doctor" name="doctor" required>
              <option value="Dr. Sarah Johnson">Dr. Sarah Johnson</option>
              <option value="Dr. Robert Wilson">Dr. Robert Wilson</option>
              <option value="Dr. Emily Clark">Dr. Emily Clark</option>
            </select>
          </div>
          
          <div class="mb-3">
            <label for="edit-purpose" class="form-label">Purpose</label>
            <input type="text" class="form-control" id="edit-purpose" name="purpose" placeholder="Appointment purpose">
          </div>
          
          <div class="mb-3">
            <label for="edit-status" class="form-label">Status</label>
            <select class="form-select" id="edit-status" name="status" required>
              <option value="Pending">Pending</option>
              <option value="Confirmed">Confirmed</option>
              <option value="Completed">Completed</option>
              <option value="Canceled">Canceled</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="save-edit-btn">Save Changes</button>
      </div>
    </div>
  </div>
</div>
<!-- Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
  <div id="editToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        Appointment updated successfully!
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteConfirmationModalLabel"><i class="bi bi-exclamation-triangle me-2"></i>Confirm Deletion</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this appointment? This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete Appointment</button>
      </div>
    </div>
  </div>
</div>

<script>
// Enable Bootstrap tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
tooltipTriggerList.forEach(function (tooltipTriggerEl) {
  new bootstrap.Tooltip(tooltipTriggerEl);
});

// Function to open the view modal with appointment details
function openViewModal(button) {
    // Get the appointment ID from the button's data attribute
    const appointmentId = button.getAttribute('data-id');
    
    // Get the appointment data from the PHP-generated array
    const appointmentsData = <?php echo json_encode($appointments); ?>;
    const appointmentData = appointmentsData[appointmentId];
    
    // Populate the modal with the appointment data
    document.getElementById('patientName').textContent = appointmentData.patient;
    
    // In a real application, you would fetch additional patient data
    // For this example, we're using placeholder data
    document.getElementById('patientId').textContent = 'P10045';
    document.getElementById('patientAge').textContent = '45';
    document.getElementById('patientGender').textContent = 'Male';
    document.getElementById('patientContact').textContent = '(555) 123-4567';
    document.getElementById('patientEmail').textContent = 'patient@example.com';
    document.getElementById('patientAddress').textContent = '123 Main St, Anytown, USA';
    document.getElementById('patientMedicalHistory').textContent = 'No significant medical history';
    
    // Show the modal using Bootstrap's API
    const viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
    viewModal.show();
}

// Edit Modal logic
function openEditModal(button) {
  // Get the appointment ID from the button's data-id attribute
  const appointmentId = button.getAttribute('data-id');
  
  // Get the modal element
  const editModal = document.getElementById('editModal');
  
  // Set the appointment ID to a hidden input in the modal if needed
  if (document.getElementById('edit-appointment-id')) {
    document.getElementById('edit-appointment-id').value = appointmentId;
  }
  
  // Show the modal using Bootstrap's modal method
  const modal = new bootstrap.Modal(editModal);
  modal.show();
}

// Helper function to format time for input field (hh:mm AM/PM to HH:MM 24-hour)
function formatTimeForInput(timeStr) {
  if (!timeStr) return '';
  
  // Check if time is already in 24-hour format
  if (/^\d{2}:\d{2}$/.test(timeStr)) {
    return timeStr;
  }
  
  // Parse 12-hour format
  const timeRegex = /(\d{1,2}):(\d{2})\s*(AM|PM)/i;
  const match = timeStr.match(timeRegex);
  
  if (match) {
    let hours = parseInt(match[1]);
    const minutes = match[2];
    const period = match[3].toUpperCase();
    
    // Convert to 24-hour format
    if (period === 'PM' && hours < 12) {
      hours += 12;
    } else if (period === 'AM' && hours === 12) {
      hours = 0;
    }
    
    return `${hours.toString().padStart(2, '0')}:${minutes}`;
  }
  
  return timeStr;
}

// Handle Edit form submit
document.getElementById('edit-appointment-form').addEventListener('submit', function(e) {
  e.preventDefault();
  // Here you would send AJAX to backend to save changes
  // For demo, just show toast
  var toast = new bootstrap.Toast(document.getElementById('editToast'));
  toast.show();
  var modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
  modal.hide();
});

// Add event listener for save button
document.getElementById('save-edit-btn').addEventListener('click', function() {
  // Trigger form submission
  document.getElementById('edit-appointment-form').dispatchEvent(new Event('submit'));
});

document.addEventListener('DOMContentLoaded', function() {
  // Handle delete button clicks
  const deleteButtons = document.querySelectorAll('.delete-appointment-btn');
  let appointmentToDelete = null;
  
  deleteButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      // Store reference to the appointment row
      appointmentToDelete = this.closest('tr');
      // Show confirmation modal
      const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
      deleteModal.show();
    });
  });
  
  // Handle confirmation button click
  const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
  if (confirmDeleteBtn) {
    confirmDeleteBtn.addEventListener('click', function() {
      if (appointmentToDelete) {
        // Here you would typically make an AJAX call to delete the appointment
        // For now, we'll just remove it from the DOM
        appointmentToDelete.remove();
        
        // Close the modal
        const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
        deleteModal.hide();
        
        // Show success message
        const toast = new bootstrap.Toast(document.getElementById('editToast'));
        document.querySelector('#editToast .toast-body').textContent = 'Appointment deleted successfully!';
        toast.show();
      }
    });
  }
  
  // Initialize all tooltips
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
  
  // Add event listener for view modal hidden event to clean up
  const viewModalElement = document.getElementById('viewModal');
  if (viewModalElement) {
    // Clean up any existing event listeners
    viewModalElement.addEventListener('hidden.bs.modal', function() {
      // Reset modal content when it's closed to prevent stale data
      document.getElementById('patientName').textContent = 'Patient Name';
      document.getElementById('patientId').textContent = 'P10045';
      document.getElementById('patientAge').textContent = '45';
      document.getElementById('patientGender').textContent = 'Male';
      document.getElementById('patientContact').textContent = '(555) 123-4567';
      document.getElementById('patientEmail').textContent = 'patient@example.com';
      document.getElementById('patientAddress').textContent = '123 Main St, Anytown, USA';
      document.getElementById('patientMedicalHistory').textContent = 'No significant medical history';
      // Clean up any resources or reset state if needed
      console.log('Modal closed');
      
      // Remove any lingering backdrop
      const backdrops = document.getElementsByClassName('modal-backdrop');
      for (let i = 0; i < backdrops.length; i++) {
        backdrops[i].parentNode.removeChild(backdrops[i]);
      }
      // Ensure body doesn't have modal-open class
      document.body.classList.remove('modal-open');
      // Remove inline style from body
      document.body.style.removeProperty('padding-right');
      document.body.style.removeProperty('overflow');
    });
  }
});
</script>

</body>
</html>
