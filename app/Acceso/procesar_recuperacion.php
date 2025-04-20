<?php
session_start();
require '../config/ConfiguracionBD/ConexionBD.php';
require '../libraries/Mailer.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Verificar si el correo existe en la BD
    $sql = "SELECT nid FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        $nid = $usuario['nid'];

        // Generar token único y fecha de expiración
        $token = bin2hex(random_bytes(50));
        $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $sql = "UPDATE usuarios SET token_recuperacion = ?, expira_token = ? WHERE nid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $token, $expira, $nid);

        if ($stmt->execute()) {
            $url_recuperacion = "http://localhost/proyectoGrado/app/Acceso/nueva_password.php?token=" . urlencode($token);

            // Asunto y contenido del correo
            $asunto = "Recuperación de Contraseña SGMP UD";
            $cuerpo = '
    <div style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
        <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
            <h2 style="color: #333333;">Recuperación de Contraseña</h2>
            <p style="font-size: 16px; color: #555555;">
                Hola, hemos recibido una solicitud para restablecer tu contraseña. Si no realizaste esta solicitud, puedes ignorar este mensaje.
            </p>
            <p style="font-size: 16px; color: #555555;">
                Para restablecer tu contraseña, haz clic en el siguiente botón:
            </p>
            <p style="text-align: center; margin: 30px 0;">
                <a href="' . $url_recuperacion . '" style="background-color: #007bff; color: white; text-decoration: none; padding: 12px 20px; border-radius: 5px; font-size: 16px;">
                    Restablecer Contraseña
                </a>
            </p>
            <p style="font-size: 14px; color: #999999;">
                También puedes copiar y pegar el siguiente enlace en tu navegador:
                <br>
                <a href="' . $url_recuperacion . '">' . $url_recuperacion . '</a>
            </p>
            <p style="font-size: 14px; color: #999999;">
                Este enlace es válido por 1 hora.
            </p>
        </div>
    </div>';


            // Enviar correo
            if (Mailer::enviarCorreo($email, $asunto, $cuerpo, $url_recuperacion)) {
                $_SESSION['mensaje'] = "✅ Revisa tu correo para restablecer la contraseña." . "<br>" . 'Ya puedes cerrar esta ventana';
            } else {
                $_SESSION['mensaje'] = "❌ Error al enviar el correo.";
                error_log("Error: No se pudo enviar el correo de recuperación a $email.");
            }
        } else {
            $_SESSION['mensaje'] = "❌ Error al generar el enlace de recuperación.";
            error_log("Error: No se pudo guardar el token en la BD para $email.");
        }
    } else {
        $_SESSION['mensaje'] = "❌ Correo no encontrado.";
    }

    $stmt->close();
    $conn->close();

    header("Location: recuperar_password_msg.php");
    exit();
}
