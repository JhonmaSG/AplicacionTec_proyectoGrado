<?php
require_once '../assets/php/Auth.php';
require_once '../../app/config/ConfiguracionBD/ConexionBD.php';
require_once '../../app/config/Logger.php';
Logger::registrarAccesoModulo($conn, 'Insumos');
Auth::iniciarSesion();
Auth::redirigirSiNoAutenticado();
Auth::evitarCache();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visor de PDF con Menú Lateral</title>
    <link rel="icon" href="http://localhost/proyectoGrado/app/Acceso/img/icon_page.ico" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/styles_document.css">
    <link rel="stylesheet" href="../assets/css/styles_menu.css">
</head>

<body>

    <?php include '../assets/php/menu.php'; ?>

    <!-- Menú lateral -->
    <nav>
        <h2>Índice</h2>
        <ul>
            <li><a href="#" data-page="1">Insumos para la misión y visión Institucional</a></li>
            <li><a href="#" data-page="3">Insumos para el Factor Estudiantes</a></li>
            <li><a href="#" data-page="5">Insumos para el Factor Profesores</a></li>
            <li><a href="#" data-page="33">Insumos para el Factor Procesos Académicos</a></li>
            <li><a href="#" data-page="42">Insumos para el Factor Visibilidad Nacional e Internacional</a></li>
            <li><a href="#" data-page="80">Insumos para el Factor Investigación, Innovación y Creación</a></li>
            <li><a href="#" data-page="136">Insumos para el Factor Pertinencia e Impacto Social</a></li>
            <li><a href="#" data-page="140">Insumos para el Factor Bienestar Institucional</a></li>
            <li><a href="#" data-page="142">Insumos para el Factor Organización Administración y Gestión</a></li>
            <li><a href="#" data-page="145">Insumos para el Factor Recurso de Apoyo Académico e Infraestructura</a></li>
            <li><a href="#" data-page="147">Insumos para el Factor Autoevaluación y Cultura de Calidad</a></li>
            <li><a href="#" data-page="150">Insumos para el Factor Impacto de los Egresados en el Medio</a></li>
        </ul>
    </nav>

    <!-- Contenido principal -->
    <div class="content">
        <canvas id="pdf-render"></canvas>
        <div class="controls">
            <button id="prev-page">Anterior</button>
            <span id="page-num"></span> / <span id="page-count"></span>
            <button id="next-page">Siguiente</button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <script src="script.js"></script>
    <script src="/proyectoGrado/public/assets/js/script_menu.js"></script>
</body>

</html>