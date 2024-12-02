document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const usuarioInput = document.getElementById('usuario');
    const contraseñaInput = document.getElementById('contraseña');

    form.addEventListener('submit', function(event) {
        let valid = true;

        // Limpiar mensajes de error previos
        clearErrors();

        if (usuarioInput.value.trim() === '') { // Si no se ha ingresado un usuario
            showError(usuarioInput, 'Por favor, ingrese su usuario.');
            valid = false;
        }

        if (contraseñaInput.value.trim() === '') { // Si no se ha ingresado una contraseña
            showError(contraseñaInput, 'Por favor, ingrese su contraseña.');
            valid = false;
        }

        if (!valid) { // Si hay errores en el formulario
            event.preventDefault(); // Prevenir el envío del formulario
        }
    });

    usuarioInput.addEventListener('blur', function() { // Añadir evento al perder el foco
        clearError(usuarioInput); // Limpiar mensajes de error previos
        if (usuarioInput.value.trim() === '') { // Si no se ha ingresado un usuario
            showError(usuarioInput, 'Por favor, ingrese su usuario.');
        }
    });

    contraseñaInput.addEventListener('blur', function() { // Añadir evento al perder el foco
        clearError(contraseñaInput); // Limpiar mensajes de error previos
        if (contraseñaInput.value.trim() === '') { // Si no se ha ingresado una contraseña
            showError(contraseñaInput, 'Por favor, ingrese su contraseña.');
        }
    });

    function showError(input, message) { // Muestra un mensaje de error
        const error = document.createElement('div'); // Crear un nuevo elemento div
        error.className = 'error-message'; // Añadir la clase 'error-message'
        error.innerText = message; // Añadir el mensaje de error
        input.classList.add('error');
        input.parentNode.insertBefore(error, input.nextSibling);
    }

    function clearErrors() { // Limpia los mensajes de error
        const errors = document.querySelectorAll('.error-message'); // Buscar todos los mensajes de error
        errors.forEach(function(error) { // Recorrer todos los mensajes de error
            error.remove();
        });
        const inputs = document.querySelectorAll('input.error'); // Buscar todos los campos con error
        inputs.forEach(function(input) {    // Recorrer todos los campos con error
            input.classList.remove('error');    // Eliminar la clase de error
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