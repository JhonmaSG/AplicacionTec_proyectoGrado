<?php
$host = "127.0.0.1:3307";
$user = "root";
$pass = "";
$dbname = "proyecto_grado_ud";

try {
    $conn = new mysqli($host, $user, $pass, $dbname);
    $conn->set_charset("utf8");
} catch (mysqli_sql_exception $e) {
    die("Error de conexión: " . $e->getMessage());
}
