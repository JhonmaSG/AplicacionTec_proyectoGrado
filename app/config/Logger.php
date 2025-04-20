<?php
session_start();

class Logger {
    public static function registrarAccesoModulo($conn, $modulo) {
        if (!isset($_SESSION['nid'])) {
            return;
        }

        $nid = $_SESSION['nid'];

        $sql = "INSERT INTO registro_accesos (nid_usuario, modulo) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("is", $nid, $modulo);
            $stmt->execute();
            $stmt->close();
        }
    }
}
