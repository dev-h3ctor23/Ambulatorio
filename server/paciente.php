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

// Buscar el ID del paciente basado en el ID del usuario
$query_paciente_id = "SELECT id_paciente FROM paciente WHERE id_usuario = $id_usuario";
$resultado_paciente_id = mysqli_query($conexion, $query_paciente_id);

if (!$resultado_paciente_id) {
    die("Error en la consulta del ID del paciente: " . mysqli_error($conexion));
}

$paciente_id_data = mysqli_fetch_assoc($resultado_paciente_id);
$id_paciente = $paciente_id_data['id_paciente'];

if (!$id_paciente) {
    die("No se encontraron datos para el paciente con ID de usuario: $id_usuario");
}

// Buscar información del paciente
$query_paciente = "SELECT * FROM paciente WHERE id_paciente = $id_paciente";
$resultado_paciente = mysqli_query($conexion, $query_paciente);

if (!$resultado_paciente) {
    die("Error en la consulta del paciente: " . mysqli_error($conexion));
}

$paciente = mysqli_fetch_assoc($resultado_paciente);

if (!$paciente) {
    die("No se encontraron datos para el paciente con ID: $id_paciente");
}

// Buscar próximas citas del paciente
$query_citas = "SELECT * FROM consulta WHERE id_paciente = $id_paciente";
$resultado_citas = mysqli_query($conexion, $query_citas);

if (!$resultado_citas) {
    die("Error en la consulta de citas: " . mysqli_error($conexion));
}

// Buscar medicación actual del paciente
$query_medicacion = "SELECT cm.cantidad, cm.frecuencia, cm.duracion, m.nombre 
AS medicamento_nombre FROM consulta_medicamento cm 
JOIN medicamentos m ON cm.id_medicamento = m.id_medicamento 
WHERE cm.id_consulta IN 
(SELECT id_consulta FROM consulta WHERE id_paciente = $id_paciente)";
$resultado_medicacion = mysqli_query($conexion, $query_medicacion);

if (!$resultado_medicacion) {
    die("Error en la consulta de medicación: " . mysqli_error($conexion));
}

// Buscar consultas pasadas del paciente
$query_consultas = "SELECT * FROM consulta WHERE id_paciente = $id_paciente";
$resultado_consultas = mysqli_query($conexion, $query_consultas);

if (!$resultado_consultas) {
    die("Error en la consulta de consultas pasadas: " . mysqli_error($conexion));
}

// Obtener la lista de médicos
$query_medicos = "SELECT id_medico, nombre, especialidad FROM medico";
$resultado_medicos = mysqli_query($conexion, $query_medicos);

if (!$resultado_medicos) {
    die("Error en la consulta de médicos: " . mysqli_error($conexion));
}

$medicos = [];
while ($medico = mysqli_fetch_assoc($resultado_medicos)) {
    $medicos[] = $medico;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paciente</title>
    <link rel="stylesheet" href="../assets/paciente.css">
</head>
<body>
    <div class="container">
        <!-- Información del Paciente -->
        <section class="paciente-info">
            <h1 class="toggle-header">Información del Paciente</h1>
            <div class="toggle-content">
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($paciente['nombre']); ?></p>
            </div>
        </section>

        <!-- Próximas Citas -->
        <section class="appointments">
            <h2 class="toggle-header">Próximas Citas</h2>
            <div class="toggle-content">
                <ul id="appointments-list">
                    <?php while ($cita = mysqli_fetch_assoc($resultado_citas)) { 
                        $id_medico = $cita['id_medico'];
                        $query_medico_info = "SELECT nombre, especialidad FROM medico WHERE id_medico = $id_medico";
                        $resultado_medico_info = mysqli_query($conexion, $query_medico_info);
                        $medico_info = mysqli_fetch_assoc($resultado_medico_info);
                        ?>
                        <li><?php echo htmlspecialchars($cita['fecha']) . " con " . htmlspecialchars($medico_info['nombre']) . " (" . htmlspecialchars($medico_info['especialidad']) . ")"; ?></li>
                    <?php } ?>
                </ul>
            </div>
        </section>

        <!-- Medicación Actual -->
        <section class="medication">
            <h2 class="toggle-header">Medicación Actual</h2>
            <div class="toggle-content">
                <ul>
                    <?php while ($medicacion = mysqli_fetch_assoc($resultado_medicacion)) { ?>
                        <li><?php echo htmlspecialchars($medicacion['medicamento_nombre']) . ": " . htmlspecialchars($medicacion['cantidad']) . " - " . htmlspecialchars($medicacion['frecuencia']); ?></li>
                    <?php } ?>
                </ul>
            </div>
        </section>

        <!-- Consultas Pasadas -->
        <section class="past-consultations">
            <h2 class="toggle-header">Consultas Pasadas</h2>
            <div class="toggle-content">
                <ul>
                    <?php while ($consulta = mysqli_fetch_assoc($resultado_consultas)) { ?>
                        <li><button onclick="showDetails('<?php echo htmlspecialchars($consulta['id_consulta']); ?>')">ID: <?php echo htmlspecialchars($consulta['id_consulta']); ?> - Fecha: <?php echo htmlspecialchars($consulta['fecha']); ?></button></li>
                    <?php } ?>
                </ul>
            </div>
            <div id="consultation-details">
                <!-- Aquí se cargará la información adicional de la consulta -->
            </div>
        </section>

        <!-- Pedir Cita -->
        <section class="request-appointment">
            <h2>Pedir Cita</h2>
            <form id="appointment-form" action="pedir-cita.php" method="POST">
                <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($id_usuario); ?>">
                
                <label for="doctor">Selecciona un Médico:</label>
                <select id="doctor" name="doctor">
                    <?php foreach ($medicos as $medico) { ?>
                        <option value="<?php echo htmlspecialchars($medico['id_medico']); ?>"><?php echo htmlspecialchars($medico['nombre']) . " (" . htmlspecialchars($medico['especialidad']) . ")"; ?></option>
                    <?php } ?>
                </select>

                <label for="date">Selecciona Fecha:</label>
                <input type="date" id="date" name="date">

                <p id="date-message" class="error-message"></p>

                <label for="symptoms">Sintomatología:</label>
                <textarea id="symptoms" name="symptoms" placeholder="Describe tus síntomas aquí (opcional)"></textarea>

                <button type="submit">Pedir Cita</button>
            </form>
        </section>
    </div>
    <script src="../client/validaciones-pedir-cita.js"></script>
</body>
</html>
