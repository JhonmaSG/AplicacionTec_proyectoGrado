<?php
include '../../../app/config/ConfiguracionBD/ConexionBD.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_dato = $_POST["id_dato"];
    $anio = $_POST["anio"];
    $periodo = $_POST["periodo"];
    $semestre = $anio . "-" . $periodo;
    $inscritos = $_POST["inscritos"];
    $reprobados = $_POST["reprobados"];

    try {
        $stmt = $conn->prepare("UPDATE Datos SET periodo = ?, inscritos = ?, reprobados = ? WHERE Id_dato_materia = ?");
        $stmt->execute([$semestre, $inscritos, $reprobados, $id_dato]);

        echo json_encode(["success" => true, "message" => "Registro actualizado correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error al actualizar: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Solicitud no válida."]);
}
