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

// Obtener el ID de la consulta de la URL
if (!isset($_GET['appointmentId'])) {
    die("No se encontró el ID de la consulta en la URL.");
}

$appointmentId = $_GET['appointmentId'];

// Buscar información de la consulta
$query_consulta = "SELECT c.fecha, p.nombre AS paciente_nombre, m.nombre AS medico_nombre, c.sintomas, c.diagnostico
    FROM consulta c
        JOIN paciente p ON c.id_paciente = p.id_paciente
            JOIN medico m ON c.id_medico = m.id_medico
    WHERE c.id_consulta = $appointmentId";
$resultado_consulta = mysqli_query($conexion, $query_consulta);

if (!$resultado_consulta) {
    die("Error en la consulta de la información de la consulta: " . mysqli_error($conexion));
}

$consulta = mysqli_fetch_assoc($resultado_consulta);

if (!$consulta) {
    die("No se encontraron datos para la consulta con ID: $appointmentId");
}

// Buscar lista de medicamentos
$query_medicamentos = "SELECT * FROM medicamentos";
$resultado_medicamentos = mysqli_query($conexion, $query_medicamentos);

if (!$resultado_medicamentos) {
    die("Error en la consulta de medicamentos: " . mysqli_error($conexion));
}

$medicamentos = [];
while ($medicamento = mysqli_fetch_assoc($resultado_medicamentos)) {
    $medicamentos[] = $medicamento;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta Médica</title>
    <link rel="stylesheet" href="../assets/consulta.css">
</head>

<body>
    <div class="container">
        <h1>Consulta Médica</h1>
        <section id="consult-info">
            <h2 class="toggle-header">Información de la Consulta</h2>
            <div class="toggle-content">
                <p>Paciente: <span id="patient-name"><?php echo htmlspecialchars($consulta['paciente_nombre']); ?></span>
                </p>
                <p>Médico: <span id="doctor-name"><?php echo htmlspecialchars($consulta['medico_nombre']); ?></span></p>
                <p>Fecha: <span id="consult-date"><?php echo htmlspecialchars($consulta['fecha']); ?></span></p>
            </div>
        </section>
        <section id="consult-edit">
            <h2>Editar Información</h2>
            <div>
                <form id="consult-form" action="actualizar-medicacion.php" method="POST">
                    <label for="sintomatologia">Sintomatología</label>
                    <textarea id="symptoms"
                        name="symptoms"><?php echo htmlspecialchars($consulta['sintomas'] ?? ''); ?></textarea>
                    
                    <label for="diagnostico">Diagnóstico</label>
                    <textarea id="diagnosis"
                        name="diagnosis"><?php echo htmlspecialchars($consulta['diagnostico'] ?? ''); ?></textarea>
                    
                    <label for="medication">Medicamento:</label>
                    <select id="medication" name="medication[]">
                        <?php foreach ($medicamentos as $medicamento) { ?>
                            <option value="<?php echo htmlspecialchars($medicamento['id_medicamento']); ?>">
                                <?php echo htmlspecialchars($medicamento['nombre']); ?></option>
                        <?php } ?>
                    </select>
                    
                    <label for="quantity">Cantidad</label>
                    <input type="text" id="quantity" name="quantity[]" placeholder="Cantidad">
                    
                    <label for="frequency">Frecuencia</label>
                    <input type="text" id="frequency" name="frequency[]" placeholder="Frecuencia">
                    
                    <label for="days">Número de días</label>
                    <input type="text" id="days" name="days[]" placeholder="Número de días">
                    
                    <label for="chronic">Crónico</label><input type="checkbox" id="chronic" name="chronic[]">
                    
                    <button type="button" id="add-medication">Añadir Medicación</button>
                    <ul id="medication-list"></ul>
                    <input type="hidden" name="id_consulta" value="<?php echo htmlspecialchars($appointmentId); ?>">
                    <button type="submit">Guardar Cambios</button>
                </form>
            </div>
        </section>
    </div>
    <script src="../client/consulta.js"></script>
    <script src="../client/validaciones-consulta.js"></script>
</body>

</html>