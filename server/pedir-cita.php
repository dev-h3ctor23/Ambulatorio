<?php
// Mostrar errores de PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir el archivo de conexión
include('../server/conexion.php');

// Obtener datos del formulario
$id_usuario = $_POST['id_usuario'];
$id_medico = $_POST['doctor'];
$fecha = $_POST['date'];
$sintomas = isset($_POST['symptoms']) ? $_POST['symptoms'] : '';

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

// Insertar nueva cita en la base de datos
$query = "INSERT INTO consulta (id_medico, id_paciente, fecha, sintomas) VALUES ('$id_medico', '$id_paciente', '$fecha', '$sintomas')";
$resultado = mysqli_query($conexion, $query);

if ($resultado) {
    // Redirigir de vuelta a la página del paciente con un mensaje de éxito
    header("Location: paciente.php?id_usuario=$id_usuario&message=Cita pedida correctamente.");
} else {
    // Redirigir de vuelta a la página del paciente con un mensaje de error
    header("Location: paciente.php?id_usuario=$id_usuario&message=Error al pedir la cita: " . mysqli_error($conexion));
}
exit();
?>
