<?php
session_start();
$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : "";
unset($_SESSION['mensaje']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center justify-content-center vh-100 bg-light">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-lg">
                    <div class="card-header text-center">
                        <h3>Recuperar Contraseña</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($mensaje)): ?>
                            <div class="alert <?php echo strpos($mensaje, '✅') !== false ? 'alert-success' : 'alert-danger'; ?> text-center">
                                <?php echo $mensaje; ?>
                            </div>
                        <?php endif; ?>

                        <form action="procesar_recuperacion.php" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" placeholder='Ingresa tu correo' required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Continuar</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <small><a href="login.php">Volver al login</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>