/**
 * User Permission Management JavaScript
 * Handles interactive functionality for the user permission management interface
 */

document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const permissionForm = document.getElementById('permissionForm');
    const roleSelect = document.getElementById('roleSelect');
    const permissionsContainer = document.querySelector('.permissions-container');
    const userSelect = document.getElementById('userSelect');
    const searchInput = document.getElementById('searchUsers');
    const roleFilter = document.getElementById('roleFilter');
    const permissionModal = document.getElementById('permissionModal');
    const viewPermissionModal = document.getElementById('viewPermissionModal');
    const savePermissionBtn = document.getElementById('savePermissionBtn');
    const permissionToast = document.getElementById('permissionToast');
    
    // Bootstrap Toast initialization
    let toastInstance;
    if (typeof bootstrap !== 'undefined' && permissionToast) {
        toastInstance = new bootstrap.Toast(permissionToast);
    }
    
    // Show success toast message
    function showToast(message, type = 'success') {
        if (permissionToast) {
            const toastBody = permissionToast.querySelector('.toast-body');
            const toastHeader = permissionToast.querySelector('.toast-header');
            
            if (toastBody) toastBody.textContent = message;
            
            // Set toast color based on type
            permissionToast.className = 'toast';
            if (type === 'success') {
                permissionToast.classList.add('bg-success', 'text-white');
                if (toastHeader) toastHeader.classList.add('bg-success', 'text-white');
            } else if (type === 'danger') {
                permissionToast.classList.add('bg-danger', 'text-white');
                if (toastHeader) toastHeader.classList.add('bg-danger', 'text-white');
            }
            
            if (toastInstance) {
                toastInstance.show();
            }
        }
    }
    
    // Role-based permission templates
    const rolePermissions = {
        doctor: {
            patients: {
                view: true,
                edit: true,
                delete: false
            },
            appointments: {
                view: true,
                create: true,
                edit: true,
                delete: true
            },
            prescriptions: {
                view: true,
                create: true,
                edit: true,
                delete: false
            },
            billing: {
                view: true,
                create: false,
                edit: false,
                delete: false
            },
            reports: {
                view: true,
                create: true,
                export: true
            },
            users: {
                view: false,
                create: false,
                edit: false,
                delete: false
            }
        },
        nurse: {
            patients: {
                view: true,
                edit: true,
                delete: false
            },
            appointments: {
                view: true,
                create: true,
                edit: true,
                delete: false
            },
            prescriptions: {
                view: true,
                create: false,
                edit: false,
                delete: false
            },
            billing: {
                view: true,
                create: false,
                edit: false,
                delete: false
            },
            reports: {
                view: true,
                create: false,
                export: true
            },
            users: {
                view: false,
                create: false,
                edit: false,
                delete: false
            }
        },
        receptionist: {
            patients: {
                view: true,
                edit: true,
                delete: false
            },
            appointments: {
                view: true,
                create: true,
                edit: true,
                delete: true
            },
            prescriptions: {
                view: true,
                create: false,
                edit: false,
                delete: false
            },
            billing: {
                view: true,
                create: true,
                edit: true,
                delete: false
            },
            reports: {
                view: false,
                create: false,
                export: false
            },
            users: {
                view: false,
                create: false,
                edit: false,
                delete: false
            }
        },
        admin: {
            patients: {
                view: true,
                edit: true,
                delete: true
            },
            appointments: {
                view: true,
                create: true,
                edit: true,
                delete: true
            },
            prescriptions: {
                view: true,
                create: true,
                edit: true,
                delete: true
            },
            billing: {
                view: true,
                create: true,
                edit: true,
                delete: true
            },
            reports: {
                view: true,
                create: true,
                export: true
            },
            users: {
                view: true,
                create: true,
                edit: true,
                delete: true
            }
        }
    };
    
    // Update permissions based on selected role
    function updatePermissions(role) {
        if (!permissionsContainer || !rolePermissions[role]) return;
        
        const permissions = rolePermissions[role];
        
        // Update all checkboxes based on the role template
        for (const category in permissions) {
            for (const action in permissions[category]) {
                const checkbox = document.getElementById(`${category}_${action}`);
                if (checkbox) {
                    checkbox.checked = permissions[category][action];
                }
            }
        }
    }
    
    // Role select change event
    if (roleSelect) {
        roleSelect.addEventListener('change', function() {
            const selectedRole = this.value;
            if (selectedRole) {
                updatePermissions(selectedRole);
            }
        });
    }
    
    // Form submission
    if (permissionForm) {
        permissionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            if (!validateForm()) {
                showToast('Please fill in all required fields', 'danger');
                return;
            }
            
            // Collect form data
            const formData = new FormData(this);
            const permissions = {};
            
            // Collect all permission checkboxes
            const checkboxes = permissionsContainer.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                const name = checkbox.id;
                permissions[name] = checkbox.checked;
            });
            
            // In a real application, you would send this data to the server
            // For now, we'll just simulate a successful save
            console.log('Form data:', {
                user: formData.get('user'),
                role: formData.get('role'),
                permissions: permissions
            });
            
            // Close modal and show success message
            const modalElement = document.getElementById('permissionModal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
            
            showToast('User permissions saved successfully');
            
            // Reset form
            this.reset();
        });
    }
    
    // Form validation
    function validateForm() {
        let isValid = true;
        
        if (userSelect && userSelect.value === '') {
            userSelect.classList.add('is-invalid');
            isValid = false;
        } else if (userSelect) {
            userSelect.classList.remove('is-invalid');
        }
        
        if (roleSelect && roleSelect.value === '') {
            roleSelect.classList.add('is-invalid');
            isValid = false;
        } else if (roleSelect) {
            roleSelect.classList.remove('is-invalid');
        }
        
        return isValid;
    }
    
    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('table tbody tr');
            
            rows.forEach(row => {
                const name = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const email = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const role = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                
                if (name.includes(searchTerm) || email.includes(searchTerm) || role.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Role filter functionality
    if (roleFilter) {
        roleFilter.addEventListener('change', function() {
            const selectedRole = this.value.toLowerCase();
            const rows = document.querySelectorAll('table tbody tr');
            
            rows.forEach(row => {
                const role = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                
                if (selectedRole === 'all' || role === selectedRole) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Edit permission button click
    const editButtons = document.querySelectorAll('.edit-permission-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            const userRole = this.getAttribute('data-user-role');
            
            // Set user info in modal
            if (userSelect) userSelect.value = userId;
            if (roleSelect) roleSelect.value = userRole;
            
            // Update permissions based on role
            updatePermissions(userRole);
            
            // Set user name in modal if there's a display element
            const userNameDisplay = document.getElementById('selectedUserName');
            if (userNameDisplay) {
                userNameDisplay.textContent = userName;
            }
            
            // Open modal
            const modal = new bootstrap.Modal(document.getElementById('permissionModal'));
            modal.show();
        });
    });
    
    // View permission button click
    const viewButtons = document.querySelectorAll('.view-permission-btn');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            const userRole = this.getAttribute('data-user-role');
            
            // Set user info in view modal
            const userNameDisplay = document.getElementById('viewUserName');
            const userRoleDisplay = document.getElementById('viewUserRole');
            
            if (userNameDisplay) userNameDisplay.textContent = userName;
            if (userRoleDisplay) userRoleDisplay.textContent = userRole;
            
            // In a real application, you would fetch the user's permissions from the server
            // For now, we'll just use our role templates
            const permissions = rolePermissions[userRole.toLowerCase()];
            const permissionsList = document.getElementById('permissionsList');
            
            if (permissionsList && permissions) {
                permissionsList.innerHTML = '';
                
                for (const category in permissions) {
                    // Create category header
                    const categoryHeader = document.createElement('div');
                    categoryHeader.className = 'permission-category mt-3';
                    categoryHeader.textContent = category.charAt(0).toUpperCase() + category.slice(1);
                    permissionsList.appendChild(categoryHeader);
                    
                    // Create permission items
                    for (const action in permissions[category]) {
                        const permissionItem = document.createElement('div');
                        permissionItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                        
                        const actionName = action.charAt(0).toUpperCase() + action.slice(1);
                        permissionItem.innerHTML = `
                            <span>${actionName}</span>
                            <span class="badge ${permissions[category][action] ? 'bg-success' : 'bg-danger'}">
                                ${permissions[category][action] ? 'Allowed' : 'Denied'}
                            </span>
                        `;
                        
                        permissionsList.appendChild(permissionItem);
                    }
                }
            }
            
            // Open view modal
            const modal = new bootstrap.Modal(document.getElementById('viewPermissionModal'));
            modal.show();
        });
    });
    
    // Initialize the page
    function initPage() {
        // If role is preselected, update permissions
        if (roleSelect && roleSelect.value) {
            updatePermissions(roleSelect.value);
        }
        
        // Add required validation to select elements
        if (userSelect) userSelect.setAttribute('required', '');
        if (roleSelect) roleSelect.setAttribute('required', '');
    }
    
    // Initialize the page
    initPage();
});