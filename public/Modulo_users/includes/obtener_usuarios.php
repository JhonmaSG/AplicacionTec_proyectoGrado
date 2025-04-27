<?php
session_start();
require_once '../../assets/php/Auth.php';
include '../../../app/config/ConfiguracionBD/ConexionBD.php';
Auth::redirigirSiNoAutenticado();

$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
$fecha_inicio = isset($_GET['fecha_inicio']) ? trim($_GET['fecha_inicio']) : '';
$fecha_fin = isset($_GET['fecha_fin']) ? trim($_GET['fecha_fin']) : '';
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

$usuarios = [];
$total = 0;

try {
    // Parámetros comunes
    $param = '%' . $busqueda . '%';
    error_log("Parámetros recibidos: busqueda='$busqueda', fecha_inicio='$fecha_inicio', fecha_fin='$fecha_fin'");

    // Construir condiciones dinámicas
    $where_conditions = ['rol_id IN (1, 2)'];
    $params = [];
    $param_types = '';

    if ($busqueda !== '') {
        $where_conditions[] = '(cedula LIKE ? OR CONCAT(nombres, \' \', apellidos) LIKE ?)';
        $params[] = $param;
        $params[] = $param;
        $param_types .= 'ss';
    }

    if ($fecha_inicio !== '' && $fecha_fin !== '') {
        $where_conditions[] = 'DATE(fecha_acceso) BETWEEN ? AND ?';
        $params[] = $fecha_inicio;
        $params[] = $fecha_fin;
        $param_types .= 'ss';
    } elseif ($fecha_inicio !== '') {
        $where_conditions[] = 'DATE(fecha_acceso) >= ?';
        $params[] = $fecha_inicio;
        $param_types .= 's';
    } elseif ($fecha_fin !== '') {
        $where_conditions[] = 'DATE(fecha_acceso) <= ?';
        $params[] = $fecha_fin;
        $param_types .= 's';
    }

    // Consulta de conteo
    $sqlCount = 'SELECT COUNT(*) FROM vista_usuarios_con_accesos';
    if (!empty($where_conditions)) {
        $sqlCount .= ' WHERE ' . implode(' AND ', $where_conditions);
    }

    $stmtCount = $conn->prepare($sqlCount);
    if (!empty($params)) {
        $stmtCount->bind_param($param_types, ...$params);
    }
    $stmtCount->execute();
    $stmtCount->bind_result($total);
    $stmtCount->fetch();
    $stmtCount->close();
    error_log("Total usuarios: $total");

    // Consulta de usuarios
    $sql = 'SELECT * FROM vista_usuarios_con_accesos';
    if (!empty($where_conditions)) {
        $sql .= ' WHERE ' . implode(' AND ', $where_conditions);
    }
    $sql .= ' LIMIT ? OFFSET ?';
    $param_types .= 'ii';
    $params[] = $limit;
    $params[] = $offset;

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($param_types, ...$params);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $usuarios[] = $fila;
        }
    }
    error_log("Usuarios devueltos: " . count($usuarios));

    echo json_encode([
        'success' => true,
        'usuarios' => $usuarios,
        'total' => $total
    ]);
} catch (Exception $e) {
    error_log("Error en obtener_usuarios.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Error al realizar la consulta: ' . $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
