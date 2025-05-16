<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Sample data for demonstration
$user = [
    'id' => 1,
    'name' => 'Nurse Johnny Sins',
    'profile_pic' => '../assets/img/doctor-profile.jpg',
    'role' => 'Nurse 1'
];

$patients = [
    ['id' => 1, 'name' => 'John Smith'],
    ['id' => 2, 'name' => 'Emily Davis'],
    ['id' => 3, 'name' => 'Michael Brown'],
    ['id' => 4, 'name' => 'Sarah Johnson'],
    ['id' => 5, 'name' => 'David Wilson']
];

$medications = [
    [
        'id' => 1,
        'patient_id' => 1,
        'patient_name' => 'John Smith',
        'medication_name' => 'Amoxicillin',
        'dosage' => '500mg',
        'scheduled_time' => '08:00:00',
        'status' => 'Pending',
        'notes' => 'Take with food'
    ],
    [
        'id' => 2,
        'patient_id' => 1,
        'patient_name' => 'John Smith',
        'medication_name' => 'Ibuprofen',
        'dosage' => '400mg',
        'scheduled_time' => '14:00:00',
        'status' => 'Given',
        'notes' => 'For pain relief'
    ],
    [
        'id' => 3,
        'patient_id' => 2,
        'patient_name' => 'Emily Davis',
        'medication_name' => 'Lisinopril',
        'dosage' => '10mg',
        'scheduled_time' => '09:00:00',
        'status' => 'Pending',
        'notes' => 'For blood pressure'
    ],
    [
        'id' => 4,
        'patient_id' => 3,
        'patient_name' => 'Michael Brown',
        'medication_name' => 'Metformin',
        'dosage' => '850mg',
        'scheduled_time' => '12:00:00',
        'status' => 'Given',
        'notes' => 'Take with meal'
    ],
    [
        'id' => 5,
        'patient_id' => 4,
        'patient_name' => 'Sarah Johnson',
        'medication_name' => 'Atorvastatin',
        'dosage' => '20mg',
        'scheduled_time' => '20:00:00',
        'status' => 'Pending',
        'notes' => 'Take at bedtime'
    ],
    [
        'id' => 6,
        'patient_id' => 5,
        'patient_name' => 'David Wilson',
        'medication_name' => 'Levothyroxine',
        'dosage' => '75mcg',
        'scheduled_time' => '07:00:00',
        'status' => 'Given',
        'notes' => 'Take on empty stomach'
    ],
    [
        'id' => 7,
        'patient_id' => 5,
        'patient_name' => 'David Wilson',
        'medication_name' => 'Albuterol',
        'dosage' => '2 puffs',
        'scheduled_time' => '16:00:00',
        'status' => 'Pending',
        'notes' => 'As needed for breathing'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medication Management - Clinic Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/doctor.css">
    <link rel="stylesheet" href="css/medications.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Include the sidebar -->
        <?php include('nurse-sidebar.php'); ?>
        
        <div class="main-content">
            <!-- Include the navbar/header -->
            <?php include('navbar.php'); ?>

            <!-- Main Content -->
            <div class="container-fluid py-4">
                <div class="row mb-4">
                    <div class="col-12">
                        <h4 class="mb-0">
                            Medication Management
                        </h4>
                    </div>
                </div>
                
                <!-- Search and Filter Section -->
                <div class="search-filter-container">
                    <div class="row align-items-center">
                        <div class="col-md-8 mb-3 mb-md-0">
                            <div class="position-relative">
                                <input type="text" class="form-control search-input" id="searchMedication" placeholder="Search patient or medication...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="statusFilter">
                                <option value="all">All Status</option>
                                <option value="Pending">Pending</option>
                                <option value="Given">Given</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Medications Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover medication-table">
                                <thead>
                                    <tr>
                                        <th>Patient Name</th>
                                        <th>Medication</th>
                                        <th>Dosage</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($medications as $medication): ?>
                                    <tr data-status="<?php echo $medication['status']; ?>">
                                        <td class="ps-3"><?php echo $medication['patient_name']; ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-capsule me-2 medication-icon"></i>
                                                <?php echo $medication['medication_name']; ?>
                                            </div>
                                        </td>
                                        <td><?php echo $medication['dosage']; ?></td>
                                        <td><?php echo date('h:i A', strtotime($medication['scheduled_time'])); ?></td>
                                        <td>
                                            <span class="badge rounded-pill <?php echo $medication['status'] == 'Given' ? 'bg-success' : 'bg-danger'; ?>">
                                                <?php echo $medication['status']; ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($medication['status'] == 'Pending'): ?>
                                            <button class="btn btn-sm btn-outline-success mark-given" data-id="<?php echo $medication['id']; ?>" data-bs-toggle="tooltip" title="Mark as Given">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                            <?php else: ?>
                                            <button class="btn btn-sm btn-outline-secondary" disabled>
                                                <i class="bi bi-check-circle-fill"></i>
                                            </button>
                                            <?php endif; ?>
                                            <button class="btn btn-sm btn-outline-info view-details" data-id="<?php echo $medication['id']; ?>" data-bs-toggle="tooltip" title="View Details">
                                                <i class="bi bi-info-circle"></i>
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
    </div>

    <!-- Add Medication Button (Fixed) -->
    <button class="btn btn-add-medication" data-bs-toggle="modal" data-bs-target="#addMedicationModal">
        <i class="bi bi-plus"></i>
    </button>

    <!-- Add Medication Modal -->
    <div class="modal fade" id="addMedicationModal" tabindex="-1" aria-labelledby="addMedicationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMedicationModalLabel">
                        <i class="bi bi-capsule me-2"></i>Add New Medication
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addMedicationForm">
                        <div class="mb-3">
                            <label for="patientSelect" class="form-label">Patient</label>
                            <select class="form-select" id="patientSelect" required>
                                <option value="">Select Patient</option>
                                <?php foreach ($patients as $patient): ?>
                                <option value="<?php echo $patient['id']; ?>"><?php echo $patient['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="medicationName" class="form-label">Medication Name</label>
                            <input type="text" class="form-control" id="medicationName" required>
                        </div>
                        <div class="mb-3">
                            <label for="dosage" class="form-label">Dosage</label>
                            <input type="text" class="form-control" id="dosage" required>
                        </div>
                        <div class="mb-3">
                            <label for="scheduledTime" class="form-label">Scheduled Time</label>
                            <input type="time" class="form-control" id="scheduledTime" required>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" rows="2" placeholder="Additional instructions..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveMedication">Add Medication</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Medication Details Modal -->
    <div class="modal fade" id="medicationDetailsModal" tabindex="-1" aria-labelledby="medicationDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="medicationDetailsModalLabel">Medication Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="medicationDetails">
                        <!-- Medication details will be loaded here via JavaScript -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container for Notifications -->
    <div class="toast-container position-fixed top-0 end-0 p-3"></div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sample data for JavaScript functionality
        const medicationsData = <?php echo json_encode($medications); ?>;
        
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    trigger: 'hover'  // Only show on hover, not on click or focus
                });
            });
            
            // Search functionality
            document.getElementById('searchMedication').addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('tbody tr');
                
                tableRows.forEach(row => {
                    const patientName = row.cells[0].textContent.toLowerCase();
                    const medicationName = row.cells[1].textContent.toLowerCase();
                    
                    if (patientName.includes(searchTerm) || medicationName.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
            
            // Status filter functionality
            document.getElementById('statusFilter').addEventListener('change', function() {
                const filterValue = this.value;
                const tableRows = document.querySelectorAll('tbody tr');
                
                tableRows.forEach(row => {
                    if (filterValue === 'all' || row.getAttribute('data-status') === filterValue) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
            
            // View medication details
            document.querySelectorAll('.view-details').forEach(button => {
                button.addEventListener('click', function() {
                    const medicationId = this.getAttribute('data-id');
                    const medication = medicationsData.find(m => m.id == medicationId);
                    
                    if (medication) {
                        const detailsHtml = `
                            <div class="medication-detail-header">
                                <h6>${medication.medication_name} - ${medication.dosage}</h6>
                                <span class="badge ${medication.status === 'Given' ? 'bg-success' : 'bg-danger'}">${medication.status}</span>
                            </div>
                            <div class="medication-detail-body">
                                <p><strong>Patient:</strong> ${medication.patient_name}</p>
                                <p><strong>Scheduled Time:</strong> ${new Date('2023-01-01T' + medication.scheduled_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</p>
                                <p><strong>Notes:</strong> ${medication.notes}</p>
                            </div>
                        `;
                        
                        document.getElementById('medicationDetails').innerHTML = detailsHtml;
                        const modal = new bootstrap.Modal(document.getElementById('medicationDetailsModal'));
                        modal.show();
                    }
                });
            });
            
            // Mark medication as given
            document.querySelectorAll('.mark-given').forEach(button => {
                button.addEventListener('click', function() {
                    const medicationId = this.getAttribute('data-id');
                    const row = this.closest('tr');
                    
                    // Manually hide the tooltip before updating the UI
                    const tooltip = bootstrap.Tooltip.getInstance(this);
                    if (tooltip) {
                        tooltip.hide();
                    }
                    
                    // In a real application, this would send data to the server
                    // For this demo, we'll just update the UI
                    const statusCell = row.cells[4];
                    statusCell.innerHTML = '<span class="badge rounded-pill bg-success">Given</span>';
                    
                    // Update the action buttons
                    this.innerHTML = '<i class="bi bi-check-circle-fill"></i>';
                    this.disabled = true;
                    this.classList.remove('btn-outline-success');
                    this.classList.add('btn-outline-secondary');
                    
                    // Remove tooltip data attribute to prevent it from showing again
                    this.removeAttribute('data-bs-toggle');
                    this.removeAttribute('data-bs-original-title');
                    this.removeAttribute('aria-label');
                    
                    // Update the row's data-status attribute
                    row.setAttribute('data-status', 'Given');
                    
                    // Show toast notification
                    showToast('success', 'Medication marked as given');
                });
            });
            
            // Save new medication
            document.getElementById('saveMedication').addEventListener('click', function() {
                // In a real application, this would send data to the server
                // For this demo, we'll just show a notification and close the modal
                showToast('success', 'New medication added successfully');
                const modal = bootstrap.Modal.getInstance(document.getElementById('addMedicationModal'));
                modal.hide();
                
                // Reset form
                document.getElementById('addMedicationForm').reset();
            });
        });
        
        // Toast notification function
        function showToast(type, message) {
            const toastContainer = document.querySelector('.toast-container');
            
            // Clear any existing toasts first
            toastContainer.innerHTML = '';
            
            const toastEl = document.createElement('div');
            toastEl.className = `toast align-items-center text-white bg-${type} border-0`;
            toastEl.setAttribute('role', 'alert');
            toastEl.setAttribute('aria-live', 'assertive');
            toastEl.setAttribute('aria-atomic', 'true');
            
            // Add data attributes for Bootstrap toast
            toastEl.setAttribute('data-bs-autohide', 'true');
            toastEl.setAttribute('data-bs-delay', '3000');
            
            toastEl.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-check-circle-fill me-2"></i>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            
            toastContainer.appendChild(toastEl);
            
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
            
            // Manual removal after 3 seconds as backup
            setTimeout(() => {
                if (toastContainer.contains(toastEl)) {
                    toastContainer.removeChild(toastEl);
                }
            }, 3500);
        }
    </script>
</body>
</html>