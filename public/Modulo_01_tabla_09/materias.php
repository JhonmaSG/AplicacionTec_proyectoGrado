<?php
require_once '../assets/php/Auth.php';
require_once '../../app/config/ConfiguracionBD/ConexionBD.php';
require_once '../../app/config/Logger.php';
Logger::registrarAccesoModulo($conn, 'Materias');
Auth::iniciarSesion();
Auth::redirigirSiNoAutenticado();
Auth::evitarCache();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Visualización de Deserción</title>
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
    <h1>Visualización de Deserción por Materia</h1>
    <!-- Filtros -->
    <div class="filters">
      <div class="filter-item">
        <label for="area-filter"><b>Filtrar por área:</b></label>
        <select id="area-filter"></select>
      </div>

      <div class="filter-item">
        <label for="carrera-filter"><b>Tipo de Carrera:</b></label>
        <select id="carrera-filter"></select>
      </div>

      <div class="filter-item">
        <label for="semestre-filter"><b>Semestre:</b></label>
        <select id="semestre-filter">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
          <option value="8">8</option>
          <option value="9">9</option>
          <option value="10">10</option>
        </select>
      </div>

      <div class="filter-item">
        <label for="search-materia"><b>Buscar materia:</b></label>
        <input type="text" id="search-materia" placeholder="Nombre de la materia">
      </div>
    </div>


    <!-- Botones -->
    <div class="actions">
      <button id="show-chart" class="btn btn-info">Mostrar Gráfica</button>
      <button id="print-table" class="btn btn-info">Imprimir Tabla</button>
      <button id="fullscreen-chart" class="btn btn-info">Vista Fullscreen</button>

      <?php include 'includes/popup.php'; ?>
    </div>

    <!-- Tabla -->
    <div class="table-responsive-custom">
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
    </div>


    <!--Btn's Grafica-->
    <div class="text-center d-none mt-3" id="container-btn-grafica">
      <button data-filtro="materias" class="btn btn-primary btn-filtro">Por Materia</button>
      <button data-filtro="area" class="btn btn-info btn-filtro">Por Área</button>
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