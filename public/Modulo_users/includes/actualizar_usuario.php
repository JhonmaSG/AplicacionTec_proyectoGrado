<?php
require_once '../../../app/config/ConfiguracionBD/ConexionBD.php';

header('Content-Type: application/json');

$response = ['success' => false];

// Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['error'] = 'Método no permitido.';
    echo json_encode($response);
    exit;
}

// Obtener datos del formulario
$nid = $_POST['nid'] ?? '';
$rol_id = $_POST['rol'] ?? '';

// Validación
if (empty($nid) || empty($rol_id)) {
    $response['error'] = 'Datos incompletos.';
    echo json_encode($response);
    exit;
}

if (!in_array($rol_id, ['1', '2'])) {
    $response['error'] = 'Rol inválido.';
    echo json_encode($response);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE usuarios SET rol_id = ? WHERE nid = ?");
    $stmt->execute([$rol_id, $nid]);

    $response['success'] = true;
} catch (PDOException $e) {
    $response['error'] = 'Error en la base de datos: ' . $e->getMessage();
}

echo json_encode($response);
