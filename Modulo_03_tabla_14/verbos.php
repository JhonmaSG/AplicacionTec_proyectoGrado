<?php include '../assets/php/ultimo_acceso.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Relaciones Verbos - Materias - Áreas</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="../assets/css/styles_menu.css">
</head>

<body>

  <?php include '../assets/php/menu.php'; ?>

  <div class="container">
    <h1>Relaciones de Verbos, Materias y Áreas</h1>
    <!-- Filtros -->
    <div class="filters">
      <label for="area-filter">Área:</label>
      <select id="area-filter">
        <option value="Todos">Todos</option>
        <option value="Matemáticas">Matemáticas</option>
        <option value="Programación">Programación</option>
        <option value="Ciencias Básicas">Ciencias Básicas</option>
      </select>

      <label for="verb-filter">Verbo:</label>
      <select id="verb-filter">
        <option value="Todos">Todos</option>
        <option value="Guardar">Guardar</option>
        <option value="Borrar">Borrar</option>
        <option value="Sincronizar">Sincronizar</option>
        <option value="Actualizar">Actualizar</option>
      </select>

      <label for="multi-area-filter">Verbos en Varias Áreas:</label>
      <input type="checkbox" id="multi-area-filter">
    </div>

    <!-- Descripción del verbo -->
    <div id="verb-description" class="description">
      <p>Selecciona un verbo o una materia para ver más información.</p>
    </div>

    <!-- Tabla de datos -->
    <table id="data-table">
      <thead>
        <tr>
          <th>Materia</th>
          <th>Verbo</th>
          <th>Área</th>
        </tr>
      </thead>
      <tbody>
        <!-- Filas dinámicas -->
      </tbody>
    </table>
  </div>

  <script src="scripts.js"></script>
  <script src="/proyectoGrado/assets/js/script_menu.js"></script>
</body>

</html>