<?php
include '../../../app/config/ConfiguracionBD/ConexionBD.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Consulta obtener todas las carreras
$query = "SELECT Id_carrera, nombre FROM Carrera";
$result = $conn->query($query);

$carreras = [];

while ($row = $result->fetch_assoc()) {
    $carreras[] = $row;
}

$conn->close();

echo json_encode($carreras, JSON_UNESCAPED_UNICODE);
exit();
