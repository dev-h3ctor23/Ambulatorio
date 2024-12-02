document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('appointment-form');
    const doctorSelect = document.getElementById('doctor');
    const dateInput = document.getElementById('date');
    const symptomsTextarea = document.getElementById('symptoms');
    const dateMessage = document.getElementById('date-message');

    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevenir el envío del formulario por defecto
        let valid = true;

        // Limpiar mensajes de error previos
        clearErrors();

        // Validar selección de médico
        if (doctorSelect.value.trim() === '') {
            showError(doctorSelect, 'Por favor, seleccione un médico.'); // Mostrar mensaje de error
            valid = false; // Marcar el formulario como inválido
        }

        // Validar fecha
        const selectedDate = new Date(dateInput.value);
        const today = new Date(); 
        const maxDate = new Date();
        maxDate.setDate(today.getDate() + 30); // Fecha máxima 30 días en el futuro

        if (isNaN(selectedDate.getTime())) {
            showError(dateInput, 'Por favor, seleccione una fecha.'); // Mostrar mensaje de error
            valid = false; // Marcar el formulario como inválido
        } else if (selectedDate < today) { // Si la fecha seleccionada es anterior a la fecha actual
            showError(dateInput, 'Fecha no válida.'); // Mostrar mensaje de error
            valid = false; // Marcar el formulario como inválido
        } else if (selectedDate.getDay() === 0 || selectedDate.getDay() === 6) { // Si la fecha seleccionada es un sábado o domingo
            showError(dateInput, 'Por favor, elija un día laborable.'); // Mostrar mensaje de error
            valid = false; // Marcar el formulario como inválido
        } else if (selectedDate > maxDate) { // Si la fecha seleccionada es más de 30 días en el futuro
            showError(dateInput, 'Tan malo no estarás. Pide una fecha como máximo 30 días en el futuro.'); // Mostrar mensaje de error
            valid = false; // Marcar el formulario como inválido
        }

        if (valid) {
            form.submit(); // Enviar el formulario si todas las validaciones son correctas
        }
    });

    dateInput.addEventListener('change', function() {
        validateDate(); // Validar la fecha al cambiar su valor
    });

    dateInput.addEventListener('blur', function() {
        validateDate(); // Validar la fecha al perder el foco
    });

    function validateDate() {
        clearError(dateInput);
        const selectedDate = new Date(dateInput.value);
        const today = new Date();
        const maxDate = new Date();
        maxDate.setDate(today.getDate() + 30);

        if (isNaN(selectedDate.getTime())) { // Si la fecha seleccionada no es válida
            showError(dateInput, 'Por favor, seleccione una fecha.');
        } else if (selectedDate < today) { // Si la fecha seleccionada es anterior a la fecha actual
            showError(dateInput, 'Fecha no válida.'); // Mostrar mensaje de error
        } else if (selectedDate.getDay() === 0 || selectedDate.getDay() === 6) {
            showError(dateInput, 'Por favor, elija un día laborable.'); // Mostrar mensaje de error
        } else if (selectedDate > maxDate) { // Si la fecha seleccionada es más de 30 días en el futuro
            showError(dateInput, 'Tan malo no estarás. Pide una fecha como máximo 30 días en el futuro.'); // Mostrar mensaje de error
        }
    }

    function showError(input, message) { // Muestra un mensaje de error
        const error = document.createElement('div'); // Crear un nuevo elemento div
        error.className = 'error-message'; // Añadir la clase 'error-message'
        error.innerText = message; // Añadir el mensaje de error
        input.classList.add('error'); // Añadir la clase 'error' al campo
        input.parentNode.insertBefore(error, input.nextSibling); // Insertar el mensaje de error después del campo
    }

    function clearErrors() {
        const errors = document.querySelectorAll('.error-message');
        errors.forEach(function(error) {
            error.remove();
        });
        const inputs = document.querySelectorAll('input.error, select.error, textarea.error');
        inputs.forEach(function(input) {
            input.classList.remove('error');
        });
    }

    function clearError(input) {
        const error = input.parentNode.querySelector('.error-message');
        if (error) {
            error.remove();
        }
        input.classList.remove('error');
    }
});