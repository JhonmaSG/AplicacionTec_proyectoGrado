<?php
//session_start();
include '../../Login/ConfiguracionBD/ConexionBD.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Access-Control-Allow-Origin: *');

header('Content-Type: application/json'); // Asegura que la respuesta sea JSON
// Consulta para obtener las áreas

$query = "SELECT Id_area, nombre FROM Area";
$result = $conn->query($query);

$areas = [];

while ($row = $result->fetch_assoc()) {
    $areas[] = $row;
}

$conn->close();

echo json_encode($areas, JSON_UNESCAPED_UNICODE);
exit();
