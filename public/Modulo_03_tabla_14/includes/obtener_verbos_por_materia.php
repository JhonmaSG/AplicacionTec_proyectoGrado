<?php
include '../../../app/config/ConfiguracionBD/ConexionBD.php';

if (!isset($_GET['idMateria'])) {
    http_response_code(400);
    echo json_encode(["error" => "idMateria no fue enviado"]);
    exit;
}

$nombreMateria = $_GET['idMateria'];
// Obtener ID de la materia
if (!$nombreMateria) {
    http_response_code(400);
    echo json_encode(["error" => "idMateria no fue enviado"]);
    exit;
}

$query = "SELECT Id_materia FROM materia WHERE nombre_materia LIKE ?";
$stmt = $conn->prepare($query);

// Agrega los comodines para el LIKE
$nombreMateriaLike = '%' . $nombreMateria . '%';
$stmt->bind_param("s", $nombreMateriaLike);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo json_encode(["errorrrrr" => $nombreMateria]);
    exit;
}

$id_materia = $row["Id_materia"];

// Obtener verbos asociados
$queryVerbos = "SELECT v.Id_verbo AS id_verbo, v.nombre 
                FROM materiaverbo vm
                JOIN verbo v ON vm.id_verbo = v.Id_verbo
                WHERE vm.id_materia = ?";
$stmt = $conn->prepare($queryVerbos);
$stmt->bind_param("i", $id_materia);
$stmt->execute();
$result = $stmt->get_result();
$verbos = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    "id_materia" => $id_materia,
    "verbos" => $verbos
]);
