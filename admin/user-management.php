<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
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
$users = [];
$roles = [];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle user creation
    if (isset($_POST['create_user'])) {
        $userData = [
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'role_id' => (int)$_POST['role_id'],
            'status' => 'active'
        ];
        
        // Generate a random password
        $password = generate_random_password();
        $userData['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        
        // Create the user
        $result = supabase_query('users', 'POST', $userData);
        
        if ($result && isset($result['id'])) {
            $message = 'User created successfully! Temporary password: ' . $password;
            $messageType = 'success';
            
            // Send email with temporary password
            // send_welcome_email($userData['email'], $userData['username'], $password);
        } else {
            $message = 'Failed to create user.';
            $messageType = 'danger';
        }
    }
    
    // Handle user update
    if (isset($_POST['update_user'])) {
        $userId = $_POST['user_id'];
        $userData = [
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'role_id' => (int)$_POST['role_id'],
            'active' => $_POST['status'] == 'active' ? 1 : 0
        ];
        
        // Update the user
        $result = supabase_query('users', 'PATCH', $userData, [
            'id' => 'eq.' . $userId
        ]);
        
        if ($result) {
            $message = 'User updated successfully!';
            $messageType = 'success';
        } else {
            $message = 'Failed to update user.';
            $messageType = 'danger';
        }
    }
    
    // Handle user deletion
    if (isset($_POST['delete_user'])) {
        $userId = $_POST['user_id'];
        
        // Don't allow deleting yourself
        if ($userId == $_SESSION['user_id']) {
            $message = 'You cannot delete your own account.';
            $messageType = 'danger';
        } else {
            // Delete the user
            $result = supabase_query('users', 'DELETE', null, [
                'id' => 'eq.' . $userId
            ]);
            
            if ($result) {
                $message = 'User deleted successfully!';
                $messageType = 'success';
            } else {
                $message = 'Failed to delete user.';
                $messageType = 'danger';
            }
        }
    }
    
    // Handle password reset
    if (isset($_POST['reset_password'])) {
        $userId = $_POST['user_id'];
        
        // Generate a new random password
        $password = generate_random_password();
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        // Update the user's password
        $result = supabase_query('users', 'PATCH', [
            'password_hash' => $passwordHash
        ], [
            'id' => 'eq.' . $userId
        ]);
        
        if ($result) {
            $message = 'Password reset successfully! New password: ' . $password;
            $messageType = 'success';
            
            // Send email with new password
            // send_password_reset_email($user['email'], $user['username'], $password);
        } else {
            $message = 'Failed to reset password.';
            $messageType = 'danger';
        }
    }
}

// Fetch all users from Supabase
$users = supabase_query('users', 'GET', null, [
    'select' => '*',
    'order' => 'created_at.desc'
]);

// Fetch all roles from Supabase
$roles = supabase_query('roles', 'GET', null, [
    'select' => '*',
    'order' => 'id.asc'
]);

