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
            --primary-color: #0d6efd;
            --primary-hover: #0b5ed7;
            --sidebar-width: 250px;
            --header-height: 60px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
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
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: all 0.3s;
        }
        
        .sidebar-header {
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 0 20px;
            background-color: var(--primary-color);
            color: white;
        }
        
        .sidebar-header h3 {
            font-size: 1.2rem;
            margin: 0;
            font-weight: 600;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #495057;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .menu-item:hover {
            background-color: rgba(13, 110, 253, 0.1);
            color: var(--primary-color);
        }
        
        .menu-item.active {
            background-color: rgba(13, 110, 253, 0.1);
            color: var(--primary-color);
            border-left: 4px solid var(--primary-color);
        }
        
        .menu-item i {
            margin-right: 10px;
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }
        
        /* Header Styles */
        .main-header {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--header-height);
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 900;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 20px;
        }
        
        .admin-profile {
            display: flex;
            align-items: center;
        }
        
        .admin-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        
        .admin-info {
            display: flex;
            flex-direction: column;
            margin-right: 15px;
        }
        
        .admin-name {
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .admin-role {
            font-size: 0.75rem;
            background-color: var(--primary-color);
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            display: inline-block;
        }
        
        .notification-icon {
            margin-left: 20px;
            font-size: 1.2rem;
            color: #6c757d;
            cursor: pointer;
            position: relative;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #dc3545;
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
            padding: 20px;
            min-height: calc(100vh - var(--header-height));
        }
        
        .dashboard-title {
            margin-bottom: 20px;
            color: #343a40;
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
        
        .toggle-sidebar {
            display: none;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1001;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
            cursor: pointer;
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
            <h3>Clinic Management</h3>
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
                <i class="bi bi-gear"></i>
                <span>System Settings</span>
            </a>
            <a href="login.php" class="menu-item">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Header -->
    <div class="main-header">
        <div class="d-flex justify-content-between w-100 align-items-center">
            <div class="page-title d-none d-md-block">
                <h4 class="m-0">Clinic Management System</h4>
            </div>
            <div class="admin-profile">
                <div class="notification-icon">
                    <i class="bi bi-bell"></i>
                    <span class="notification-badge">3</span>
                </div>
                <img src="https://via.placeholder.com/40" alt="Admin Profile">
                <div class="admin-info">
                    <span class="admin-name">Dr. John Smith</span>
                    <span class="admin-role">Admin</span>
                </div>
            </div>
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
                    <div class="stats-number">1,248</div>
                    <div class="stats-label">Total Patients</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stats-number">42</div>
                    <div class="stats-label">Total Staff</div>
                    <div class="staff-chart">
                        <div class="chart-bar">
                            <div class="bar-fill doctors-bar"></div>
                            <div class="bar-label">Doctors (18)</div>
                        </div>
                        <div class="chart-bar">
                            <div class="bar-fill nurses-bar"></div>
                            <div class="bar-label">Nurses (15)</div>
                        </div>
                        <div class="chart-bar">
                            <div class="bar-fill receptionists-bar"></div>
                            <div class="bar-label">Reception (9)</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="stats-number">37</div>
                    <div class="stats-label">Appointments Today</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="stats-number">$8,540</div>
                    <div class="stats-label">Today's Revenue</div>
                </div>
            </div>
        </div>
        
        <!-- User Management Section -->
        <div class="content-card" id="users">
            <div class="card-header">
                <h5 class="card-title">User Management</h5>
                <button class="btn btn-primary action-btn">
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
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://via.placeholder.com/40" alt="User" class="user-avatar me-2">
                                    <span>Dr. Sarah Johnson</span>
                                </div>
                            </td>
                            <td>Doctor</td>
                            <td>sarah.j@clinic.com</td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td>Today, 9:45 AM</td>
                            <td>
                                <i class="bi bi-eye action-icon"></i>
                                <i class="bi bi-pencil action-icon"></i>
                                <i class="bi bi-trash action-icon delete"></i>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://via.placeholder.com/40" alt="User" class="user-avatar me-2">
                                    <span>Nurse Mike Peterson</span>
                                </div>
                            </td>
                            <td>Nurse</td>
                            <td>mike.p@clinic.com</td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td>Yesterday, 4:30 PM</td>
                            <td>
                                <i class="bi bi-eye action-icon"></i>
                                <i class="bi bi-pencil action-icon"></i>
                                <i class="bi bi-trash action-icon delete"></i>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://via.placeholder.com/40" alt="User" class="user-avatar me-2">
                                    <span>Lisa Morgan</span>
                                </div>
                            </td>
                            <td>Receptionist</td>
                            <td>lisa.m@clinic.com</td>
                            <td><span class="status-badge status-inactive">Inactive</span></td>
                            <td>Aug 15, 2023</td>
                            <td>
                                <i class="bi bi-eye action-icon"></i>
                                <i class="bi bi-pencil action-icon"></i>
                                <i class="bi bi-trash action-icon delete"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>Showing 1-3 of 42 users</div>
                <nav>
                    <ul class="pagination mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- System Settings Section -->
        <div class="content-card" id="settings">
            <div class="card-header">
                <h5 class="card-title">System Settings</h5>
                <button class="btn btn-outline-primary action-btn">
                    <i class="bi bi-arrow-clockwise"></i> Reset to Default
                </button>
            </div>
            <div class="settings-grid">
                <div class="settings-item">
                    <div class="settings-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div class="settings-label">Roles & Permissions</div>
                </div>
                <div class="settings-item">
                    <div class="settings-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="settings-label">Site Information</div>
                </div>
                <div class="settings-item">
                    <div class="settings-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div class="settings-label">Email Templates</div>
                </div>
                <div class="settings-item">
                    <div class="settings-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <div class="settings-label">Security Settings</div>
                </div>
                <div class="settings-item">
                    <div class="settings-icon">
                        <i class="bi bi-database"></i>
                    </div>
                    <div class="settings-label">Backup & Restore</div>
                </div>
                <div class="settings-item">
                    <div class="settings-icon">
                        <i class="bi bi-translate"></i>
                    </div>
                    <div class="settings-label">Language Settings</div>
                </div>
                <div class="settings-item">
                    <div class="settings-icon">
                        <i class="bi bi-palette"></i>
                    </div>
                    <div class="settings-label">Theme Settings</div>
                </div>
                <div class="settings-item">
                    <div class="settings-icon">
                        <i class="bi bi-bell"></i>
                    </div>
                    <div class="settings-label">Notification Settings</div>
                </div>
            </div>
        </div>
        <!-- System Settings Section -->
        <div class="content-card" id="settings">
            <div class="card-header">
                <h5 class="card-title">System Settings</h5>
                <button class="btn btn-outline-secondary action-btn">
                    <i class="bi bi-arrow-clockwise"></i> Reset to Default
                </button>
            </div>

            