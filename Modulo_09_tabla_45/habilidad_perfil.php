<?php include '../assets/php/ultimo_acceso.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tabla Interactiva Mejorada</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="../assets/css/styles_menu.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>

<body>

  <?php include '../assets/php/menu.php'; ?>

  <div class="container">
    <h1>Características vs Perfil Profesional</h1>

    <!-- Botones -->
    <div class="buttons">
      <button id="btnFullScreen" class="btn">Pantalla Completa</button>
      <button id="btnPDF" class="btn">Imprimir PDF</button>
      <button id="btnNightMode" class="btn">Modo Noche</button>
    </div>

    <div id="pdf-content">
      <table id="tabla">
        <thead>
          <tr>
            <th>Características / Perfil Profesional</th>
            <th>P1</th>
            <th>P2</th>
            <th>P3</th>
            <th>P4</th>
            <th>P5</th>
            <th>P6</th>
            <th>P7</th>
            <th>P8</th>
            <th>P9</th>
          </tr>
        </thead>
        <tbody>
          <tr data-description="Principios de diseño de software ayudan a construir soluciones informáticas.">
            <td>C1) Principios de diseño de software</td>
            <td class="clickable" data-justification="Esta característica está relacionada con la capacidad de construir soluciones informáticas.">✔️</td>
            <td></td>
            <td></td>
            <td class="clickable" data-justification="La capacidad de liderar procesos de software es relevante para esta habilidad.">✔️</td>
            <td></td>
            <td></td>
            <td></td>
            <td class="clickable" data-justification="Es importante en el contexto de la responsabilidad social y la ética.">✔️</td>
            <td></td>
          </tr>
          <tr data-description="Principios de diseño de ingeniería telemática facilitan la gestión del ciclo de vida de la información.">
            <td>C2) Principios de diseño de ingeniería telemática</td>
            <td class="clickable" data-justification="Está vinculada a la capacidad de construir soluciones informáticas específicas para la telemática.">✔️</td>
            <td></td>
            <td class="clickable" data-justification="Relacionada con la capacidad de gestionar el ciclo de vida de la información.">✔️</td>
            <td class="clickable" data-justification="Esta característica es útil para seleccionar tecnologías adecuadas en el campo de la telemática.">✔️</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="clickable" data-justification="Esta característica es clave para el diseño de procesos telemáticos.">✔️</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Descripciones -->
    <div id="description" class="description">
      <h3>Descripción de la Habilidad</h3>
      <p>Pasa el mouse sobre una fila para ver su descripción.</p>
    </div>
    <div id="justification" class="justification">
      <h3>Justificación</h3>
      <p>Haz clic en las celdas con el chulo para ver la justificación de la relación.</p>
    </div>
  </div>

  <script src="scripts.js"></script>
  <script src="/proyectoGrado/assets/js/script_menu.js"></script>
</body>

</html>