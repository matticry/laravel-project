// JavaScript para manejar los modales
function showOrderDetail(workOrderId, modalId) {
    // Hacer una petición AJAX para obtener los datos de la orden de trabajo
    fetch(`/getWorkOrderById/${workOrderId}`)
        .then(response => response.json())
        .then(data => {
            // Llenar los campos del formulario con los datos recibidos
            document.getElementById('selected-employee-id').value = data.user.us_id;
            document.getElementById('selected-client-id').value = data.client.cli_id;

            // Actualizar los dropdowns (necesitarás implementar estas funciones)
            updateEmployeeDropdown(data.user.us_name);
            updateClientDropdown(data.client.cli_name);

            document.getElementById('start_datetime').value = formatDate(data.wo_start_date);
            document.getElementById('end_datetime').value = formatDate(data.wo_final_date);
            document.getElementById('description').value = data.wo_description;

            // Marcar los productos seleccionados y establecer sus cantidades
            data.products.forEach(product => {
                let checkbox = document.querySelector(`input[name="selected_products[]"][value="${product.pro_id}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                    let quantityInput = document.querySelector(`input[name="product_quantity[${product.pro_id}]"]`);
                    if (quantityInput) {
                        quantityInput.value = product.dwo_amount;
                    }
                }
            });

            // Marcar los servicios seleccionados y establecer sus precios
            data.services.forEach(service => {
                let checkbox = document.querySelector(`input[name="selected_services[]"][value="${service.service_id}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                    let priceInput = document.querySelector(`input[name="service_price[${service.service_id}]"]`);
                    if (priceInput) {
                        priceInput.value = service.price_service;
                    }
                }
            });

            // Abrir el modal
            showModal(modalId);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function formatDate(dateString) {
    let date = new Date(dateString);
    return date.toLocaleString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }).replace(',', '');
}

function updateEmployeeDropdown(employeeName) {
    // Implementa la lógica para actualizar el dropdown de empleados
    // Por ejemplo, podrías tener un span dentro del botón que muestre el nombre seleccionado
    document.querySelector('#dropdown-button-1 span').textContent = employeeName;
}

function updateClientDropdown(clientName) {
    // Implementa la lógica para actualizar el dropdown de clientes
    document.querySelector('#dropdown-button-2 span').textContent = clientName;
}


// JavaScript para manejar los dropdowns
function setupDropdown(buttonId, menuId, searchInputId, hiddenInputId) {
    const dropdownButton = document.getElementById(buttonId);
    const dropdownMenu = document.getElementById(menuId);
    const searchInput = document.getElementById(searchInputId);
    const hiddenInput = document.getElementById(hiddenInputId);
    let isOpen = false;

    function toggleDropdown() {
        isOpen = !isOpen;
        dropdownMenu.classList.toggle('hidden', !isOpen);
    }

    dropdownButton.addEventListener('click', toggleDropdown);

    searchInput.addEventListener('input', () => {
        const searchTerm = searchInput.value.toLowerCase();
        const items = dropdownMenu.querySelectorAll('option');

        items.forEach((item) => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(searchTerm) ? 'block' : 'none';
        });
    });

    // Manejar la selección de elementos
    dropdownMenu.querySelectorAll('option').forEach((item) => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            dropdownButton.querySelector('span').textContent = item.textContent;
            hiddenInput.value = item.value;
            toggleDropdown();
        });
    });
}

// Configurar ambos dropdowns
setupDropdown('dropdown-button-1', 'dropdown-menu-1', 'search-input-1', 'selected-employee-id');
setupDropdown('dropdown-button-2', 'dropdown-menu-2', 'search-input-2', 'selected-client-id');

document.addEventListener('click', function(event) {
    const dropdowns = document.querySelectorAll('.relative.group');
    dropdowns.forEach(dropdown => {
        if (!dropdown.contains(event.target)) {
            const dropdownMenu = dropdown.querySelector('[id^="dropdown-menu-"]');
            if (dropdownMenu) {
                dropdownMenu.classList.add('hidden');
            }
        }
    });
});
