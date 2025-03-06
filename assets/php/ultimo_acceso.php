<?php
session_start();
if (!isset($_SESSION['nid'])) {
    header("Location: /proyectoGrado/Login/Acceso/login.php");
    exit();
}

if (isset($_SESSION['ultimo_acceso'])) {
    $tiempo_inactivo = time() - $_SESSION['ultimo_acceso']; // Diferencia de tiempo

    if ($tiempo_inactivo > $_SESSION['duracion_sesion']) {
        session_unset();  // Limpiar variables de sesión
        session_destroy(); // Destruir la sesión
        header("Location: /proyectoGrado/Login/Acceso/login.php?mensaje=sesion_expirada"); // Redirigir con mensaje
        exit();
    } else {
        $_SESSION['ultimo_acceso'] = time(); // Refrescar el tiempo de sesión
    }
}
