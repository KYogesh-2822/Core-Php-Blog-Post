// ─── Auto hide alerts after 4 seconds ───
document.addEventListener('DOMContentLoaded', function () {

    const alerts = document.querySelectorAll('.alert-error, .alert-success');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 4000);
    });

    // ─── Init form validation if form exists ───
    initFormValidation();
});


// ════════════════════════════════════════
//  VALIDATION RULES
// ════════════════════════════════════════
const validationRules = {
    name: [
        {
            test: value => value.trim() !== '',
            message: 'Full name is required.'
        },
        {
            test: value => value.trim().length >= 3,
            message: 'Name must be at least 3 characters.'
        }
    ],

    email: [
        {
            test: value => value.trim() !== '',
            message: 'Email address is required.'
        },
        {
            test: value => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
            message: 'Please enter a valid email address.'
        }
    ],

    password: [
        {
            test: value => value !== '',
            message: 'Password is required.'
        },
        {
            test: value => value.length >= 6,
            message: 'Password must be at least 6 characters.'
        }
    ],

    confirm_password: [
        {
            test: value => value !== '',
            message: 'Please confirm your password.'
        },
        {
            test: value => {
                const password = document.querySelector('[name="password"]');
                return password ? value === password.value : true;
            },
            message: 'Passwords do not match.'
        }
    ],

    code: [
        {
            test: value => value.trim() !== '',
            message: 'Verification code is required.'
        },
        {
            test: value => /^\d{6}$/.test(value.trim()),
            message: 'Code must be exactly 6 digits.'
        }
    ]
};


// ════════════════════════════════════════
//  CORE FUNCTIONS
// ════════════════════════════════════════

// Show error under a field
function showError(input, message) {
    clearError(input);

    input.classList.add('input-error');

    const error = document.createElement('div');
    error.className = 'field-error';
    error.textContent = message;

    input.parentNode.insertBefore(error, input.nextSibling);
}

// Remove error from a field
function clearError(input) {
    input.classList.remove('input-error');
    const next = input.nextSibling;
    if (next && next.classList && next.classList.contains('field-error')) {
        next.remove();
    }
}

// Validate a single field
function validateField(input) {
    const name  = input.name;
    const value = input.value;
    const rules = validationRules[name];

    if (!rules) return true;  // no rules = skip

    for (const rule of rules) {
        if (!rule.test(value)) {
            showError(input, rule.message);
            return false;
        }
    }

    clearError(input);
    return true;
}

// Validate entire form
function validateForm(form) {
    let isValid = true;

    const inputs = form.querySelectorAll('input[name]');
    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });

    return isValid;
}


// ════════════════════════════════════════
//  INIT — attach events to form
// ════════════════════════════════════════
function initFormValidation() {
    const form = document.querySelector('form[data-validate]');
    if (!form) return;

    const inputs = form.querySelectorAll('input[name]');

    // ─── Remove error as user types ───
    inputs.forEach(input => {
        input.addEventListener('input', function () {
            validateField(this);
        });

        // ─── Also validate on blur (when user leaves field) ───
        input.addEventListener('blur', function () {
            validateField(this);
        });
    });

    // ─── Validate all on submit ───
    form.addEventListener('submit', function (e) {
        if (!validateForm(form)) {
            e.preventDefault();  // stop form submission
        }
    });
}