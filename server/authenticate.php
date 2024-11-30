<?php
function authenticate($dni, $password, $conn) {

    // ? Preparar la consulta para obtener el id y el tipo de usuario

    $stmt = $conn->prepare("SELECT id, tipo_usuario FROM usuarios WHERE dni = ? AND password = ?"); // * Prepara la consulta para obtener el id y el tipo de usuario
    $stmt->bind_param("ss", $dni, $password); // * Asocia los parámetros a la consulta
    $stmt->execute(); // * Ejecuta la consulta
    $result = $stmt->get_result(); // * Obtiene el resultado de la consulta

    if ($result->num_rows > 0) { // * Si el número de filas del resultado es mayor a 0
        $row = $result->fetch_assoc(); // * Obtiene la fila del resultado

        $tipo_usuario = $row['tipo_usuario']; // * Obtiene el tipo de usuario
        $id = $row['id']; // * Obtiene el id del usuario

        if ($tipo_usuario === 'paciente') { // ! Si el tipo de usuario es paciente
            
            $stmt = $conn->prepare("SELECT nombre, apellido, fecha_de_nacimiento FROM paciente WHERE usuario_id = ?");
            
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            $user_data = $result->fetch_assoc(); // * Obtiene los datos del usuario
            $nombre = $user_data['nombre']; 
            $apellido = $user_data['apellido'];
            $fecha_de_nacimiento = $user_data['fecha_de_nacimiento'];

            // * Redirige a la página del paciente con los datos obtenidos
            echo "<script>window.location.href = '../patient.html?id=$id&nombre=$nombre&apellido=$apellido&fecha_de_nacimiento=$fecha_de_nacimiento';</script>";

        } elseif ($tipo_usuario === 'medico') { // ! Si el tipo de usuario es medico

            $stmt = $conn->prepare("SELECT nombre, apellido, fecha_de_nacimiento, especialidad FROM medico WHERE usuario_id = ?");

            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user_data = $result->fetch_assoc();

            $nombre = $user_data['nombre'];
            $apellido = $user_data['apellido'];
            $fecha_de_nacimiento = $user_data['fecha_de_nacimiento'];
            $especialidad = $user_data['especialidad'];

            // * Redirige a la página del médico con los datos obtenidos
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