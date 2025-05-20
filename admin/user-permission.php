<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and has admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    header("Location: ../login/login.php");
    exit();
}

// Include database connection and functions
include_once '../includes/db_connection.php';
include_once '../includes/user_functions.php';
include_once '../includes/cache_config.php';

// Initialize variables
$message = '';
$messageType = '';

// Fetch all roles from Supabase
$roles = supabase_query('roles', 'GET', null, [
    'select' => 'id,name,description',
    'order' => 'id.asc'
]);

// Create a role lookup array for easier access
$role_lookup = [];
if (is_array($roles)) {
    foreach ($roles as $role) {
        if (isset($role['id']) && isset($role['name'])) {
            $role_lookup[$role['id']] = $role['name'];
        }
    }
}

// Handle role assignment if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_role'])) {
    $user_id = $_POST['user_id'] ?? '';
    $role_id = $_POST['role_id'] ?? '';
    
    if (!empty($user_id) && !empty($role_id)) {
        // Update user role in Supabase
        $update_result = supabase_query('users', 'PATCH', $user_id, [
            'role_id' => $role_id
        ]);
        
        if ($update_result) {
            $message = 'Role updated successfully!';
            $messageType = 'success';
            
            // Log the action
            supabase_query('system_logs', 'POST', null, [
                'user_id' => $_SESSION['user_id'],
                'action' => 'updated_user_role',
                'details' => json_encode(['user_id' => $user_id, 'role_id' => $role_id])
            ]);
        } else {
            $message = 'Failed to update role. Please try again.';
            $messageType = 'danger';
        }
    } else {
        $message = 'Invalid user or role selected.';
        $messageType = 'danger';
    }
}

// Handle user status toggle (active/inactive)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status'])) {
    $user_id = $_POST['user_id'] ?? '';
    $current_status = $_POST['current_status'] ?? '';
    $new_status = ($current_status == 1) ? 0 : 1;
    
    if (!empty($user_id)) {
        // Update user status in Supabase
        $update_result = supabase_query('users', 'PATCH', $user_id, [
            'active' => $new_status
        ]);
        
        if ($update_result) {
            $message = 'User status updated successfully!';
            $messageType = 'success';
            
            // Log the action
            supabase_query('system_logs', 'POST', null, [
                'user_id' => $_SESSION['user_id'],
                'action' => 'updated_user_status',
                'details' => json_encode(['user_id' => $user_id, 'status' => $new_status])
            ]);
        } else {
            $message = 'Failed to update user status. Please try again.';
            $messageType = 'danger';
        }
    } else {
        $message = 'Invalid user selected.';
        $messageType = 'danger';
    }
}

// Fetch all users with their roles from Supabase - this was missing!
$users = supabase_query('users', 'GET', null, [
    'select' => 'id,first_name,last_name,email,role_id,active',
    'order' => 'created_at.desc'
]);

// Add debugging to check what's being returned
error_log("User query result: " . print_r($users, true));

