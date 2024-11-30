document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const nombre = params.get('nombre');
    const apellido = params.get('apellido');
    const fecha_de_nacimiento = params.get('fecha_de_nacimiento');
    const especialidad = params.get('especialidad'); // Obtener la especialidad

    const userInfoDiv = document.getElementById('userInfo');
    if (userInfoDiv) {
        let userInfoHtml = `<p>Nombre: ${nombre}</p><p>Apellido: ${apellido}</p><p>Fecha de Nacimiento: ${fecha_de_nacimiento}</p>`;
        if (especialidad) {
            userInfoHtml += `<p>Especialidad: ${especialidad}</p>`; // Mostrar la especialidad si existe
        }
        userInfoDiv.innerHTML = userInfoHtml;
    }
});