<?php
// Mostrar errores de PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir el archivo de conexión
include('conexion.php');

// Verificar la conexión
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Asegurarse de que no hay salidas antes de la redirección
ob_start();

// Obtener datos del formulario
$id_consulta = $_POST['id_consulta'];
$sintomas = $_POST['symptoms'];
$diagnostico = $_POST['diagnosis'];
$medicamentos = $_POST['medication'];
$cantidades = $_POST['quantity'];
$frecuencias = $_POST['frequency'];
$dias_list = $_POST['days'];
$cronicos = isset($_POST['chronic']) ? $_POST['chronic'] : [];

// Actualizar síntomas y diagnóstico en la base de datos
$query_actualizar_consulta = "UPDATE consulta SET sintomas = '$sintomas', diagnostico = '$diagnostico' WHERE id_consulta = '$id_consulta'";
$resultado_actualizar_consulta = mysqli_query($conexion, $query_actualizar_consulta);

if (!$resultado_actualizar_consulta) {
    die("Error al actualizar la consulta: " . mysqli_error($conexion));
}

// Eliminar medicaciones previas de la base de datos para la consulta dada
$query_eliminar_medicacion = "DELETE FROM consulta_medicamento WHERE id_consulta = '$id_consulta'";
mysqli_query($conexion, $query_eliminar_medicacion);

// Insertar nueva medicación en la base de datos
foreach ($medicamentos as $index => $id_medicamento) {
    $cantidad = $cantidades[$index];
    $frecuencia = $frecuencias[$index];
    $dias = $dias_list[$index];
    $cronico = in_array($index, $cronicos) ? 1 : 0;

    $query_medicacion = "INSERT INTO consulta_medicamento (id_consulta, id_medicamento, cantidad, frecuencia, duracion, cronica) VALUES ('$id_consulta', '$id_medicamento', '$cantidad', '$frecuencia', '$dias', '$cronico')";
    $resultado_medicacion = mysqli_query($conexion, $query_medicacion);

    if (!$resultado_medicacion) {
        die("Error al actualizar la medicación: " . mysqli_error($conexion));
    }
}

header("Location: consulta.php?appointmentId=$id_consulta");
exit();

?>
