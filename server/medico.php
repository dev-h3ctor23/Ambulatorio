<?php
// Mostrar errores de PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir el archivo de conexión
include('../server/conexion.php');

// Verificar la conexión
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Obtener el ID del usuario de la URL
if (!isset($_GET['id_usuario'])) {
    die("No se encontró el ID de usuario en la URL.");
}

$id_usuario = $_GET['id_usuario'];

// Buscar el ID del médico basado en el ID del usuario
$query_medico_id = "SELECT id_medico FROM medico WHERE id_usuario = $id_usuario";
$resultado_medico_id = mysqli_query($conexion, $query_medico_id);

if (!$resultado_medico_id) {
    die("Error en la consulta del ID del médico: " . mysqli_error($conexion));
}

$medico_id_data = mysqli_fetch_assoc($resultado_medico_id);
$id_medico = $medico_id_data['id_medico'];

if (!$id_medico) {
    die("No se encontraron datos para el médico con ID de usuario: $id_usuario");
}

// Buscar información del médico
$query_medico = "SELECT * FROM medico WHERE id_medico = $id_medico";
$resultado_medico = mysqli_query($conexion, $query_medico);

if (!$resultado_medico) {
    die("Error en la consulta de médico: " . mysqli_error($conexion));
}

$medico = mysqli_fetch_assoc($resultado_medico);

if (!$medico) {
    die("No se encontraron datos para el médico con ID: $id_medico");
}

// Buscar el número de consultas semanales del médico
$query_week_appointments = "SELECT COUNT(*) AS week_count FROM consulta WHERE id_medico = $id_medico AND fecha >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
$resultado_week_appointments = mysqli_query($conexion, $query_week_appointments);

if (!$resultado_week_appointments) {
    die("Error en la consulta de citas semanales: " . mysqli_error($conexion));
}

$week_appointments = mysqli_fetch_assoc($resultado_week_appointments);

// Buscar consultas de hoy del médico
$query_today_appointments = "SELECT * FROM consulta WHERE id_medico = $id_medico AND fecha = CURDATE()";
$resultado_today_appointments = mysqli_query($conexion, $query_today_appointments);

if (!$resultado_today_appointments) {
    die("Error en la consulta de citas de hoy: " . mysqli_error($conexion));
}

$today_appointments = [];
while ($appointment = mysqli_fetch_assoc($resultado_today_appointments)) {
    $today_appointments[] = $appointment;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Médico</title>
    <link rel="stylesheet" href="../assets/medico.css">
</head>
<body>
    <div class="container">
        <h1>Portal Médico</h1>
        <section id="doctor-info">
            <h2 class="toggle-header">Información del Médico</h2>
            <div class="toggle-content">
                <p>Nombre: <span id="doctor-name"><?php echo htmlspecialchars($medico['nombre']); ?></span></p>
                <p>Especialidad: <span id="doctor-specialty"><?php echo htmlspecialchars($medico['especialidad']); ?></span></p>
            </div>
        </section>
        <section id="week-appointments">
            <h2 class="toggle-header">Consultas Semanales</h2>
            <div class="toggle-content">
                <p>Número de consultas: <span id="week-count"><?php echo htmlspecialchars($week_appointments['week_count']); ?></span></p>
            </div>
        </section>
        <section id="today-appointments">
            <h2 class="toggle-header">Consultas de Hoy</h2>
            <div class="toggle-content">
                <ul id="today-list">
                    <?php foreach ($today_appointments as $appointment): ?>
                        <li>ID: <?php echo htmlspecialchars($appointment['id_consulta']); ?>, Paciente: <?php echo htmlspecialchars($appointment['id_paciente']); ?>, Síntomas: <?php echo htmlspecialchars(substr($appointment['sintomas'], 0, 100)); ?>
                        <button onclick="passConsultation(<?php echo htmlspecialchars($appointment['id_consulta']); ?>)">Pasar Consulta</button></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
    </div>
    <script>
        const todayAppointments = <?php echo json_encode($today_appointments); ?>;

        function passConsultation(appointmentId) {
            // Abrimos una nueva pestaña para realizar la consulta
            window.open(`consulta.php?appointmentId=${appointmentId}`, '_blank');
        }
    </script>
    <script src="client/medico.js"></script>
</body>
</html>
