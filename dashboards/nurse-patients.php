<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
// if (!isset($_SESSION['user_id'])) {
//     header("Location: ../login.php");
//     exit();
// }

// Placeholder user data - replace with actual data from your database
$user = [
    'id' => 1,
    'name' => 'Nurse Johnny Sins',
    'profile_pic' => '../assets/img/doctor-profile.jpg',
    'role' => 'Nurse 1'
];

// Placeholder for patient data - replace with actual data from your database
$patients = [
    [
        'id' => 'P10045', 
        'name' => 'Robert Johnson', 
        'age' => 45, 
        'gender' => 'Male', 
        'contact' => '(555) 123-4567', 
        'last_visit' => '2023-06-15'
    ],
    [
        'id' => 'P10089', 
        'name' => 'Linda Williams', 
        'age' => 38, 
        'gender' => 'Female', 
        'contact' => '(555) 987-6543', 
        'last_visit' => '2023-06-10'
    ],
    [
        'id' => 'P10023', 
        'name' => 'Thomas Anderson', 
        'age' => 52, 
        'gender' => 'Male', 
        'contact' => '(555) 456-7890', 
        'last_visit' => '2023-06-05'
    ],
    [
        'id' => 'P10067', 
        'name' => 'Emily Davis', 
        'age' => 29, 
        'gender' => 'Female', 
        'contact' => '(555) 234-5678', 
        'last_visit' => '2023-06-18'
    ],
    [
        'id' => 'P10112', 
        'name' => 'Michael Brown', 
        'age' => 41, 
        'gender' => 'Male', 
        'contact' => '(555) 876-5432', 
        'last_visit' => '2023-06-20'
    ]
];

