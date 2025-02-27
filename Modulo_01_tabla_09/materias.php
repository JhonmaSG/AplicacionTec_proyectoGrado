<?php
session_start();
if (!isset($_SESSION['nid'])) {
    header("Location: ../Login/Acceso/login.php");
    exit();
}

if (isset($_SESSION['ultimo_acceso'])) {
  $tiempo_inactivo = time() - $_SESSION['ultimo_acceso']; // Diferencia de tiempo

  if ($tiempo_inactivo > $_SESSION['duracion_sesion']) {
      session_unset();  // Limpiar variables de sesión
      session_destroy(); // Destruir la sesión
      header("Location: ../Login/Acceso/login.php?mensaje=sesion_expirada"); // Redirigir con mensaje
      exit();
  } else {
      $_SESSION['ultimo_acceso'] = time(); // Refrescar el tiempo de sesión
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Visualización de Deserción</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="styles_menu.css">
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="https://code.highcharts.com/modules/full-screen.js"></script>
</head>
<body>

<!-- Botón de cerrar sesión -->
<a href="../Login/Acceso/logout.php" class="logout-btn">Cerrar Sesión</a>


<!-- Menú -->
<button class="menu-btn" id="menu-btn">
  <div class="line"></div>
  <div class="line"></div>
  <div class="line"></div>
</button>

    <!-- Menú -->
    <div class="menu" id="menu">
      <!-- Primer opción de menú -->
      <a href="../tabla_09_Modulo_01/materias.html" class="menu-item">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M3 12h18"></path>
          <path d="M3 6h18"></path>
          <path d="M3 18h18"></path>
        </svg>
        Visualización de la Deserción
      </a>
      <!-- Otras opciones del menú -->
      <a href="../tabla_13_Modulo_02/resultados.html" class="menu-item">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M12 5v14"></path>
          <path d="M5 12h14"></path>
        </svg>
        Asignaturas y Resultados de Aprendizaje
      </a>
      <a href="../tabla_14_Modulo_03/verbos.html" class="menu-item">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <circle cx="12" cy="12" r="10"></circle>
        </svg>
        Verbos Materias y Areas
      </a>
      <a href="../tabla_19_Modulo_04/verbos.html" class="menu-item">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <circle cx="12" cy="12" r="10"></circle>
        </svg>
        Verbos Asociados a Areas
      </a>

      <a href="../tabla_21_Modulo_05/factores.html" class="menu-item">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <circle cx="12" cy="12" r="10"></circle>
        </svg>
        Factores de Acreditación
      </a>

      <a href="../tabla_27_Modulo_06/index.html" class="menu-item">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <circle cx="12" cy="12" r="10"></circle>
        </svg>
        Priorización Eisenhower
      </a>


      <a href="../tabla_29_CandidatoAModulo/plan.html" class="menu-item">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <circle cx="12" cy="12" r="10"></circle>
        </svg>
        Indicadores de Progreso del Plan de Mejoramiento
      </a>

      <a href="../tabla_34_Modulo_07/proyectos.html" class="menu-item">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <circle cx="12" cy="12" r="10"></circle>
        </svg>
        Proyectos del Plan de Mejoramiento
      </a>


      <a href="../tabla_37_Modulo_08/mejoramiento.html" class="menu-item">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <circle cx="12" cy="12" r="10"></circle>
        </svg>
        Resumen Informe de AutoEvaluación
      </a>

      <a href="../tabla_41_CandidatoAModulo/habilidad_perfil.html" class="menu-item">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <circle cx="12" cy="12" r="10"></circle>
        </svg>
        Caracteristicas VS Perfil Profesional
      </a>

      <a href="../tabla_45_Modulo_09/habilidad_perfil.html" class="menu-item">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <circle cx="12" cy="12" r="10"></circle>
        </svg>
        Perfiles Vs Habilidades
      </a>
    </div>







  <div class="container">
    <h1>Visualización de Deserción por Materia</h1>
    <!-- Filtros -->
    <div class="filters">
      <label for="area-filter">Filtrar por área:</label>
      <select id="area-filter">
        <!-- Opciones dinámicas -->
      </select>

      <label for="search-materia">Buscar materia:</label>
      <input type="text" id="search-materia" placeholder="Nombre de la materia">
    </div>

    <!-- Botones -->
    <div class="actions">
      <button id="show-chart" class="btn">Mostrar Gráfica</button>
      <button id="print-table" class="btn">Imprimir Tabla</button>
      <button id="fullscreen-chart" class="btn">Vista Fullscreen</button>
    </div>

    <!-- Tabla -->
    <table id="data-table" class="table">
      <thead>
        <tr>
          <th>Materia</th>
          <th>Área</th>
          <th>Semestre</th>
          <th>Inscritos</th>
          <th>Reprobados</th>
          <th>Tasa de Reprobación (%)</th>
        </tr>
      </thead>
      <tbody id="table-body">
        <!-- Filas dinámicas -->
      </tbody>
    </table>

    <!-- Contenedor para la gráfica -->
    <div id="chart-container" class="chart"></div>
    <div id="grafica-container" style="width:100%; height:400px;"></div>

  </div>

  <script src="script.js"></script>
  <script src="script_menu.js"></script>
</body>
</html>

