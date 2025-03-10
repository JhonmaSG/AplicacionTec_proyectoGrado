<?php

include '../../Login/ConfiguracionBD/ConexionBD.php';
// Verificar conexión


// Obtener la consulta del usuario
$query = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

$sql = "SELECT * FROM Materia WHERE nombre_materia LIKE '%$query%' LIMIT 10";
$resultado = $conn->query($sql);

$materias = [];
while ($fila = $resultado->fetch_assoc()) {
    $materias[] = [
        "id" => $fila['Id_materia'],
        "nombre" => $fila['nombre_materia']
    ];
}

// Enviar resultado en formato JSON
echo json_encode($materias,  JSON_UNESCAPED_UNICODE);

// Cerrar conexión
$conn->close();
