<?php
// Mostrar errores de PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar la sesión
session_start();

// Incluir el archivo de conexión
include('conexion.php');

// Añadir mensajes de depuración
if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $contrasenia = mysqli_real_escape_string($conexion, $_POST['contrasenia']);

    // Consultar si el usuario y la contraseña son correctos
    $query = "SELECT * FROM usuario WHERE usuario = '$usuario' AND contrasenia = '$contrasenia'";
    $resultado = mysqli_query($conexion, $query);

    if (!$resultado) {
        die("Error en la consulta: " . mysqli_error($conexion));
    }

    // Si el usuario existe y la contraseña es correcta
    if (mysqli_num_rows($resultado) == 1) {
        $usuario_data = mysqli_fetch_assoc($resultado);
        $tipo_usuario = $usuario_data['tipo'];
        $id_usuario = $usuario_data['id_usuario'];

        // Redirigir a la página correspondiente dependiendo del tipo de usuario
        if ($tipo_usuario == 'paciente') {
            header("Location: paciente.php?id_usuario=$id_usuario");
            exit();
        } elseif ($tipo_usuario == 'medico') {
            header("Location: medico.php?id_usuario=$id_usuario");
            exit();
        } elseif ($tipo_usuario == "") {
            header("Location: error-404.html");
            exit();
        }

    } else {
        header("Location: error-404.html");
    }
}
?>
