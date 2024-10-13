document.addEventListener('DOMContentLoaded', function() {
    const serviceTable = document.getElementById('service-table');
    const checkboxes = serviceTable.querySelectorAll('input[type="checkbox"][name="selected_services[]"]');
    const priceInputs = serviceTable.querySelectorAll('input[name^="service_price"]');
    const totalDisplay = document.getElementById('total-display');
    const woTotalInput = document.getElementById('wo_total');

    function updateTotal() {
        let total = 0;
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const row = checkbox.closest('tr');
                const priceInput = row.querySelector('input[name^="service_price"]');
                const price = parseFloat(priceInput.value) || 0;
                total += price;
            }
        });
        totalDisplay.textContent = `Total: $ ${total.toFixed(2)}`;
        woTotalInput.value = total.toFixed(2);
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateTotal);
    });

    priceInputs.forEach(input => {
        input.addEventListener('input', updateTotal);
    });
});
