<?php
session_start();
$_SESSION['ultimo_acceso'] = time(); // Tiempo actual
$_SESSION['duracion_sesion'] = 300; // seg

include '../ConfiguracionBD/ConexionBD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = trim($_POST['password']);

    $sql = "SELECT nid, nombres, apellidos, password, rol_id FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($password, $usuario['password'])) {
            // Actualizar la última conexión
            $sql_update = "UPDATE usuarios SET ult_conexion = NOW() WHERE nid = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("i", $usuario['nid']);
            $stmt_update->execute();

            $_SESSION['nid'] = $usuario['nid'];
            $_SESSION['nombre'] = $usuario['nombres'];
            $_SESSION['rol'] = $usuario['rol_id'];

            // Redirección
            header("Location: /proyectoGrado/Modulo_01_tabla_09/materias.php");
            exit();
        } else {
            $_SESSION['mensaje'] = "❌ Contraseña incorrecta.";
        }
    } else {
        $_SESSION['mensaje'] = "❌ Usuario no encontrado.";
    }

    $stmt->close();
    $conn->close();

    // Redirigir a login.php con el mensaje
    header("Location: /proyectoGrado/Login/Acceso/login.php");
    exit();
}
