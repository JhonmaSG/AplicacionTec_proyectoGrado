<?php
include '../../Login/ConfiguracionBD/ConexionBD.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $semestre = $_POST["anio"] . "-" . $_POST["periodo"]; // Combina año y período en el formato "YYYY-P"
    $nombre_materia = $_POST["nombre_materia"];
    $inscritos = $_POST["inscritos"];
    $reprobados = $_POST["reprobados"];
    try {
        $stmt = $conn->prepare("CALL InsertarDatosMateria(?, ?, ?, ?)");
        $stmt->execute([$semestre, $nombre_materia, $inscritos, $reprobados]);

        echo json_encode(["success" => true, "message" => "Datos guardados correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Solicitud no válida."]);
}
