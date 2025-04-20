<?php
session_start();
require_once '../../assets/php/Auth.php';
include '../../../app/config/ConfiguracionBD/ConexionBD.php';
Auth::redirigirSiNoAutenticado();

$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';

$usuarios = [];

try {
    if ($busqueda !== '') {
        $sql = "SELECT * FROM vista_usuarios_con_accesos 
        WHERE (cedula LIKE ? OR CONCAT(nombres, ' ', apellidos) LIKE ?) 
        AND Nombres != 'Admin'";

        $stmt = $conn->prepare($sql);
        $param = '%' . $busqueda . '%';
        $stmt->bind_param("ss", $param, $param);
        $stmt->execute();
        $resultado = $stmt->get_result();
    } else {
        $sql = "SELECT * FROM vista_usuarios_con_accesos";
        $resultado = $conn->query($sql);
    }

    // Verificar que la consulta tenga resultados
    if ($resultado && $resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $usuarios[] = $fila;
        }
    }
    echo json_encode([
        'success' => true,
        'usuarios' => $usuarios
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Hubo un error al realizar la consulta: ' . $e->getMessage()
    ]);
} finally {
    $conn->close();
}
