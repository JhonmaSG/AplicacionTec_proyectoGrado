<?php
session_start();
$mensaje = '';
$form_data = [];

if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
}



include '../config/ConfiguracionBD/ConexionBD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nid = trim($_POST['nid']);
    $nombres = ucwords(strtolower(trim($_POST['nombres'])));
    $apellidos = ucwords(strtolower(trim($_POST['apellidos'])));
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $pregunta = $_POST['pregunta'];
    $respuesta = trim($_POST['respuesta']);
    define("ROL_LECTOR", 2);
    $rol_id = ROL_LECTOR;

    $_SESSION['form_data'] = [
        'nid' => $nid,
        'nombres' => $nombres,
        'apellidos' => $apellidos,
        'email' => $email,
        'pregunta' => $pregunta
    ];

    // Validaciones
    if (empty($nid) || empty($nombres) || empty($apellidos) || empty($email) || empty($password) || empty($confirm_password) || empty($pregunta) || empty($respuesta)) {
        $_SESSION['mensaje'] = "❌ Todos los campos son obligatorios.";
        header("Location: registrar_usuario.php");
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['mensaje'] = "❌ El formato del correo es inválido.";
        header("Location: registrar_usuario.php");
        exit();
    }
    if ($pregunta === '') {
        $_SESSION['mensaje'] = "❌ Debes seleccionar una pregunta de seguridad.";
        header("Location: registrar_usuario.php");
        exit();
    }


    $_SESSION['form_data'] = $_POST;

    // Validar contraseñas coincidan
    if ($password !== $confirm_password) {
        $_SESSION['mensaje'] = "❌ Las contraseñas no coinciden.";
        header("Location: registrar_usuario.php");
        exit();
    }

    // Validar fortaleza de contraseña con regex
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/', $password)) {
        $_SESSION['mensaje'] = '❌ La contraseña debe tener al menos:'
            . "<br>" . '  - Una letra mayúscula'
            . "<br>" . '  - Minúsculas'
            . "<br>" . '  - Un caracter especial'
            . "<br>" . '  - Y al menos 8 caracteres';
        header("Location: registrar_usuario.php");
        exit();
    }

    // Encriptar contraseña
    $password_hashed = password_hash($password, PASSWORD_BCRYPT);

    // Verificar duplicados
    $sql_check = "SELECT nid, email FROM usuarios WHERE nid = ? OR email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("is", $nid, $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $_SESSION['mensaje'] = "❌ El número de identificación o correo ya están registrados.";
        header("Location: registrar_usuario.php");
        exit();
    }
    $stmt_check->close();

    // Insertar usuario
    $sql = "INSERT INTO usuarios (nid, nombres, apellidos, email, password, pregunta, respuesta, rol_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssssi", $nid, $nombres, $apellidos, $email, $password_hashed, $pregunta, $respuesta, $rol_id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "✅ Registro exitoso. Inicia sesión.";
        header("Location: login.php");
    } else {
        $_SESSION['mensaje'] = "❌ Error al registrar el usuario.";
        header("Location: registrar_usuario.php");
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
    <title>Registro | Proyecto Grado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function validarContraseña() {
            let pass1 = document.getElementById("password").value;
            let pass2 = document.getElementById("confirm_password").value;
            let mensajeError = document.getElementById("error_contraseña");

            if (pass1 !== pass2) {
                mensajeError.textContent = "❌ Las contraseñas no coinciden.";
                return false;
            } else {
                mensajeError.textContent = "";
                return true;
            }
        }
    </script>
</head>

<body class="d-flex align-items-center justify-content-center vh-100 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg">
                    <div class="card-header text-center">
                        <h3>Registro de Usuario</h3>
                    </div>
                    <div class="card-body">
                        <form action="registrar_usuario.php" method="POST" onsubmit="return validarContraseña()">
                            <div class="mb-3">
                                <label class="form-label">Número de Identificación</label>
                                <input type="text" name="nid" class="form-control" placeholder='Ingresa tu No. de identificación' required pattern="[0-9]+" title="Solo se permiten números" value="<?= htmlspecialchars($form_data['nid'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nombres</label>
                                <input type="text" name="nombres" class="form-control" placeholder='Ingresa tus Nombres' required value="<?= htmlspecialchars($form_data['nombres'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Apellidos</label>
                                <input type="text" name="apellidos" class="form-control" placeholder='Ingresa tus Apellidos' required value="<?= htmlspecialchars($form_data['apellidos'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" placeholder='Ingresa tu correo' required value="<?= htmlspecialchars($form_data['email'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contraseña</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder='Ingresa tu contraseña' required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirmar Contraseña</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder='Vuelve a repetir tu contraseña' required>
                                <small id="error_contraseña" class="text-danger"></small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pregunta de Seguridad</label>
                                <select name="pregunta" class="form-control" required>
                                    <option value="">Selecciona una pregunta</option>
                                    <option value="Color favorito">¿Cuál es tu color favorito?</option>
                                    <option value="Mascota de la infancia">¿Cómo se llamaba tu primera mascota?</option>
                                    <option value="Ciudad de nacimiento">¿En qué ciudad naciste?</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Respuesta</label>
                                <input type="text" name="respuesta" class="form-control" placeholder='Ingresa tu respuesta a la pregunta' required>
                            </div>
                            <?php if (!empty($mensaje)): ?>
                                <div class="alert <?php echo strpos($mensaje, '✅') !== false ? 'alert-success' : 'alert-danger'; ?> text-center">
                                    <?php echo $mensaje; ?>
                                </div>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-success w-100">Registrarse</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <small>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>