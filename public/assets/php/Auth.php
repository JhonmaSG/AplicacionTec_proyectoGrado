<?php
class Auth {
    const SUPERADMIN = 0;
    const ADMIN = 1;
    const LECTOR = 2;
    public static function iniciarSesion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function estaAutenticado() {
        self::iniciarSesion();

        if (!isset($_SESSION['nid']) || !isset($_SESSION['duracion_sesion'])) {
            return false;
        }

        if (isset($_SESSION['ultimo_acceso'])) {
            $tiempo_inactivo = time() - $_SESSION['ultimo_acceso'];

            if ($tiempo_inactivo > $_SESSION['duracion_sesion']) {
                session_unset();
                session_destroy();
                header("Location: /proyectoGrado/app/Acceso/login.php?mensaje=sesion_expirada");
                exit;
            } else {
                $_SESSION['ultimo_acceso'] = time();
            }
        }

        return true;
    }

    public static function redirigirSiNoAutenticado($ruta = '/proyectoGrado/app/Acceso/login.php') {
        if (!self::estaAutenticado()) {
            header("Location: $ruta");
            exit;
        }
    }

    public static function esSuperAdmin() {
        self::iniciarSesion();

        return isset($_SESSION['rol']) && $_SESSION['rol'] === self::SUPERADMIN;
    }

    public static function esAdmin() {
        self::iniciarSesion();

        return isset($_SESSION['rol']) && ($_SESSION['rol'] === self::ADMIN || $_SESSION['rol'] === self::SUPERADMIN);
    }

    public static function esLector() {
        self::iniciarSesion();
        return isset($_SESSION['rol']) && $_SESSION['rol'] === self::LECTOR;
    }

    public static function redirigirSiNoEsAdmin($ruta = '/proyectoGrado/app/Acceso/login.php') {
        if (!self::esAdmin()) {
            header("Location: $ruta");
            exit;
        }
    }

    public static function obtenerRol() {
        self::iniciarSesion();
        return $_SESSION['rol'] ?? null;
    }

    public static function evitarCache() {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }
}
