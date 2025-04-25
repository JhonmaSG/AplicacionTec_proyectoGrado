<?php
include '../../../app/config/ConfiguracionBD/ConexionBD.php';

try {
    $query = "SELECT * FROM vista_materias_verbos";
    $result = $conn->query($query);
    $materias = [];

    while ($row = $result->fetch_assoc()) {
        $nombre_materia = $row['nombre_materia'];
        $creditos = $row['creditos'];
        $materias[] = [
            "nombre" => $nombre_materia,
            "creditos" => $creditos,
            "area" => $row['NombreArea'],
            "verbos" => array_map('intval', explode(',', $row['verbos_ids'])),
            "nombres_verbos" => array_map('trim', explode(',', $row['verbos'])),
            "id_area" =>  $row['id_area']
        ];
    }

    echo json_encode(array_values($materias), JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode(["error" => "Error al obtener los verbos: " . $e->getMessage()]);
}

$conn->close();
