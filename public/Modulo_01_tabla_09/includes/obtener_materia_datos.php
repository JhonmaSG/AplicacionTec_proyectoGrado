<?php
include '../../../app/config/ConfiguracionBD/ConexionBD.php';

$materias = [];

// Consulta
$query = "SELECT * FROM vista_materias_ordenadas";

$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    $id_materia = $row['Id_materia'];

    if (!isset($materias[$id_materia])) {
        $materias[$id_materia] = [
            "id_materia" => $id_materia,
            "nombre" => $row['nombre_materia'],
            "area" => $row['nombre_area'],
            "semestre" => $row['semestre'],
            "id_carrera" => $row['Id_carrera'],
            "id_area_materia" => $row['id_area_materia'],
            "datos" => []
        ];
    }

    $materias[$id_materia]["datos"][] = [
        "id_dato_materia" => $row['Id_dato_materia'],
        "periodo" => $row['periodo'],
        "inscritos" => $row['inscritos'],
        "reprobados" => $row['reprobados'],
        "tasa_reprobacion" => $row['tasa_reprobacion']
    ];
}

$conn->close();
echo json_encode(array_values($materias), JSON_UNESCAPED_UNICODE);
