document.addEventListener('DOMContentLoaded', function() {

    // constantes para los elementos del formulario

    const form = document.getElementById('consult-form');
    const symptomsInput = document.getElementById('symptoms');
    const diagnosisInput = document.getElementById('diagnosis');
    const medicationSelect = document.getElementById('medication');
    const quantityInputs = document.querySelectorAll('input[name="quantity[]"]');
    const frequencyInputs = document.querySelectorAll('input[name="frequency[]"]');
    const daysInputs = document.querySelectorAll('input[name="days[]"]');

    form.addEventListener('submit', function(event) {
        let valid = true; 

        // Limpiar mensajes de error previos
        clearErrors();

        // Validar sintomatología
        if (symptomsInput.value.trim() === '') {
            showError(symptomsInput, 'Por favor, ingrese la sintomatología.');
            valid = false;
        }

        // Validar diagnóstico
        if (diagnosisInput.value.trim() === '') { // Si no se ha ingresado un diagnóstico
            showError(diagnosisInput, 'Por favor, ingrese el diagnóstico.');
            valid = false;
        }

        // Validar selección de medicamento
        if (medicationSelect.value.trim() === '') { // Si no se ha seleccionado un medicamento
            showError(medicationSelect, 'Por favor, seleccione un medicamento.');
            valid = false;
        }

        // Validar cantidad
        quantityInputs.forEach(function(input) {
            if (input.value.trim() === '') { // Si el campo está vacío
                showError(input, 'Por favor, ingrese la cantidad.');
                valid = false;
            } else if (isNaN(input.value) || input.value <= 0) { // Si el valor no es un número o es menor o igual a cero
                showError(input, 'Por favor, ingrese un número válido para la cantidad.');
                valid = false;
            }
        });

        // Validar frecuencia
        frequencyInputs.forEach(function(input) {
            if (input.value.trim() === '') { // Si el campo está vacío
                showError(input, 'Por favor, ingrese la frecuencia.');
                valid = false;
            } else if (isNaN(input.value) || input.value <= 0) { // Si el valor no es un número o es menor o igual a cero
                showError(input, 'Por favor, ingrese un número válido para la frecuencia.');
                valid = false;
            }
        });

        // Validar número de días
        daysInputs.forEach(function(input) {
            if (input.value.trim() === '') { // Si el campo está vacío
                showError(input, 'Por favor, ingrese el número de días.');
                valid = false;
            } else if (isNaN(input.value) || input.value <= 0) { // Si el valor no es un número o es menor o igual a cero
                showError(input, 'Por favor, ingrese un número válido para el número de días.');
                valid = false;
            }
        });

        if (!valid) {
            event.preventDefault();
        }
    });

    symptomsInput.addEventListener('blur', function() {
        clearError(symptomsInput);
        if (symptomsInput.value.trim() === '') { // Si no se ha ingresado la sintomatología
            showError(symptomsInput, 'Por favor, ingrese la sintomatología.');
        }
    });

    diagnosisInput.addEventListener('blur', function() {
        clearError(diagnosisInput);
        if (diagnosisInput.value.trim() === '') { // Si no se ha ingresado un diagnóstico
            showError(diagnosisInput, 'Por favor, ingrese el diagnóstico.');
        }
    });

    medicationSelect.addEventListener('blur', function() {
        clearError(medicationSelect);
        if (medicationSelect.value.trim() === '') {     // Si no se ha seleccionado un medicamento
            showError(medicationSelect, 'Por favor, seleccione un medicamento.');
        }
    });

    quantityInputs.forEach(function(input) {
        input.addEventListener('blur', function() {
            clearError(input);
            if (input.value.trim() === '') { // Si el campo está vacío
                showError(input, 'Por favor, ingrese la cantidad.');
            } else if (isNaN(input.value) || input.value <= 0) { // Si el valor no es un número o es menor o igual a cero
                showError(input, 'Por favor, ingrese un número válido para la cantidad.');
            }
        });
    });

    frequencyInputs.forEach(function(input) {
        input.addEventListener('blur', function() {
            clearError(input);
            if (input.value.trim() === '') {    // Si el campo está vacío
                showError(input, 'Por favor, ingrese la frecuencia.');
            } else if (isNaN(input.value) || input.value <= 0) { // Si el valor no es un número o es menor o igual a cero
                showError(input, 'Por favor, ingrese un número válido para la frecuencia.');
            }
        });
    });

    daysInputs.forEach(function(input) {
        input.addEventListener('blur', function() {
            clearError(input);
            if (input.value.trim() === '') {   // Si el campo está vacío
                showError(input, 'Por favor, ingrese el número de días.');
            } else if (isNaN(input.value) || input.value <= 0) { // Si el valor no es un número o es menor o igual a cero
                showError(input, 'Por favor, ingrese un número válido para el número de días.');
            }
        });
    });

    function showError(input, message) {
        const error = document.createElement('div'); // Crea un nuevo elemento div
        error.className = 'error-message'; // Añade la clase 'error-message'
        error.innerText = message; // Añade el mensaje de error
        input.classList.add('error'); // Añade la clase 'error' al campo
        input.parentNode.insertBefore(error, input.nextSibling); // Inserta el mensaje de error después del campo
    }


    // Función para limpiar mensajes de error
    function clearErrors() { // Limpia todos los mensajes de error
        const errors = document.querySelectorAll('.error-message'); // Busca todos los mensajes de error
        errors.forEach(function(error) { // Por cada mensaje de error
            error.remove(); // Elimina el mensaje de error
        });
        const inputs = document.querySelectorAll('input.error, select.error, textarea.error'); // Busca todos los campos con error
        inputs.forEach(function(input) { // Por cada campo con error
            input.classList.remove('error'); // Elimina la clase de error
        });
    }

    // Función para limpiar un mensaje de error
    function clearError(input) {
        const error = input.parentNode.querySelector('.error-message'); // Busca un mensaje de error
        if (error) { // Si existe un mensaje de error
            error.remove(); // Elimina el mensaje de error
        }
        input.classList.remove('error'); // Elimina la clase de error del campo
    }
});