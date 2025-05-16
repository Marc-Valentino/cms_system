/**
 * User Management JavaScript
 * Handles interactive functionality for the user management interface
 */

document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const searchInput = document.getElementById('searchUser');
    const addUserForm = document.getElementById('addUserForm');
    const editUserForm = document.getElementById('editUserForm');
    const saveNewUserBtn = document.getElementById('saveNewUser');
    const updateUserBtn = document.getElementById('updateUser');
    const confirmActionBtn = document.getElementById('confirmActionBtn');
    const successToast = document.getElementById('successToast');
    
    // Bootstrap Toast initialization
    let toast;
    if (typeof bootstrap !== 'undefined' && successToast) {
        toast = new bootstrap.Toast(successToast);
    }
    
    // Show toast message
    function showToast(message) {
        const toastMessage = document.getElementById('toastMessage');
        if (toastMessage) {
            toastMessage.textContent = message;
        }
        
        if (toast) {
            toast.show();
        }
    }
    
    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('.user-table tbody tr');
            
            tableRows.forEach(row => {
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
    
    // Form validation
    function validateForm(form) {
        let isValid = true;
        const inputs = form.querySelectorAll('input[required], select[required]');
        
        inputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });
        
        // Email validation
        const emailInput = form.querySelector('input[type="email"]');
        if (emailInput && emailInput.value.trim()) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(emailInput.value)) {
                emailInput.classList.add('is-invalid');
                isValid = false;
            }
        }
        
        // Password validation for new user
        if (form.id === 'addUserForm') {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirmPassword');
            
            if (password.value !== confirmPassword.value) {
                password.classList.add('is-invalid');
                confirmPassword.classList.add('is-invalid');
                isValid = false;
            }
        }
        
        return isValid;
    }
    
    // Add event listeners to remove validation styling on input
    const formInputs = document.querySelectorAll('input, select');
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
    
    // Save new user
    if (saveNewUserBtn) {
        saveNewUserBtn.addEventListener('click', function() {
            if (!validateForm(addUserForm)) {
                return;
            }
            
            // In a real application, you would send this data to the server
            // For now, we'll just simulate a successful save
            const userData = {
                name: document.getElementById('fullName').value,
                email: document.getElementById('email').value,
                role: document.getElementById('role').value,
                password: document.getElementById('password').value
            };
            
            console.log('New user data:', userData);
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
            if (modal) {
                modal.hide();
            }
            
            // Show success message
            showToast('User added successfully!');
            
            // Reset form
            addUserForm.reset();
            
            // In a real application, you would refresh the table or add the new user to it
        });
    }
    
    // Edit user - populate form when edit button is clicked
    const editButtons = document.querySelectorAll('.btn-outline-primary');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            // In a real application, you would fetch user data from the server
            // For now, we'll just use the data from the table row
            const row = this.closest('tr');
            const name = row.cells[0].textContent;
            const email = row.cells[1].textContent;
            const role = row.cells[2].querySelector('.badge').textContent;
            
            // Populate edit form
            document.getElementById('editUserId').value = '1'; // Placeholder ID
            document.getElementById('editFullName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editRole').value = role;
            document.getElementById('editPassword').value = '';
        });
    });
    
    // Update user
    if (updateUserBtn) {
        updateUserBtn.addEventListener('click', function() {
            if (!validateForm(editUserForm)) {
                return;
            }
            
            // In a real application, you would send this data to the server
            // For now, we'll just simulate a successful update
            const userData = {
                id: document.getElementById('editUserId').value,
                name: document.getElementById('editFullName').value,
                email: document.getElementById('editEmail').value,
                role: document.getElementById('editRole').value,
                password: document.getElementById('editPassword').value
            };
            
            console.log('Updated user data:', userData);
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
            if (modal) {
                modal.hide();
            }
            
            // Show success message
            showToast('User updated successfully!');
            
            // In a real application, you would refresh the table or update the user in it
        });
    }
    
    // Confirmation for deactivating user
    window.confirmDeactivate = function(userId) {
        const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        document.getElementById('confirmationTitle').textContent = 'Deactivate User';
        document.getElementById('confirmationMessage').textContent = 'Are you sure you want to deactivate this user?';
        
        const confirmBtn = document.getElementById('confirmActionBtn');
        confirmBtn.className = 'btn btn-danger';
        confirmBtn.textContent = 'Deactivate';
        
        // Set up the action to perform when confirmed
        confirmBtn.onclick = function() {
            // In a real application, you would send this to the server
            console.log('Deactivating user ID:', userId);
            
            // Close modal
            confirmationModal.hide();
            
            // Show success message
            showToast('User deactivated successfully!');
            
            // In a real application, you would refresh the table or update the user status
        };
        
        confirmationModal.show();
    };
    
    // Confirmation for activating user
    window.confirmActivate = function(userId) {
        const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        document.getElementById('confirmationTitle').textContent = 'Activate User';
        document.getElementById('confirmationMessage').textContent = 'Are you sure you want to activate this user?';
        
        const confirmBtn = document.getElementById('confirmActionBtn');
        confirmBtn.className = 'btn btn-success';
        confirmBtn.textContent = 'Activate';
        
        // Set up the action to perform when confirmed
        confirmBtn.onclick = function() {
            // In a real application, you would send this to the server
            console.log('Activating user ID:', userId);
            
            // Close modal
            confirmationModal.hide();
            
            // Show success message
            showToast('User activated successfully!');
            
            // In a real application, you would refresh the table or update the user status
        };
        
        confirmationModal.show();
    };
    
    // Clear form when modal is closed
    const addUserModal = document.getElementById('addUserModal');
    if (addUserModal) {
        addUserModal.addEventListener('hidden.bs.modal', function() {
            addUserForm.reset();
            const invalidInputs = addUserForm.querySelectorAll('.is-invalid');
            invalidInputs.forEach(input => {
                input.classList.remove('is-invalid');
            });
        });
    }
    
    const editUserModal = document.getElementById('editUserModal');
    if (editUserModal) {
        editUserModal.addEventListener('hidden.bs.modal', function() {
            editUserForm.reset();
            const invalidInputs = editUserForm.querySelectorAll('.is-invalid');
            invalidInputs.forEach(input => {
                input.classList.remove('is-invalid');
            });
        });
    }
});