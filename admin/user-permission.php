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

// Fetch all users with their roles from Supabase
$users = supabase_query('users', 'GET', null, [
    'select' => 'id,first_name,last_name,email,role_id,active',
    'order' => 'created_at.desc'
]);

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
$message = '';
$messageType = '';

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
            
            // Refresh the users list
            $users = supabase_query('users', 'GET', null, [
                'select' => 'id,first_name,last_name,email,role_id,active',
                'order' => 'created_at.desc'
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
            
            // Refresh the users list
            $users = supabase_query('users', 'GET', null, [
                'select' => 'id,first_name,last_name,email,role_id,active',
                'order' => 'created_at.desc'
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
                            <table class="table table-hover align-middle">
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
                                    <?php if (is_array($users) && count($users) > 0): ?>
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
                                                    <span class="badge bg-<?php echo $role_class; ?>"><?php echo htmlspecialchars($role_name); ?></span>
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
                                            <td colspan="5" class="text-center py-4">No users found</td>
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
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('userSearchInput');
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');
            
            tableRows.forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                const email = row.cells[1].textContent.toLowerCase();
                
                if (name.includes(searchTerm) || email.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Role filter functionality
        const roleFilters = document.querySelectorAll('[data-role]');
        roleFilters.forEach(filter => {
            filter.addEventListener('click', function(e) {
                e.preventDefault();
                const roleId = this.getAttribute('data-role');
                const tableRows = document.querySelectorAll('tbody tr');
                
                document.getElementById('filterRoleDropdown').textContent = this.textContent;
                
                tableRows.forEach(row => {
                    if (roleId === 'all') {
                        row.style.display = '';
                    } else {
                        const roleBadge = row.cells[2].querySelector('.badge');
                        const rowRoleId = getRoleIdFromBadge(roleBadge);
                        
                        if (rowRoleId == roleId) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            });
        });
        
        // Helper function to get role ID from badge
        function getRoleIdFromBadge(badge) {
            const roleClass = badge.classList[1];
            switch (roleClass) {
                case 'bg-primary': return 1; // Doctor
                case 'bg-success': return 2; // Nurse
                case 'bg-danger': return 3;  // Admin
                case 'bg-info': return 4;    // Receptionist
                default: return 0;
            }
        }
        
        // View user details
        const viewUserBtns = document.querySelectorAll('.view-user');
        viewUserBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                // In a real implementation, you would fetch user details via AJAX
                // For now, we'll just show a placeholder
                document.getElementById('userDetails').innerHTML = `
                    <div class="text-center mb-4">
                        <img src="../assets/img/default-profile.jpg" class="rounded-circle" width="100" height="100" alt="User Profile">
                    </div>
                    <div class="mb-3">
                        <h5>User ID: ${userId}</h5>
                        <p class="text-muted">Detailed user information would be loaded here via AJAX in a real implementation.</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> ${this.closest('tr').cells[0].textContent}</p>
                            <p><strong>Email:</strong> ${this.closest('tr').cells[1].textContent}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Role:</strong> ${this.closest('tr').cells[2].textContent}</p>
                            <p><strong>Status:</strong> ${this.closest('tr').cells[3].textContent}</p>
                        </div>
                    </div>
                `;
            });
        });
        
        // Edit user
        const editUserBtns = document.querySelectorAll('.edit-user');
        editUserBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const row = this.closest('tr');
                const roleBadge = row.cells[2].querySelector('.badge');
                const roleId = getRoleIdFromBadge(roleBadge);
                const isActive = row.cells[3].textContent.trim() === 'Active';
                
                // Set form values
                document.getElementById('editUserId').value = userId;
                document.getElementById('editUserRole').value = roleId;
                document.getElementById('userStatusToggle').checked = isActive;
                document.getElementById('currentStatus').value = isActive ? 1 : 0;
            });
        });
        
        // Save role button
        document.getElementById('saveRoleBtn').addEventListener('click', function() {
            const form = document.getElementById('editUserForm');
            const roleInput = document.createElement('input');
            roleInput.type = 'hidden';
            roleInput.name = 'assign_role';
            roleInput.value = '1';
            form.appendChild(roleInput);
            form.submit();
        });
        
        // Save status button
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
                    <div>
                        <button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                            <i class="bi bi-plus-circle me-1"></i> Add Permission
                        </button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignRoleModal">
                            <i class="bi bi-person-plus-fill me-2"></i> Assign New Role
                        </button>
                    </div>
                </div>
                
                <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <!-- Tabs for Users and Permissions -->
                <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab" aria-controls="users" aria-selected="true">
                            <i class="bi bi-people me-1"></i> Users
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" role="tab" aria-controls="roles" aria-selected="false">
                            <i class="bi bi-person-badge me-1"></i> Roles & Permissions
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content" id="myTabContent">
                    <!-- Users Tab -->
                    <div class="tab-pane fade show active" id="users" role="tabpanel" aria-labelledby="users-tab">
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <!-- ... existing user search and filter code ... -->
                                
                                <div class="table-responsive">
                                    <!-- ... existing user table code ... -->
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Roles & Permissions Tab -->
                    <div class="tab-pane fade" id="roles" role="tabpanel" aria-labelledby="roles-tab">
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card h-100">
                                            <div class="card-header">
                                                <h5 class="mb-0">Roles</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="list-group" id="rolesList">
                                                    <?php if (is_array($roles)): ?>
                                                        <?php foreach ($roles as $role): ?>
                                                            <a href="#" class="list-group-item list-group-item-action role-item" data-role-id="<?php echo $role['id']; ?>">
                                                                <?php echo htmlspecialchars($role['name']); ?>
                                                                <span class="badge bg-secondary float-end">
                                                                    <?php 
                                                                    echo isset($role_permission_map[$role['id']]) ? count($role_permission_map[$role['id']]) : 0; 
                                                                    ?> permissions
                                                                </span>
                                                            </a>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <div class="text-center py-3">No roles found</div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-8">
                                        <div class="card h-100">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h5 class="mb-0">Role Permissions</h5>
                                                <button type="button" class="btn btn-sm btn-primary" id="savePermissionsBtn" style="display: none;">
                                                    <i class="bi bi-save me-1"></i> Save Changes
                                                </button>
                                            </div>
                                            <div class="card-body">
                                                <div id="permissionsContainer">
                                                    <div class="text-center py-5">
                                                        <i class="bi bi-arrow-left-circle fs-1 text-muted"></i>
                                                        <p class="mt-3 text-muted">Select a role to manage its permissions</p>
                                                    </div>
                                                </div>
                                                
                                                <form id="permissionsForm" method="post" style="display: none;">
                                                    <input type="hidden" id="selectedRoleId" name="role_id" value="">
                                                    <input type="hidden" name="update_permissions" value="1">
                                                    
                                                    <div class="mb-3">
                                                        <h6 class="mb-3">Permissions for <span id="selectedRoleName">Role</span></h6>
                                                        
                                                        <div class="row" id="permissionsCheckboxes">
                                                            <?php if (is_array($permissions)): ?>
                                                                <?php foreach ($permissions as $permission): ?>
                                                                    <div class="col-md-6 mb-2">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input permission-checkbox" type="checkbox" 
                                                                                   name="permissions[]" 
                                                                                   value="<?php echo $permission['id']; ?>" 
                                                                                   id="permission_<?php echo $permission['id']; ?>">
                                                                            <label class="form-check-label" for="permission_<?php echo $permission['id']; ?>" 
                                                                                   title="<?php echo htmlspecialchars($permission['description'] ?? ''); ?>">
                                                                                <?php echo htmlspecialchars($permission['name']); ?>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            <?php else: ?>
                                                                <div class="col-12">
                                                                    <p class="text-center text-muted">No permissions found. Add some permissions first.</p>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Permission Modal -->
    <div class="modal fade" id="addPermissionModal" tabindex="-1" aria-labelledby="addPermissionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPermissionModalLabel">Add New Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="permission_name" class="form-label">Permission Name</label>
                            <input type="text" class="form-control" id="permission_name" name="permission_name" required>
                            <div class="form-text">Use a descriptive name like "manage_users" or "view_reports"</div>
                        </div>
                        <div class="mb-3">
                            <label for="permission_description" class="form-label">Description</label>
                            <textarea class="form-control" id="permission_description" name="permission_description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_permission" class="btn btn-primary">Add Permission</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('userSearchInput');
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');
            
            tableRows.forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                const email = row.cells[1].textContent.toLowerCase();
                
                if (name.includes(searchTerm) || email.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Role filter functionality
        const roleFilters = document.querySelectorAll('[data-role]');
        roleFilters.forEach(filter => {
            filter.addEventListener('click', function(e) {
                e.preventDefault();
                const roleId = this.getAttribute('data-role');
                const tableRows = document.querySelectorAll('tbody tr');
                
                document.getElementById('filterRoleDropdown').textContent = this.textContent;
                
                tableRows.forEach(row => {
                    if (roleId === 'all') {
                        row.style.display = '';
                    } else {
                        const roleBadge = row.cells[2].querySelector('.badge');
                        const rowRoleId = getRoleIdFromBadge(roleBadge);
                        
                        if (rowRoleId == roleId) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            });
        });
        
        // Helper function to get role ID from badge
        function getRoleIdFromBadge(badge) {
            const roleClass = badge.classList[1];
            switch (roleClass) {
                case 'bg-primary': return 1; // Doctor
                case 'bg-success': return 2; // Nurse
                case 'bg-danger': return 3;  // Admin
                case 'bg-info': return 4;    // Receptionist
                default: return 0;
            }
        }
        
        // View user details
        const viewUserBtns = document.querySelectorAll('.view-user');
        viewUserBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                // In a real implementation, you would fetch user details via AJAX
                // For now, we'll just show a placeholder
                document.getElementById('userDetails').innerHTML = `
                    <div class="text-center mb-4">
                        <img src="../assets/img/default-profile.jpg" class="rounded-circle" width="100" height="100" alt="User Profile">
                    </div>
                    <div class="mb-3">
                        <h5>User ID: ${userId}</h5>
                        <p class="text-muted">Detailed user information would be loaded here via AJAX in a real implementation.</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> ${this.closest('tr').cells[0].textContent}</p>
                            <p><strong>Email:</strong> ${this.closest('tr').cells[1].textContent}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Role:</strong> ${this.closest('tr').cells[2].textContent}</p>
                            <p><strong>Status:</strong> ${this.closest('tr').cells[3].textContent}</p>
                        </div>
                    </div>
                `;
            });
        });
        
        // Edit user
        const editUserBtns = document.querySelectorAll('.edit-user');
        editUserBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const row = this.closest('tr');
                const roleBadge = row.cells[2].querySelector('.badge');
                const roleId = getRoleIdFromBadge(roleBadge);
                const isActive = row.cells[3].textContent.trim() === 'Active';
                
                // Set form values
                document.getElementById('editUserId').value = userId;
                document.getElementById('editUserRole').value = roleId;
                document.getElementById('userStatusToggle').checked = isActive;
                document.getElementById('currentStatus').value = isActive ? 1 : 0;
            });
        });
        
        // Save role button
        document.getElementById('saveRoleBtn').addEventListener('click', function() {
            const form = document.getElementById('editUserForm');
            const roleInput = document.createElement('input');
            roleInput.type = 'hidden';
            roleInput.name = 'assign_role';
            roleInput.value = '1';
            form.appendChild(roleInput);
            form.submit();
        });
        
        // Save status button
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
                    <div>
                        <button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                            <i class="bi bi-plus-circle me-1"></i> Add Permission
                        </button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignRoleModal">
                            <i class="bi bi-person-plus-fill me-2"></i> Assign New Role
                        </button>
                    </div>
                </div>
                
                <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <!-- Tabs for Users and Permissions -->
                <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab" aria-controls="users" aria-selected="true">
                            <i class="bi bi-people me-1"></i> Users
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" role="tab" aria-controls="roles" aria-selected="false">
                            <i class="bi bi-person-badge me-1"></i> Roles & Permissions
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content" id="myTabContent">
                    <!-- Users Tab -->
                    <div class="tab-pane fade show active" id="users" role="tabpanel" aria-labelledby="users-tab">
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <!-- ... existing user search and filter code ... -->
                                
                                <div class="table-responsive">
                                    <!-- ... existing user table code ... -->
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Roles & Permissions Tab -->
                    <div class="tab-pane fade" id="roles" role="tabpanel" aria-labelledby="roles-tab">
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card h-100">
                                            <div class="card-header">
                                                <h5 class="mb-0">Roles</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="list-group" id="rolesList">
                                                    <?php if (is_array($roles)): ?>
                                                        <?php foreach ($roles as $role): ?>
                                                            <a href="#" class="list-group-item list-group-item-action role-item" data-role-id="<?php echo $role['id']; ?>">
                                                                <?php echo htmlspecialchars($role['name']); ?>
                                                                <span class="badge bg-secondary float-end">
                                                                    <?php 
                                                                    echo isset($role_permission_map[$role['id']]) ? count($role_permission_map[$role['id']]) : 0; 
                                                                    ?> permissions
                                                                </span>
                                                            </a>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <div class="text-center py-3">No roles found</div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-8">
                                        <div class="card h-100">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h5 class="mb-0">Role Permissions</h5>
                                                <button type="button" class="btn btn-sm btn-primary" id="savePermissionsBtn" style="display: none;">
                                                    <i class="bi bi-save me-1"></i> Save Changes
                                                </button>
                                            </div>
                                            <div class="card-body">
                                                <div id="permissionsContainer">
                                                    <div class="text-center py-5">
                                                        <i class="bi bi-arrow-left-circle fs-1 text-muted"></i>
                                                        <p class="mt-3 text-muted">Select a role to manage its permissions</p>
                                                    </div>
                                                </div>
                                                
                                                <form id="permissionsForm" method="post" style="display: none;">
                                                    <input type="hidden" id="selectedRoleId" name="role_id" value="">
                                                    <input type="hidden" name="update_permissions" value="1">
                                                    
                                                    <div class="mb-3">
                                                        <h6 class="mb-3">Permissions for <span id="selectedRoleName">Role</span></h6>
                                                        
                                                        <div class="row" id="permissionsCheckboxes">
                                                            <?php if (is_array($permissions)): ?>
                                                                <?php foreach ($permissions as $permission): ?>
                                                                    <div class="col-md-6 mb-2">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input permission-checkbox" type="checkbox" 
                                                                                   name="permissions[]" 
                                                                                   value="<?php echo $permission['id']; ?>" 
                                                                                   id="permission_<?php echo $permission['id']; ?>">
                                                                            <label class="form-check-label" for="permission_<?php echo $permission['id']; ?>" 
                                                                                   title="<?php echo htmlspecialchars($permission['description'] ?? ''); ?>">
                                                                                <?php echo htmlspecialchars($permission['name']); ?>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            <?php else: ?>
                                                                <div class="col-12">
                                                                    <p class="text-center text-muted">No permissions found. Add some permissions first.</p>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Permission Modal -->
    <div class="modal fade" id="addPermissionModal" tabindex="-1" aria-labelledby="addPermissionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPermissionModalLabel">Add New Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="permission_name" class="form-label">Permission Name</label>
                            <input type="text" class="form-control" id="permission_name" name="permission_name" required>
                            <div class="form-text">Use a descriptive name like "manage_users" or "view_reports"</div>
                        </div>
                        <div class="mb-3">
                            <label for="permission_description" class="form-label">Description</label>
                            <textarea class="form-control" id="permission_description" name="permission_description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_permission" class="btn btn-primary">Add Permission</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('userSearchInput');
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');
            
            tableRows.forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                const email = row.cells[1].textContent.toLowerCase();
                
                if (name.includes(searchTerm) || email.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Role filter functionality
        const roleFilters = document.querySelectorAll('[data-role]');
        roleFilters.forEach(filter => {
            filter.addEventListener('click', function(e) {
                e.preventDefault();
                const roleId = this.getAttribute('data-role');
                const tableRows = document.querySelectorAll('tbody tr');
                
                document.getElementById('filterRoleDropdown').textContent = this.textContent;
                
                tableRows.forEach(row => {
                    if (roleId === 'all') {
                        row.style.display = '';
                    } else {
                        const roleBadge = row.cells[2].querySelector('.badge');
                        const rowRoleId = getRoleIdFromBadge(roleBadge);
                        
                        if (rowRoleId == roleId) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            });
        });
        
        // Helper function to get role ID from badge
        function getRoleIdFromBadge(badge) {
            const roleClass = badge.classList[1];
            switch (roleClass) {
                case 'bg-primary': return 1; // Doctor
                case 'bg-success': return 2; // Nurse
                case 'bg-danger': return 3;  // Admin
                case 'bg-info': return 4;    // Receptionist
                default: return 0;
            }
        }
        
        // View user details
        const viewUserBtns = document.querySelectorAll('.view-user');
        viewUserBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                // In a real implementation, you would fetch user details via AJAX
                // For now, we'll just show a placeholder
                document.getElementById('userDetails').innerHTML = `
                    <div class="text-center mb-4">
                        <img src="../assets/img/default-profile.jpg" class="rounded-circle" width="100" height="100" alt="User Profile">
                    </div>
                    <div class="mb-3">
                        <h5>User ID: ${userId}</h5>
                        <p class="text-muted">Detailed user information would be loaded here via AJAX in a real implementation.</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> ${this.closest('tr').cells[0].textContent}</p>
                            <p><strong>Email:</strong> ${this.closest('tr').cells[1].textContent}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Role:</strong> ${this.closest('tr').cells[2].textContent}</p>
                            <p><strong>Status:</strong> ${this.closest('tr').cells[3].textContent}</p>
                        </div>
                    </div>
                `;
            });
        });
        
        // Edit user
        const editUserBtns = document.querySelectorAll('.edit-user');
        editUserBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const row = this.closest('tr');
                const roleBadge = row.cells[2].querySelector('.badge');
                const roleId = getRoleIdFromBadge(roleBadge);
                const isActive = row.cells[3].textContent.trim() === 'Active';
                
                // Set form values
                document.getElementById('editUserId').value = userId;
                document.getElementById('editUserRole').value = roleId;
                document.getElementById('userStatusToggle').checked = isActive;
                document.getElementById('currentStatus').value = isActive ? 1 : 0;
            });
        });
        
        // Save role button
        document.getElementById('saveRoleBtn').addEventListener('click', function() {
            const form = document.getElementById('editUserForm');
            const roleInput = document.createElement('input');
            roleInput.type = 'hidden';
            roleInput.name = 'assign_role';
            roleInput.value = '1';
            form.appendChild(roleInput);
            form.submit();
        });
        
        // Save status button
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