<?php
include '../../Login/ConfiguracionBD/ConexionBD.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
