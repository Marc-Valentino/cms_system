<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user = [
    'id' => 1,
    'name' => 'Nurse Johnny Sins',
    'profile_pic' => '../assets/img/doctor-profile.jpg',
    'role' => 'Nurse 1'
];
// Sample data for demonstration
$current_shift = [
    'start_time' => '07:00:00',
    'end_time' => '15:00:00'
];

$upcoming_shifts = [
    [
        'date' => '2023-08-15',
        'start_time' => '07:00:00',
        'end_time' => '15:00:00',
        'department' => 'Emergency'
    ],
    [
        'date' => '2023-08-16',
        'start_time' => '15:00:00',
        'end_time' => '23:00:00',
        'department' => 'Pediatrics'
    ],
    [
        'date' => '2023-08-18',
        'start_time' => '23:00:00',
        'end_time' => '07:00:00',
        'department' => 'ICU'
    ],
    [
        'date' => '2023-08-20',
        'start_time' => '07:00:00',
        'end_time' => '15:00:00',
        'department' => 'General Ward'
    ],
    [
        'date' => '2023-08-21',
        'start_time' => '15:00:00',
        'end_time' => '23:00:00',
        'department' => 'Maternity'
    ]
];

// Format time function
function formatTime($time) {
    return date('h:i A', strtotime($time));
}

