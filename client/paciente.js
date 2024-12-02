function submitAppointment() {
    const form = document.getElementById('appointment-form');
    const formData = new FormData(form);

    // Enviar los datos del formulario al servidor
    fetch('pedir-cita.php', {
        method: 'POST',
        body: formData
    })

    // Procesar la respuesta del servidor
    .then(response => response.json())
    .then(data => { // data es el objeto JSON devuelto por el servidor
        if (data.success) {
            alert('Cita pedida correctamente.');
            // Actualizar la lista de próximas citas
            const appointmentsList = document.getElementById('appointments-list');
            const newAppointment = document.createElement('li');
            newAppointment.textContent = `${data.cita.fecha} con el Dr. ${data.cita.id_medico}`;
            appointmentsList.appendChild(newAppointment);
        } else {
            alert('Error al pedir la cita: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error); // Imprimir el error en la consola
        alert('Error al pedir la cita.');
    });
}

// 
function validateDate() {

    // constantes para los elementos del formulario

    const dateInput = document.getElementById('date');
    const dateMessage = document.getElementById('date-message');
    const selectedDate = new Date(dateInput.value);
    const today = new Date();
    const oneMonthLater = new Date(today);
    oneMonthLater.setDate(today.getDate() + 30);

    if (selectedDate < today) { // Si la fecha seleccionada es anterior a la fecha actual
        dateMessage.textContent = 'Fecha no válida.';
    } else if (selectedDate.getDay() === 0 || selectedDate.getDay() === 6) { // Si la fecha seleccionada es un sábado o domingo
        dateMessage.textContent = 'Por favor, elija un día laborable.';
    } else if (selectedDate > oneMonthLater) { // Si la fecha seleccionada es más de un mes en el futuro
        dateMessage.textContent = 'Tan malo no estarás. Pide una fecha como máximo 30 días en el futuro.';
    } else {
        dateMessage.textContent = ''; // Si la fecha es válida, borrar el mensaje de error
    }
}

function showDetails(consultationId) { // Muestra los detalles de una consulta
    const details = document.getElementById('consultation-details');
    details.innerHTML = `<p>Detalles de la consulta ${consultationId}:</p><p>Información del tratamiento</p>`; // Mostrar los detalles de la consulta
}

// Selecciona todos los encabezados con la clase "toggle-header"
document.querySelectorAll('.toggle-header').forEach(header => { // Añade un evento a cada encabezado
    header.addEventListener('click', () => {
        const content = header.nextElementSibling; // Contenido inmediatamente siguiente
        if (content.classList.contains('active')) { // Si el contenido está activo
            content.classList.remove('active'); // Ocultar el contenido
        } else {
            content.classList.add('active'); // Mostrar el contenido
        }
    });
});
