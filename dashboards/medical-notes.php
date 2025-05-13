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
    'name' => 'Dr. Sarah Johnson',
    'profile_pic' => '../assets/img/doctor-profile.jpg',
    'role' => 'Doctor - Cardiologist'
];

// Placeholder for notifications - replace with actual data
$notifications = [
    ['type' => 'Lab Result', 'message' => 'New lab results for patient Emily Davis', 'time' => '2 hours ago'],
    ['type' => 'Reminder', 'message' => 'Follow-up call with John Smith', 'time' => '1 day ago'],
    ['type' => 'System', 'message' => 'System maintenance scheduled for tonight', 'time' => '3 days ago']
];

// Placeholder for medical notes - replace with actual data from your database
$medicalNotes = [
    [
        'id' => 'N10045', 
        'patient' => 'Emily Davis', 
        'date' => '2023-06-15',
        'title' => 'Medication Change Follow-up', 
        'summary' => 'Patient reported improvement in symptoms after medication change.'
    ],
    [
        'id' => 'N10089', 
        'patient' => 'John Smith', 
        'date' => '2023-06-12',
        'title' => 'Hypertension Treatment', 
        'summary' => 'Prescribed new medication for hypertension. Follow-up in 2 weeks.'
    ],
    [
        'id' => 'N10023', 
        'patient' => 'Thomas Anderson', 
        'date' => '2023-06-05',
        'title' => 'Headache Investigation', 
        'summary' => 'Patient complained of persistent headaches. Ordered additional tests.'
    ],
    [
        'id' => 'N10067', 
        'patient' => 'Linda Williams', 
        'date' => '2023-06-18',
        'title' => 'Post-Surgery Follow-up', 
        'summary' => 'Post-surgery follow-up. Wound healing well, no signs of infection.'
    ],
    [
        'id' => 'N10112', 
        'patient' => 'Michael Brown', 
        'date' => '2023-06-20',
        'title' => 'Lab Results Review', 
        'summary' => 'Reviewed lab results. All values within normal range.'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Notes - Clinic Management System</title>
    
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
                <input type="text" placeholder="Search for notes by patient name...">
            </div>

            <!-- Medical Notes Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Medical Notes</h5>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createNoteModal">
                                <i class="bi bi-plus-circle"></i> Create Note
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Note ID</th>
                                            <th>Patient Name</th>
                                            <th>Date</th>
                                            <th>Summary</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($medicalNotes as $note): ?>
                                        <tr>
                                            <td><?php echo $note['id']; ?></td>
                                            <td><?php echo $note['patient']; ?></td>
                                            <td><?php echo $note['date']; ?></td>
                                            <td><?php echo $note['summary']; ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary view-note-btn" data-bs-toggle="modal" data-bs-target="#viewNoteModal" data-note-id="<?php echo $note['id']; ?>" data-note-patient="<?php echo $note['patient']; ?>" data-note-date="<?php echo $note['date']; ?>" data-note-title="<?php echo isset($note['title']) ? $note['title'] : 'Medical Note'; ?>" data-note-summary="<?php echo $note['summary']; ?>">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-success edit-note-btn" data-bs-toggle="modal" data-bs-target="#editNoteModal" data-note-id="<?php echo $note['id']; ?>" data-note-patient="<?php echo $note['patient']; ?>" data-note-date="<?php echo $note['date']; ?>" data-note-summary="<?php echo $note['summary']; ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger delete-note-btn" data-bs-toggle="modal" data-bs-target="#deleteNoteModal" data-note-id="<?php echo $note['id']; ?>" data-note-patient="<?php echo $note['patient']; ?>">
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

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle sidebar on mobile
        document.getElementById('toggle-sidebar').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.main-content').classList.toggle('active');
        });
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Remove conflicting notification bell code
        // Notification bell functionality is now handled globally in navbar.php
        
        // Highlight active sidebar link
        document.addEventListener('DOMContentLoaded', function() {
            // Get all sidebar links
            var sidebarLinks = document.querySelectorAll('.sidebar-menu a');
            
            // Remove active class from all links
            sidebarLinks.forEach(function(link) {
                link.classList.remove('active');
            });
            
            // Add active class to Medical Notes link
            var medicalNotesLink = document.querySelector('.sidebar-menu a[href="medical-notes.php"]');
            if (medicalNotesLink) {
                medicalNotesLink.classList.add('active');
            }
        });
    </script>
