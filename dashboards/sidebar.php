<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo-container">
            <div class="heart-logo">
                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <!-- Heart shape -->
                    <path class="heart" d="M50,30 C50,30 65,10 80,20 C95,30 95,50 80,65 C65,80 50,90 50,90 C50,90 35,80 20,65 C5,50 5,30 20,20 C35,10 50,30 50,30 Z" fill="#e74c3c" />
                    
                    <!-- Heart rate line -->
                    <path class="heartbeat-line" d="M10,50 L30,50 L40,30 L50,70 L60,30 L70,70 L80,50 L90,50" fill="none" stroke="#fff" stroke-width="3" />
                </svg>
            </div>
            <h3>MediCare Clinic</h3>
        </div>
        <!-- Removed the X button here -->
    </div>
    <div class="sidebar-menu">
        <ul>
            <li><a href="doctor.php" <?php echo basename($_SERVER['PHP_SELF']) == 'doctor.php' ? 'class="active"' : ''; ?>><i class="bi bi-speedometer2"></i> <span>Dashboard</span></a></li>
            <li><a href="appointments.php" <?php echo basename($_SERVER['PHP_SELF']) == 'appointments.php' ? 'class="active"' : ''; ?>><i class="bi bi-calendar-check"></i> <span>Appointments</span></a></li>
            <li><a href="patients.php" <?php echo basename($_SERVER['PHP_SELF']) == 'patients.php' ? 'class="active"' : ''; ?>><i class="bi bi-people"></i> <span>Patients</span></a></li>
            <li><a href="medical-notes.php" <?php echo basename($_SERVER['PHP_SELF']) == 'medical-notes.php' ? 'class="active"' : ''; ?>><i class="bi bi-journal-medical"></i> <span>Medical Notes</span></a></li>
            <li><a href="notifications.php" <?php echo basename($_SERVER['PHP_SELF']) == 'notifications.php' ? 'class="active"' : ''; ?>><i class="bi bi-bell"></i> <span>Notifications</span></a></li>
            <li><a href="settings.php" <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'class="active"' : ''; ?>><i class="bi bi-gear"></i> <span>Settings</span></a></li>
            <li><a href="#" id="logout-link"><i class="bi bi-box-arrow-right"></i> <span>Logout</span></a></li>
        </ul>
    </div>
    <div class="sidebar-footer">
        <p>&copy; 2025 Clinic Management System</p>
    </div>
</div>

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                    Confirm Logout
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to log out of your session?</p>
                <p class="text-muted small">Any unsaved changes will be lost.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </button>
                <a href="logout.php" class="btn btn-primary">
                    <i class="bi bi-box-arrow-right me-1"></i> Yes, Logout
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Add event listener to logout link
    document.addEventListener('DOMContentLoaded', function() {
        const logoutLink = document.getElementById('logout-link');
        if (logoutLink) {
            logoutLink.addEventListener('click', function(e) {
                e.preventDefault();
                const logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
                logoutModal.show();
            });
        }
    });
</script>

<style>
    /* Heart Logo Animation */
    .heart-logo {
        width: 65px;
        height: 65px;
        margin-right: 10px;
    }
    
    .heart {
        animation: pulse 1.5s ease-in-out infinite;
        transform-origin: center;
    }
    
    .heartbeat-line {
        stroke-dasharray: 400;
        stroke-dashoffset: 400;
        animation: dash 3s linear infinite;
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }
    
    @keyframes dash {
        0% {
            stroke-dashoffset: 400;
        }
        100% {
            stroke-dashoffset: 0;
        }
    }
    
    /* Logo container styling */
    .logo-container {
        display: flex;
        align-items: center;
        padding: 15px;
    }
    
    .logo-container h3 {
        margin: 0;
        color: #fff;
        font-size: 1.3rem;
        font-weight: 700;
    }
</style>
