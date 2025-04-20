<?php
header("Content-Type: application/json"); // Asegurar formato JSON
include '../../../app/config/ConfiguracionBD/ConexionBD.php';

try {
    $query = "SELECT * FROM Verbo";
    $result = $conn->query($query);
    $verbos = [];

    while ($row = $result->fetch_assoc()) {
        $verbos[] = $row;
    }

    echo json_encode($verbos, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode(["error" => "Error al obtener los verbos: " . $e->getMessage()]);
}
