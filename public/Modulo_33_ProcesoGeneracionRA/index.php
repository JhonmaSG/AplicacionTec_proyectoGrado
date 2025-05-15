<?php
require_once '../assets/php/Auth.php';
require_once '../../app/config/ConfiguracionBD/ConexionBD.php';
require_once '../../app/config/Logger.php';
Logger::registrarAccesoModulo($conn, 'Documentos');
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
            <li><a href="#" data-page="2">Tabla Contenido</a></li>
            <li><a href="#" data-page="11">Justificación</a></li>
            <li><a href="#" data-page="15">Introducción</a></li>
            <li><a href="#" data-page="19">GESTIÓN 2022Conclusión</a></li>
            <li><a href="#" data-page="20">Contexto 2022</a></li>
            <li><a href="#" data-page="34">Pedagogía 2022</a></li>
            <li><a href="#" data-page="36">Perfil 2022</a></li>
            <li><a href="#" data-page="53">TSD vs IT 2022</a></li>
            <li><a href="#" data-page="62">Necesidad: RA</a></li>
            <li><a href="#" data-page="67">Equipo 2022</a></li>
            <li><a href="#" data-page="71">Pensamiento</a></li>
            <li><a href="#" data-page="76">Posiciones</a></li>
            <li><a href="#" data-page="78">Enfoque 1</a></li>
            <li><a href="#" data-page="83">Propuesta 1 RA</a></li>
            <li><a href="#" data-page="85">Artefactos Ing</a></li>
            <li><a href="#" data-page="99">Entregables Grado</a></li>
            <li><a href="#" data-page="100">Metas semestrales</a></li>
            <li><a href="#" data-page="103">Detalle Artefactos</a></li>
            <li><a href="#" data-page="170">Comienzo del Trabajo</a></li>
            <li><a href="#" data-page="173">Objetos de Estudio</a></li>
            <li><a href="#" data-page="175">Términos según entorno</a></li>
            <li><a href="#" data-page="186">Término Dato</a></li>
            <li><a href="#" data-page="188">Propuesta 1 Perfil</a></li>
            <li><a href="#" data-page="194">Propuesta 1 RA</a></li>
            <li><a href="#" data-page="196">Metas semestrales vs Perfil</a></li>
            <li><a href="#" data-page="199">Primer Sistema Información</a></li>
            <li><a href="#" data-page="204">Aproximación Diseño Curricular</a></li>
            <li><a href="#" data-page="211">GESTIÓN 2023</a></li>
            <li><a href="#" data-page="218">Herramientas conceptuales</a></li>
            <li><a href="#" data-page="228">Segundo sistema información 2023</a></li>
            <li><a href="#" data-page="229">Pensamientos</a></li>
            <li><a href="#" data-page="230">Propedéutico</a></li>
            <li><a href="#" data-page="231">Ciencias básicas</a></li>
            <li><a href="#" data-page="232">Básica de Ingeniería</a></li>
            <li><a href="#" data-page="233">Ingeniería Aplicada</a></li>
            <li><a href="#" data-page="234">Socio Humanística</a></li>
            <li><a href="#" data-page="235">Propedéutico vs Básicas</a></li>
            <li><a href="#" data-page="236">Propedéutico vs Ing</a></li>
            <li><a href="#" data-page="240">Propedéutico vs IngAplicada</a></li>
            <li><a href="#" data-page="245">RA versión 2</a></li>
            <li><a href="#" data-page="317">Sistema 3 Contenidos</a></li>
            <li><a href="#" data-page="317">Resultados por semestre</a></li>
            <li><a href="#" data-page="341">GESTIÓN 2024</a></li>
            <li><a href="#" data-page="342">Sistema 4 Egresados</a></li>
            <li><a href="#" data-page="396">Modelo pedagógico Versión 1</a></li>
            <li><a href="#" data-page="407">Eurace charla</a></li>
            <li><a href="#" data-page="419">Taller Perfiles</a></li>
            <li><a href="#" data-page="421">Versión 1: Objetivos Programa</a></li>
            <li><a href="#" data-page="443">Necesidades TIC Colombia</a></li>
            <li><a href="#" data-page="448">Análisis Syllabus con LN</a></li>
            <li><a href="#" data-page="455">RA Versión 2</a></li>
            <li><a href="#" data-page="518">Sistema 4: Contexto Areas</a></li>
            <li><a href="#" data-page="528">RA: Versión 3</a></li>
            <li><a href="#" data-page="534">RA vs Espacios Versión 4</a></li>
            <li><a href="#" data-page="543">Bibliografía</a></li>
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