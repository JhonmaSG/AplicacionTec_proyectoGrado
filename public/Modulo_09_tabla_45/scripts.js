// Inicializar Eventos DOM
const clickableCells = document.querySelectorAll('.clickable');
const justificationElement = document.querySelector('#justification p');
const descriptionElement = document.querySelector('#description p');
const rows = document.querySelectorAll('#tabla tbody tr');
const btnFullScreen = document.getElementById('btnFullScreen');
const btnPDF = document.getElementById('btnPDF');
const btnNightMode = document.getElementById('btnNightMode');
const body = document.body;

function inicializarEventosDOM() {
  // Evento para impresión de tabla
  if (btnPDF) {
    btnPDF.addEventListener('click', imprimirTabla);
  } else {
    console.error('Elemento #btnPDF no encontrado en el DOM');
  }

  // Eventos para celdas clicables (justificaciones)
  clickableCells.forEach(cell => {
    cell.addEventListener('click', function () {
      clickableCells.forEach(c => c.classList.remove('active'));
      this.classList.add('active');
      const justificationText = this.getAttribute('data-justification');
      justificationElement.textContent = justificationText;
    });
  });

  // Eventos para descripciones al pasar el mouse
  rows.forEach(row => {
    row.addEventListener('mouseenter', function () {
      const descriptionText = this.getAttribute('data-description');
      descriptionElement.textContent = descriptionText;
    });
    row.addEventListener('mouseleave', function () {
      descriptionElement.textContent = 'Pasa el mouse sobre una fila para ver su descripción.';
    });
  });

  // Evento para pantalla completa
  if (btnFullScreen) {
    btnFullScreen.addEventListener('click', () => {
      const elem = document.documentElement;
      if (elem.requestFullscreen) {
        elem.requestFullscreen();
      }
    });
  } else {
    console.error('Elemento #btnFullScreen no encontrado en el DOM');
  }

  // Evento para modo noche
  if (btnNightMode) {
    btnNightMode.addEventListener('click', () => {
      body.classList.toggle('night-mode');
    });
  } else {
    console.error('Elemento #btnNightMode no encontrado en el DOM');
  }
}

// Ejecutar la inicialización al cargar la página
document.addEventListener("DOMContentLoaded", inicializarEventosDOM);

// Imprimir Tabla
function imprimirTabla() {
  const printWindow = window.open('', '', 'height=600,width=800');
  const table = document.getElementById('tabla').cloneNode(true);

  const ahora = new Date();
  const opciones = {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false
  };
  const fechaHora = ahora.toLocaleString('es-ES', opciones);

  printWindow.document.write(`
    <html>
    <head>
      <title>Características vs Perfil Profesional</title>
      <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        h1 { text-align: center; margin-bottom: 10px; }
        .fecha-hora { text-align: center; font-size: 14px; color: #555; margin-bottom: 20px; }
      </style>
    </head>
    <body>
      <h1>Características vs Perfil Profesional</h1>
      <p class="fecha-hora">Generado el ${fechaHora}</p>
      ${table.outerHTML}
    </body>
    </html>
  `);
  printWindow.document.close();
  printWindow.print();
}