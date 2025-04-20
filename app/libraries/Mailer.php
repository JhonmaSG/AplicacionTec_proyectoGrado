<?php
// Incluir la libreria PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/../config/env.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

loadEnv(__DIR__ . '/../config/.env');

// Inicio
class Mailer {
    public static function enviarCorreo($destino, $asunto, $cuerpoHTML, $url_recuperacion) {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        try {
            // Configuracion SMTP
            $mail->isSMTP();      // Activar envio SMTP
            $mail->Host       = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USERNAME'];
            $mail->Password   = $_ENV['SMTP_PASSWORD'];
            $mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION'];
            $mail->Port       = $_ENV['SMTP_PORT'];
            $mail->setFrom($_ENV['SMTP_FROM'], $_ENV['SMTP_FROM_NAME']); // Remitente del correo

            // Destinatarios
            $mail->addAddress($destino);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body  = $cuerpoHTML;
            $mail->AltBody = 'Para restablecer tu contraseña, copia y pega el siguiente enlace en tu navegador: ' . $url_recuperacion;

            return $mail->send(); // Devuelve true si se envía correctamente
        } catch (Exception $e) {
            // Puedes guardar el error en log y devolver false
            error_log("Mailer Error: " . $mail->ErrorInfo);
            return false;
        }
    }
}
