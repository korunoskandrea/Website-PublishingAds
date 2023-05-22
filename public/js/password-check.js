function displayValidationError(message, displayTime = 5000) {
    const validationError = document.getElementById('validation-error');
    validationError.textContent = message;
    validationError.style.display = 'block';
    setTimeout(() => {
        validationError.style.display = 'none';
    }, 5000);
}

document.getElementById('reg-submit').addEventListener('click', target => {
    const password = document.getElementById("password");
    const rePassword = document.getElementById("rePassword");
    if (password.value !== rePassword.value) {
        displayValidationError('Passwords do not match');
        target.preventDefault();
    }
    if (password.value.length < 8) {
        displayValidationError('Entered password is too short. Its length must be minimum 8 characters');
        target.preventDefault();
    }
    if(!/[0-9]+/.test(password.value)) {
        displayValidationError('Your password does not contain any digits');
        target.preventDefault();
    }
});