</body>
</html>

    <!-- Create Note Modal -->
    <div class="modal fade" id="createNoteModal" tabindex="-1" aria-labelledby="createNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createNoteModalLabel">
                        <i class="bi bi-journal-medical me-2"></i>
                        Create Medical Note
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createNoteForm">
                        <div class="mb-3">
                            <label for="patientSelect" class="form-label">Patient Name / ID</label>
                            <select class="form-select" id="patientSelect" required>
                                <option value="" selected disabled>Select a patient</option>
                                <?php foreach ($patients as $patient): ?>
                                <option value="<?php echo $patient['id']; ?>"><?php echo $patient['name']; ?> (<?php echo $patient['id']; ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="noteTitle" class="form-label">Note Title</label>
                            <input type="text" class="form-control" id="noteTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="noteDetails" class="form-label">Description / Details</label>
                            <textarea class="form-control" id="noteDetails" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="noteDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="noteDate" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="saveNoteBtn">
                        <i class="bi bi-check2-circle me-1"></i> Save Note
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Note Modal -->
    <div class="modal fade" id="viewNoteModal" tabindex="-1" aria-labelledby="viewNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewNoteModalLabel">
                        <i class="bi bi-journal-medical me-2"></i>Medical Note Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="avatar-container mb-3">
                            <i class="bi bi-journal-text" style="font-size: 4rem; color: #6c757d;"></i>
                        </div>
                        <h4 id="viewNotePatient">Patient Name</h4>
                        <p class="text-muted">Note ID: <span id="viewNoteId">N10045</span></p>
                    </div>
                    
                    <div class="note-details">
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Title:</div>
                            <div class="col-md-8" id="viewNoteTitle">Medication Change Follow-up</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Date:</div>
                            <div class="col-md-8" id="viewNoteDate">2023-06-15</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Summary:</div>
                            <div class="col-md-8" id="viewNoteSummary">Patient reported improvement in symptoms after medication change.</div>
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

    <!-- Edit Note Modal -->
    <div class="modal fade" id="editNoteModal" tabindex="-1" aria-labelledby="editNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editNoteModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit Medical Note
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editNoteForm">
                        <input type="hidden" id="editNoteId">
                        <div class="mb-3">
                            <label for="editNotePatient" class="form-label">Patient Name</label>
                            <input type="text" class="form-control" id="editNotePatient" required>
                        </div>
                        <div class="mb-3">
                            <label for="editNoteDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="editNoteDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="editNoteSummary" class="form-label">Summary</label>
                            <textarea class="form-control" id="editNoteSummary" rows="4" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-success" id="updateNoteBtn">
                        <i class="bi bi-check2-circle me-1"></i> Update Note
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Note Modal -->
    <div class="modal fade" id="deleteNoteModal" tabindex="-1" aria-labelledby="deleteNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteNoteModalLabel">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the medical note for <strong id="deleteNotePatient"></strong>?</p>
                    <p class="text-danger"><small>This action cannot be undone.</small></p>
                    <input type="hidden" id="deleteNoteId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="bi bi-trash me-1"></i> Delete Note
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container for all notifications -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <!-- Success Toast -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="noteToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-success text-white">
                    <i class="bi bi-check-circle me-2"></i>
                    <strong class="me-auto">Success</strong>
                    <small>Just now</small>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    Medical note has been saved successfully!
                </div>
            </div>
        </div>
        
        <!-- Update Success Toast -->
        <div id="updateSuccessToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <i class="bi bi-check-circle me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Medical note has been successfully updated!
            </div>
        </div>
        
        <!-- Delete Success Toast -->
        <div id="deleteSuccessToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-danger text-white">
                <i class="bi bi-check-circle me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Medical note has been successfully deleted!
            </div>
        </div>
    </div>

    <!-- Add custom styles for the modals -->
    <style>
        /* Modal animation */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
        }
        
        /* Form element styling */
        .form-control:hover, .form-select:hover {
            border-color: #80bdff;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        }
        
        /* Button hover effects */
        .btn-success:hover, .btn-danger:hover {
            transform: translateY(-1px);
            transition: all 0.2s;
        }
        
        /* Toast styling */
        .toast {
            opacity: 1;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        /* Responsive adjustments */
        @media (max-width: 576px) {
            .modal-dialog {
                margin: 0.5rem;
            }
            
            .toast {
                width: 300px;
            }
        }
    </style>

    <!-- Add JavaScript for modal functionality -->
    <script>
        // Function to populate the view note modal with data
        document.addEventListener('DOMContentLoaded', function() {
            // View Note Modal
            // View Note Modal
            const viewNoteModal = document.getElementById('viewNoteModal');
            if (viewNoteModal) {
                viewNoteModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const noteId = button.getAttribute('data-note-id');
                    const notePatient = button.getAttribute('data-note-patient');
                    const noteDate = button.getAttribute('data-note-date');
                    const noteTitle = button.getAttribute('data-note-title') || 'Medical Note'; // Default value if title is missing
                    const noteSummary = button.getAttribute('data-note-summary');
                    
                    // Update modal content
                    document.getElementById('viewNoteId').textContent = noteId;
                    document.getElementById('viewNotePatient').textContent = notePatient;
                    document.getElementById('viewNoteDate').textContent = noteDate;
                    document.getElementById('viewNoteTitle').textContent = noteTitle;
                    document.getElementById('viewNoteSummary').textContent = noteSummary;
                });
            }
            
            // Clean up modal when it's closed
            if (viewNoteModal) {
                viewNoteModal.addEventListener('hidden.bs.modal', function() {
                    // Reset modal content when it's closed to prevent stale data
                    document.getElementById('viewNotePatient').textContent = 'Patient Name';
                    document.getElementById('viewNoteId').textContent = 'N10045';
                    document.getElementById('viewNoteTitle').textContent = 'Medication Change Follow-up';
                    document.getElementById('viewNoteDate').textContent = '2023-06-15';
                    document.getElementById('viewNoteSummary').textContent = 'Patient reported improvement in symptoms after medication change.';
                });
            }
            
            // Edit Note Modal
            const editNoteModal = document.getElementById('editNoteModal');
            if (editNoteModal) {
                editNoteModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const noteId = button.getAttribute('data-note-id');
                    const notePatient = button.getAttribute('data-note-patient');
                    const noteDate = button.getAttribute('data-note-date');
                    const noteSummary = button.getAttribute('data-note-summary');
                    
                    // Update form fields
                    document.getElementById('editNoteId').value = noteId;
                    document.getElementById('editNotePatient').value = notePatient;
                    document.getElementById('editNoteDate').value = noteDate;
                    document.getElementById('editNoteSummary').value = noteSummary;
                });
            }
            
            // Delete Note Modal
            const deleteNoteModal = document.getElementById('deleteNoteModal');
            if (deleteNoteModal) {
                deleteNoteModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const noteId = button.getAttribute('data-note-id');
                    const notePatient = button.getAttribute('data-note-patient');
                    
                    // Update modal content
                    document.getElementById('deleteNoteId').value = noteId;
                    document.getElementById('deleteNotePatient').textContent = notePatient;
                });
            }
            
            // Handle Update Note button click
            const updateNoteBtn = document.getElementById('updateNoteBtn');
            if (updateNoteBtn) {
                updateNoteBtn.addEventListener('click', function() {
                    const form = document.getElementById('editNoteForm');
                    
                    // Check form validity
                    if (form.checkValidity()) {
                        // Here you would normally send the data to the server
                        // For now, we'll just show the success toast
                        
                        // Get the note ID and update the table row (in a real app)
                        const noteId = document.getElementById('editNoteId').value;
                        const notePatient = document.getElementById('editNotePatient').value;
                        const noteDate = document.getElementById('editNoteDate').value;
                        const noteSummary = document.getElementById('editNoteSummary').value;
                        
                        // Close the modal
                        const modal = bootstrap.Modal.getInstance(editNoteModal);
                        modal.hide();
                        
                        // Show success toast
                        const toast = new bootstrap.Toast(document.getElementById('updateSuccessToast'));
                        toast.show();
                    } else {
                        // Trigger browser's native validation UI
                        form.reportValidity();
                    }
                });
            }
            
            // Handle Delete Note button click
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', function() {
                    // Get the note ID
                    const noteId = document.getElementById('deleteNoteId').value;
                    
                    // Here you would normally send a delete request to the server
                    // For now, we'll just show the success toast
                    
                    // Close the modal
                    const modal = bootstrap.Modal.getInstance(deleteNoteModal);
                    modal.hide();
                    
                    // Show success toast
                    const toast = new bootstrap.Toast(document.getElementById('deleteSuccessToast'));
                    toast.show();
                });
            }
            
            // Auto-focus on first field when edit modal opens
            if (editNoteModal) {
                editNoteModal.addEventListener('shown.bs.modal', function() {
                    document.getElementById('editNotePatient').focus();
                });
            }
        });
    </script>

    <!-- Add JavaScript for form handling -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-focus on note title when modal opens
            const createNoteModal = document.getElementById('createNoteModal');
            if (createNoteModal) {
                createNoteModal.addEventListener('shown.bs.modal', function() {
                    document.getElementById('noteTitle').focus();
                });
            }

            // Reset form when modal is closed
            createNoteModal.addEventListener('hidden.bs.modal', function() {
                document.getElementById('createNoteForm').reset();
            });

            // Handle save button click
            const saveNoteBtn = document.getElementById('saveNoteBtn');
            if (saveNoteBtn) {
                saveNoteBtn.addEventListener('click', function() {
                    const form = document.getElementById('createNoteForm');
                    
                    // Check form validity
                    if (form.checkValidity()) {
                        // Here you would normally send the data to the server
                        // For now, we'll just show the success toast
                        
                        // Close the modal
                        const modal = bootstrap.Modal.getInstance(createNoteModal);
                        modal.hide();
                        
                        // Show success toast
                        const toast = new bootstrap.Toast(document.getElementById('noteToast'));
                        toast.show();
                        
                        // Reset the form
                        form.reset();
                    } else {
                        // Trigger browser's native validation UI
                        form.reportValidity();
                    }
                });
            }
        });
    </script>
</body>
</html>