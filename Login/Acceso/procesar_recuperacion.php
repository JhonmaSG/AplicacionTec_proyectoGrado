<?php
session_start();
include '../ConfiguracionBD/ConexionBDPDO.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    $sql = "SELECT nid, pregunta FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        $_SESSION['nid'] = $usuario['nid'];
        $_SESSION['pregunta'] = $usuario['pregunta'];
        $_SESSION['mensaje'] = "✅ Responde la pregunta de seguridad.";
        header("Location: validar_respuesta.php");
    } else {
        $_SESSION['mensaje'] = "❌ Correo no encontrado.";
        header("Location: recuperar_password.php");
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>
