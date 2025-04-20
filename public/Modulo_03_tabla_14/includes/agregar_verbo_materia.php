<?php
include '../../../app/config/ConfiguracionBD/ConexionBD.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST["nombreMateria"]) || empty($_POST["nombreMateria"])) {
        echo json_encode(["success" => false, "message" => "El nombre de la materia es obligatorio."]);
        exit;
    }

    if (!isset($_POST["verbo_id"]) || empty($_POST["verbo_id"])) {
        //echo json_encode(["success" => false, "message" => "Debe seleccionar al menos un verbo."]);
        exit;
    }

    $nombre_materia = trim($_POST["nombreMateria"]); // Capturar la materia
    $verbo_ids = explode(",", $_POST["verbo_id"]); // Convertir la lista de IDs en un array

    // Insertar cada verbo asociado a la materia
    foreach ($verbo_ids as $verbo_id) {
        $verbo_id = intval($verbo_id); // Asegurar que es un número
        if ($verbo_id > 0) {
            $stmt = $conn->prepare("CALL sp_insertar_materia_verbo(?, ?)");
            $stmt->execute([$nombre_materia, $verbo_id]);
        }
    }

    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
}
