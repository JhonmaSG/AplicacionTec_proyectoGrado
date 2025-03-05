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







    <!-- Botón de tres rayas -->
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
    <script src="../assets/js/script_menu.js"></script>
</body>
</html>

