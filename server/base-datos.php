<?php
// Incluir el archivo de conexión
include('../server/conexion.php');

// Crear las tablas
$tablas = [
    'CREATE TABLE IF NOT EXISTS usuario (
        id_usuario INT AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(20),
        contrasenia VARCHAR(20),
        tipo ENUM("paciente", "medico")
    )',
    'CREATE TABLE IF NOT EXISTS paciente (
        id_paciente INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(50),
        citas VARCHAR(100),
        medicacion VARCHAR(100),
        id_usuario INT,
        FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE
    )',
    'CREATE TABLE IF NOT EXISTS medico (
        id_medico INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(50),
        especialidad VARCHAR(50),
        num_consultas INT,
        consultas_hoy VARCHAR(100),
        id_usuario INT,
        FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE
    )',
    'CREATE TABLE IF NOT EXISTS medicamentos (
        id_medicamento INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(20)
    )',
    'CREATE TABLE IF NOT EXISTS consulta (
        id_consulta INT AUTO_INCREMENT PRIMARY KEY,
        id_medico INT,
        id_paciente INT,
        fecha DATE,
        sintomas VARCHAR(200),
        diagnostico VARCHAR(200),
        FOREIGN KEY (id_medico) REFERENCES medico(id_medico) ON DELETE CASCADE,
        FOREIGN KEY (id_paciente) REFERENCES paciente(id_paciente) ON DELETE CASCADE
    )',
    'CREATE TABLE IF NOT EXISTS consulta_medicamento (
        id_consulta_medicamento INT AUTO_INCREMENT PRIMARY KEY,
        cantidad VARCHAR(200),
        frecuencia VARCHAR(200),
        duracion VARCHAR(200),
        cronica INT,
        id_consulta INT,
        id_medicamento INT,
        FOREIGN KEY (id_consulta) REFERENCES consulta(id_consulta) ON DELETE CASCADE,
        FOREIGN KEY (id_medicamento) REFERENCES medicamentos(id_medicamento) ON DELETE CASCADE
    )',
    'CREATE TABLE IF NOT EXISTS paciente_medico (
        id_paciente_medico INT AUTO_INCREMENT PRIMARY KEY,
        id_paciente INT,
        id_medico INT,
        FOREIGN KEY (id_paciente) REFERENCES paciente(id_paciente) ON DELETE CASCADE,
        FOREIGN KEY (id_medico) REFERENCES medico(id_medico) ON DELETE CASCADE
    )'
];

// Ejecutar las consultas para crear las tablas
foreach ($tablas as $tabla) {
    mysqli_query($conexion, $tabla) or die("Error al crear la tabla: " . mysqli_error($conexion));
}

echo "Tablas creadas correctamente.<br>";

// Inserciones en las tablas
$inserciones = [
    "INSERT INTO usuario (usuario, contrasenia, tipo) VALUES 
    ('paciente', 'paciente', 'paciente'),
    ('medico1', 'medico1', 'medico'),
    ('medico2', 'medico2', 'medico')",

    "INSERT INTO paciente (nombre, citas, medicacion, id_usuario) VALUES
    ('Juan Pérez', 'Cita con el Dr. Gómez', 'Ibuprofeno, 3 veces al día', 1),
    ('Carlos Sánchez', 'Cita con la Dra. Martínez', 'Paracetamol, 2 veces al día', 1)",

    "INSERT INTO medico (nombre, especialidad, num_consultas, consultas_hoy, id_usuario) VALUES
    ('Dr. Gómez', 'Cabecera', 20, 3, 2), 
    ('Dra. Martínez', 'Familia', 15, 2, 3)",

    "INSERT INTO medicamentos (nombre) VALUES
    ('paracetamol'),
    ('ibuprofeno')",

    "INSERT INTO consulta (id_medico, id_paciente, fecha, sintomas, diagnostico) VALUES
    (1, 1, '2024-12-02', 'Dolor de cabeza persistente', 'Migraña'),
    (2, 2, '2024-11-30', 'Dolor de estómago recurrente', 'Gastritis')",

    "INSERT INTO consulta_medicamento (id_consulta, id_medicamento, cantidad, frecuencia, duracion, cronica) VALUES
    (1, 1, '200mg', 'cada 1 horas', '1 dia', 1),
    (2, 2, '300mg', 'cada 2 horas', '2 dia', 0)",

    "INSERT INTO paciente_medico (id_paciente, id_medico) VALUES
    (1, 1),
    (2, 2)"
];

// Ejecutar las consultas de inserción
foreach ($inserciones as $insercion) {
    if (!mysqli_query($conexion, $insercion)) {
        die("Error al insertar datos: " . mysqli_error($conexion) . " - Consulta: " . $insercion);
    }
}

echo "Datos insertados correctamente en todas las tablas.<br>";
?>
