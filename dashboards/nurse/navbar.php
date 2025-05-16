<?php
// This file contains the header/navbar component for the CMS system
// It should be included in all dashboard pages

// Initialize the unread_count variable
$unread_count = 0; // You can replace this with actual notification count logic later

?>

<!-- Header -->
<link rel="stylesheet" href="css/navbar.css">
<div class="header">
    <!-- Burger icon removed as requested -->
    <div class="doctor-info">
        <i class="bi bi-person-circle user-icon"></i>
        <div>
            <p class="doctor-name">
                <?php 
                // Display user's name or username
                if (!empty($user['first_name']) && !empty($user['last_name'])) {
                    echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']);
                } else {
                    echo htmlspecialchars($user['username'] ?? $_SESSION['username'] ?? 'User');
                }
                ?>
            </p>
            <p class="doctor-role">
                <?php 
                if (isset($user['specialty'])) {
                    echo "Doctor - " . htmlspecialchars($user['specialty']);
                } elseif (isset($_SESSION['role'])) {
                    echo htmlspecialchars($_SESSION['role']);
                }
                ?>
            </p>
        </div>
    </div>
    <div class="notification-bell" id="notificationBell">
        <i class="bi bi-bell"></i>
        <?php if ($unread_count > 0): ?>
            <span class="badge bg-danger"><?php echo $unread_count; ?></span>
        <?php endif; ?>
        <div class="notification-dropdown">
            <!-- Notification content here -->
        </div>
    </div>
    
    <!-- Dropdown menu removed as requested -->
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
    
    /* User Icon Styles */
    .user-icon {
        font-size: 2.2rem;
        color: #0d6efd;
        margin-right: 15px;
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

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
    </div>
</nav>
