<?php
session_start();
error_log("🔍 NID recibido en actualizar_password.php: " . ($_SESSION['nid'] ?? 'No existe'));

include '../config/ConfiguracionBD/ConexionBD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    define("LONG_PASSWORD", 8);
    if (!isset($_SESSION['nid'])) {
        $_SESSION['mensaje'] = "❌ Acceso no autorizado.";
        header("Location: nueva_password_msg.php");
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

    if (
        strlen($password) < LONG_PASSWORD ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[\W_]/', $password)
    ) {
        $_SESSION['mensaje'] = '❌ La contraseña debe tener al menos:'
            . "<br>" . '  - Una letra mayúscula'
            . "<br>" . '  - Minúsculas'
            . "<br>" . '  - Un caracter especial'
            . "<br>" . '  - Y al menos 8 caracteres';
        header("Location: nueva_password.php");
        exit();
    }

    $sql = "SELECT password FROM usuarios WHERE nid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $nid);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        $password_actual_hast = $usuario['password'];

        //verificacion de pass
        if (password_verify($password, $password_actual_hast)) {
            $_SESSION['mensaje'] = "❌ La nueva contraseña no puede ser igual a la anterior.";
            header("Location: nueva_password.php");
            exit();
        }
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE usuarios SET password = ?, token_recuperacion = NULL, expira_token = NULL WHERE nid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hashed_password, $nid);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "✅ Contraseña actualizada correctamente.";
        unset($_SESSION['nid']);
        header("Location: nueva_password_msg.php");
    } else {
        $_SESSION['mensaje'] = "❌ Error al actualizar la contraseña.";
        header("Location: nueva_password.php");
    }

    $stmt->close();
    $conn->close();
    exit();
}
