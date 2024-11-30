<?php
function authenticate($dni, $password, $conn) {
    // Consulta para verificar el tipo de usuario
    $stmt = $conn->prepare("SELECT id, tipo_usuario FROM usuarios WHERE dni = ? AND password = ?");
    $stmt->bind_param("ss", $dni, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $tipo_usuario = $row['tipo_usuario'];
        $id = $row['id'];

        if ($tipo_usuario === 'paciente') {
            // Obtener datos del paciente
            $stmt = $conn->prepare("SELECT nombre, apellido, fecha_de_nacimiento FROM paciente WHERE usuario_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user_data = $result->fetch_assoc();
            $nombre = $user_data['nombre'];
            $apellido = $user_data['apellido'];
            $fecha_de_nacimiento = $user_data['fecha_de_nacimiento'];
            echo "<script>window.location.href = '../patient.html?id=$id&nombre=$nombre&apellido=$apellido&fecha_de_nacimiento=$fecha_de_nacimiento';</script>";
        } elseif ($tipo_usuario === 'medico') {
            // Obtener datos del medico
            $stmt = $conn->prepare("SELECT nombre, apellido, fecha_de_nacimiento, especialidad FROM medico WHERE usuario_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user_data = $result->fetch_assoc();
            $nombre = $user_data['nombre'];
            $apellido = $user_data['apellido'];
            $fecha_de_nacimiento = $user_data['fecha_de_nacimiento'];
            $especialidad = $user_data['especialidad'];
            echo "<script>window.location.href = '../doctor.html?id=$id&nombre=$nombre&apellido=$apellido&fecha_de_nacimiento=$fecha_de_nacimiento&especialidad=$especialidad';</script>";
        } else {
            echo '<script>window.location.href = "unknown-user.html";</script>';
        }
    } else {
        echo '<script>window.location.href = "../unknown-user.html";</script>';
    }

    $stmt->close();
}
?>