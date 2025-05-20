<?php
// This file contains the header/navbar component for the CMS system
// It should be included in all dashboard pages
?>

<!-- Header -->
<!-- Add this style section if you don't have these styles in your styles.css -->

<link rel="stylesheet" href="css/admin-navbar.css">
<div class="header">
    <button class="btn d-lg-none" id="toggle-sidebar">
        <i class="bi bi-list"></i>
    </button>
    <div class="navbar-user">
        <span class="me-2">Welcome, Administrator</span>
        <div class="dropdown">
            <button class="btn btn-link dropdown-toggle" type="button" id="settingsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-gear-fill fs-5"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="profile.php">
                    <i class="bi bi-person me-2"></i> Profile
                </a>
                <a class="dropdown-item" href="settings.php">
                    <i class="bi bi-gear me-2"></i> Settings
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout.php">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
            </div>
        </div>
    </div>
    <div class="notification-bell" id="notificationBell">
        <i class="bi bi-bell"></i>
        <span class="notification-badge"><?php echo isset($notifications) ? count($notifications) : '0'; ?></span>
        
        <!-- Notification Panel -->
        <div class="notification-panel" id="notificationPanel">
            <div class="notification-panel-header">
                <h6 class="mb-0">Notifications</h6>
                <span class="mark-all-read">Mark all as read</span>
            </div>
            
            <!-- Heartbeat Loader -->
            <div class="heartbeat-loader" id="heartbeatLoader">
                <div class="heartbeat-line">
                    <svg viewBox="0 0 600 100" preserveAspectRatio="none">
                        <path d="M0,50 L100,50 L120,30 L140,70 L160,30 L180,70 L200,30 L220,70 L240,50 L300,50 L320,30 L340,70 L360,30 L380,70 L400,30 L420,70 L440,50 L500,50 L520,30 L540,70 L560,30 L580,70 L600,50" />
                    </svg>
                </div>
            </div>
            
            <!-- Notification Content -->
            <div class="notification-panel-body" id="notificationContent" style="display: none;">
                <?php if(isset($notifications)): ?>
                    <?php foreach ($notifications as $notification): ?>
                    <div class="notification-item">
                        <div class="notification-icon">
                            <?php 
                            $icon = 'bi-bell';
                            switch ($notification['type']) {
                                case 'Lab Result':
                                    $icon = 'bi-clipboard2-pulse';
                                    break;
                                case 'Reminder':
                                    $icon = 'bi-alarm';
                                    break;
                                case 'System':
                                    $icon = 'bi-gear';
                                    break;
                                case 'Appointment':
                                    $icon = 'bi-calendar-check';
                                    break;
                                case 'Patient Feedback':
                                    $icon = 'bi-chat-left-text';
                                    break;
                            }
                            ?>
                            <i class="bi <?php echo $icon; ?>"></i>
                        </div>
                        <div class="notification-details">
                            <p class="notification-title"><?php echo $notification['type']; ?></p>
                            <p class="notification-message"><?php echo $notification['message']; ?></p>
                            <p class="notification-time"><?php echo $notification['time']; ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="notification-item">
                        <div class="notification-icon">
                            <i class="bi bi-info-circle"></i>
                        </div>
                        <div class="notification-details">
                            <p class="notification-title">No Notifications</p>
                            <p class="notification-message">You have no new notifications</p>
                            <p class="notification-time">Just now</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add this script to fix the sidebar toggle functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('toggle-sidebar');
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        
        // Create overlay element
        const overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        document.body.appendChild(overlay);
        
        if (toggleButton && sidebar && mainContent) {
            toggleButton.addEventListener('click', function(e) {
                e.preventDefault();
                sidebar.classList.toggle('active');
                mainContent.classList.toggle('active');
                overlay.classList.toggle('active');
            });
            
            // Close sidebar when clicking on overlay
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                mainContent.classList.remove('active');
                overlay.classList.remove('active');
            });
        }
    });
</script>

