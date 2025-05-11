<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Management System - Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #3498db;
            --primary-hover: #2980b9;
            --sidebar-width: 260px;
            --header-height: 70px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background-color: #2c3e50;
            color: #ecf0f1;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }
        
        .sidebar-header {
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            background-color: #1a2530;
            color: white;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header h3 {
            font-size: 1.3rem;
            margin: 0;
            font-weight: 600;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
            margin-bottom: 8px;
            font-size: 1.05rem;
        }
        
        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff;
            border-left: 4px solid #3498db;
        }
        
        .menu-item.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff;
            border-left: 4px solid #3498db;
            font-weight: 600;
        }
        
        .menu-item i {
            margin-right: 12px;
            font-size: 1.2rem;
            width: 28px;
            text-align: center;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 992px) {
            :root {
                --sidebar-width: 0px;
            }
            
            .sidebar {
                width: 240px;
                transform: translateX(-100%);
                box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-header, .main-content {
                left: 0;
                width: 100%;
            }
            
            .toggle-sidebar {
                display: block;
            }
        }
        
        .toggle-sidebar {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1001;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            width: 45px;
            height: 45px;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.3s;
        }
        
        .toggle-sidebar:hover {
            background-color: var(--primary-hover);
        }
        
        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-header, .main-content {
                left: 0;
                width: 100%;
            }
            
            .toggle-sidebar {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }
        
        /* Close button for mobile */
        .close-sidebar {
            display: none;
            color: white;
            background: transparent;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
        }
        
        @media (max-width: 992px) {
            .close-sidebar {
                display: block;
            }
        }
        
        /* Header Styles */
        .main-header {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--header-height);
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            z-index: 900;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 25px;
            transition: all 0.3s ease;
        }
        
        .admin-profile {
            display: flex;
            align-items: center;
        }
        
        .admin-profile img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            margin-right: 10px;
            border: 2px solid #f1f1f1;
        }
        
        .admin-info {
            display: flex;
            flex-direction: column;
            margin-right: 15px;
        }
        
        .admin-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #2c3e50;
        }
        
        .admin-role {
            font-size: 0.7rem;
            background-color: #3498db;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            display: inline-block;
        }
        
        .notification-icon {
            margin-left: 20px;
            font-size: 1.2rem;
            color: #7f8c8d;
            cursor: pointer;
            position: relative;
            transition: color 0.3s;
        }
        
        .notification-icon:hover {
            color: #3498db;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Main Content Styles */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 30px;
            min-height: calc(100vh - var(--header-height));
            transition: all 0.3s ease;
        }
        
        .dashboard-title {
            margin-bottom: 30px;
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.8rem;
        }
        
        .stats-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            background-color: rgba(13, 110, 253, 0.1);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        
        .stats-number {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stats-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .content-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #343a40;
            margin: 0;
        }
        
        .action-btn {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.85rem;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .user-table th, .user-table td {
            vertical-align: middle;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
        }
        
        .status-active {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
        }
        
        .status-inactive {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }
        
        .action-icon {
            color: #6c757d;
            font-size: 1rem;
            margin-right: 10px;
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .action-icon:hover {
            color: var(--primary-color);
        }
        
        .action-icon.delete:hover {
            color: #dc3545;
        }
        
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .settings-item {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .settings-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .settings-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .settings-label {
            font-weight: 600;
            color: #343a40;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-header, .main-content {
                left: 0;
                width: 100%;
            }
            
            .toggle-sidebar {
                display: block;
            }
        }
        
        /* Staff breakdown chart */
        .staff-chart {
            display: flex;
            height: 100px;
            margin-top: 10px;
        }
        
        .chart-bar {
            flex-grow: 1;
            margin: 0 5px;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .bar-fill {
            width: 100%;
            background-color: var(--primary-color);
            border-radius: 4px 4px 0 0;
            margin-top: auto;
        }
        
        .bar-label {
            font-size: 0.75rem;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .doctors-bar { height: 80%; }
        .nurses-bar { height: 60%; }
        .receptionists-bar { height: 40%; }
    </style>
</head>
<body>
    <!-- Toggle Sidebar Button (Mobile) -->
    <button class="toggle-sidebar" id="toggleSidebar">
        <i class="bi bi-list"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>Admin Panel</h3>
            <button class="close-sidebar" id="closeSidebar">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="sidebar-menu">
            <a href="#dashboard" class="menu-item active">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
            <a href="#users" class="menu-item">
                <i class="bi bi-people"></i>
                <span>User Management</span>
            </a>
            <a href="#settings" class="menu-item">
                <i class="bi bi-shield-lock"></i>
                <span>Roles & Permissions</span>
            </a>
            <a href="#profile" class="menu-item">
                <i class="bi bi-gear"></i>
                <span>General Settings</span>
            </a>
            <a href="login.php" class="menu-item">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Main Header -->
    <div class="main-header" id="mainHeader">
        <div class="admin-profile">
            <div class="admin-info">
                <div class="admin-name">Admin User</div>
                <div class="admin-role">Administrator</div>
            </div>
        </div>
        <div class="notification-icon" data-bs-toggle="tooltip" title="Notifications">
            <i class="bi bi-bell"></i>
            <span class="notification-badge">3</span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="dashboard-title">Dashboard</h2>
        
        <!-- System Overview Section -->
        <div class="row mb-4">
            <div class="col-12">
                <h4>System Overview</h4>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="bi bi-person"></i>
                    </div>
                    <div class="stats-number">0</div>
                    <div class="stats-label">Total Doctors</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stats-number">0</div>
                    <div class="stats-label">Total Nurses</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="stats-number">0</div>
                    <div class="stats-label">Total Receptionists</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="stats-number">0</div>
                    <div class="stats-label">Total Users</div>
                </div>
            </div>
        </div>
        
        <!-- User Management Section -->
        <div class="content-card" id="users">
            <div class="card-header">
                <h5 class="card-title">User Management</h5>
                <button class="btn btn-primary action-btn" data-bs-toggle="tooltip" title="Add new user">
                    <i class="bi bi-plus"></i> Add New User
                </button>
            </div>
            <div class="table-responsive">
                <table class="table user-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Empty table - no default users -->
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>Showing 0 users</div>
                <nav>
                    <ul class="pagination mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- System Settings Section -->
        <div class="content-card" id="settings">
            <div class="card-header">
                <h5 class="card-title">Roles & Permissions</h5>
                <button class="btn btn-outline-primary action-btn" data-bs-toggle="tooltip" title="Reset permissions">
                    <i class="bi bi-arrow-clockwise"></i> Reset to Default
                </button>
            </div>
            <div class="roles-permissions-container">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Available Roles</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Administrator
                                        <span class="badge bg-primary rounded-pill">Full Access</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Doctor
                                        <span class="badge bg-info rounded-pill">Limited</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Nurse
                                        <span class="badge bg-info rounded-pill">Limited</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Receptionist
                                        <span class="badge bg-info rounded-pill">Limited</span>
                                    </li>
                                </ul>
                                <button class="btn btn-sm btn-primary mt-3" data-bs-toggle="tooltip" title="Add new role">
                                    <i class="bi bi-plus"></i> Add New Role
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Permission Management</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Permission</th>
                                                <th>Administrator</th>
                                                <th>Doctor</th>
                                                <th>Nurse</th>
                                                <th>Receptionist</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>View Patients</td>
                                                <td><input type="checkbox" checked disabled></td>
                                                <td><input type="checkbox" checked></td>
                                                <td><input type="checkbox" checked></td>
                                                <td><input type="checkbox" checked></td>
                                            </tr>
                                            <tr>
                                                <td>Add Patients</td>
                                                <td><input type="checkbox" checked disabled></td>
                                                <td><input type="checkbox" checked></td>
                                                <td><input type="checkbox" checked></td>
                                                <td><input type="checkbox" checked></td>
                                            </tr>
                                            <tr>
                                                <td>Edit Patients</td>
                                                <td><input type="checkbox" checked disabled></td>
                                                <td><input type="checkbox" checked></td>
                                                <td><input type="checkbox" checked></td>
                                                <td><input type="checkbox"></td>
                                            </tr>
                                            <tr>
                                                <td>Delete Patients</td>
                                                <td><input type="checkbox" checked disabled></td>
                                                <td><input type="checkbox"></td>
                                                <td><input type="checkbox"></td>
                                                <td><input type="checkbox"></td>
                                            </tr>
                                            <tr>
                                                <td>Manage Appointments</td>
                                                <td><input type="checkbox" checked disabled></td>
                                                <td><input type="checkbox" checked></td>
                                                <td><input type="checkbox" checked></td>
                                                <td><input type="checkbox" checked></td>
                                            </tr>
                                            <tr>
                                                <td>Access Reports</td>
                                                <td><input type="checkbox" checked disabled></td>
                                                <td><input type="checkbox" checked></td>
                                                <td><input type="checkbox"></td>
                                                <td><input type="checkbox"></td>
                                            </tr>
                                            <tr>
                                                <td>Manage Users</td>
                                                <td><input type="checkbox" checked disabled></td>
                                                <td><input type="checkbox"></td>
                                                <td><input type="checkbox"></td>
                                                <td><input type="checkbox"></td>
                                            </tr>
                                            <tr>
                                                <td>Roles & Permissions</td>
                                                <td><input type="checkbox" checked disabled></td>
                                                <td><input type="checkbox"></td>
                                                <td><input type="checkbox"></td>
                                                <td><input type="checkbox"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-primary" data-bs-toggle="tooltip" title="Save permission changes">Save Changes</button>
                                    <button class="btn btn-outline-secondary ms-2">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Settings Grid Section -->
        <div class="settings-grid">
            <div class="settings-item" data-bs-toggle="tooltip" title="General Settings">
                <div class="settings-icon">
                    <i class="bi bi-gear"></i>
                </div>
                <div class="settings-label">General Settings</div>
            </div>
            <div class="settings-item" data-bs-toggle="tooltip" title="User Management">
                <div class="settings-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="settings-label">User Management</div>
            </div>
            <div class="settings-item" data-bs-toggle="tooltip" title="Roles & Permissions">
                <div class="settings-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <div class="settings-label">Roles & Permissions</div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent scrolling when sidebar is open
        });
        
        // Close sidebar on mobile
        document.getElementById('closeSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('active');
            document.body.style.overflow = ''; // Restore scrolling
        });
        
        // Handle navigation
        document.addEventListener('DOMContentLoaded', function() {
            // Get all menu items
            const menuItems = document.querySelectorAll('.menu-item');
            
            // Get all sections
            const sections = {
                dashboard: document.querySelector('.row.mb-4'),
                users: document.getElementById('users'),
                settings: document.getElementById('settings'),
                profile: document.getElementById('profile')
            };
            
            // Settings grid
            const settingsGrid = document.querySelector('.settings-grid');
            
            // Function to show a section
            function showSection(sectionId) {
                // Hide all sections first
                for (const key in sections) {
                    if (sections[key]) {
                        sections[key].style.display = 'none';
                    }
                }
                
                // Hide settings grid by default
                if (settingsGrid) {
                    settingsGrid.style.display = 'none';
                }
                
                // Show dashboard title by default
                document.querySelector('.dashboard-title').style.display = 'block';
                
                // Show the selected section
                if (sectionId === '#dashboard') {
                    // For dashboard, show the overview section and settings grid
                    sections.dashboard.style.display = 'flex';
                    if (settingsGrid) {
                        settingsGrid.style.display = 'grid';
                    }
                } else if (sectionId === '#users') {
                    sections.users.style.display = 'block';
                } else if (sectionId === '#settings') {
                    sections.settings.style.display = 'block';
                } else if (sectionId === '#profile') {
                    sections.profile.style.display = 'block';
                }
                
                // Update active menu item
                menuItems.forEach(item => {
                    if (item.getAttribute('href') === sectionId) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                });
            }
            
            // Add click event to menu items
            menuItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const targetId = this.getAttribute('href');
                    showSection(targetId);
                    
                    // Close sidebar on mobile
                    if (window.innerWidth <= 992) {
                        document.getElementById('sidebar').classList.remove('active');
                        document.body.style.overflow = ''; // Restore scrolling
                    }
                });
            });
            
            // Handle settings grid items click
            const settingsItems = document.querySelectorAll('.settings-item');
            settingsItems.forEach((item, index) => {
                item.addEventListener('click', function() {
                    if (index === 0) {
                        // General Settings
                        showSection('#profile');
                    } else if (index === 1) {
                        // User Management
                        showSection('#users');
                    } else if (index === 2) {
                        // Roles & Permissions
                        showSection('#settings');
                    }
                });
            });
            
            // Show dashboard by default
            showSection('#dashboard');
        });
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    </script>
</body>
</html>

        <!-- User Profile Section (General Settings) --> 
        <div class="content-card" id="profile"> 
            <div class="card-header"> 
                <h5 class="card-title">General Settings</h5> 
                <button class="btn btn-primary action-btn" data-bs-toggle="tooltip" title="Save changes"> 
                    <i class="bi bi-save"></i> Save Changes 
                </button> 
            </div> 
            <div class="p-4">
                <div class="mb-4">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="admin@example.com">
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Role</label>
                    <input type="text" class="form-control" value="Administrator" disabled>
                </div>
                
                <div class="mt-5 mb-3">
                    <h5>Change Password</h5>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control">
                    </div>
                </div>
            </div>
        </div>