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
    fecha_de_nacimiento DATE NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
)";
$conn->query($sql);

// ? Crear la tabla de medicos
$sql = "CREATE TABLE IF NOT EXISTS medico (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    usuario_id INT(6) UNSIGNED NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    fecha_de_nacimiento DATE NOT NULL,
    especialidad VARCHAR(50) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
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
('45328901', '45328901', 'paciente'),
('56437802', '56437802', 'paciente'),
('67548903', '67548903', 'paciente'),
('78659004', '67548903', 'paciente'),
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

// Insertar datos en la tabla paciente
$insert_sql = "INSERT INTO paciente (usuario_id, nombre, apellido, fecha_de_nacimiento) VALUES 
((SELECT id FROM usuarios WHERE dni='45328901'), 'Juan', 'Pérez', '1987-03-15'),
((SELECT id FROM usuarios WHERE dni='56437802'), 'María', 'Gómez', '1990-07-25'),
((SELECT id FROM usuarios WHERE dni='67548903'), 'Luis', 'Martínez', '1992-11-02'),
((SELECT id FROM usuarios WHERE dni='78659004'), 'Ana', 'Rodríguez', '1985-05-14'),
((SELECT id FROM usuarios WHERE dni='89760105'), 'Carlos', 'López', '1995-08-08'),
((SELECT id FROM usuarios WHERE dni='90871206'), 'Sofía', 'García', '1997-01-30'),
((SELECT id FROM usuarios WHERE dni='01982307'), 'Diego', 'Hernández', '1994-04-10'),
((SELECT id FROM usuarios WHERE dni='12093408'), 'Laura', 'Díaz', '1991-09-19'),
((SELECT id FROM usuarios WHERE dni='23104509'), 'Andrés', 'Castro', '1989-02-22'),
((SELECT id FROM usuarios WHERE dni='34215610'), 'Paula', 'Ramos', '1996-12-05')";
$conn->query($insert_sql);

// Insertar datos en la tabla medico
$insert_sql = "INSERT INTO medico (usuario_id, nombre, apellido, fecha_de_nacimiento, especialidad) VALUES 
((SELECT id FROM usuarios WHERE dni='45326711'), 'Miguel', 'Romero', '1975-06-12', 'Cardiología'),
((SELECT id FROM usuarios WHERE dni='56437812'), 'Julia', 'Fernández', '1980-03-28', 'Dermatología'),
((SELECT id FROM usuarios WHERE dni='67548913'), 'Álvaro', 'Santos', '1982-09-14', 'Neurología'),
((SELECT id FROM usuarios WHERE dni='78659014'), 'Clara', 'Mendoza', '1978-01-05', 'Pediatría'),
((SELECT id FROM usuarios WHERE dni='89760115'), 'Roberto', 'Silva', '1976-10-20', 'Psiquiatría'),
((SELECT id FROM usuarios WHERE dni='90871216'), 'Elena', 'Ortiz', '1983-08-17', 'Ginecología'),
((SELECT id FROM usuarios WHERE dni='01982317'), 'Ignacio', 'Moreno', '1977-04-22', 'Oncología'),
((SELECT id FROM usuarios WHERE dni='12093418'), 'Teresa', 'Cruz', '1984-12-11', 'Urología'),
((SELECT id FROM usuarios WHERE dni='23104519'), 'Felipe', 'Vega', '1981-02-03', 'Oftalmología'),
((SELECT id FROM usuarios WHERE dni='34215620'), 'Mariana', 'Reyes', '1979-11-30', 'Gastroenterología')";
$conn->query($insert_sql);

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
            echo '<script>window.location.href = "unknown-user.html";</script>';
        }
    } else {
            // ! Redirigir a unknown-user.html
        echo '<script>window.location.href = "../unknown-user.html";</script>';
    }

// ? Cerramos la declaracion y la conexión a la base de datos
$stmt->close();
$conn->close();
?>