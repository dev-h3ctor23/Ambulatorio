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

// ? Crear la tabla de pacientes
$sql = "CREATE TABLE IF NOT EXISTS paciente (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    usuario_id INT(6) UNSIGNED NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    dni VARCHAR(30) NOT NULL UNIQUE,
    fecha_de_nacimiento DATE NOT NULL,
    tipo_usuario ENUM('paciente') NOT NULL,
    contraseña VARCHAR(50) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (dni) REFERENCES usuarios(dni),
    FOREIGN KEY (contraseña) REFERENCES usuarios(password)
)";
$conn->query($sql);

// ? Crear la tabla de medicos
$sql = "CREATE TABLE IF NOT EXISTS medico (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    usuario_id INT(6) UNSIGNED NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    dni VARCHAR(30) NOT NULL UNIQUE,
    fecha_de_nacimiento DATE NOT NULL,
    tipo_usuario ENUM('medico') NOT NULL,
    contraseña VARCHAR(50) NOT NULL,
    especialidad VARCHAR(50) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (dni) REFERENCES usuarios(dni),
    FOREIGN KEY (contraseña) REFERENCES usuarios(password)
)";
$conn->query($sql);

// ! NO TOCAR: Verificamos si los usuarios de prueba ya existen
$check_sql = "SELECT dni FROM usuarios WHERE dni IN ('12345678', '87654321')";
$result = $conn->query($check_sql);

if ($result->num_rows == 0) {
    // Insertamos los datos de los usuarios en la tabla usuarios solo si no existen
    $insert_sql = "INSERT INTO usuarios (dni, password, tipo_usuario) VALUES 
    ('12345678', 'password1', 'medico'),
    ('87654321', 'password2', 'paciente')";
    $conn->query($insert_sql);
}

// Insertar 20 usuarios, 10 pacientes y 10 médicos
$insert_sql = "INSERT INTO usuarios (dni, password, tipo_usuario) VALUES 
('11111111', 'password1', 'paciente'),
('22222222', 'password2', 'paciente'),
('33333333', 'password3', 'paciente'),
('44444444', 'password4', 'paciente'),
('55555555', 'password5', 'paciente'),
('66666666', 'password6', 'paciente'),
('77777777', 'password7', 'paciente'),
('88888888', 'password8', 'paciente'),
('99999999', 'password9', 'paciente'),
('10101010', 'password10', 'paciente'),
('12121212', 'password11', 'medico'),
('13131313', 'password12', 'medico'),
('14141414', 'password13', 'medico'),
('15151515', 'password14', 'medico'),
('16161616', 'password15', 'medico'),
('17171717', 'password16', 'medico'),
('18181818', 'password17', 'medico'),
('19191919', 'password18', 'medico'),
('20202020', 'password19', 'medico'),
('21212121', 'password20', 'medico')";
$conn->query($insert_sql);

echo "Tablas creadas y usuarios insertados exitosamente.";

$conn->close();
?>