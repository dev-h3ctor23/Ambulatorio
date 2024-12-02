// Espera a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', () => {
    const todayList = document.getElementById('today-list');

    // Función para cargar las citas de hoy
    function loadTodayAppointments() {
        todayAppointments.forEach((appointment) => {
            // Crea un nuevo elemento de lista para cada cita
            const listItem = document.createElement('li');
            listItem.textContent = `ID: ${appointment.id_consulta}, Paciente: ${appointment.id_paciente}, Síntomas: ${appointment.sintomas.slice(0, 100)}`;
            
            // Crea un botón para pasar consulta
            const passButton = document.createElement('button');
            passButton.textContent = "Pasar Consulta";
            passButton.addEventListener('click', () => passConsultation(appointment.id_consulta));
            
            // Añade el botón al elemento de lista
            listItem.appendChild(passButton);
            // Añade el elemento de lista a la lista de hoy
            todayList.appendChild(listItem);
        });
    }

    // Función para pasar consulta
    function passConsultation(appointmentId) {
        // Redirige a la página de consulta con el ID de la cita
        window.location.href = `consulta.php?appointmentId=${appointmentId}`;
    }

    // Carga las citas de hoy al cargar la página
    loadTodayAppointments();
});

// Añade eventos a todos los encabezados con la clase "toggle-header"
document.querySelectorAll('.toggle-header').forEach(header => {
    header.addEventListener('click', () => {
        const content = header.nextElementSibling;
        // Alterna la clase "active" para mostrar u ocultar el contenido
        if (content.classList.contains('active')) {
            content.classList.remove('active');
        } else {
            content.classList.add('active');
        }
    });
});
