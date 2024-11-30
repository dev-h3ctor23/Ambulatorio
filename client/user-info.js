document.addEventListener('DOMContentLoaded', function() {

    // ? Variables para obtener los parámetros de la URL

    const params = new URLSearchParams(window.location.search); // * Obtener los parámetros de la URL
    const nombre = params.get('nombre');
    const apellido = params.get('apellido');
    const fecha_de_nacimiento = params.get('fecha_de_nacimiento');
    const especialidad = params.get('especialidad'); // * Obtener la especialidad si existe

    const userInfoDiv = document.getElementById('userInfo'); // * Obtener el div donde se mostrará la información del usuario

    if (userInfoDiv) { // * Si el div existe, mostrar la información del usuario
        let userInfoHtml = `<p>Nombre: ${nombre}</p><p>Apellido: ${apellido}</p><p>Fecha de Nacimiento: ${fecha_de_nacimiento}</p>`;
        if (especialidad) {
            userInfoHtml += `<p>Especialidad: ${especialidad}</p>`; // * Si la especialidad existe, mostrarla
        }
        userInfoDiv.innerHTML = userInfoHtml; // * Mostrar la información del usuario
    }
});