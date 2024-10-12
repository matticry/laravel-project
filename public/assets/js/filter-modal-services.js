// Función para filtrar servicios en el modal
function filterModalServices() {
    const input = document.getElementById('modal-service-filter');
    const filter = input.value.toUpperCase();
    const table = document.getElementById('modal-service-table');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        const tdName = tr[i].getElementsByTagName('td')[1];
        if (tdName) {
            const txtValue = tdName.textContent || tdName.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }
}

// Función para agregar los servicios seleccionados a la tabla principal
function addSelectedServices() {
    const modalTable = document.getElementById('modal-service-table');
    const mainTable = document.getElementById('service-table');
    const checkboxes = modalTable.querySelectorAll('input[type="checkbox"]:checked');

    checkboxes.forEach(checkbox => {
        const row = checkbox.closest('tr');
        const serviceId = checkbox.value;
        const serviceName = row.cells[1].innerText;
        const serviceTasks = row.cells[2].innerHTML;
        const servicePrice = row.cells[3].innerText;

        // Verificar si el servicio ya existe en la tabla principal
        const existingRow = mainTable.querySelector(`tr[data-service-id="${serviceId}"]`);
        if (existingRow) {
            console.log('El servicio ya existe en la tabla principal');
            return;
        }

        // Crear nueva fila en la tabla principal
        const newRow = mainTable.insertRow(-1);
        newRow.setAttribute('data-service-id', serviceId);
        newRow.classList.add('hover:bg-gray-50');
        newRow.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600" name="selected_products[]" value="${serviceId}" checked>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${serviceName}</div>
            </td>
            <td class="px-6 py-4">
                ${serviceTasks}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="number"
                       name="service_price[${serviceId}]"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                       value="${servicePrice}"
                       min="0"
                       step="0.01"
                >
            </td>
        `;
    });

    // Cerrar el modal después de agregar los servicios
    hideModal('service-selection-modal');
}

// Event listeners
document.getElementById('modal-service-filter').addEventListener('keyup', filterModalServices);
