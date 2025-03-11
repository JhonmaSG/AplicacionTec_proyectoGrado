<?php include '../assets/php/ultimo_acceso.php'; ?>
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
      <label for="area-filter">Filtrar por área:</label>
      <select id="area-filter"><!-- Opciones dinámicas --></select>

      <label for="carrera-filter">Filtrar por Carrera:</label>
      <select id="carrera-filter"><!-- Opciones dinámicas --></select>

      <label for="search-materia">Buscar materia:</label>
      <input type="text" id="search-materia" placeholder="Nombre de la materia">
    </div>

    <!-- Botones -->
    <div class="actions">
      <button id="show-chart" class="btn btn-info">Mostrar Gráfica</button>
      <button id="print-table" class="btn btn-info">Imprimir Tabla</button>
      <button id="fullscreen-chart" class="btn btn-info">Vista Fullscreen</button>

      <?php include 'includes/popup.php'; ?>
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
    <!--Paginación-->


    <!-- Contenedor para la gráfica -->
    <div id="chart-container" class="chart"></div>
    <div id="grafica-container" style="width:100%; height:700px;"></div>

    <button id="btnSubir" onclick="subirArriba()">↑</button>
  </div>
  <script src="script.js"></script>
  <script src="/proyectoGrado/assets/js/script_menu.js"></script>
</body>

</html>