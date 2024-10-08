const passwordInput = document.getElementById('us_password');
const confirmPasswordInput = document.getElementById('us_password_confirmation');
const strengthIndicator = document.getElementById('password-strength');
const lengthCheck = document.getElementById('length');
const lowercaseCheck = document.getElementById('lowercase');
const uppercaseCheck = document.getElementById('uppercase');
const numberCheck = document.getElementById('number');
const symbolCheck = document.getElementById('symbol');
const matchCheck = document.getElementById('match');

function checkPasswordStrength() {
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;
    let strength = 0;

    strengthIndicator.style.maxHeight = (password.length > 0 || confirmPassword.length > 0) ? '1000px' : '0';

    // Verificaciones existentes
    if (password.length >= 8) {
        lengthCheck.classList.replace('text-red-500', 'text-green-500');
        lengthCheck.querySelector('.icon').textContent = '✓';
        strength++;
    } else {
        lengthCheck.classList.replace('text-green-500', 'text-red-500');
        lengthCheck.querySelector('.icon').textContent = '✕';
    }

    if (/[a-z]/.test(password)) {
        lowercaseCheck.classList.replace('text-red-500', 'text-green-500');
        lowercaseCheck.querySelector('.icon').textContent = '✓';
        strength++;
    } else {
        lowercaseCheck.classList.replace('text-green-500', 'text-red-500');
        lowercaseCheck.querySelector('.icon').textContent = '✕';
    }

    if (/[A-Z]/.test(password)) {
        uppercaseCheck.classList.replace('text-red-500', 'text-green-500');
        uppercaseCheck.querySelector('.icon').textContent = '✓';
        strength++;
    } else {
        uppercaseCheck.classList.replace('text-green-500', 'text-red-500');
        uppercaseCheck.querySelector('.icon').textContent = '✕';
    }

    if (/\d/.test(password)) {
        numberCheck.classList.replace('text-red-500', 'text-green-500');
        numberCheck.querySelector('.icon').textContent = '✓';
        strength++;
    } else {
        numberCheck.classList.replace('text-green-500', 'text-red-500');
        numberCheck.querySelector('.icon').textContent = '✕';
    }

    if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
        symbolCheck.classList.replace('text-red-500', 'text-green-500');
        symbolCheck.querySelector('.icon').textContent = '✓';
        strength++;
    } else {
        symbolCheck.classList.replace('text-green-500', 'text-red-500');
        symbolCheck.querySelector('.icon').textContent = '✕';
    }

    // Verificación de coincidencia de contraseñas
    if (password === confirmPassword && password.length > 0) {
        matchCheck.classList.replace('text-red-500', 'text-green-500');
        matchCheck.querySelector('.icon').textContent = '✓';
        strength++;
    } else {
        matchCheck.classList.replace('text-green-500', 'text-red-500');
        matchCheck.querySelector('.icon').textContent = '✕';
    }
}

passwordInput.addEventListener('input', checkPasswordStrength);
confirmPasswordInput.addEventListener('input', checkPasswordStrength);
