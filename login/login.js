// Role selector functionality
document.addEventListener('DOMContentLoaded', function() {
    const roleOptions = document.querySelectorAll('.role-option');
    
    roleOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all options
            roleOptions.forEach(opt => opt.classList.remove('active'));
            
            // Add active class to clicked option
            this.classList.add('active');
            
            // You can store the selected role in a hidden input if needed
            const selectedRole = this.getAttribute('data-role');
            
            // If you have a hidden input for role
            if (document.getElementById('selected_role')) {
                document.getElementById('selected_role').value = selectedRole;
            }
        });
    });
});