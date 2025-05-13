 // Role selector functionality
 document.querySelectorAll('.role-option').forEach(option => {
    option.addEventListener('click', function() {
        // Remove active class from all options
        document.querySelectorAll('.role-option').forEach(opt => {
            opt.classList.remove('active');
        });
        
        // Add active class to clicked option
        this.classList.add('active');
        
        // Set username field based on role
        const role = this.getAttribute('data-role');
        document.getElementById('username').value = role;
    });
});