// Helper function to generate a random password
function generate_random_password($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Clinic Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/admin.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Include the sidebar -->
        <?php include('admin-sidebar.php'); ?>
        
        <div class="main-content">
            <!-- Include the navbar/header -->
            <?php include('admin-navbar.php'); ?>

            <!-- User Management Content -->
            <div class="container-fluid py-4">
                <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5><i class="bi bi-people me-2"></i>User Management</h5>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="bi bi-person-plus me-1"></i> Add New User
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="usersTable" class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Status</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($users as $user): ?>
                                            <tr>
                                                <td><?php echo $user['id']; ?></td>
                                                <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                <td>
                                                    <?php 
                                                    $roleName = 'Unknown';
                                                    foreach ($roles as $role) {
                                                        if ($role['id'] == $user['role_id']) {
                                                            $roleName = $role['name'];
                                                            break;
                                                        }
                                                    }
                                                    echo htmlspecialchars($roleName);
                                                    ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php echo isset($user['active']) && $user['active'] == 1 ? 'success' : 'danger'; ?>">
                                                        <?php echo isset($user['active']) && $user['active'] == 1 ? 'Active' : 'Inactive'; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm btn-outline-primary edit-user-btn" 
                                                                data-bs-toggle="modal" data-bs-target="#editUserModal"
                                                                data-user-id="<?php echo $user['id']; ?>"
                                                                data-username="<?php echo htmlspecialchars($user['username']); ?>"
                                                                data-email="<?php echo htmlspecialchars($user['email']); ?>"
                                                                data-first-name="<?php echo htmlspecialchars($user['first_name']); ?>"
                                                                data-last-name="<?php echo htmlspecialchars($user['last_name']); ?>"
                                                                data-role-id="<?php echo $user['role_id']; ?>"
                                                                data-status="<?php echo isset($user['active']) ? ($user['active'] == 1 ? 'active' : 'inactive') : 'inactive'; ?>">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-warning reset-password-btn"
                                                                data-bs-toggle="modal" data-bs-target="#resetPasswordModal"
                                                                data-user-id="<?php echo $user['id']; ?>"
                                                                data-username="<?php echo htmlspecialchars($user['username']); ?>">
                                                            <i class="bi bi-key"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger delete-user-btn"
                                                                data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                                                                data-user-id="<?php echo $user['id']; ?>"
                                                                data-username="<?php echo htmlspecialchars($user['username']); ?>">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
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
            </div>
        </div>
    </div>
    
    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            <div class="col">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="role_id" class="form-label">Role</label>
                            <select class="form-select" id="role_id" name="role_id" required>
                                <?php foreach ($roles as $role): ?>
                                <option value="<?php echo $role['id']; ?>"><?php echo htmlspecialchars($role['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <p class="text-info">
                            <i class="bi bi-info-circle me-1"></i> A temporary password will be generated and displayed after user creation.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="create_user" class="btn btn-primary">Create User</button>
                    </div>
                </form>
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
                <form method="post">
                    <div class="modal-body">
                        <input type="hidden" id="edit_user_id" name="user_id">
                        <div class="mb-3">
                            <label for="edit_username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="edit_username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="edit_first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                            </div>
                            <div class="col">
                                <label for="edit_last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_role_id" class="form-label">Role</label>
                            <select class="form-select" id="edit_role_id" name="role_id" required>
                                <?php foreach ($roles as $role): ?>
                                <option value="<?php echo $role['id']; ?>"><?php echo htmlspecialchars($role['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-select" id="edit_status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_user" class="btn btn-primary">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Reset Password Modal -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="hidden" id="reset_user_id" name="user_id">
                        <p>Are you sure you want to reset the password for <strong id="reset_username"></strong>?</p>
                        <p>A new random password will be generated and displayed.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="reset_password" class="btn btn-warning">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Delete User Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="hidden" id="delete_user_id" name="user_id">
                        <p>Are you sure you want to delete the user <strong id="delete_username"></strong>?</p>
                        <p class="text-danger">This action cannot be undone!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="delete_user" class="btn btn-danger">Delete User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#usersTable').DataTable({
                order: [[0, 'desc']]
            });
            
            // Handle edit user modal
            $('.edit-user-btn').click(function() {
                $('#edit_user_id').val($(this).data('user-id'));
                $('#edit_username').val($(this).data('username'));
                $('#edit_email').val($(this).data('email'));
                $('#edit_first_name').val($(this).data('first-name'));
                $('#edit_last_name').val($(this).data('last-name'));
                $('#edit_role_id').val($(this).data('role-id'));
                $('#edit_status').val($(this).data('status'));
            });
            
            // Handle reset password modal
            $('.reset-password-btn').click(function() {
                $('#reset_user_id').val($(this).data('user-id'));
                $('#reset_username').text($(this).data('username'));
            });
            
            // Handle delete user modal
            $('.delete-user-btn').click(function() {
                $('#delete_user_id').val($(this).data('user-id'));
                $('#delete_username').text($(this).data('username'));
            });
        });
    </script>
</body>
</html>