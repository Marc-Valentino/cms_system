/**
 * Admin Dashboard JavaScript
 * Handles interactive functionality for the admin dashboard
 */

document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');
    const logoutBtn = document.querySelector('#logout-btn');
    
    // Toggle sidebar on mobile
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            mainContent.classList.toggle('active');
            
            // Create overlay if it doesn't exist
            if (!sidebarOverlay) {
                const overlay = document.createElement('div');
                overlay.classList.add('sidebar-overlay');
                document.body.appendChild(overlay);
                
                // Add click event to close sidebar when overlay is clicked
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    mainContent.classList.remove('active');
                    overlay.classList.remove('active');
                });
            } else {
                sidebarOverlay.classList.toggle('active');
            }
        });
    }
    
    // Logout confirmation
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Create and show confirmation modal
            const confirmLogout = confirm('Are you sure you want to logout?');
            
            if (confirmLogout) {
                window.location.href = logoutBtn.getAttribute('href');
            }
        });
    }
    
    // Initialize tooltips if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    // Handle responsive tables
    const tables = document.querySelectorAll('.table-responsive');
    if (tables.length > 0) {
        // Add horizontal scroll indicator if needed
        tables.forEach(table => {
            if (table.scrollWidth > table.clientWidth) {
                const scrollIndicator = document.createElement('div');
                scrollIndicator.classList.add('scroll-indicator');
                scrollIndicator.innerHTML = '<i class="bi bi-arrow-left-right"></i> Scroll horizontally to see more';
                table.parentNode.insertBefore(scrollIndicator, table);
            }
        });
    }
    
    // Dynamic card updates (placeholder for future AJAX functionality)
    function updateDashboardCards() {
        // This function would typically fetch updated data via AJAX
        // For now, it's just a placeholder for future implementation
        console.log('Dashboard cards would update with real-time data here');
        
        // Example of how this might work with real data:
        /*
        fetch('/api/admin/dashboard-stats')
            .then(response => response.json())
            .then(data => {
                document.querySelector('#total-patients').textContent = data.totalPatients;
                document.querySelector('#total-staff').textContent = data.totalStaff;
                document.querySelector('#appointments-today').textContent = data.appointmentsToday;
                document.querySelector('#billing-summary').textContent = data.billingSummary;
            })
            .catch(error => console.error('Error updating dashboard:', error));
        */
    }
    
    // System logs auto-refresh (placeholder)
    function refreshSystemLogs() {
        // This would fetch the latest system logs
        console.log('System logs would refresh here');
        
        // Example implementation:
        /*
        fetch('/api/admin/latest-logs')
            .then(response => response.json())
            .then(data => {
                const logsContainer = document.querySelector('.logs-container');
                // Clear existing logs
                logsContainer.innerHTML = '';
                
                // Add new logs
                data.logs.forEach(log => {
                    const logItem = document.createElement('div');
                    logItem.classList.add('log-item');
                    logItem.innerHTML = `
                        <div class="log-time">${log.time}</div>
                        <div class="log-content">
                            <div class="log-action">${log.action}</div>
                            <p class="log-timestamp">${log.timestamp}</p>
                        </div>
                    `;
                    logsContainer.appendChild(logItem);
                });
            })
            .catch(error => console.error('Error refreshing logs:', error));
        */
    }
    
    // Add event listeners for tab switching if tabs exist
    const tabLinks = document.querySelectorAll('.nav-link[data-bs-toggle="tab"]');
    if (tabLinks.length > 0) {
        tabLinks.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(e) {
                // You could load specific content when a tab is activated
                const targetId = e.target.getAttribute('href');
                console.log(`Tab ${targetId} activated`);
                
                // Example: Load specific content for each tab
                if (targetId === '#user-management') {
                    // Load user management data
                } else if (targetId === '#system-logs') {
                    refreshSystemLogs();
                }
            });
        });
    }
    
    // Handle collapsible sections
    const collapsibleHeaders = document.querySelectorAll('.collapsible-header');
    if (collapsibleHeaders.length > 0) {
        collapsibleHeaders.forEach(header => {
            header.addEventListener('click', function() {
                const content = this.nextElementSibling;
                this.classList.toggle('active');
                
                if (content.style.maxHeight) {
                    content.style.maxHeight = null;
                } else {
                    content.style.maxHeight = content.scrollHeight + "px";
                }
            });
        });
    }
    
    // Initialize any charts if Chart.js is available
    if (typeof Chart !== 'undefined') {
        // Example: Patient Growth Chart
        const patientGrowthCtx = document.getElementById('patientGrowthChart');
        if (patientGrowthCtx) {
            new Chart(patientGrowthCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'New Patients',
                        data: [12, 19, 15, 17, 22, 25],
                        borderColor: '#0d6efd',
                        tension: 0.1,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
        
        // Example: Staff Distribution Chart
        const staffDistributionCtx = document.getElementById('staffDistributionChart');
        if (staffDistributionCtx) {
            new Chart(staffDistributionCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Doctors', 'Nurses', 'Receptionists', 'Admin'],
                    datasets: [{
                        data: [12, 19, 8, 5],
                        backgroundColor: [
                            '#0d6efd',
                            '#28a745',
                            '#ffc107',
                            '#17a2b8'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    }
    
    // Call initial update
    updateDashboardCards();
    
    // Set up periodic refresh (commented out for now)
    // const refreshInterval = 60000; // 1 minute
    // setInterval(updateDashboardCards, refreshInterval);
});