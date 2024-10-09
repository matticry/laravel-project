document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('us_email');
    const emailValidationMessage = document.getElementById('emailValidationMessage');


    emailInput.addEventListener('blur', function() {
        const email = this.value;
        if (email) {
            validateEmail(email);
        }
    });

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
