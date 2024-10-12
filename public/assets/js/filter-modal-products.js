// Función para filtrar productos en el modal
function filterModalProducts() {
    const input = document.getElementById('modal-product-filter');
    const filter = input.value.toUpperCase();
    const table = document.getElementById('modal-product-table');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        const tdCode = tr[i].getElementsByTagName('td')[1];
        if (tdCode) {
            const txtValue = tdCode.textContent || tdCode.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }
}

// Función para agregar los productos seleccionados a la tabla principal
function addSelectedProductsToMainTable() {
    const modalTable = document.getElementById('modal-product-table');
    const mainTable = document.getElementById('product-table');
    const checkboxes = modalTable.querySelectorAll('input[type="checkbox"]:checked');

    checkboxes.forEach(checkbox => {
        const row = checkbox.closest('tr');
        const productId = checkbox.value;
        const productCode = row.cells[1].innerText;
        const productImage = row.cells[2].querySelector('img').src;
        const productName = row.cells[3].innerText;
        const productStock = row.cells[4].innerText;

        // Verificar si el producto ya existe en la tabla principal
        const existingRow = mainTable.querySelector(`tr[data-product-id="${productId}"]`);
        if (existingRow) {
            // Si el producto ya existe, podrías actualizar la cantidad o mostrar un mensaje
            console.log('El producto ya existe en la tabla principal');
            return;
        }

        // Crear nueva fila en la tabla principal
        const newRow = mainTable.insertRow(-1);
        newRow.setAttribute('data-product-id', productId);
        newRow.classList.add('hover:bg-gray-50');
        newRow.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded" name="selected_products[]" value="${productId}" checked>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${productCode}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <img src="${productImage}" alt="${productName}" class="w-16 h-16 object-cover rounded-md">
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${productName}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-500">${productStock}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="number"
                       name="product_quantity[${productId}]"
                       class="mt-1 block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                       value="1"
                       min="1"
                       max="${productStock}"
                >
            </td>
        `;
    });

    // Cerrar el modal después de agregar los productos
    hideModal('product-selection-modal');
}

// Event listeners
document.getElementById('modal-product-filter').addEventListener('keyup', filterModalProducts);
