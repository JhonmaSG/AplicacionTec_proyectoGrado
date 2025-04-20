<?php
require_once __DIR__ . '/Auth.php';
Auth::iniciarSesion();
$nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Invitado';
$apellido = isset($_SESSION['apellido']) ? $_SESSION['apellido'] : '';
$usuario = $nombre . " " . $apellido;
?>

<script src="/proyectoGrado/public/assets/js/cerrar_sesion.js"></script>

<!-- Menú -->
<div class="top-bar">
    <button class="menu-btn" id="menu-btn">☰ Menú</button>

    <a href="/proyectoGrado/public/assets/php/principal.php" class="user-btn text-center" style="text-decoration: none;">
        <b>👤 Bienvenido/a <?php echo htmlspecialchars(strtoupper($usuario)); ?></b>
    </a>


    <button class="logout-btn" id="logout-btn" onclick="cerrarSesion()">🚪 Cerrar Sesión</button>
</div>

<!-- Módulos -->
<div class="menu" id="menu">
    <!-- Primer opción de menú -->
    <a href="/proyectoGrado/public/Modulo_01_tabla_09/materias.php" class="menu-item">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M3 12h18"></path>
            <path d="M3 6h18"></path>
            <path d="M3 18h18"></path>
        </svg>
        Visualización de la Deserción por Materia
    </a>
    <!-- Otras opciones del menú -->
    <a href="/proyectoGrado/public/Modulo_03_tabla_14/verbos.php" class="menu-item">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <circle cx="12" cy="12" r="10"></circle>
        </svg>
        Verbos Materias y Areas
    </a>

    <a href="/proyectoGrado/public/Modulo_09_tabla_45/habilidad_perfil.php" class="menu-item">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <circle cx="12" cy="12" r="10"></circle>
        </svg>
        Características vs Perfil Profesional
    </a>
    <!--Objetivos de Programa y Resultados de Aprendizaje-->
    <a href="/proyectoGrado/public/Modulo_20_ObjetivosVs_RA/objetivos_RA.php" class="menu-item">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <circle cx="12" cy="12" r="10"></circle>
        </svg>
        Objetivos de Programa y Resultados de Aprendizaje
    </a>
    <!--Documentación-->
    <a href="/proyectoGrado/public/Modulo_33_ProcesoGeneracionRA/index.php" class="menu-item">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <circle cx="12" cy="12" r="10"></circle>
        </svg>
        Documentación
    </a>
    <!--Insumos-->
    <a href="/proyectoGrado/public/Modulo_35_InsumosParaAutoevaluacion_2025/index.php" class="menu-item">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <circle cx="12" cy="12" r="10"></circle>
        </svg>
        Insumos
    </a>
    <!--Ver usuarios-->
    <?php if (Auth::esAdmin()): ?>
        <a href="/proyectoGrado/public/Modulo_users/index.php" class="menu-item">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="12" cy="12" r="10"></circle>
            </svg>
            Ver Usuarios
        </a>
    <?php endif; ?>
</div>