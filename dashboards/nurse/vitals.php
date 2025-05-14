<?php
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

$vitals = [
    [
        'id' => 1,
        'patient_id' => 1,
        'patient_name' => 'John Smith',
        'date_time' => '2023-06-25 09:15:00',
        'blood_pressure' => '120/80',
        'heart_rate' => 72,
        'temperature' => 98.6,
        'respiratory_rate' => 16,
        'oxygen_saturation' => 98,
        'notes' => 'Patient appears healthy. No complaints.'
    ],
    [
        'id' => 2,
        'patient_id' => 2,
        'patient_name' => 'Emily Davis',
        'date_time' => '2023-06-25 10:30:00',
        'blood_pressure' => '135/85',
        'heart_rate' => 88,
        'temperature' => 99.2,
        'respiratory_rate' => 18,
        'oxygen_saturation' => 96,
        'notes' => 'Patient reports mild headache. Advised to rest and hydrate.'
    ],
    [
        'id' => 3,
        'patient_id' => 3,
        'patient_name' => 'Michael Brown',
        'date_time' => '2023-06-24 14:45:00',
        'blood_pressure' => '145/95',
        'heart_rate' => 92,
        'temperature' => 100.1,
        'respiratory_rate' => 20,
        'oxygen_saturation' => 94,
        'notes' => 'Patient has elevated BP and temperature. Scheduled follow-up.'
    ],
    [
        'id' => 4,
        'patient_id' => 4,
        'patient_name' => 'Sarah Johnson',
        'date_time' => '2023-06-23 11:00:00',
        'blood_pressure' => '118/75',
        'heart_rate' => 68,
        'temperature' => 98.4,
        'respiratory_rate' => 14,
        'oxygen_saturation' => 99,
        'notes' => 'Routine check-up. All vitals normal.'
    ],
    [
        'id' => 5,
        'patient_id' => 5,
        'patient_name' => 'David Wilson',
        'date_time' => '2023-06-22 16:15:00',
        'blood_pressure' => '160/100',
        'heart_rate' => 96,
        'temperature' => 98.8,
        'respiratory_rate' => 22,
        'oxygen_saturation' => 93,
        'notes' => 'Hypertension noted. Medication adjusted. Schedule follow-up in 2 weeks.'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vitals Monitoring - Clinic Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/doctor.css">
    <link rel="stylesheet" href="css/vitals.css">
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
                <div class="row">
                    <div class="col-12">
                        <h4 class="mb-4">Vitals Monitoring</h4>
                        
                        <!-- Search and Filter Section -->
                        <div class="search-filter-container">
                            <div class="row align-items-center">
                                <div class="col-md-8 mb-3 mb-md-0">
                                    <div class="position-relative">
                                        <input type="text" class="form-control search-input" id="searchVitals" placeholder="Search patient name...">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" id="dateFilter">
                                        <option value="all">All Time</option>
                                        <option value="today">Today</option>
                                        <option value="week">This Week</option>
                                        <option value="month">This Month</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Vitals Table Section -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Patient Vitals Records</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Patient Name</th>
                                                <th>Date/Time</th>
                                                <th>Blood Pressure</th>
                                                <th>Heart Rate</th>
                                                <th>Temperature</th>
                                                <th>Respiratory Rate</th>
                                                <th>O₂ Saturation</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($vitals as $vital): ?>
                                            <tr>
                                                <td><?php echo $vital['patient_name']; ?></td>
                                                <td><?php echo date('M d, Y h:i A', strtotime($vital['date_time'])); ?></td>
                                                <td class="<?php echo (intval(explode('/', $vital['blood_pressure'])[0]) > 140 || intval(explode('/', $vital['blood_pressure'])[1]) > 90) ? 'vital-danger' : ((intval(explode('/', $vital['blood_pressure'])[0]) > 130 || intval(explode('/', $vital['blood_pressure'])[1]) > 85) ? 'vital-warning' : 'vital-normal'); ?>">
                                                    <?php echo $vital['blood_pressure']; ?> mmHg
                                                </td>
                                                <td class="<?php echo ($vital['heart_rate'] > 100) ? 'vital-danger' : (($vital['heart_rate'] > 90) ? 'vital-warning' : 'vital-normal'); ?>">
                                                    <?php echo $vital['heart_rate']; ?> bpm
                                                </td>
                                                <td class="<?php echo ($vital['temperature'] > 100.4) ? 'vital-danger' : (($vital['temperature'] > 99.5) ? 'vital-warning' : 'vital-normal'); ?>">
                                                    <?php echo $vital['temperature']; ?> °F
                                                </td>
                                                <td class="<?php echo ($vital['respiratory_rate'] > 20) ? 'vital-danger' : (($vital['respiratory_rate'] > 18) ? 'vital-warning' : 'vital-normal'); ?>">
                                                    <?php echo $vital['respiratory_rate']; ?> bpm
                                                </td>
                                                <td class="<?php echo ($vital['oxygen_saturation'] < 94) ? 'vital-danger' : (($vital['oxygen_saturation'] < 96) ? 'vital-warning' : 'vital-normal'); ?>">
                                                    <?php echo $vital['oxygen_saturation']; ?>%
                                                </td>
                                                <td>
                                                    <button class="btn btn-action view-vitals" data-bs-toggle="modal" data-bs-target="#viewVitalsModal" data-vital-id="<?php echo $vital['id']; ?>">
                                                        <i class="bi bi-eye"></i>
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
        </div>
    </div>

    <!-- Add Vitals Button (Fixed) -->
    <button class="btn btn-add-vitals" data-bs-toggle="modal" data-bs-target="#addVitalsModal">
        <i class="bi bi-plus"></i>
    </button>

    <!-- Toast Container -->
    <div class="toast-container"></div>

    <!-- View Vitals Modal -->
    <div class="modal fade" id="viewVitalsModal" tabindex="-1" aria-labelledby="viewVitalsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewVitalsModalLabel">Patient Vitals Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="vitalDetails">
                        <!-- Vital details will be loaded here via JavaScript -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Vitals Modal -->
    <div class="modal fade" id="addVitalsModal" tabindex="-1" aria-labelledby="addVitalsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addVitalsModalLabel">Add New Vitals</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addVitalsForm">
                        <div class="mb-3">
                            <label for="patientSelect" class="form-label">Patient</label>
                            <select class="form-select" id="patientSelect" required>
                                <option value="">Select Patient</option>
                                <?php foreach ($patients as $patient): ?>
                                <option value="<?php echo $patient['id']; ?>"><?php echo $patient['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bloodPressure" class="form-label">Blood Pressure (mmHg)</label>
                                <input type="text" class="form-control" id="bloodPressure" placeholder="120/80" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="heartRate" class="form-label">Heart Rate (bpm)</label>
                                <input type="number" class="form-control" id="heartRate" placeholder="72" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="temperature" class="form-label">Temperature (°F)</label>
                                <input type="number" class="form-control" id="temperature" placeholder="98.6" step="0.1" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="respiratoryRate" class="form-label">Respiratory Rate (bpm)</label>
                                <input type="number" class="form-control" id="respiratoryRate" placeholder="16" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="oxygenSaturation" class="form-label">Oxygen Saturation (%)</label>
                            <input type="number" class="form-control" id="oxygenSaturation" placeholder="98" required>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" rows="3" placeholder="Enter any additional notes here..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveVitals">Save Vitals</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="vitals.js"></script>
    
    <script>
        // Sample data for JavaScript functionality
        const vitalsData = <?php echo json_encode($vitals); ?>;
        
        // View Vitals Modal
        document.querySelectorAll('.view-vitals').forEach(button => {
            button.addEventListener('click', function() {
                const vitalId = this.getAttribute('data-vital-id');
                const vital = vitalsData.find(v => v.id == vitalId);
                
                if (vital) {
                    // Tinanggal ang toast notification dito
                    // showToast('info', 'Viewing Patient', `Displaying vitals for ${vital.patient_name}`);
                    
                    const detailsHtml = `
                        <div class="mb-3">
                            <h6 class="text-primary">${vital.patient_name}</h6>
                            <p class="text-muted mb-3">${new Date(vital.date_time).toLocaleString()}</p>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <p class="mb-1 fw-bold">Blood Pressure:</p>
                                <p>${vital.blood_pressure} mmHg</p>
                            </div>
                            <div class="col-6">
                                <p class="mb-1 fw-bold">Heart Rate:</p>
                                <p>${vital.heart_rate} bpm</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <p class="mb-1 fw-bold">Temperature:</p>
                                <p>${vital.temperature} °F</p>
                            </div>
                            <div class="col-6">
                                <p class="mb-1 fw-bold">Respiratory Rate:</p>
                                <p>${vital.respiratory_rate} bpm</p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <p class="mb-1 fw-bold">Oxygen Saturation:</p>
                            <p>${vital.oxygen_saturation}%</p>
                        </div>
                        <div class="mb-3">
                            <p class="mb-1 fw-bold">Notes:</p>
                            <p>${vital.notes}</p>
                        </div>
                    `;
                    
                    document.getElementById('vitalDetails').innerHTML = detailsHtml;
                }
            });
        });
        
        // Search functionality
        document.getElementById('searchVitals').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');
            
            tableRows.forEach(row => {
                const patientName = row.cells[0].textContent.toLowerCase();
                if (patientName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Date filter functionality
        document.getElementById('dateFilter').addEventListener('change', function() {
            const filterValue = this.value;
            const tableRows = document.querySelectorAll('tbody tr');
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            const weekStart = new Date(today);
            weekStart.setDate(today.getDate() - today.getDay());
            
            const monthStart = new Date(today.getFullYear(), today.getMonth(), 1);
            
            tableRows.forEach(row => {
                const dateStr = row.cells[1].textContent;
                const rowDate = new Date(dateStr);
                
                if (filterValue === 'all') {
                    row.style.display = '';
                } else if (filterValue === 'today' && rowDate >= today) {
                    row.style.display = '';
                } else if (filterValue === 'week' && rowDate >= weekStart) {
                    row.style.display = '';
                } else if (filterValue === 'month' && rowDate >= monthStart) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Save new vitals
        document.getElementById('saveVitals').addEventListener('click', function() {
            // In a real application, this would send data to the server
            // Tinanggal din ang toast notification dito
            // showToast('success', 'Success', 'Vitals saved successfully!');
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('addVitalsModal'));
            modal.hide();
            
            // Reset form
            document.getElementById('addVitalsForm').reset();
        });
    </script>
</body>
</html>