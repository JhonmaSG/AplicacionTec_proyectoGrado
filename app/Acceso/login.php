<?php
session_start();
$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : "";
unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SGMP UD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(270deg, rgb(1, 106, 129), rgb(223, 251, 255));
            background-size: 400% 400%;
            animation: gradientBG 8s ease infinite;
        }

        .sl label {
            font-size: 12px;
            color: gray;
        }

        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }
    </style>
</head>

<body class="vh-100 d-flex align-items-center bg-light">

    <div class="container" style="max-width: 900px;">
        <div class="row shadow rounded overflow-hidden bg-white">
            <div class="col-md-6 d-none d-md-block p-0">
                <img src="img/logo2.png" alt="Fondo" class="w-100 h-100 object-fit-cover">
            </div>
            <div class="col-md-6 p-5">
                <div class="text-center mb-4">
                    <h1>SGMP UD</h1>
                    <h4>Inicio de sesión</h4><br>
                </div>

                <?php if (!empty($mensaje)) : ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <?= nl2br($mensaje) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>


                <form action="verificar_usuario.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Correo</label>
                        <input type="email" name="email" class="form-control" placeholder='Ingresa tu correo' required>
                    </div>
                    <div class="mb-5">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" placeholder='Ingresa tu contraseña' required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>

                <div class="text-center mt-3 sl">
                    <label>¿No recuerdas tu contraseña o quieres cambiarla?</label><br>
                    <a href="recuperar_password.php">¿Olvidaste tu contraseña?</a><br><br>
                    <label>¿Quieres ingresar? Crea una cuenta</label><br>
                    <a href="registrar_usuario.php">Crear cuenta nueva</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>