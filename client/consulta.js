document.addEventListener('DOMContentLoaded', () => {
    const addMedicationButton = document.getElementById('add-medication');
    const medicationList = document.getElementById('medication-list');
    const medicationSelect = document.getElementById('medication');
    const quantityInput = document.getElementById('quantity');
    const frequencyInput = document.getElementById('frequency');
    const daysInput = document.getElementById('days');
    const chronicCheckbox = document.getElementById('chronic');

    // Añade un evento al botón de agregar medicación
    addMedicationButton.addEventListener('click', () => {
        // Obtiene los valores seleccionados y de entrada
        const medication = medicationSelect.options[medicationSelect.selectedIndex].text;
        const medicationValue = medicationSelect.value;
        const quantity = quantityInput.value.trim();
        const frequency = frequencyInput.value.trim();
        const days = daysInput.value.trim();
        const isChronic = chronicCheckbox.checked;
        const chronicValue = isChronic ? 1 : 0;

        // Verifica que todos los campos estén correctamente llenados
        if (medication && quantity.length <= 100 && frequency.length <= 100 && (!isChronic && days.length <= 100 || isChronic)) {
            // Crea un nuevo elemento de lista para la medicación
            const medicationItem = document.createElement('li');
            medicationItem.innerHTML = `${medication}: ${quantity}, ${frequency}` + (isChronic ? ", crónica" : `, ${days} días`);
            // Añade campos ocultos con los valores de la medicación
            medicationItem.innerHTML += `<input type="hidden" name="medication[]" value="${medicationValue}">`;
            medicationItem.innerHTML += `<input type="hidden" name="quantity[]" value="${quantity}">`;
            medicationItem.innerHTML += `<input type="hidden" name="frequency[]" value="${frequency}">`;
            medicationItem.innerHTML += `<input type="hidden" name="days[]" value="${days}">`;
            medicationItem.innerHTML += `<input type="hidden" name="chronic[]" value="${chronicValue}">`;

            // Añade el nuevo elemento de medicación a la lista
            medicationList.appendChild(medicationItem);
            // Limpia los campos de entrada
            quantityInput.value = '';
            frequencyInput.value = '';
            daysInput.value = '';
            chronicCheckbox.checked = false;
        } else {
            // Muestra una alerta si los campos no están correctamente llenados
            alert('Por favor, complete todos los campos de medicación correctamente.');
        }
    });

    // Añade eventos a todos los encabezados con la clase "toggle-header"
    document.querySelectorAll('.toggle-header').forEach(header => {
        header.addEventListener('click', () => {
            // Obtiene el contenido inmediatamente siguiente al encabezado
            const content = header.nextElementSibling;
            // Alterna la clase "active" para mostrar u ocultar el contenido
            if (content.classList.contains('active')) {
                content.classList.remove('active');
            } else {
                content.classList.add('active');
            }
        });
    });
});
