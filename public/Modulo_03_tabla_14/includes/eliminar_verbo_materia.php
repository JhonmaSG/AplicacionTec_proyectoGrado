<?php
include '../../../app/config/ConfiguracionBD/ConexionBD.php';

$data = json_decode(file_get_contents("php://input"), true);

$id_verbo = isset($data['id_verbo']) ? intval($data['id_verbo']) : null;
$id_materia = isset($data['id_materia']) ? intval($data['id_materia']) : null;

if (!$id_verbo || !$id_materia) {
    echo json_encode(["success" => false, "message" => "Faltan datos requeridos"]);
    exit;
}

$query = "DELETE FROM materiaverbo WHERE id_verbo = ? AND id_materia = ?";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("ii", $id_verbo, $id_materia);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "No se eliminó ningún registro"]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Error en la consulta"]);
}

$conn->close();
