<?php
// Configuración de la base de datos
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "ambulatorio";

// Conexión a la base de datos
$conexion = mysqli_connect($servidor, $usuario, $password);
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Crear la base de datos si no existe
mysqli_query($conexion, "CREATE DATABASE IF NOT EXISTS $base_datos") or die("Error al crear la base de datos: " . mysqli_error($conexion));
mysqli_select_db($conexion, $base_datos) or die("Error al seleccionar la base de datos: " . mysqli_error($conexion));
?>