// Placeholder for notifications - replace with actual data
$notifications = [
    ['type' => 'Lab Result', 'message' => 'New lab results for patient Emily Davis', 'time' => '2 hours ago'],
    ['type' => 'Reminder', 'message' => 'Follow-up call with John Smith', 'time' => '1 day ago'],
    ['type' => 'System', 'message' => 'System maintenance scheduled for tonight', 'time' => '3 days ago']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Records - Clinic Management System</title>
    
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
        <?php include('nurse-sidebar.php'); ?>
        
        <div class="main-content">
            <!-- Include the navbar/header -->
            <?php include('navbar.php'); ?>

            <!-- Notification Panel -->
            <div class="notification-panel" id="notificationPanel">
                <div class="notification-header">
                    <h6>Notifications</h6>
                    <a href="#" class="mark-all-read">Mark all as read</a>
                </div>
                <div class="notification-body">
                    <?php foreach ($notifications as $notification): ?>
                    <div class="notification-item">
                        <div class="notification-icon <?php echo strtolower($notification['type']); ?>">
                            <i class="bi bi-bell"></i>
                        </div>
                        <div class="notification-content">
                            <p class="notification-text"><?php echo $notification['message']; ?></p>
                            <p class="notification-time"><?php echo $notification['time']; ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="notification-footer">
                    <a href="notifications.php">View all notifications</a>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="search-bar">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Search for patients by name or ID...">
            </div>

            <!-- Patient Records Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Patient Records</h5>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPatientModal">
                                <i class="bi bi-plus-circle"></i> Add New Patient
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Patient ID</th>
                                            <th>Patient Name</th>
                                            <th>Age</th>
                                            <th>Gender</th>
                                            <th>Contact Info</th>
                                            <th>Last Visit Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($patients as $patient): ?>
                                        <tr>
                                            <td><?php echo $patient['id']; ?></td>
                                            <td><?php echo $patient['name']; ?></td>
                                            <td><?php echo $patient['age']; ?></td>
                                            <td><?php echo $patient['gender']; ?></td>
                                            <td><?php echo $patient['contact']; ?></td>
                                            <td><?php echo $patient['last_visit']; ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="View Patient">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Edit Patient">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete Patient">
                                                    <i class="bi bi-trash"></i>
                                                </button>
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

    <!-- Add Patient Modal -->
    <div class="modal fade" id="addPatientModal" tabindex="-1" aria-labelledby="addPatientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPatientModalLabel">
                        <i class="bi bi-person-plus me-2"></i>Add New Patient
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addPatientForm">
                        <div class="mb-3">
                            <label for="patientName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="patientName" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="patientAge" class="form-label">Age</label>
                                <input type="number" class="form-control" id="patientAge" required>
                            </div>
                            <div class="col-md-6">
                                <label for="patientGender" class="form-label">Gender</label>
                                <select class="form-select" id="patientGender" required>
                                    <option value="" selected disabled>Select gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="patientContact" class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="patientContact" required>
                        </div>
                        <div class="mb-3">
                            <label for="patientEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="patientEmail">
                        </div>
                        <div class="mb-3">
                            <label for="patientAddress" class="form-label">Address</label>
                            <textarea class="form-control" id="patientAddress" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="patientMedicalHistory" class="form-label">Medical History</label>
                            <textarea class="form-control" id="patientMedicalHistory" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="savePatientBtn">
                        <i class="bi bi-check-circle me-1"></i> Save Patient
                    </button>
                </div>
            </div>
        </div>
    </div>

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
                    <div class="patient-profile mb-4 text-center">
                        <div class="patient-avatar mb-3">
                            <i class="bi bi-person-circle" style="font-size: 4rem; color: #6c757d;"></i>
                        </div>
                        <h4 id="viewPatientName">Patient Name</h4>
                        <p class="text-muted" id="viewPatientID">Patient ID: P12345</p>
                    </div>
                    
                    <div class="patient-details">
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Age:</div>
                            <div class="col-md-8" id="viewPatientAge">35</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Gender:</div>
                            <div class="col-md-8" id="viewPatientGender">Male</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Contact:</div>
                            <div class="col-md-8" id="viewPatientContact">+1 234-567-8901</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Email:</div>
                            <div class="col-md-8" id="viewPatientEmail">patient@example.com</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Address:</div>
                            <div class="col-md-8" id="viewPatientAddress">123 Main St, Anytown, USA</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Medical History:</div>
                            <div class="col-md-8" id="viewPatientMedicalHistory">No significant medical history.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Patient Modal -->
    <div class="modal fade" id="editPatientModal" tabindex="-1" aria-labelledby="editPatientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPatientModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>Edit Patient
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editPatientForm">
                        <input type="hidden" id="editPatientID">
                        <div class="mb-3">
                            <label for="editPatientName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="editPatientName" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editPatientAge" class="form-label">Age</label>
                                <input type="number" class="form-control" id="editPatientAge" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editPatientGender" class="form-label">Gender</label>
                                <select class="form-select" id="editPatientGender" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editPatientContact" class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="editPatientContact" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPatientEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editPatientEmail">
                        </div>
                        <div class="mb-3">
                            <label for="editPatientAddress" class="form-label">Address</label>
                            <textarea class="form-control" id="editPatientAddress" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editPatientMedicalHistory" class="form-label">Medical History</label>
                            <textarea class="form-control" id="editPatientMedicalHistory" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-success" id="updatePatientBtn">
                        <i class="bi bi-check-circle me-1"></i> Update Patient
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Patient Confirmation Modal -->
    <div class="modal fade" id="deletePatientModal" tabindex="-1" aria-labelledby="deletePatientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePatientModalLabel">
                        <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                        Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this patient record?</p>
                    <p class="text-danger"><strong>Warning:</strong> This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="bi bi-trash me-1"></i> Delete Patient
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Notification bell toggle
        document.addEventListener('DOMContentLoaded', function() {
            const notificationBell = document.getElementById('notificationBell');
            const notificationPanel = document.getElementById('notificationPanel');
            
            if (notificationBell && notificationPanel) {
                notificationBell.addEventListener('click', function(e) {
                    e.preventDefault();
                    notificationPanel.classList.toggle('show');
                });
                
                // Close notification panel when clicking outside
                document.addEventListener('click', function(e) {
                    if (!notificationBell.contains(e.target) && !notificationPanel.contains(e.target)) {
                        notificationPanel.classList.remove('show');
                    }
                });
            }
            
            // Handle view and edit button functionality here
            const viewButtons = document.querySelectorAll('.btn-outline-primary');
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // In a real application, you would fetch patient data from the server
                    // For now, we'll use placeholder data
                    const patientRow = this.closest('tr');
                    const patientName = patientRow.cells[1].textContent;
                    const patientAge = patientRow.cells[2].textContent;
                    const patientGender = patientRow.cells[3].textContent;
                    const patientContact = patientRow.cells[4].textContent;
                    
                    // Set values in the view modal
                    document.getElementById('viewPatientName').textContent = patientName;
                    document.getElementById('viewPatientID').textContent = 'Patient ID: ' + patientRow.cells[0].textContent;
                    document.getElementById('viewPatientAge').textContent = patientAge;
                    document.getElementById('viewPatientGender').textContent = patientGender;
                    document.getElementById('viewPatientContact').textContent = patientContact;
                    
                    // Show the modal
                    const viewModal = new bootstrap.Modal(document.getElementById('viewPatientModal'));
                    viewModal.show();
                });
            });
            
            // Handle edit button clicks
            const editButtons = document.querySelectorAll('.btn-outline-success');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // In a real application, you would fetch patient data from the server
                    // For now, we'll use placeholder data
                    const patientRow = this.closest('tr');
                    const patientID = patientRow.cells[0].textContent;
                    const patientName = patientRow.cells[1].textContent;
                    const patientAge = patientRow.cells[2].textContent;
                    const patientGender = patientRow.cells[3].textContent;
                    const patientContact = patientRow.cells[4].textContent;
                    
                    // Set values in the edit form
                    document.getElementById('editPatientID').value = patientID;
                    document.getElementById('editPatientName').value = patientName;
                    document.getElementById('editPatientAge').value = patientAge;
                    
                    // Set gender dropdown
                    const genderSelect = document.getElementById('editPatientGender');
                    for (let i = 0; i < genderSelect.options.length; i++) {
                        if (genderSelect.options[i].value === patientGender) {
                            genderSelect.selectedIndex = i;
                            break;
                        }
                    }
                    
                    document.getElementById('editPatientContact').value = patientContact;
                    
                    // Show the modal
                    const editModal = new bootstrap.Modal(document.getElementById('editPatientModal'));
                    editModal.show();
                });
            });
            
            // Handle delete button clicks
            const deleteButtons = document.querySelectorAll('.btn-outline-danger');
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Store reference to the patient row
                    patientToDelete = this.closest('tr');
                    
                    // Show confirmation modal
                    const deleteModal = new bootstrap.Modal(document.getElementById('deletePatientModal'));
                    deleteModal.show();
                });
            });
            
            // Handle confirmation button click
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', function() {
                    if (patientToDelete) {
                        // Here you would typically make an AJAX call to delete the patient
                        // For now, we'll just remove it from the DOM
                        patientToDelete.remove();
                        
                        // Close the modal properly
                        const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deletePatientModal'));
                        deleteModal.hide();
                        
                        // Remove modal backdrop manually if it persists
                        const modalBackdrops = document.querySelectorAll('.modal-backdrop');
                        modalBackdrops.forEach(backdrop => {
                            backdrop.remove();
                        });
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                        
                        // Show success message
                        const toastContainer = document.createElement('div');
                        toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
                        toastContainer.style.zIndex = '11';
                        
                        toastContainer.innerHTML = `
                            <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="d-flex">
                                    <div class="toast-body">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Patient record deleted successfully!
                                    </div>
                                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                            </div>
                        `;
                        
                        document.body.appendChild(toastContainer);
                        const toast = new bootstrap.Toast(toastContainer.querySelector('.toast'));
                        toast.show();
                        
                        // Remove toast after it's hidden
                        toastContainer.querySelector('.toast').addEventListener('hidden.bs.toast', function() {
                            toastContainer.remove();
                        });
                    }
                });
            }
            
            // Handle save new patient button click
            const savePatientBtn = document.getElementById('savePatientBtn');
            if (savePatientBtn) {
                savePatientBtn.addEventListener('click', function() {
                    const form = document.getElementById('addPatientForm');
                    
                    // Check form validity
                    if (form.checkValidity()) {
                        // Here you would typically make an AJAX call to save the patient
                        // For now, we'll just close the modal
                        
                        // Close the modal properly
                        const addModal = bootstrap.Modal.getInstance(document.getElementById('addPatientModal'));
                        addModal.hide();
                        
                        // Remove modal backdrop manually if it persists
                        const modalBackdrops = document.querySelectorAll('.modal-backdrop');
                        modalBackdrops.forEach(backdrop => {
                            backdrop.remove();
                        });
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                        
                        // Reset the form
                        form.reset();
                        
                        // Show success message
                        const toastContainer = document.createElement('div');
                        toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
                        toastContainer.style.zIndex = '11';
                        
                        toastContainer.innerHTML = `
                            <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="d-flex">
                                    <div class="toast-body">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Patient added successfully!
                                    </div>
                                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                            </div>
                        `;
                        
                        document.body.appendChild(toastContainer);
                        const toast = new bootstrap.Toast(toastContainer.querySelector('.toast'));
                        toast.show();
                        
                        // Remove toast after it's hidden
                        toastContainer.querySelector('.toast').addEventListener('hidden.bs.toast', function() {
                            toastContainer.remove();
                        });
                        
                        // Reload the page or update the table
                        // location.reload();
                    } else {
                        // Trigger browser's native validation UI
                        form.reportValidity();
                    }
                });
            }
            
            // Handle update patient button click
            const updatePatientBtn = document.getElementById('updatePatientBtn');
            if (updatePatientBtn) {
                updatePatientBtn.addEventListener('click', function() {
                    const form = document.getElementById('editPatientForm');
                    
                    // Check form validity
                    if (form.checkValidity()) {
                        // Here you would typically make an AJAX call to update the patient
                        // For now, we'll just close the modal
                        
                        // Close the modal properly
                        const editModal = bootstrap.Modal.getInstance(document.getElementById('editPatientModal'));
                        editModal.hide();
                        
                        // Remove modal backdrop manually if it persists
                        const modalBackdrops = document.querySelectorAll('.modal-backdrop');
                        modalBackdrops.forEach(backdrop => {
                            backdrop.remove();
                        });
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                        
                        // Show success message
                        const toastContainer = document.createElement('div');
                        toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
                        toastContainer.style.zIndex = '11';
                        
                        toastContainer.innerHTML = `
                            <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="d-flex">
                                    <div class="toast-body">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Patient updated successfully!
                                    </div>
                                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                            </div>
                        `;
                        
                        document.body.appendChild(toastContainer);
                        const toast = new bootstrap.Toast(toastContainer.querySelector('.toast'));
                        toast.show();
                        
                        // Remove toast after it's hidden
                        toastContainer.querySelector('.toast').addEventListener('hidden.bs.toast', function() {
                            toastContainer.remove();
                        });
                    } else {
                        // Trigger browser's native validation UI
                        form.reportValidity();
                    }
                });
            }
            
            // Global modal backdrop fix for all modals
            document.addEventListener('hidden.bs.modal', function() {
                // Remove modal backdrop manually if it persists
                setTimeout(() => {
                    const modalBackdrops = document.querySelectorAll('.modal-backdrop');
                    if (modalBackdrops.length > 0 && !document.querySelector('.modal.show')) {
                        modalBackdrops.forEach(backdrop => {
                            backdrop.remove();
                        });
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                    }
                }, 300);
            });
        });
