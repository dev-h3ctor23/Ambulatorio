<?php

// ! NO TOCAR: Incluye el archivo authenticate.php
include 'authenticate.php';

// ? Variables que guardan los datos recibidos del formulario en index.html

$dni = $_GET['dni']; 
$password = $_GET['password']; 

// ? Variables para la conexión a la base de datos

$servername = "localhost"; 
$username = "root"; 
$dbpassword = ""; 
$dbname = "ambulatorio"; // * Nombre de la base de datos

// ? Declaración de la conexión a la base de datos 

$conn = new mysqli($servername, $username, $dbpassword);

// ? Verificacion de la conexión a la base de datos

if ($conn->connect_error) {
    die('Error de conexión a la base de datos: ' . $conn->connect_error); // ! Si no se puede conectar a la base de datos, se muestra un mensaje de error
}

// ? En el hipotetico caso de que no exista la base de datos, se crea la base de datos "ambulatorio" 

$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
$conn->query($sql);

// ? Aqui se selecciona la base de datos "ambulatorio"

    // * conn: Variable que guarda la conexión a la base de datos
    // * -> : Operador de acceso a métodos y propiedades de un objeto
    // * select_db: Método que selecciona la base de datos a la que se conectará

$conn->select_db($dbname);

// ? Crear la tabla de usuarios

    // * AUTO_INCREMENT: Incrementa el valor de la columna automáticamente
    // * UNIQUE: No se pueden repetir los valores de la columna
    // * ENUM: Tipo de dato que permite especificar un conjunto de valores

$sql = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(10) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('paciente', 'medico') NOT NULL
)";
$conn->query($sql);

// ? Crear la tabla de pacientes

    // * FOREIGN KEY: Clave foránea que establece una relación entre dos tablas
    // * REFERENCES: Establece la relación entre la clave foránea y la clave primaria de otra tabla

$sql = "CREATE TABLE IF NOT EXISTS paciente (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    usuario_id INT NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    fecha_de_nacimiento DATE NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
)";
$conn->query($sql);

// ? Crear la tabla de medicos

$sql = "CREATE TABLE IF NOT EXISTS medico (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    usuario_id INT NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    fecha_de_nacimiento DATE NOT NULL,
    especialidad VARCHAR(50) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
)";
$conn->query($sql);

// ! IMPORTANTE: $check_sql: Consulta para verificar si los datos de los usuarios ya existen en la tabla usuarios

$check_sql = "SELECT dni FROM usuarios WHERE dni IN (
    '45328901', '56437802', '67548903', '78659004', '89760105', 
    '90871206', '01982307', '12093408', '23104509', '34215610', 
    '45326711', '56437812', '67548913', '78659014', '89760115', 
    '90871216', '01982317', '12093418', '23104519', '34215620'
)";

// ? Verificar si los datos de los usuarios ya existen en la tabla usuarios

    // * num_rows: Propiedad que devuelve el número de filas de un resultado
    // * query: Método que ejecuta una consulta a la base de datos

$result = $conn->query($check_sql);

if ($result->num_rows == 0) {

    $insert_sql = "INSERT INTO usuarios (dni, password, tipo_usuario) VALUES 
    ('45328901', '45328901', 'paciente'),
    ('56437802', '56437802', 'paciente'),
    ('67548903', '67548903', 'paciente'),
    ('78659004', '78659004', 'paciente'),
    ('89760105', '89760105', 'paciente'),
    ('90871206', '90871206', 'paciente'),
    ('01982307', '01982307', 'paciente'),
    ('12093408', '12093408', 'paciente'),
    ('23104509', '23104509', 'paciente'),
    ('34215610', '34215610', 'paciente'),
    ('45326711', '45326711', 'medico'),
    ('56437812', '56437812', 'medico'),
    ('67548913', '67548913', 'medico'),
    ('78659014', '78659014', 'medico'),
    ('89760115', '89760115', 'medico'),
    ('90871216', '90871216', 'medico'),
    ('01982317', '01982317', 'medico'),
    ('12093418', '12093418', 'medico'),
    ('23104519', '23104519', 'medico'),
    ('34215620', '34215620', 'medico')

    ON DUPLICATE KEY UPDATE password=VALUES(password), tipo_usuario=VALUES(tipo_usuario)";
    $conn->query($insert_sql);
}

// ? Datos de los pacientes guardados en un array

$pacientes = [
    ['45328901', 'Juan', 'Perez', '1987-03-15'],
    ['56437802', 'María', 'Gómez', '1990-07-25'],
    ['67548903', 'Luis', 'Martínez', '1992-11-02'],
    ['78659004', 'Ana', 'Rodríguez', '1985-05-14'],
    ['89760105', 'Carlos', 'López', '1995-08-08'],
    ['90871206', 'Sofía', 'García', '1997-01-30'],
    ['01982307', 'Diego', 'Hernández', '1994-04-10'],
    ['12093408', 'Laura', 'Díaz', '1991-09-19'],
    ['23104509', 'Andrés', 'Castro', '1989-02-22'],
    ['34215610', 'Paula', 'Ramos', '1996-12-05']
];

// ? Insertamos los datos de los pacientes en la tabla paciente solo si no existen

foreach ($pacientes as $paciente) {
    $check_sql = "SELECT * FROM paciente WHERE usuario_id = (SELECT id FROM usuarios WHERE dni = '{$paciente[0]}')";
    $result = $conn->query($check_sql);
    if ($result->num_rows == 0) {
        $insert_sql = "INSERT INTO paciente (usuario_id, nombre, apellido, fecha_de_nacimiento) VALUES 
        ((SELECT id FROM usuarios WHERE dni='{$paciente[0]}'), '{$paciente[1]}', '{$paciente[2]}', '{$paciente[3]}')";
        $conn->query($insert_sql);
    }
}

// ? Datos de los medicos guardados en un array

$medicos = [
    ['45326711', 'Pedro', 'González', '1980-06-15', 'Cardiología'],
    ['56437812', 'Lucía', 'Fernández', '1983-09-25', 'Neurología'],
    ['67548913', 'Miguel', 'Sánchez', '1987-12-02', 'Pediatría'],
    ['78659014', 'Elena', 'Torres', '1982-03-14', 'Dermatología'],
    ['89760115', 'Javier', 'Ruiz', '1985-07-08', 'Ginecología'],
    ['90871216', 'Carmen', 'Molina', '1989-11-30', 'Oftalmología'],
    ['01982317', 'Roberto', 'Navarro', '1990-02-10', 'Psiquiatría'],
    ['12093418', 'Isabel', 'Ortega', '1984-05-19', 'Oncología'],
    ['23104519', 'Alberto', 'Vega', '1981-08-22', 'Urología'],
    ['34215620', 'Patricia', 'Rojas', '1986-10-05', 'Traumatología']
];

// Insertamos los datos de los medicos en la tabla medico solo si no existen
foreach ($medicos as $medico) {
    $check_sql = "SELECT * FROM medico WHERE usuario_id = (SELECT id FROM usuarios WHERE dni = '{$medico[0]}')";
    $result = $conn->query($check_sql);
    if ($result->num_rows == 0) {
        $insert_sql = "INSERT INTO medico (usuario_id, nombre, apellido, fecha_de_nacimiento, especialidad) VALUES 
        ((SELECT id FROM usuarios WHERE dni='{$medico[0]}'), '{$medico[1]}', '{$medico[2]}', '{$medico[3]}', '{$medico[4]}')";
        $conn->query($insert_sql);
    }
}

// Llamar a la función de autenticación
authenticate($dni, $password, $conn);

// Cerramos la conexión a la base de datos
$conn->close();
?>