<!-- Add notification panel styles and script -->
<style>
    /* Notification Panel Styles */
    .notification-bell {
        position: relative;
        cursor: pointer;
    }
    
    .notification-panel {
        position: absolute;
        top: 100%;
        right: 0;
        width: 320px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        z-index: 1000;
        display: none;
        overflow: hidden;
    }
    
    .notification-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .mark-all-read {
        color: #0d6efd;
        font-size: 0.8rem;
        cursor: pointer;
    }
    
    .notification-panel-body {
        max-height: 350px;
        overflow-y: auto;
    }
    
    .notification-item {
        display: flex;
        padding: 12px 15px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }
    
    .notification-item:hover {
        background-color: #f8f9fa;
    }
    
    .notification-icon {
        margin-right: 15px;
        color: #0d6efd;
    }
    
    .notification-details {
        flex: 1;
    }
    
    .notification-title {
        font-weight: 600;
        margin-bottom: 3px;
    }
    
    .notification-message {
        font-size: 0.9rem;
        margin-bottom: 5px;
    }
    
    .notification-time {
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 0;
    }
    
    /* Heartbeat Loader Styles */
    .heartbeat-loader {
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .heartbeat-line {
        width: 100%;
        height: 40px;
    }
    
    .heartbeat-line svg {
        width: 100%;
        height: 100%;
    }
    
    .heartbeat-line svg path {
        fill: none;
        stroke: #0d6efd;
        stroke-width: 2;
        stroke-linecap: round;
        animation: pulse 1.5s infinite ease-in-out;
    }
    
    @keyframes pulse {
        0% {
            stroke-dasharray: 0 1000;
            stroke-dashoffset: 0;
        }
        50% {
            stroke-dasharray: 1000 1000;
            stroke-dashoffset: 0;
        }
        100% {
            stroke-dasharray: 1000 1000;
            stroke-dashoffset: -1000;
        }
    }
</style>

<!-- Add notification panel script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notificationBell = document.getElementById('notificationBell');
        const notificationPanel = document.getElementById('notificationPanel');
        const heartbeatLoader = document.getElementById('heartbeatLoader');
        const notificationContent = document.getElementById('notificationContent');
        
        // Toggle notification panel
        if (notificationBell) {
            notificationBell.addEventListener('click', function(e) {
                e.stopPropagation();
                
                if (notificationPanel.style.display === 'block') {
                    notificationPanel.style.display = 'none';
                } else {
                    notificationPanel.style.display = 'block';
                    heartbeatLoader.style.display = 'flex';
                    notificationContent.style.display = 'none';
                    
                    // Show content after animation (500ms)
                    setTimeout(function() {
                        heartbeatLoader.style.display = 'none';
                        notificationContent.style.display = 'block';
                    }, 500);
                }
            });
        }
        
        // Close panel when clicking outside
        document.addEventListener('click', function(e) {
            if (notificationBell && notificationPanel && !notificationBell.contains(e.target) && !notificationPanel.contains(e.target)) {
                notificationPanel.style.display = 'none';
            }
        });
        
        // Mark all as read functionality
        const markAllRead = document.querySelector('.mark-all-read');
        if (markAllRead) {
            markAllRead.addEventListener('click', function(e) {
                e.stopPropagation();
                const badge = document.querySelector('.notification-badge');
                if (badge) {
                    badge.textContent = '0';
                }
                // Here you would also make an AJAX call to update the notification status in the database
            });
        }
    });
</script>

<!-- Add sidebar script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the toggle button and sidebar elements
        const toggleButton = document.getElementById('toggle-sidebar');
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        const closeButton = document.getElementById('close-sidebar');
        
        // Toggle sidebar when burger icon is clicked
        if (toggleButton) {
            toggleButton.addEventListener('click', function() {
                if (sidebar) {
                    sidebar.classList.toggle('active');
                }
                if (mainContent) {
                    mainContent.classList.toggle('active');
                }
            });
        }
        
        // Close sidebar when X button is clicked
        if (closeButton) {
            closeButton.addEventListener('click', function() {
                if (sidebar) {
                    sidebar.classList.remove('active');
                }
                if (mainContent) {
                    mainContent.classList.remove('active');
                }
            });
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnToggleButton = toggleButton.contains(event.target);
            
            // Only apply this behavior on mobile screens
            if (window.innerWidth < 992 && !isClickInsideSidebar && !isClickOnToggleButton && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                mainContent.classList.remove('active');
            }
        });
    });
</script>
