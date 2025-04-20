<?php
require_once '../assets/php/Auth.php';
require_once '../../app/config/ConfiguracionBD/ConexionBD.php';
require_once '../../app/config/Logger.php';
Logger::registrarAccesoModulo($conn, 'Verbos');
Auth::iniciarSesion();
Auth::redirigirSiNoAutenticado();
Auth::evitarCache();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Relaciones Verbos - Materias - Áreas</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="../assets/css/styles_menu.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script src="https://code.highcharts.com/12.1/highcharts.js"></script>
  <script src="https://code.highcharts.com/12.1/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/12.1/highcharts-more.js"></script>
  <script src="https://code.highcharts.com/modules/full-screen.js"></script>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

  <?php include '../assets/php/menu.php'; ?>

  <div class="container">
    <h1>Relaciones de Verbos, Materias y Áreas</h1>
    <!-- Filtros -->
    <div class="filters">
      <div class="filter-item">
        <label for="area-filter"><b>Filtrar por área:</b></label>
        <select id="area-filter"></select>
      </div>

      <div class="filter-item">
        <label for="verb-filter"><b>Verbo:</b></label>
        <select id="verb-filter"></select>
      </div>

      <div class="filter-item text-center">
        <label for="multi-area-filter"><b>Verbos en Varias Áreas:</b></label>
        <input type="checkbox" id="multi-area-filter" class="checkbox-filter">
      </div>
    </div>

    <!-- Botones -->
    <div class="actions">
      <button id="show-chart" class="btn btn-info">Mostrar Gráfica</button>
      <button id="print-table" class="btn btn-info">Imprimir Tabla</button>
      <button id="fullscreen-chart" class="btn btn-info">Vista Fullscreen</button>
      <?php include 'includes/popup_verbo.php'; ?>
    </div>

    <!-- Descripción del verbo -->
    <div id="verb-description" class="description list-group mt-2">
      <center>
        <h4>Verbos asociados:</h4>
        <br>
        <h6> Selecciona un verbo o una materia para ver más información.</h6>
      </center>
    </div>

    <!-- Tabla -->
    <div class="table-responsive-custom">
      <table id="data-table" class="table">
        <thead>
          <tr>
            <th>Materia</th>
            <th>Creditos</th>
            <th class="d-flex flex-wrap gap-1">Verbo</th>
            <th>Área</th>
            <?php if (Auth::esAdmin()): ?>
              <th>Acción</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody id="table-body">
          <!-- Filas dinámicas -->
        </tbody>
      </table>
    </div>

    <!--Btn's Grafica-->
    <div class="text-center d-none mt-3" id="container-btn-grafica">
      <button data-filtro="materias" class="btn btn-primary btn-filtro">Verbos x Materia</button>
      <button data-filtro="areas" class="btn btn-info btn-filtro">Verbos x Área</button>
    </div>

    <!-- Contenedor para la gráfica -->
    <div id="chart-container" class="chart"></div>
    <div id="grafica-container"></div>

    <button id="btnSubir">↑</button>
  </div>

  <script type="module" src="/proyectoGrado/public/assets/js/desplazamiento.js"></script>
  <script type="module" src="script.js"></script>
  <script src="/proyectoGrado/public/assets/js/script_menu.js"></script>
</body>

</html>