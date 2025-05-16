<?php
// Include database configuration
include('../includes/config.php');

// Fetch users with their roles
$users = [
    ['id' => 1, 'name' => 'Dr. Sarah Johnson', 'email' => 'sarah.johnson@example.com', 'role' => 'Doctor', 'status' => 'Active'],
    ['id' => 2, 'name' => 'Nurse Rebecca Adams', 'email' => 'rebecca.adams@example.com', 'role' => 'Nurse', 'status' => 'Active'],
    ['id' => 3, 'name' => 'Admin Michael Chen', 'email' => 'michael.chen@example.com', 'role' => 'Admin', 'status' => 'Active'],
    ['id' => 4, 'name' => 'Dr. James Wilson', 'email' => 'james.wilson@example.com', 'role' => 'Doctor', 'status' => 'Inactive'],
    ['id' => 5, 'name' => 'Receptionist Emily Davis', 'email' => 'emily.davis@example.com', 'role' => 'Receptionist', 'status' => 'Active']
];

// Define permissions for each role
$permissions = [
    'Doctor' => [
        'view_patient_records' => true,
        'edit_patient_records' => true,
        'view_appointments' => true,
        'manage_appointments' => true,
        'view_billing' => true,
        'manage_billing' => false,
        'view_reports' => true,
        'manage_staff' => false
    ],
    'Nurse' => [
        'view_patient_records' => true,
        'edit_patient_records' => true,
        'view_appointments' => true,
        'manage_appointments' => true,
        'view_billing' => false,
        'manage_billing' => false,
        'view_reports' => false,
        'manage_staff' => false
    ],
    'Receptionist' => [
        'view_patient_records' => true,
        'edit_patient_records' => false,
        'view_appointments' => true,
        'manage_appointments' => true,
        'view_billing' => true,
        'manage_billing' => true,
        'view_reports' => false,
        'manage_staff' => false
    ],
    'Admin' => [
        'view_patient_records' => true,
        'edit_patient_records' => true,
        'view_appointments' => true,
        'manage_appointments' => true,
        'view_billing' => true,
        'manage_billing' => true,
        'view_reports' => true,
        'manage_staff' => true
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Role & Permission Management - Clinic Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="user-permission.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Include the sidebar -->
        <?php include('admin-sidebar.php'); ?>
        
        <div class="main-content">
            <!-- Include the navbar/header -->
            <?php include('admin-navbar.php'); ?>

            <!-- User Permission Content -->
            <div class="container-fluid py-4">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5><i class="bi bi-shield-lock me-2"></i>User Role & Permission Management</h5>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignRoleModal">
                                    <i class="bi bi-person-plus-fill me-1"></i> Assign New Role
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                                <input type="text" class="form-control" id="searchUsers" placeholder="Search users...">
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                            <div class="btn-group">
                                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterRole" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Filter by Role
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="filterRole">
                                                    <li><a class="dropdown-item" href="#" data-role="all">All Roles</a></li>
                                                    <li><a class="dropdown-item" href="#" data-role="Doctor">Doctor</a></li>
                                                    <li><a class="dropdown-item" href="#" data-role="Nurse">Nurse</a></li>
                                                    <li><a class="dropdown-item" href="#" data-role="Receptionist">Receptionist</a></li>
                                                    <li><a class="dropdown-item" href="#" data-role="Admin">Admin</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Status</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($users as $user): ?>
                                            <tr>
                                                <td><?php echo $user['name']; ?></td>
                                                <td><?php echo $user['email']; ?></td>
                                                <td>
                                                    <span class="badge rounded-pill 
                                                        <?php 
                                                        switch($user['role']) {
                                                            case 'Doctor': echo 'bg-primary'; break;
                                                            case 'Nurse': echo 'bg-success'; break;
                                                            case 'Receptionist': echo 'bg-info'; break;
                                                            case 'Admin': echo 'bg-danger'; break;
                                                            default: echo 'bg-secondary';
                                                        }
                                                        ?>">
                                                        <?php echo $user['role']; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge rounded-pill <?php echo $user['status'] == 'Active' ? 'bg-success' : 'bg-secondary'; ?>">
                                                        <?php echo $user['status']; ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-outline-primary view-permissions" data-bs-toggle="modal" data-bs-target="#viewPermissionsModal" data-user-id="<?php echo $user['id']; ?>" data-user-name="<?php echo $user['name']; ?>" data-user-role="<?php echo $user['role']; ?>">
                                                        <i class="bi bi-eye"></i> View
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-success edit-permissions" data-bs-toggle="modal" data-bs-target="#editPermissionsModal" data-user-id="<?php echo $user['id']; ?>" data-user-name="<?php echo $user['name']; ?>" data-user-role="<?php echo $user['role']; ?>">
                                                        <i class="bi bi-pencil"></i> Edit
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

    <!-- Assign New Role Modal -->
    <div class="modal fade" id="assignRoleModal" tabindex="-1" aria-labelledby="assignRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignRoleModalLabel">
                        <i class="bi bi-person-plus-fill me-2"></i>Assign New Role
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="assignRoleForm">
                        <div class="mb-3">
                            <label for="selectUser" class="form-label">Select User</label>
                            <select class="form-select" id="selectUser" required>
                                <option value="" selected disabled>Choose a user...</option>
                                <option value="1">Dr. Sarah Johnson</option>
                                <option value="2">Nurse Rebecca Adams</option>
                                <option value="3">Admin Michael Chen</option>
                                <option value="4">Dr. James Wilson</option>
                                <option value="5">Receptionist Emily Davis</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="selectRole" class="form-label">Assign Role</label>
                            <select class="form-select" id="selectRole" required>
                                <option value="" selected disabled>Choose a role...</option>
                                <option value="Doctor">Doctor</option>
                                <option value="Nurse">Nurse</option>
                                <option value="Receptionist">Receptionist</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Permissions</label>
                            <div class="permissions-container">
                                <div class="permission-group">
                                    <h6 class="permission-category">Patient Records</h6>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="viewPatientRecords">
                                        <label class="form-check-label" for="viewPatientRecords">View Patient Records</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="editPatientRecords">
                                        <label class="form-check-label" for="editPatientRecords">Edit Patient Records</label>
                                    </div>
                                </div>
                                
                                <div class="permission-group">
                                    <h6 class="permission-category">Appointments</h6>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="viewAppointments">
                                        <label class="form-check-label" for="viewAppointments">View Appointments</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="manageAppointments">
                                        <label class="form-check-label" for="manageAppointments">Manage Appointments</label>
                                    </div>
                                </div>
                                
                                <div class="permission-group">
                                    <h6 class="permission-category">Billing</h6>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="viewBilling">
                                        <label class="form-check-label" for="viewBilling">View Billing</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="manageBilling">
                                        <label class="form-check-label" for="manageBilling">Manage Billing</label>
                                    </div>
                                </div>
                                
                                <div class="permission-group">
                                    <h6 class="permission-category">Administration</h6>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="viewReports">
                                        <label class="form-check-label" for="viewReports">View Reports</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="manageStaff">
                                        <label class="form-check-label" for="manageStaff">Manage Staff</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="saveRoleBtn">
                        <i class="bi bi-check-circle me-1"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Permissions Modal -->
    <div class="modal fade" id="viewPermissionsModal" tabindex="-1" aria-labelledby="viewPermissionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewPermissionsModalLabel">
                        <i class="bi bi-shield-lock me-2"></i>User Permissions
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="user-info mb-4">
                        <h5 id="viewUserName">User Name</h5>
                        <span class="badge rounded-pill bg-primary mb-2" id="viewUserRole">Role</span>
                    </div>
                    
                    <div class="permissions-list">
                        <div class="permission-category">
                            <h6>Patient Records</h6>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    View Patient Records
                                    <span id="viewPatientRecordsStatus" class="badge rounded-pill bg-success"><i class="bi bi-check-lg"></i></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Edit Patient Records
                                    <span id="editPatientRecordsStatus" class="badge rounded-pill bg-success"><i class="bi bi-check-lg"></i></span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="permission-category mt-3">
                            <h6>Appointments</h6>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    View Appointments
                                    <span id="viewAppointmentsStatus" class="badge rounded-pill bg-success"><i class="bi bi-check-lg"></i></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Manage Appointments
                                    <span id="manageAppointmentsStatus" class="badge rounded-pill bg-success"><i class="bi bi-check-lg"></i></span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="permission-category mt-3">
                            <h6>Billing</h6>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    View Billing
                                    <span id="viewBillingStatus" class="badge rounded-pill bg-success"><i class="bi bi-check-lg"></i></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Manage Billing
                                    <span id="manageBillingStatus" class="badge rounded-pill bg-danger"><i class="bi bi-x-lg"></i></span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="permission-category mt-3">
                            <h6>Administration</h6>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    View Reports
                                    <span id="viewReportsStatus" class="badge rounded-pill bg-success"><i class="bi bi-check-lg"></i></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Manage Staff
                                    <span id="manageStaffStatus" class="badge rounded-pill bg-danger"><i class="bi bi-x-lg"></i></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Permissions Modal -->
    <div class="modal fade" id="editPermissionsModal" tabindex="-1" aria-labelledby="editPermissionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPermissionsModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>Edit User Permissions
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="user-info mb-4">
                        <h5 id="editUserName">User Name</h5>
                        <div class="mb-3">
                            <label for="editUserRole" class="form-label">Role</label>
                            <select class="form-select" id="editUserRole">
                                <option value="Doctor">Doctor</option>
                                <option value="Nurse">Nurse</option>
                                <option value="Receptionist">Receptionist</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="permissions-container">
                        <div class="permission-group">
                            <h6 class="permission-category">Patient Records</h6>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="editViewPatientRecords">
                                <label class="form-check-label" for="editViewPatientRecords">View Patient Records</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="editEditPatientRecords">
                                <label class="form-check-label" for="editEditPatientRecords">Edit Patient Records</label>
                            </div>
                        </div>
                        
                        <div class="permission-group">
                            <h6 class="permission-category">Appointments</h6>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="editViewAppointments">
                                <label class="form-check-label" for="editViewAppointments">View Appointments</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="editManageAppointments">
                                <label class="form-check-label" for="editManageAppointments">Manage Appointments</label>
                            </div>
                        </div>
                        
                        <div class="permission-group">
                            <h6 class="permission-category">Billing</h6>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="editViewBilling">
                                <label class="form-check-label" for="editViewBilling">View Billing</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="editManageBilling">
                                <label class="form-check-label" for="editManageBilling">Manage Billing</label>
                            </div>
                        </div>
                        
                        <div class="permission-group">
                            <h6 class="permission-category">Administration</h6>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="editViewReports">
                                <label class="form-check-label" for="editViewReports">View Reports</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="editManageStaff">
                                <label class="form-check-label" for="editManageStaff">Manage Staff</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-success" id="updatePermissionsBtn">
                        <i class="bi bi-check-circle me-1"></i> Update Permissions
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Toast -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="permissionToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <i class="bi bi-check-circle me-2"></i>
                <strong class="me-auto">Success</strong>
                <small>Just now</small>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastMessage">
                Permissions have been updated successfully!
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="user-permission.js"></script>
</body>
</html>