remove    </script>

<style>
    /* Modal styling */
    .modal-content {
        border-radius: 0.5rem;
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .modal-header {
        border-bottom: 1px solid #e9ecef;
        background-color: #f8f9fa;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }
    
    .modal-footer {
        border-top: 1px solid #e9ecef;
        background-color: #f8f9fa;
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }
    
    /* Form styling */
    .form-control:focus, .form-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
    }
    
    /* Button hover effects */
    .btn-primary:hover, .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.2s;
    }
    
    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }
    
    /* Patient profile in view modal */
    .patient-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    /* Responsive adjustments */
    @media (max-width: 576px) {
        .patient-details .row {
            margin-bottom: 1rem;
        }
        
        .patient-details .col-md-4 {
            margin-bottom: 0.25rem;
        }
    }
</style>

<script>
    // Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.search-bar input');
    const tableRows = document.querySelectorAll('tbody tr');
    
    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        
        tableRows.forEach(row => {
            const patientId = row.cells[0].textContent.toLowerCase();
            const patientName = row.cells[1].textContent.toLowerCase();
            
            // Check if either patient ID or name contains the search term
            if (patientId.includes(searchTerm) || patientName.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Notification bell toggle
    const notificationBell = document.getElementById('notificationBell');
    const notificationPanel = document.getElementById('notificationPanel');
    
    if (notificationBell && notificationPanel) {
        notificationBell.addEventListener('click', function(e) {
            e.preventDefault();
            notificationPanel.classList.toggle('show');
        });
        
        // Close notification panel when clicking outside
        document.addEventListener('click', function(e) {
            if (!notificationBell.contains(e.target) && !notificationPanel.contains(e.target)) {
                notificationPanel.classList.remove('show');
            }
        });
    }
    
    // Handle view and edit button functionality here
    const viewButtons = document.querySelectorAll('.btn-outline-primary');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            // In a real application, you would fetch patient data from the server
            // For now, we'll use placeholder data
            const patientRow = this.closest('tr');
            const patientName = patientRow.cells[1].textContent;
            const patientAge = patientRow.cells[2].textContent;
            const patientGender = patientRow.cells[3].textContent;
            const patientContact = patientRow.cells[4].textContent;
            
            // Set values in the view modal
            document.getElementById('viewPatientName').textContent = patientName;
            document.getElementById('viewPatientID').textContent = 'Patient ID: ' + patientRow.cells[0].textContent;
            document.getElementById('viewPatientAge').textContent = patientAge;
            document.getElementById('viewPatientGender').textContent = patientGender;
            document.getElementById('viewPatientContact').textContent = patientContact;
            
            // Show the modal
            const viewModal = new bootstrap.Modal(document.getElementById('viewPatientModal'));
            viewModal.show();
        });
    });
    
    // Handle edit button clicks
    const editButtons = document.querySelectorAll('.btn-outline-success');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            // In a real application, you would fetch patient data from the server
            // For now, we'll use placeholder data
            const patientRow = this.closest('tr');
            const patientID = patientRow.cells[0].textContent;
            const patientName = patientRow.cells[1].textContent;
            const patientAge = patientRow.cells[2].textContent;
            const patientGender = patientRow.cells[3].textContent;
            const patientContact = patientRow.cells[4].textContent;
            
            // Set values in the edit form
            document.getElementById('editPatientID').value = patientID;
            document.getElementById('editPatientName').value = patientName;
            document.getElementById('editPatientAge').value = patientAge;
            
            // Set gender dropdown
            const genderSelect = document.getElementById('editPatientGender');
            for (let i = 0; i < genderSelect.options.length; i++) {
                if (genderSelect.options[i].value === patientGender) {
                    genderSelect.selectedIndex = i;
                    break;
                }
            }
            
            document.getElementById('editPatientContact').value = patientContact;
            
            // Show the modal
            const editModal = new bootstrap.Modal(document.getElementById('editPatientModal'));
            editModal.show();
        });
    });
    
    // Handle delete button clicks
    const deleteButtons = document.querySelectorAll('.btn-outline-danger');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            // Store reference to the patient row
            patientToDelete = this.closest('tr');
            
            // Show confirmation modal
            const deleteModal = new bootstrap.Modal(document.getElementById('deletePatientModal'));
            deleteModal.show();
        });
    });
    
    // Handle confirmation button click
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            if (patientToDelete) {
                // Here you would typically make an AJAX call to delete the patient
                // For now, we'll just remove it from the DOM
                patientToDelete.remove();
                
                // Close the modal properly
                const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deletePatientModal'));
                deleteModal.hide();
                
                // Remove modal backdrop manually if it persists
                const modalBackdrops = document.querySelectorAll('.modal-backdrop');
                modalBackdrops.forEach(backdrop => {
                    backdrop.remove();
                });
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                
                // Show success message
                const toastContainer = document.createElement('div');
                toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
                toastContainer.style.zIndex = '11';
                
                toastContainer.innerHTML = `
                    <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="bi bi-check-circle me-2"></i>
                                Patient record deleted successfully!
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(toastContainer);
                const toast = new bootstrap.Toast(toastContainer.querySelector('.toast'));
                toast.show();
                
                // Remove toast after it's hidden
                toastContainer.querySelector('.toast').addEventListener('hidden.bs.toast', function() {
                    toastContainer.remove();
                });
            }
        });
    }
    
    // Handle save new patient button click
    const savePatientBtn = document.getElementById('savePatientBtn');
    if (savePatientBtn) {
        savePatientBtn.addEventListener('click', function() {
            const form = document.getElementById('addPatientForm');
            
            // Check form validity
            if (form.checkValidity()) {
                // Here you would typically make an AJAX call to save the patient
                // For now, we'll just close the modal
                
                // Close the modal properly
                const addModal = bootstrap.Modal.getInstance(document.getElementById('addPatientModal'));
                addModal.hide();
                
                // Remove modal backdrop manually if it persists
                const modalBackdrops = document.querySelectorAll('.modal-backdrop');
                modalBackdrops.forEach(backdrop => {
                    backdrop.remove();
                });
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                
                // Reset the form
                form.reset();
                
                // Show success message
                const toastContainer = document.createElement('div');
                toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
                toastContainer.style.zIndex = '11';
                
                toastContainer.innerHTML = `
                    <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="bi bi-check-circle me-2"></i>
                                Patient added successfully!
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(toastContainer);
                const toast = new bootstrap.Toast(toastContainer.querySelector('.toast'));
                toast.show();
                
                // Remove toast after it's hidden
                toastContainer.querySelector('.toast').addEventListener('hidden.bs.toast', function() {
                    toastContainer.remove();
                });
                
                // Reload the page or update the table
                // location.reload();
            } else {
                // Trigger browser's native validation UI
                form.reportValidity();
            }
        });
    }
    
    // Handle update patient button click
    const updatePatientBtn = document.getElementById('updatePatientBtn');
    if (updatePatientBtn) {
        updatePatientBtn.addEventListener('click', function() {
            const form = document.getElementById('editPatientForm');
            
            // Check form validity
            if (form.checkValidity()) {
                // Here you would typically make an AJAX call to update the patient
                // For now, we'll just close the modal
                
                // Close the modal properly
                const editModal = bootstrap.Modal.getInstance(document.getElementById('editPatientModal'));
                editModal.hide();
                
                // Remove modal backdrop manually if it persists
                const modalBackdrops = document.querySelectorAll('.modal-backdrop');
                modalBackdrops.forEach(backdrop => {
                    backdrop.remove();
                });
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                
                // Show success message
                const toastContainer = document.createElement('div');
                toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
                toastContainer.style.zIndex = '11';
                
                toastContainer.innerHTML = `
                    <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="bi bi-check-circle me-2"></i>
                                Patient updated successfully!
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(toastContainer);
                const toast = new bootstrap.Toast(toastContainer.querySelector('.toast'));
                toast.show();
                
                // Remove toast after it's hidden
                toastContainer.querySelector('.toast').addEventListener('hidden.bs.toast', function() {
                    toastContainer.remove();
                });
            } else {
                // Trigger browser's native validation UI
                form.reportValidity();
            }
        });
    }
    
    // Global modal backdrop fix for all modals
    document.addEventListener('hidden.bs.modal', function() {
        // Remove modal backdrop manually if it persists
        setTimeout(() => {
            const modalBackdrops = document.querySelectorAll('.modal-backdrop');
            if (modalBackdrops.length > 0 && !document.querySelector('.modal.show')) {
                modalBackdrops.forEach(backdrop => {
                    backdrop.remove();
                });
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            }
        }, 300);
    });
});

</script>
