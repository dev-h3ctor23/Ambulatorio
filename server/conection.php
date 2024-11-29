<?php

// ? Datos del formulario de index.hmtl validados con validation-index.js
$dni = $_GET['dni']; // * DNI recibido del formulario
$password = $_GET['password']; // * Contraseña recibida del formulario

// ? Variables para la conexión a la base de datos
$servername = "localhost"; // * Servidor de la base de datos
$username = "root"; // * Usuario de la base de datos
$dbpassword = ""; // * Contraseña de la base de datos
$dbname = "ambulatorio"; // * Nombre de la base de datos

// ? Declaramos la conexión a la base de datos
$conn = new mysqli($servername, $username, $dbpassword);

// ? Verificamos la conexión a la base de datos
if ($conn->connect_error) {
    die('Error de conexión a la base de datos: ' . $conn->connect_error); // * En caso de un error con la conexion a la base de datos se muestra un mensaje de error
}

// ? En caso de que la base de datos no exista, la creamos 
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
$conn->query($sql); // * Ejecutamos la consulta guardada en la variable $sql para crear la base de datos

// ? Seleccionamos la base de datos del ambulatorio
$conn->select_db($dbname);

// ? En caso de que no exista, creamos la tabla para los usuarios

    // * UNSIGNED: Solo permite valores positivos
    // * AUTO_INCREMENT: Incrementa el valor de la columna automáticamente
    // * ENUM: Tipo de dato que permite especificar un conjunto de valores

$sql = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    dni VARCHAR(30) NOT NULL UNIQUE,
    password VARCHAR(50) NOT NULL,
    tipo_usuario ENUM('medico', 'paciente') NOT NULL 
)";

$conn->query($sql);

// ! NO TOCAR: Verificamos si los usuarios de prueba ya existen
$check_sql = "SELECT dni FROM usuarios WHERE dni IN ('12345678', '87654321')";
$result = $conn->query($check_sql);

if ($result->num_rows == 0) {
    // Insertamos los datos de los usuarios en la tabla usuarios solo si no existen
    $sql = "INSERT INTO usuarios (dni, password, tipo_usuario) VALUES
        ('12345678', 'password123', 'paciente'),
        ('87654321', 'password456', 'medico')";
    $conn->query($sql);
}

// ? Conslta para verificar el tipo de usuario y redirigir a la página correspondiente

    // * prepare: Prepara una sentencia SQL para ser ejecutada por el método execute()
    // * bind_param: Une variables a una sentencia SQL
    // * execute: Ejecuta la sentencia preparada
    // * get_result: Obtiene un resultado de la sentencia preparada
    // * ss: Tipo de datos de las variables que se van a unir a la sentencia SQL

$stmt = $conn->prepare("SELECT tipo_usuario FROM usuarios WHERE dni = ? AND password = ?"); // ! NO TOCAR: Funciona milagrosamente!
$stmt->bind_param("ss", $dni, $password);
$stmt->execute();
$result = $stmt->get_result();

// ? Verificamos si el usuario existe en la base de datos

    // * fetch_assoc: Obtiene una fila de resultados como un array asociativo
    // * echo '<script>window.location.href = "";</script>': Redirige a la página correspondiente con el echo de un script.

if($result->num_rows > 0) {
    $row = $result->fetch_assoc();  
    $tipo_usuario = $row['tipo_usuario']; 
    if($tipo_usuario === 'paciente') {
        // ! Redirigir a paciente.html
        echo '<script>window.location.href = "../patient.html";</script>';
    } elseif($tipo_usuario === 'medico') {
        // ! Redirigir a medico.html
        echo '<script>window.location.href = "../doctor.html";</script>';
    } else {
        // ! Redirigir a unknown-user.html
        echo '<script>window.location.href = "../unknown-user.html";</script>';
    }
} else {
        // ! Redirigir a unknown-user.html
    echo '<script>window.location.href = "../unknown-user.html";</script>';
}
// ? Cerramos la declaracion y la conexión a la base de datos
$stmt->close();
$conn->close();
?>