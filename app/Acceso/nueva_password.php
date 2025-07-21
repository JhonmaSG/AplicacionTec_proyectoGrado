<?php
session_start();
$mensaje = $_SESSION['mensaje'] ?? "";
unset($_SESSION['mensaje']);

require '../config/ConfiguracionBD/ConexionBD.php';

if (!isset($_SESSION['nid'])) {
    if (!isset($_GET['token'])) {
        $_SESSION['mensaje'] = "❌ Token inválido.";
        header("Location: nueva_password_msg.php");
        exit();
    }

    $token = $_GET['token'];

    $sql = "SELECT nid FROM usuarios WHERE token_recuperacion = ? AND expira_token > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
        $_SESSION['nid'] = $usuario['nid'];
    } else {
        $_SESSION['mensaje'] = "❌ El enlace es inválido o ha expirado.";
        header("Location: nueva_password_msg.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link rel="icon" href="http://localhost/proyectoGrado/app/Acceso/img/icon_page.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center justify-content-center vh-100 bg-light">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-lg">
                    <div class="card-header text-center">
                        <h3>Restablecer Contraseña</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($mensaje)): ?>
                            <div class="alert alert-info text-center"><?php echo $mensaje; ?></div>
                        <?php endif; ?>

                        <form action="actualizar_password.php" method="POST">
                            <div class="mb-3">
                                <label for="password" class="form-label">Nueva Contraseña</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder='Ingresa tu nueva contraseña' required>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder='Confirma tu nueva contraseña' required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Actualizar Contraseña</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelector("form").addEventListener("submit", function(event) {
            let password = document.getElementById("password").value;
            let confirmPassword = document.getElementById("confirm_password").value;

            if (password !== confirmPassword) {
                event.preventDefault();
                alert("Las contraseñas no coinciden.");
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>