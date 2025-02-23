<?php
session_start();
include '../ConfiguracionBD/ConexionBDPDO.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['nid'])) {
        $_SESSION['mensaje'] = "❌ Acceso no autorizado.";
        header("Location: recuperar_password.php");
        exit();
    }

    $nid = $_SESSION['nid'];
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $_SESSION['mensaje'] = "❌ Las contraseñas no coinciden.";
        header("Location: nueva_password.php");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE usuarios SET password = ? WHERE nid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hashed_password, $nid);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "✅ Contraseña actualizada correctamente.";
        session_destroy();
        header("Location: login.php");
    } else {
        $_SESSION['mensaje'] = "❌ Error al actualizar la contraseña.";
        header("Location: nueva_password.php");
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>
