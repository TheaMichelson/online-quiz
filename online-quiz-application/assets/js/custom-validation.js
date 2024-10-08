document.querySelector('form').addEventListener('submit', function(event) {
    // Custom form validation logic
    let valid = true;
    const inputs = document.querySelectorAll('input[required]');
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            valid = false;
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    });

    if (!valid) {
        event.preventDefault();  // Prevent form submission if validation fails
        alert('Please fill all required fields');
    }
});
