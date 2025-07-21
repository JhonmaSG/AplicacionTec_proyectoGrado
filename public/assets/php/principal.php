<?php
require_once './Auth.php';
Auth::iniciarSesion();
Auth::redirigirSiNoAutenticado();
Auth::evitarCache();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal SGPM UD</title>
    <link rel="stylesheet" href="../css/styles_principal.css">
    <link rel="stylesheet" href="../css/styles_menu.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="http://localhost/proyectoGrado/app/Acceso/img/icon_page.ico" type="image/x-icon">
</head>

<body>
    <?php include './menu.php'; ?>
    <div class="container">
        <h1 class="text-center my-4">Bienvenido a Sistema de Gestión de Información Pénsum de Materias UD</h1>

        <p class="intro-text text-center mb-5">
            Esta aplicación llamada <b>Sistema de Gestión de Información Pénsum de Materias UD</b> ha sido desarrollada para apoyar la sistematización, visualización y análisis de la información curricular de los programas de <strong>Tecnología en Sistematización de Datos</strong> e <strong>Ingeniería Telemática</strong> de la Universidad Francisco José de Caldas.
        </p>

        <h2 class="text-center mb-4 text-primary">Módulos del Sistema</h2>

        <div class="dashboard-grid">
            <a href="/proyectoGrado/public/Modulo_01_tabla_09/materias.php" class="dashboard-card">
                <h3>Materias</h3>
                <p>Gestión y organización de las materias académicas por:<br>Nombre, Carrera, Área, Semestre y Tasa de deserción.</p>
            </a>
            <a href="/proyectoGrado/public/Modulo_03_tabla_14/verbos.php" class="dashboard-card">
                <h3>Verbos</h3>
                <p>Administración de verbos cognitivos según niveles de aprendizaje.</p>
            </a>
            <a href="/proyectoGrado/public/Modulo_09_tabla_45/habilidad_perfil.php" class="dashboard-card">
                <h3>Características vs Perfil Profesional</h3>
                <p>Visualización de características en relación con el perfil profesional.</p>
            </a>
            <a href="/proyectoGrado/public/Modulo_20_ObjetivosVs_RA/objetivos_RA.php" class="dashboard-card">
                <h3>Objetivos de Programa y Resultados de Aprendizaje</h3>
                <p>Gestión de objetivos y resultados de aprendizaje de los programas.</p>
            </a>
            <a href="/proyectoGrado/public/Modulo_33_ProcesoGeneracionRA/index.php" class="dashboard-card">
                <h3>Documentación</h3>
                <p>Visualización de documentos de las carreras de TSD e IT.</p>
            </a>
            <a href="/proyectoGrado/public/Modulo_35_InsumosParaAutoevaluacion_2025/index.php" class="dashboard-card">
                <h3>Insumos</h3>
                <p>Visualización de InsumosAE 2025.</p>
            </a>
            <?php if (Auth::esAdmin()): ?>
                <a href="/proyectoGrado/public/Modulo_users/index.php" class="dashboard-card">
                    <h3>Ver usuarios</h3>
                    <p>Historial de usuarios</p>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <script src="/proyectoGrado/public/assets/js/script_menu.js"></script>
</body>

</html>