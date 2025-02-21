<?php
session_start();
include '../ConfiguracionBD/ConexionBDPDO.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nid = trim($_POST['nid']);
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $pregunta = $_POST['pregunta'];
    $respuesta = trim($_POST['respuesta']);

    $rol_id = 2;

    // Verificar si el NID o el email ya existen
    $sql_check = "SELECT nid, email FROM usuarios WHERE nid = ? OR email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("is", $nid, $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $_SESSION['mensaje'] = "❌ El número de identificación o correo ya están registrados.";
        header("Location: registro.php");
        exit();
    }
    $stmt_check->close();

    // Insertar usuario
    $sql = "INSERT INTO usuarios (nid, nombres, apellidos, email, password, pregunta, respuesta, rol_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssssi", $nid, $nombres, $apellidos, $email, $password, $pregunta, $respuesta, $rol_id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "✅ Registro exitoso. Inicia sesión.";
        header("Location: login.php");
    } else {
        $_SESSION['mensaje'] = "❌ Error al registrar el usuario.";
        header("Location: registro.php");
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
                                <input type="text" name="nid" class="form-control" required pattern="[0-9]+" title="Solo se permiten números">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nombres</label>
                                <input type="text" name="nombres" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Apellidos</label>
                                <input type="text" name="apellidos" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contraseña</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirmar Contraseña</label>
                                <input type="password" id="confirm_password" class="form-control" required>
                                <small id="error_contraseña" class="text-danger"></small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pregunta de Seguridad</label>
                                <select name="pregunta" class="form-control" required>
                                    <option value="Color favorito">¿Cuál es tu color favorito?</option>
                                    <option value="Mascota de la infancia">¿Cómo se llamaba tu primera mascota?</option>
                                    <option value="Ciudad de nacimiento">¿En qué ciudad naciste?</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Respuesta</label>
                                <input type="text" name="respuesta" class="form-control" required>
                            </div>
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
