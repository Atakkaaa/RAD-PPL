// Client-side validation functions

// Validate login form
function validateLoginForm() {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();
    let isValid = true;
    
    // Reset validation messages
    document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    
    // Validate username
    if (username === '') {
        showError('username', 'Username is required');
        isValid = false;
    }
    
    // Validate password
    if (password === '') {
        showError('password', 'Password is required');
        isValid = false;
    }
    
    return isValid;
}

// Validate registration form
function validateRegistrationForm() {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();
    const confirmPassword = document.getElementById('confirm_password').value.trim();
    let isValid = true;
    
    // Reset validation messages
    document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    
    // Validate username
    if (username === '') {
        showError('username', 'Username is required');
        isValid = false;
    } else if (username.length < 4) {
        showError('username', 'Username must be at least 4 characters');
        isValid = false;
    }
    
    // Validate password
    if (password === '') {
        showError('password', 'Password is required');
        isValid = false;
    } else if (password.length < 6) {
        showError('password', 'Password must be at least 6 characters');
        isValid = false;
    } else if (!/[A-Z]/.test(password)) {
        showError('password', 'Password must contain at least one uppercase letter');
        isValid = false;
    } else if (!/[0-9]/.test(password)) {
        showError('password', 'Password must contain at least one number');
        isValid = false;
    }
    
    // Validate confirm password
    if (confirmPassword === '') {
        showError('confirm_password', 'Please confirm your password');
        isValid = false;
    } else if (confirmPassword !== password) {
        showError('confirm_password', 'Passwords do not match');
        isValid = false;
    }
    
    return isValid;
}

// Validate student form
function validateStudentForm() {
    const name = document.getElementById('name').value.trim();
    const nim = document.getElementById('nim').value.trim();
    const birthDate = document.getElementById('birth_date').value.trim();
    const address = document.getElementById('address').value.trim();
    const phone = document.getElementById('phone').value.trim();
    let isValid = true;
    
    // Reset validation messages
    document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    
    // Validate name (letters only)
    if (name === '') {
        showError('name', 'Name is required');
        isValid = false;
    } else if (!/^[A-Za-z\s]+$/.test(name)) {
        showError('name', 'Name should contain only letters');
        isValid = false;
    }
    
    // Validate NIM (numbers only)
    if (nim === '') {
        showError('nim', 'NIM is required');
        isValid = false;
    } else if (!/^[0-9]+$/.test(nim)) {
        showError('nim', 'NIM should contain only numbers');
        isValid = false;
    }
    
    // Validate birth date
    if (birthDate === '') {
        showError('birth_date', 'Birth date is required');
        isValid = false;
    }
    
    // Validate address
    if (address === '') {
        showError('address', 'Address is required');
        isValid = false;
    }
    
    // Validate phone (numbers only)
    if (phone === '') {
        showError('phone', 'Phone number is required');
        isValid = false;
    } else if (!/^[0-9]+$/.test(phone)) {
        showError('phone', 'Phone number should contain only numbers');
        isValid = false;
    }
    
    return isValid;
}

// Show error message
function showError(inputId, message) {
    const input = document.getElementById(inputId);
    input.classList.add('is-invalid');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.innerText = message;
    
    input.parentNode.appendChild(errorDiv);
}

// Add event listeners when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Login form validation
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            if (!validateLoginForm()) {
                e.preventDefault();
            }
        });
    }
    
    // Registration form validation
    const registrationForm = document.getElementById('registrationForm');
    if (registrationForm) {
        registrationForm.addEventListener('submit', function(e) {
            if (!validateRegistrationForm()) {
                e.preventDefault();
            }
        });
    }
    
    // Student form validation
    const studentForm = document.getElementById('studentForm');
    if (studentForm) {
        studentForm.addEventListener('submit', function(e) {
            if (!validateStudentForm()) {
                e.preventDefault();
            }
        });
    }
});