// If users is not an array or is empty, try to troubleshoot
if (!is_array($users) || empty($users)) {
    error_log("No users found or query failed. Checking database connection...");
    
    // Test database connection
    $test_query = supabase_query('roles', 'GET', null, [
        'select' => 'count',
        'count' => 'exact'
    ]);
    
    error_log("Test query result: " . print_r($test_query, true));
    
    // Set a message for the admin
    $message = 'No users found. Please check the error log for details.';
    $messageType = 'warning';
}
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
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Include the sidebar -->
        <?php include('admin-sidebar.php'); ?>
        
        <div class="main-content">
            <!-- Include the navbar/header -->
            <?php include('admin-navbar.php'); ?>
            
            <div class="container-fluid p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="mb-0">
                        <i class="bi bi-shield-lock me-2"></i> User Role & Permission Management
                    </h1>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignRoleModal">
                        <i class="bi bi-person-plus-fill me-2"></i> Assign New Role
                    </button>
                </div>
                
                <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" id="userSearchInput" class="form-control border-0 bg-light" placeholder="Search users...">
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterRoleDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        Filter by Role
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="filterRoleDropdown">
                                        <li><a class="dropdown-item" href="#" data-role="all">All Roles</a></li>
                                        <?php if (is_array($roles)): ?>
                                            <?php foreach ($roles as $role): ?>
                                                <li><a class="dropdown-item" href="#" data-role="<?php echo $role['id']; ?>"><?php echo htmlspecialchars($role['name']); ?></a></li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="usersTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    // Debug: Display count of users
                                    echo "<!-- Debug: Found " . (is_array($users) ? count($users) : 0) . " users -->";
                                    
                                    if (is_array($users) && count($users) > 0): 
                                    ?>
                                        <?php foreach ($users as $user): ?>
                                            <tr>
                                                <td>
                                                    <?php 
                                                    $name_prefix = '';
                                                    if (isset($user['role_id']) && $user['role_id'] == 1) {
                                                        $name_prefix = 'Dr. ';
                                                    } elseif (isset($user['role_id']) && $user['role_id'] == 2) {
                                                        $name_prefix = 'Nurse ';
                                                    } elseif (isset($user['role_id']) && $user['role_id'] == 3) {
                                                        $name_prefix = 'Admin ';
                                                    } elseif (isset($user['role_id']) && $user['role_id'] == 4) {
                                                        $name_prefix = 'Receptionist ';
                                                    }
                                                    
                                                    echo htmlspecialchars($name_prefix . ($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
                                                    ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($user['email'] ?? ''); ?></td>
                                                <td>
                                                    <?php 
                                                    $role_name = 'Unknown';
                                                    $role_class = 'secondary';
                                                    
                                                    if (isset($user['role_id']) && isset($role_lookup[$user['role_id']])) {
                                                        $role_name = $role_lookup[$user['role_id']];
                                                        
                                                        // Set badge color based on role
                                                        switch ($user['role_id']) {
                                                            case 1: // Doctor
                                                                $role_class = 'primary';
                                                                break;
                                                            case 2: // Nurse
                                                                $role_class = 'success';
                                                                break;
                                                            case 3: // Admin
                                                                $role_class = 'danger';
                                                                break;
                                                            case 4: // Receptionist
                                                                $role_class = 'info';
                                                                break;
                                                            default:
                                                                $role_class = 'secondary';
                                                        }
                                                    }
                                                    ?>
                                                    <span class="badge bg-<?php echo $role_class; ?>" data-role-id="<?php echo $user['role_id']; ?>"><?php echo htmlspecialchars($role_name); ?></span>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $status = isset($user['active']) && $user['active'] == 1 ? 'Active' : 'Inactive';
                                                    $status_class = $status == 'Active' ? 'success' : 'secondary';
                                                    ?>
                                                    <span class="badge bg-<?php echo $status_class; ?>"><?php echo $status; ?></span>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="#" class="btn btn-sm btn-outline-primary view-user" data-bs-toggle="modal" data-bs-target="#viewUserModal" data-user-id="<?php echo $user['id']; ?>">
                                                            <i class="bi bi-eye"></i> View
                                                        </a>
                                                        <a href="#" class="btn btn-sm btn-outline-success edit-user" data-bs-toggle="modal" data-bs-target="#editUserModal" data-user-id="<?php echo $user['id']; ?>">
                                                            <i class="bi bi-pencil"></i> Edit
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4">No users found. Please check your database connection or add users to the system.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Assign Role Modal -->
    <div class="modal fade" id="assignRoleModal" tabindex="-1" aria-labelledby="assignRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignRoleModalLabel">Assign New Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="userSelect" class="form-label">Select User</label>
                            <select class="form-select" id="userSelect" name="user_id" required>
                                <option value="">-- Select User --</option>
                                <?php if (is_array($users)): ?>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?php echo $user['id']; ?>">
                                            <?php echo htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '') . ' (' . ($user['email'] ?? '') . ')'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="roleSelect" class="form-label">Select Role</label>
                            <select class="form-select" id="roleSelect" name="role_id" required>
                                <option value="">-- Select Role --</option>
                                <?php if (is_array($roles)): ?>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?php echo $role['id']; ?>">
                                            <?php echo htmlspecialchars($role['name'] . ' - ' . $role['description']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="assign_role" class="btn btn-primary">Assign Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- View User Modal -->
    <div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewUserModalLabel">User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="userDetails">
                        <!-- User details will be loaded here via AJAX -->
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm" action="" method="POST">
                        <input type="hidden" id="editUserId" name="user_id" value="">
                        <input type="hidden" id="currentStatus" name="current_status" value="">
                        
                        <div class="mb-3">
                            <label for="editUserRole" class="form-label">Role</label>
                            <select class="form-select" id="editUserRole" name="role_id">
                                <?php if (is_array($roles)): ?>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?php echo $role['id']; ?>">
                                            <?php echo htmlspecialchars($role['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label d-block">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="userStatusToggle">
                                <label class="form-check-label" for="userStatusToggle">Active</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="saveRoleBtn" class="btn btn-success">Save Role</button>
                    <button type="button" id="saveStatusBtn" class="btn btn-primary">Save Status</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle user search
        const searchInput = document.getElementById('userSearchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const userRows = document.querySelectorAll('#usersTable tbody tr');
                
                userRows.forEach(row => {
                    const name = row.cells[0].textContent.toLowerCase();
                    const email = row.cells[1].textContent.toLowerCase();
                    const role = row.cells[2].textContent.toLowerCase();
                    
                    if (name.includes(searchTerm) || email.includes(searchTerm) || role.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
        
        // Handle role filtering
        const roleFilters = document.querySelectorAll('.dropdown-item[data-role]');
        roleFilters.forEach(filter => {
            filter.addEventListener('click', function(e) {
                e.preventDefault();
                const roleId = this.getAttribute('data-role');
                const userRows = document.querySelectorAll('#usersTable tbody tr');
                
                userRows.forEach(row => {
                    if (roleId === 'all') {
                        row.style.display = '';
                    } else {
                        const roleBadge = row.cells[2].querySelector('.badge');
                        const userRoleId = roleBadge.getAttribute('data-role-id');
                        
                        if (userRoleId === roleId) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
                
                // Update dropdown button text
                document.getElementById('filterRoleDropdown').textContent = 'Filter: ' + this.textContent;
            });
        });
        
        // Handle edit user modal
        const editUserButtons = document.querySelectorAll('.edit-user');
        editUserButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const row = this.closest('tr');
                const roleCell = row.cells[2];
                const statusCell = row.cells[3];
                
                // Get role ID from data attribute
                const roleBadge = roleCell.querySelector('.badge');
                const roleId = roleBadge.getAttribute('data-role-id');
                
                // Get status
                const statusBadge = statusCell.querySelector('.badge');
                const isActive = statusBadge.textContent.trim() === 'Active';
                
                // Set form values
                document.getElementById('editUserId').value = userId;
                document.getElementById('currentStatus').value = isActive ? 1 : 0;
                document.getElementById('editUserRole').value = roleId;
                document.getElementById('userStatusToggle').checked = isActive;
            });
        });
        
        // Handle save role button
        document.getElementById('saveRoleBtn').addEventListener('click', function() {
            const form = document.getElementById('editUserForm');
            const roleInput = document.createElement('input');
            roleInput.type = 'hidden';
            roleInput.name = 'assign_role';
            roleInput.value = '1';
            form.appendChild(roleInput);
            form.submit();
        });
        
        // Handle save status button
        document.getElementById('saveStatusBtn').addEventListener('click', function() {
            const form = document.getElementById('editUserForm');
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'toggle_status';
            statusInput.value = '1';
            form.appendChild(statusInput);
            form.submit();
        });
    });
    </script>
</body>
</html>
