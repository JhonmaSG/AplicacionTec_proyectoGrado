<?php
session_start();
include '../ConfiguracionBD/ConexionBD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nid = $_SESSION['nid'];
    $respuesta = trim($_POST['respuesta']);

    $sql = "SELECT respuesta FROM usuarios WHERE nid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $nid);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        
        if (strtolower($usuario['respuesta']) === strtolower($respuesta)) {
            $_SESSION['mensaje'] = "✅ Respuesta correcta. Ingresa una nueva contraseña.";
            header("Location: nueva_password.php");
        } else {
            $_SESSION['mensaje'] = "❌ Respuesta incorrecta.";
            header("Location: validar_respuesta.php");
        }
    } else {
        $_SESSION['mensaje'] = "❌ Error en la validación.";
        header("Location: recuperar_password.php");
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>
