// Función para filtrar la tabla de productos
function filterProducts() {
    const input = document.getElementById('product-filter');
    const filter = input.value.toUpperCase();
    const table = document.getElementById('product-table');
    const tr = table.getElementsByTagName('tr');

    for (let i = 0; i < tr.length; i++) {
        const td = tr[i].getElementsByTagName('td')[1]; // Columna del código del producto
        if (td) {
            const txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }
}

// Función para filtrar la tabla de servicios
function filterServices() {
    const input = document.getElementById('service-filter');
    const filter = input.value.toUpperCase();
    const table = document.getElementById('service-table');
    const tr = table.getElementsByTagName('tr');

    for (let i = 0; i < tr.length; i++) {
        const td = tr[i].getElementsByTagName('td')[1]; // Columna del nombre del servicio
        if (td) {
            const txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }
}

// Event listeners para los filtros
document.getElementById('product-filter').addEventListener('keyup', filterProducts);
document.getElementById('service-filter').addEventListener('keyup', filterServices);

// Funciones para abrir y cerrar modales
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}
