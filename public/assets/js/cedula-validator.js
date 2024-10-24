document.addEventListener('DOMContentLoaded', function() {
    const dniInput = document.getElementById('us_dni');
    const nameInput = document.getElementById('us_name');
    const lastNameInput = document.getElementById('us_lastName');
    const validationMessage = document.getElementById('dniValidationMessage');

    function validateDNI(dni) {
        // Implementa aquí la lógica de validación de cédula ecuatoriana
        // Esta es una validación básica, deberías implementar la lógica completa
        return dni.length === 10 && /^\d+$/.test(dni);
    }

    dniInput.addEventListener('input', function() {
        const cedula = this.value;
        if (validateDNI(cedula)) {
            validationMessage.textContent = 'Cédula válida';
            validationMessage.className = 'text-sm mt-1 text-green-600 font-bold';
        } else {
            validationMessage.textContent = 'Cédula inválida';
            validationMessage.className = 'text-sm mt-1 text-red-600 font-bold';
        }
    });

    dniInput.addEventListener('blur', function() {
        const cedula = this.value;
        if (validateDNI(cedula)) {
            fetch(`/cedula/${cedula}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.nombres && data.apellidos) {
                        nameInput.value = data.nombres;
                        lastNameInput.value = data.apellidos;
                        validationMessage.textContent = 'Datos obtenidos correctamente';
                        validationMessage.className = 'text-sm mt-1 text-green-600 font-bold';
                    } else {
                        validationMessage.textContent = 'No se encontraron datos para esta cédula';
                        validationMessage.className = 'text-sm mt-1 text-red-600 font-bold';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    validationMessage.textContent = 'Error al obtener los datos';
                    validationMessage.className = 'text-sm mt-1 text-red-600 font-bold';
                });
        }
    });
});
