
// ? validarDNI: Función para validar el campo DNI del formulario de login.html

// * dni: Valor del campo DNI
// * dniError: Elemento donde se mostrará el mensaje de error
// * trim: Método para eliminar los espacios en blanco al principio y al final de una cadena
// * textContent: Propiedad para establecer o devolver el contenido de texto de un nodo y sus descendientes

function validarDNI() {
    var dni = document.getElementById('dni').value;
    var dniError = document.getElementById('dniError');
    if (dni.trim() === '') {
        dniError.textContent = 'El campo DNI es obligatorio.'; // * Mensajes de error en el campo DNI
        return false; // * La validacion ha fallado
    } else if (dni.length !== 8) { // ! En caso de que el DNI no tenga 8 caracteres
        dniError.textContent = 'El DNI debe tener 8 caracteres.'; // * Mensajes insuficientes caracteres en el campo DNI
        return false;
    } else {
        dniError.textContent = '';
        return true; // * La validacion ha sido exitosa
    }
}

// ? validarPassword: Función para validar el campo Contraseña del formulario de login.html

// * password: Valor del campo Contraseña
// * passwordError: Elemento donde se mostrará el mensaje de error

function validarPassword() {
    var password = document.getElementById('password').value;
    var passwordError = document.getElementById('passwordError');
    if (password.trim() === '') {
        passwordError.textContent = 'El campo Contraseña es obligatorio.';
        return false; // * La validacion ha fallado
    } else if (password.length < 6) {
        passwordError.textContent = 'La Contraseña debe tener al menos 6 caracteres.'; // ! En caso de que la contraseña tenga menos de 6 caracteres
        return false;
    } else {
        passwordError.textContent = '';
        return true; // * La validacion ha sido exitosa
    }
}

// ? Listener en caso de que se pierda el foco en el campo DNI se ejecutará la función validarDNI
document.getElementById('dni').addEventListener('blur', validarDNI);

// ? Listener en caso de que se pierda el foco en el campo Contraseña se ejecutará la función validarPassword
document.getElementById('password').addEventListener('blur', validarPassword);

// ? Listener que accede al id loginForm y en caso de que se envíe el formulario se ejecutará la función validarDNI y validarPassword
// ! En caso de que alguna de las dos funciones retorne false se detendrá el envío del formulario

// * preventDefault: Método que cancela el evento si este es cancelable, sin detener el resto del funcionamiento del evento, es decir, puede ser llamado nuevamente.
// * encodeURIComponent: Método que codifica un componente URI. Devuelve una cadena codificada que representa un componente URI válido.
// * window.location.href: Propiedad que devuelve la URL de la página actual.

document.getElementById('loginForm').addEventListener('submit', function (event) {
    if (!validarDNI() || !validarPassword()) { // * Si la validación del DNI o la contraseña falla
        event.preventDefault(); // * Detener el envío del formulario
    } else {
        var dni = document.getElementById('dni').value; 
        var password = document.getElementById('password').value;
        // * Redirigir a la página de conexión con los parámetros DNI y Contraseña
        window.location.href = 'conection.php?dni=' + encodeURIComponent(dni) + '&password=' + encodeURIComponent(password);
    }
});