// Format date function
function formatDate($date) {
    return date('l, F j, Y', strtotime($date));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shift Schedule - Clinic Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/doctor.css">
    <link rel="stylesheet" href="css/schedule.css">
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
                            Shift Schedule
                        </h4>
                    </div>
                </div>
                
                <!-- Current Shift Overview -->
                <div class="card current-shift-card mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="shift-icon-container me-3">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">You're on shift</h5>
                                        <h3 class="mb-0"><?php echo formatTime($current_shift['start_time']); ?> – <?php echo formatTime($current_shift['end_time']); ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                <div class="current-date" id="currentDateTime">
                                    <div class="current-day mb-1" id="currentDay"></div>
                                    <div class="current-time" id="currentTime"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Search and Filter Section -->
                <div class="search-filter-container mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-8 mb-3 mb-md-0">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0" id="searchSchedule" placeholder="Search by date or department...">
                            </div>
                        </div>
                        <div class="col-md-4 d-flex justify-content-md-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                                <i class="bi bi-plus-circle me-2"></i>Add Schedule
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Upcoming Shifts Calendar -->
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar-week me-2"></i>Upcoming Shifts
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="upcoming-shifts">
                            <?php foreach ($upcoming_shifts as $shift): ?>
                            <div class="shift-item" data-date="<?php echo $shift['date']; ?>" data-department="<?php echo $shift['department']; ?>">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <div class="shift-date">
                                            <div class="day-name"><?php echo date('D', strtotime($shift['date'])); ?></div>
                                            <div class="date-number"><?php echo date('d', strtotime($shift['date'])); ?></div>
                                            <div class="month-name"><?php echo date('M', strtotime($shift['date'])); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="shift-time">
                                            <i class="bi bi-clock me-2"></i>
                                            <?php echo formatTime($shift['start_time']); ?> - <?php echo formatTime($shift['end_time']); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="shift-department">
                                            <i class="bi bi-building me-2"></i>
                                            <?php echo $shift['department']; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-md-end">
                                        <button class="btn btn-sm btn-outline-primary view-details" data-bs-toggle="tooltip" title="View Details">
                                            <i class="bi bi-info-circle"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-warning edit-shift" data-bs-toggle="tooltip" title="Edit Shift">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Schedule Modal -->
    <div class="modal fade" id="addScheduleModal" tabindex="-1" aria-labelledby="addScheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addScheduleModalLabel">
                        <i class="bi bi-calendar-plus me-2"></i>Add New Schedule
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addScheduleForm">
                        <div class="mb-3">
                            <label for="nurseName" class="form-label">Nurse Name</label>
                            <input type="text" class="form-control" id="nurseName" placeholder="Enter nurse name">
                        </div>
                        <div class="mb-3">
                            <label for="shiftDate" class="form-label">Shift Date</label>
                            <input type="date" class="form-control" id="shiftDate">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="startTime" class="form-label">Start Time</label>
                                <input type="time" class="form-control" id="startTime">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="endTime" class="form-label">End Time</label>
                                <input type="time" class="form-control" id="endTime">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <select class="form-select" id="department">
                                <option value="">Select Department</option>
                                <option value="Emergency">Emergency</option>
                                <option value="ICU">ICU</option>
                                <option value="Pediatrics">Pediatrics</option>
                                <option value="Maternity">Maternity</option>
                                <option value="General Ward">General Ward</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" id="notes" rows="3" placeholder="Add any additional notes"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveSchedule">Save Schedule</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Shift Details Modal -->
    <div class="modal fade" id="viewShiftModal" tabindex="-1" aria-labelledby="viewShiftModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewShiftModalLabel">
                        <i class="bi bi-info-circle me-2"></i>Shift Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="shiftDetails">
                        <!-- Content will be dynamically populated -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Shift Modal -->
    <div class="modal fade" id="editShiftModal" tabindex="-1" aria-labelledby="editShiftModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editShiftModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>Edit Shift
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editShiftForm">
                        <input type="hidden" id="editShiftId">
                        <div class="mb-3">
                            <label for="editShiftDate" class="form-label">Shift Date</label>
                            <input type="date" class="form-control" id="editShiftDate">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editStartTime" class="form-label">Start Time</label>
                                <input type="time" class="form-control" id="editStartTime">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editEndTime" class="form-label">End Time</label>
                                <input type="time" class="form-control" id="editEndTime">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editDepartment" class="form-label">Department</label>
                            <select class="form-select" id="editDepartment">
                                <option value="">Select Department</option>
                                <option value="Emergency">Emergency</option>
                                <option value="ICU">ICU</option>
                                <option value="Pediatrics">Pediatrics</option>
                                <option value="Maternity">Maternity</option>
                                <option value="General Ward">General Ward</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editNotes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" id="editNotes" rows="3" placeholder="Add any additional notes"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="updateShift">Update Shift</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container for Notifications -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    trigger: 'hover'
                });
            });
            
            // Update current date and time
            function updateDateTime() {
                const now = new Date();
                const dayElement = document.getElementById('currentDay');
                const timeElement = document.getElementById('currentTime');
                
                dayElement.textContent = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                timeElement.textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            }
            
            // Initial call and set interval
            updateDateTime();
            setInterval(updateDateTime, 60000); // Update every minute
            
            // Search functionality
            document.getElementById('searchSchedule').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const shiftItems = document.querySelectorAll('.shift-item');
                
                shiftItems.forEach(item => {
                    const date = item.getAttribute('data-date').toLowerCase();
                    const department = item.getAttribute('data-department').toLowerCase();
                    
                    if (date.includes(searchTerm) || department.includes(searchTerm)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
            
            // Save new schedule
            document.getElementById('saveSchedule').addEventListener('click', function() {
                // In a real application, this would send data to the server
                // For this demo, we'll just show a notification and close the modal
                showToast('success', 'New schedule added successfully');
                const modal = bootstrap.Modal.getInstance(document.getElementById('addScheduleModal'));
                modal.hide();
                
                // Reset form
                document.getElementById('addScheduleForm').reset();
            });
            
            // View details buttons
            document.querySelectorAll('.view-details').forEach(button => {
                button.addEventListener('click', function() {
                    // Get the parent shift item
                    const shiftItem = this.closest('.shift-item');
                    const date = shiftItem.getAttribute('data-date');
                    const department = shiftItem.getAttribute('data-department');
                    
                    // Get other data from the shift item
                    const timeElement = shiftItem.querySelector('.shift-time');
                    const time = timeElement.textContent.trim().replace('', '');
                    
                    // Format the date for display
                    const formattedDate = new Date(date).toLocaleDateString('en-US', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    });
                    
                    // Populate the modal with shift details
                    const detailsHtml = `
                        <div class="shift-detail-card">
                            <div class="shift-detail-header mb-3 pb-2 border-bottom">
                                <h5 class="mb-1">${formattedDate}</h5>
                                <span class="badge bg-primary">${department}</span>
                            </div>
                            <div class="shift-detail-body">
                                <p><strong><i class="bi bi-clock me-2"></i>Shift Time:</strong> ${time}</p>
                                <p><strong><i class="bi bi-person me-2"></i>Assigned Nurse:</strong> Johnny Sins</p>
                                <p><strong><i class="bi bi-building me-2"></i>Department:</strong> ${department}</p>
                                <p><strong><i class="bi bi-card-text me-2"></i>Notes:</strong> Regular shift duties. Check patient vitals every 2 hours.</p>
                            </div>
                        </div>
                    `;
                    
                    document.getElementById('shiftDetails').innerHTML = detailsHtml;
                    const modal = new bootstrap.Modal(document.getElementById('viewShiftModal'));
                    modal.show();
                });
            });
            
            // Edit shift buttons
            document.querySelectorAll('.edit-shift').forEach(button => {
                button.addEventListener('click', function() {
                    // Get the parent shift item
                    const shiftItem = this.closest('.shift-item');
                    const date = shiftItem.getAttribute('data-date');
                    const department = shiftItem.getAttribute('data-department');
                    
                    // Get time from the shift item
                    const timeElement = shiftItem.querySelector('.shift-time');
                    const timeText = timeElement.textContent.trim();
                    const timeParts = timeText.split('-');
                    const startTime = timeParts[0].trim();
                    const endTime = timeParts[1].trim();
                    
                    // Convert to 24-hour format for the input fields
                    function convertTo24Hour(timeStr) {
                        const [time, modifier] = timeStr.split(' ');
                        let [hours, minutes] = time.split(':');
                        
                        if (hours === '12') {
                            hours = '00';
                        }
                        
                        if (modifier === 'PM') {
                            hours = parseInt(hours, 10) + 12;
                        }
                        
                        return `${hours}:${minutes}`;
                    }
                    
                    // Populate the form fields
                    document.getElementById('editShiftId').value = date; // Using date as ID for demo
                    document.getElementById('editShiftDate').value = date;
                    document.getElementById('editStartTime').value = convertTo24Hour(startTime);
                    document.getElementById('editEndTime').value = convertTo24Hour(endTime);
                    document.getElementById('editDepartment').value = department;
                    document.getElementById('editNotes').value = "Regular shift duties. Check patient vitals every 2 hours.";
                    
                    // Show the modal
                    const modal = new bootstrap.Modal(document.getElementById('editShiftModal'));
                    modal.show();
                });
            });
            
            // Update shift button
            document.getElementById('updateShift').addEventListener('click', function() {
                // In a real application, this would send data to the server
                // For this demo, we'll just show a notification and close the modal
                showToast('success', 'Shift updated successfully');
                const modal = bootstrap.Modal.getInstance(document.getElementById('editShiftModal'));
                modal.hide();
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