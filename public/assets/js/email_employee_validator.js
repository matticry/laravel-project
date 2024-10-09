document.addEventListener('DOMContentLoaded', function() {
    const emailEmployeeInput = document.getElementById('email_emplo');
    const emailValidationMessage = document.getElementById('emailValidationMessage');


    emailEmployeeInput.addEventListener('blur', function() {
        const email = this.value;
        if (email) {
            validateEmail(email);
        }
    });

    /**
     * Valida un correo electrónico utilizando la API de Hunter.
     *
     * @param {string} email - El correo electrónico a validar.
     */
    function validateEmail(email) {
        const apiKey = '4a6256f760017b78ef5b7fe2056844ae29e85b1d';
        const url = `https://api.hunter.io/v2/email-verifier?email=${email}&api_key=${apiKey}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.data.status === 'valid') {
                    emailValidationMessage.textContent = `El correo electrónico ${email} es válido`;
                    emailValidationMessage.className = 'text-sm mt-1 text-green-600';
                } else {
                    emailValidationMessage.textContent = `El correo electrónico ${email} no es válido`;
                    emailValidationMessage.className = 'text-sm mt-1 text-red-600';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                emailValidationMessage.textContent = 'Error al verificar el correo electrónico';
                emailValidationMessage.className = 'text-sm mt-1 text-red-600';
            });
    }
});
