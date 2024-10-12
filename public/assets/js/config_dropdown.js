// JavaScript para manejar los modales
function showOrderDetail() {
    showModal('order-detail-modal');
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
