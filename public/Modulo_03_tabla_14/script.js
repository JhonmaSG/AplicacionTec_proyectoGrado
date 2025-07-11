/////////////////////////////////////////////////////////////////////
import { subirArriba, desplazamientoGrafica } from '/proyectoGrado/public/assets/js/desplazamiento.js';
// Variables GLOBALES
let state = {
  materias: [],
  tipoFiltro: 'materias',
  mostrarGrafica: false
};
// Variables para la paginación
let evitarDesplazamientoGlobal = true;
let limit = 20;  // Valor inicial
let offset = 0;
let currentPage = 1;
let totalPages = 1;

// Inicializar Eventos DOM
const tableBody = document.getElementById("table-body");
const areaFilter = document.getElementById('area-filter');
const verbFilter = document.getElementById('verb-filter');
const multiAreaFilter = document.getElementById('multi-area-filter');
const verbDescription = document.getElementById('verb-description');
const graficaContainer = document.getElementById("grafica-container");
const container = document.querySelector('.container');
const btnSubir = document.getElementById("btnSubir");


function inicializarEventosDOM() {
  // Evento para impresión de tabla
  document.getElementById('print-table').addEventListener('click', imprimirTabla);

  // Configurar eventos para btnSubir
  if (btnSubir) {
    // Evento para el clic del botón
    btnSubir.addEventListener('click', subirArriba);

    // Mostrar/Ocultar botón según el desplazamiento del contenedor o ventana
    const scrollElement = container && container.scrollHeight > container.clientHeight ? container : window;
    scrollElement.addEventListener('scroll', () => {
      const scrollPosition = scrollElement === window ? window.scrollY : container.scrollTop;
      if (scrollPosition > 100) { // Mostrar botón después de 100px de desplazamiento
        btnSubir.style.display = 'block';
      } else {
        btnSubir.style.display = 'none';
      }
    });
  } else {
    console.error('Elemento #btnSubir no encontrado en el DOM');
  }

  // Evento para activar pantalla completa en la gráfica
const fullscreenButton = document.getElementById("fullscreen-chart");
if (fullscreenButton) {
  fullscreenButton.addEventListener("click", () => {
    // Verificar si la página está en un iframe
    if (window.self !== window.top) {
      alert("El modo de pantalla completa no está soportado dentro de un iframe. Por favor, abre la página directamente o contacta al administrador.");
      return;
    }

    // Verificar si la gráfica existe
    if (!graficaContainer) {
      alert("El contenedor de la gráfica no está disponible. Por favor, muestra la gráfica primero.");
      return;
    }

    // Activar pantalla completa
    if (graficaContainer.requestFullscreen) {
      graficaContainer.requestFullscreen();
    } else if (graficaContainer.webkitRequestFullscreen) { // Safari
      graficaContainer.webkitRequestFullscreen();
    } else if (graficaContainer.mozRequestFullScreen) { // Firefox
      graficaContainer.mozRequestFullScreen();
    } else {
      alert("El modo de pantalla completa no está soportado en este navegador.");
    }
  });
}

  // Evento para mostrar la gráfica con datos filtrados
  const showChartButton = document.getElementById("show-chart");
  if (showChartButton) {
    showChartButton.addEventListener("click", () => {
      state.mostrarGrafica = true;
      state.tipoFiltro = 'materias';
      evitarDesplazamientoGlobal = false;
      graficaContainer.classList.remove('d-none');
      filtrarMaterias();
      graficaContainer.scrollIntoView({ behavior: "smooth" });
    });
  }

  // Eventos de botones dinamicos de filtro Graphics
  document.querySelectorAll(".btn-filtro").forEach(btn => {
    btn.addEventListener("click", function () {
      state.tipoFiltro = this.getAttribute("data-filtro");
      filtrarMaterias();
    });
  });

  areaFilter.addEventListener("change", filtrarMaterias);
  verbFilter.addEventListener("change", filtrarMaterias);

  // Evento para el selector de registros por página
  const limitSelect = document.getElementById("limit-select");
  if (limitSelect) {
    limitSelect.addEventListener("change", () => {
      limit = parseInt(limitSelect.value);
      currentPage = 1; // Reiniciar a la primera página
      offset = 0;
      filtrarMaterias();
    });
  }

  //Evento para filtrar materias en el apartado de los datos
  buscador_materias_datos("nombre-materia", "http://localhost/proyectoGrado/public/Modulo_01_tabla_09/includes/buscar_materia.php");

  // Cargar áreas, verbos y materias
  cargarAreas();
  cargarVerbos("verb-filter_popup");
  cargarVerbos("verb-filter");

  cargarMateriaVerbo();

  // Modal
  configurarModal();
}


// START DOM
document.addEventListener("DOMContentLoaded", inicializarEventosDOM);

function configurarModal() {
  let modal = document.getElementById("modal-datos");
  let btn = document.getElementById("btnAbrir");
  function cerrarModal() {
    modal.style.display = "none";
  }

  window.onclick = function (event) {
    if (event.target == modal) {
      cerrarModal();
    }
  };
}
function imprimirTabla() {
  const printWindow = window.open('', '', 'height=800,width=1000');
  const table = document.getElementById('data-table').cloneNode(true);

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

  // Obtener la configuración de la gráfica actual
  let chartConfig = null;
  const chart = Highcharts.charts.find(chart => chart && chart.renderTo === graficaContainer);
  if (chart) {
    chartConfig = JSON.parse(JSON.stringify(chart.userOptions)); // Clonar la configuración
    chartConfig.chart = chartConfig.chart || {};
    chartConfig.chart.renderTo = 'print-grafica-container';
    chartConfig.chart.animation = false; // Desactivar animaciones
    chartConfig.series = chartConfig.series.map(series => ({
      ...series,
      animation: false // Desactivar animaciones en series
    }));
    // Añadir evento load para la gráfica
    chartConfig.chart.events = chartConfig.chart.events || {};
    chartConfig.chart.events.load = function() {
      window.focus(); // Enfocar la ventana
      window.print(); // Abrir diálogo de impresión
    };
  }

  printWindow.document.write(`
    <html>
    <head>
      <title>Tasa de Deserción por Materia</title>
      <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        h1 { text-align: center; margin-bottom: 10px; }
        .fecha-hora { text-align: center; font-size: 14px; color: #555; margin-bottom: 20px; }
        #print-grafica-container {
          width: 100%;
          min-height: 400px;
          height: auto;
          margin-top: 20px;
          box-sizing: border-box;
        }
        @media print {
          body { margin: 0.5in; }
          h1, .fecha-hora { page-break-after: avoid; }
          table { page-break-inside: auto; width: 100%; }
          #print-grafica-container {
            page-break-before: auto; /* Permitir que siga a la tabla */
            page-break-inside: avoid; /* Evitar dividir la gráfica */
            width: 100% !important;
            height: auto !important;
            min-height: 400px;
            max-height: 600px; /* Ajustar al alto útil de A4 */
            box-sizing: border-box;
            overflow: visible;
          }
          .highcharts-container, .highcharts-container svg {
            width: 100% !important;
            height: auto !important;
            max-height: 600px !important;
          }
          @page { size: A4; margin: 0.5in; }
        }
      </style>
      <!-- Cargar Highcharts -->
      <script src="https://code.highcharts.com/12.1/highcharts.js"></script>
      <script src="https://code.highcharts.com/12.1/modules/exporting.js"></script>
      <script src="https://code.highcharts.com/12.1/highcharts-more.js"></script>
    </head>
    <body>
      <h1>Relaciones de Verbos, Materias y Áreas</h1>
      <p class="fecha-hora">Generado el ${fechaHora}</p>
      ${table.outerHTML}
      <div id="print-grafica-container"></div>
      <script>
        // Renderizar la gráfica o mostrar mensaje
        ${chartConfig ? `
          Highcharts.chart('print-grafica-container', ${JSON.stringify(chartConfig)});
          // Respaldo con setTimeout por si el evento load falla
          setTimeout(() => {
            window.focus();
            window.print();
          }, 1000);
        ` : `
          document.getElementById('print-grafica-container').innerHTML = '<p style="text-align: center; color: #555;">No se encontró una gráfica para imprimir.</p>';
          window.focus();
          window.print();
        `}
      </script>
    </body>
    </html>
  `);
  printWindow.document.close();
}
///////////////////////////////////////////////////////////////////
// Función para preparar datos de la gráfica desde la tabla
function generarDatosGrafica(materiasFiltradas) {
  return materiasFiltradas.map(materia => ({
    name: materia.nombre,
    y: materia.verbos.length,
    creditos: materia.creditos,
    verbos: materia.nombres_verbos
  }));
}

// Función para actualizar la gráfica cada que hay petición
function actualizarGrafica(materiasFiltradas) {
  if (!state.mostrarGrafica) return; // Evitar generar gráfica si no se ha activado
  // Caso: No hay datos para graficar
  if (!materiasFiltradas || materiasFiltradas.length === 0) {
    graficaContainer.innerHTML = '<center><p>No se encontraron datos para graficar</p></center>';
    return;
  }

  let tipoChart = 'bar';
  let tituloX = '';
  let tituloY = 'Cantidad de Verbos';
  let categorias = [];
  let series = [];

  switch (state.tipoFiltro) {
    case 'materias':
      tituloX = 'Materias';
      const datosMaterias = generarDatosGrafica(materiasFiltradas);
      categorias = datosMaterias.map(d => d.name);
      series = [{
        name: 'Verbos por Materia',
        data: datosMaterias
      }];
      break;
    case 'areas':
      tituloX = 'Áreas';
      const datosAreas = datosArea(materiasFiltradas);
      categorias = datosAreas.map(d => d.name);
      series = [{
        name: 'Verbos por Área',
        data: datosAreas
      }];
      break;
  }

  // Tooltip dinámico según tipo de filtro
  let tooltipConfig = state.tipoFiltro === 'materias' ? {
    formatter: function () {
      const punto = this.point;
      const verbos = Array.isArray(punto.verbos) ? punto.verbos.join(', ') : 'N/D';
      return `<b>${this.series.name}</b><br>
              <b>${state.tipoFiltro === 'materias' ? 'Materia' : 'Área'}:</b> ${punto.name}<br>
              ${state.tipoFiltro === 'materias' ? `<b>Créditos:</b> ${punto.creditos ?? 'N/D'}<br>` : ''}
              <b>Cantidad de Verbos:</b> ${punto.y}<br>
              <b>Verbos:</b> ${verbos}`;
    }
  } : {
    formatter: function () {
      const punto = this.point;
      const verbos = Array.isArray(punto.verbos) ? punto.verbos.join(', ') : 'N/D';
      return `<b>${this.series.name}</b><br>
              <b>Área:</b> ${punto.name}<br>
              <b>Cantidad de Verbos:</b> ${punto.y}<br>
              <b>Verbos:</b> ${verbos}`;
    }
  };


  // Destruir gráfica anterior si existe
  if (Highcharts.charts && Highcharts.charts.length > 0) {
    Highcharts.charts.forEach(chart => {
      if (chart && chart.renderTo === graficaContainer) {
        chart.destroy();
      }
    });
  }

  Highcharts.chart(graficaContainer, {
    chart: { type: tipoChart },
    title: { text: 'Cantidad de Verbos por ' + (state.tipoFiltro === 'materias' ? 'Materia' : 'Áreas') },
    xAxis: {
      categories: categorias,
      title: { text: tituloX }
    },
    yAxis: {
      min: 0,
      allowDecimals: false,
      title: { text: tituloY }
    },
    tooltip: tooltipConfig,
    series: series,
    exporting: { enabled: true },
    credits: { enabled: false }
  });
}

/////////////////////////////////////////////////////////////////
function datosArea(materiasFiltradas) {
  const areaVerbosUnicos = {};

  materiasFiltradas.forEach(materia => {
    const area = materia.area;

    if (!areaVerbosUnicos[area]) {
      areaVerbosUnicos[area] = new Set();
    }

    let verbosMateria = materia.nombres_verbos; // Usar solo nombres ya procesados

    if (!Array.isArray(verbosMateria)) {
      verbosMateria = [];
    }

    verbosMateria.forEach(verbo => {
      areaVerbosUnicos[area].add(verbo.trim().toLowerCase());
    });
  });

  return Object.keys(areaVerbosUnicos).map(area => ({
    name: area,
    y: areaVerbosUnicos[area].size,
    verbos: Array.from(areaVerbosUnicos[area])
  }));
}

///////////////////////////////////////////////////////////////////
// filtra las materias por Area y por el verbo 
async function filtrarMaterias() {
  const areaSeleccionada = areaFilter.value;
  const verbSeleccionado = verbFilter.value;

  // Activa/Agrega botónes dinámicos (Por Materia / Por Área) solo si la gráfica está activa
  if (state.mostrarGrafica) {
    document.querySelectorAll(".btn-filtro").forEach(btn => {
      btn.classList.remove("active");
    });
    const btnFiltro = document.querySelector(`[data-filtro="${state.tipoFiltro}"]`);
    if (btnFiltro) {
      btnFiltro.classList.add("active");
    }
  }

  if (!state.materias || state.materias.length === 0) {
    try {
      const response = await fetch("http://localhost/proyectoGrado/public/Modulo_03_tabla_14/includes/obtener_materia_verbo.php");

      if (!response.ok) {
        throw new Error("Error al obtener las materias desde la base de datos");
      }
      state.materias = await response.json();
      aplicarFiltro(state.materias, areaSeleccionada, verbSeleccionado);
    } catch (error) {
      console.error("Error cargando las verbos de obtener verbos:", error);
    }
  } else {
    aplicarFiltro(state.materias, areaSeleccionada, verbSeleccionado);
  }
  desplazamientoGrafica(evitarDesplazamientoGlobal);
}


// Función para filtrar materias por área, verbo y búsqueda
function aplicarFiltro(materias, areaFilter, verbFilter) {
  // Validar que materias sea un array
  if (!Array.isArray(materias)) {
    // Obtener elementos del DOM
    const pagination = document.getElementById("pagination");

    console.error('Materias no es un array:', materias);
    tableBody.innerHTML = "<tr><td colspan='7'>Error en los datos: materias no válidas</td></tr>";
    if (pagination) pagination.innerHTML = "";
    return;
  }
  // Convertir para hacer comparación correcta
  const areaSeleccionadaNum = areaFilter ? parseInt(areaFilter) : null;
  const verboSeleccionadoId = verbFilter ? parseInt(verbFilter) : null;


  const materiasFiltradas = materias.filter(materia => {
    return (
      (areaSeleccionadaNum === null || materia.id_area == areaSeleccionadaNum) &&
      (verboSeleccionadoId === null || materia.verbos.includes(verboSeleccionadoId))
      // Compara con los IDs directamente
    );
  });


  // Calcular total de filas para paginación (cada materia es una fila)
  let totalItems = materiasFiltradas.length;

  // Actualizar totalPages
  totalPages = Math.max(1, Math.ceil(totalItems / limit));

  // Ajustar currentPage si es mayor que totalPages
  if (currentPage > totalPages) {
    currentPage = totalPages;
    offset = (currentPage - 1) * limit;
  }

  // Paginar los datos
  const startIndex = (currentPage - 1) * limit;
  const paginatedData = materiasFiltradas.slice(startIndex, startIndex + limit);

  // Actualizar la tabla y paginación
  if (paginatedData.length === 0) {
    tableBody.innerHTML = "<tr><td colspan='7'>No se encontraron resultados</td></tr>";
    const pagination = document.getElementById("pagination");
    if (pagination) pagination.innerHTML = "";
  } else {
    cargarTabla(paginatedData);
    renderPagination();
  }

  actualizarGrafica(materiasFiltradas);
}


///////////////////////////////////////////////////////////////////////////////////
// Función para cargar materias desde el PHP/API
async function cargarMateriaVerbo() {
  try {
    const response = await fetch("http://localhost/proyectoGrado/public/Modulo_03_tabla_14/includes/obtener_materia_verbo.php");
    if (!response.ok) throw new Error("Error al obtener las materias");
    const materias = await response.json();
    aplicarFiltro(materias, "", ""); // Carga inicial con filtros vacíos
  } catch (error) {
    console.error("Error cargando materiasVerbos:", error);
    tableBody.innerHTML = "<tr><td colspan='7'>Error al cargar materias</td></tr>";
  }
}

// Cargar áreas desde PHP
async function cargarAreas() {
  try {
    const response = await fetch("http://localhost/proyectoGrado/public/Modulo_01_tabla_09/includes/obtener_areas.php");
    if (!response.ok) throw new Error("Error en la respuesta del servidor");

    const data = await response.json();
    if (!Array.isArray(data)) throw new Error("Respuesta inesperada del servidor");

    areaFilter.innerHTML = '<option value="">Seleccione un área</option>';
    data.forEach(area => {
      const option = document.createElement("option");
      option.value = area.Id_area;
      option.textContent = area.nombre;
      areaFilter.appendChild(option);
    });
  } catch (error) {
    console.error("Error cargando las áreas:", error);
  }
}

// Función para cargar verbos desde el PHP/API
async function cargarVerbos(id_select) {
  try {
    const response = await fetch("http://localhost/proyectoGrado/public/Modulo_03_tabla_14/includes/obtener_verbos.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({})
    });

    if (!response.ok) {
      throw new Error(`Error en la solicitud: ${response.status} ${response.statusText}`);
    }

    const text = await response.text();

    let data;
    try {
      data = JSON.parse(text);
    } catch (jsonError) {
      throw new Error(" Error parseando JSON: " + jsonError.message);
    }

    if (!Array.isArray(data)) {
      throw new Error(" La respuesta no es un array válido");
    }

    const selectElement = document.getElementById(id_select);
    if (!selectElement) {
      throw new Error(` No se encontró el elemento con ID: ${id_select}`);
    }

    selectElement.innerHTML = '<option value="">Seleccione un verbo</option>';

    data.forEach(verb => {
      const option = document.createElement("option");
      option.value = verb.Id_verbo;
      option.textContent = verb.nombre;
      selectElement.appendChild(option);
    });

    // Guardar el ID del verbo seleccionado en un campo oculto
    selectElement.addEventListener("change", function () {
      document.getElementById("verbo_id").value = this.value; // Guarda el ID
    });

  } catch (error) {
    console.error(" Error en la función de cargar los Verbos:", error);
  }
}
///////////////////////////////////////////////////////////////////////////////////
// Función para cargar datos en la tabla
function cargarTabla(materiasFiltradas) {
  if (!tableBody) {
    console.error("El elemento 'table-body' no se encuentra en el DOM.");
    return;
  }

  tableBody.innerHTML = ""; // Limpiar la tabla antes de insertar nuevos datos

  // Definir constantes para los roles (coinciden con Auth.php)
  const ROLES = {
    SUPERADMIN: 0,
    ADMIN: 1,
    LECTOR: 2
  };

  materiasFiltradas.forEach(materia => {
    // Mostrar los nombres de los verbos asociados a cada materia
    const verbosNombres = materia.nombres_verbos ? materia.nombres_verbos.join(', ') : 'N/A';

    // Generar la fila base con las 4 columnas siempre visibles
    let rowHtml = `
      <tr>
        <td>${materia.nombre || 'N/A'}</td>
        <td>${materia.creditos || 'N/A'}</td>
        <td>${verbosNombres}</td>
        <td>${materia.area || 'N/A'}</td>
    `;

    // Añadir la columna "Acción" solo si NO es Lector
    if (window.rolUsuario !== ROLES.LECTOR) {
      rowHtml += `
        <td>
          <button 
            type="button" 
            class="btn btn-primary btn-actualizar" 
            data-nombre="${materia.nombre}" 
            data-bs-toggle="modal" 
            data-bs-target="#modal-datos">
            Actualizar
          </button>
        </td>
      `;
    }

    // Cerrar la fila
    rowHtml += `</tr>`;
    tableBody.innerHTML += rowHtml;
  });
}
//////////////////////////////////////////////////////////////////////
// RenderPagination
function renderPagination() {
  const pagination = document.getElementById("pagination");
  if (!pagination) {
    console.error("Elemento #pagination no encontrado en el DOM. Asegúrate de incluir <nav aria-label='Page navigation example'><ul id='pagination' class='pagination'></ul></nav> después de la tabla.");
    return;
  }

  pagination.innerHTML = "";

  // Botón "Atras"
  const prevLi = document.createElement("li");
  prevLi.className = `page-item ${currentPage === 1 ? "disabled" : ""}`;
  prevLi.innerHTML = `<a class="page-link" href="#">Atras</a>`;
  prevLi.querySelector("a").addEventListener("click", (e) => {
    e.preventDefault();
    if (currentPage > 1) {
      changePage(currentPage - 1);
    }
  });
  pagination.appendChild(prevLi);

  // Números de página (mostrar un rango limitado para evitar demasiados botones)
  const maxButtons = 5; // Máximo de botones numéricos a mostrar
  let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
  let endPage = Math.min(totalPages, startPage + maxButtons - 1);

  // Ajustar startPage si endPage está cerca del final
  if (endPage - startPage + 1 < maxButtons) {
    startPage = Math.max(1, endPage - maxButtons + 1);
  }

  for (let i = startPage; i <= endPage; i++) {
    const li = document.createElement("li");
    li.className = `page-item ${i === currentPage ? "active" : ""}`;
    li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
    li.querySelector("a").addEventListener("click", (e) => {
      e.preventDefault();
      changePage(i);
    });
    pagination.appendChild(li);
  }

  // Botón "Siguiente"
  const nextLi = document.createElement("li");
  nextLi.className = `page-item ${currentPage === totalPages ? "disabled" : ""}`;
  nextLi.innerHTML = `<a class="page-link" href="#">Siguiente</a>`;
  nextLi.querySelector("a").addEventListener("click", (e) => {
    e.preventDefault();
    if (currentPage < totalPages) {
      changePage(currentPage + 1);
    }
  });
  pagination.appendChild(nextLi);
}

function changePage(page) {
  if (page >= 1 && page <= totalPages) {
    currentPage = page;
    offset = (currentPage - 1) * limit;
    evitarDesplazamientoGlobal = true;
    filtrarMaterias();
  } else {
    console.warn('Página inválida:', page, 'Total Pages:', totalPages); // Depuración
  }
}
////////////////////////////////////////////////////////////////////////////////
//  Funcion para buscar la materia mas rapido en la parte de agregar datos
async function buscador_materias_datos(inputId, apiUrl) {
  const inputElemento = document.getElementById(inputId);
  if (!inputElemento) {
    console.error(`Elemento con ID "${inputId}" no encontrado en el DOM`);
    return;
  }
  const listaSugerencias = document.createElement("div");
  listaSugerencias.classList.add("dropdown-menu", "show");
  listaSugerencias.style.display = "none";
  inputElemento.parentNode.appendChild(listaSugerencias);

  inputElemento.addEventListener("input", async function () {
    const query = this.value.trim();
    if (query.length === 0) {
      listaSugerencias.style.display = "none";
      return;
    }

    try {
      const response = await fetch(`${apiUrl}?q=${query}`);
      const data = await response.json();

      listaSugerencias.innerHTML = "";
      if (data.length > 0) {
        listaSugerencias.style.display = "block";
        data.forEach(materia => {
          const item = document.createElement("div");
          item.classList.add("dropdown-item");
          item.textContent = materia.nombre;
          item.addEventListener("click", function () {
            inputElemento.value = materia.nombre;
            listaSugerencias.style.display = "none";
          });
          listaSugerencias.appendChild(item);
        });
      } else {
        listaSugerencias.style.display = "none";
      }
    } catch (error) {
      console.error("Error en la solicitud:", error);
    }
  });

  document.addEventListener("click", function (e) {
    if (!inputElemento.contains(e.target) && !listaSugerencias.contains(e.target)) {
      listaSugerencias.style.display = "none";
    }
  });
}
//////////////////////////////////////////////////////////////////////////////
// Función para mostrarMensajes
function mostrarMensaje(tipo, mensaje) {
  let alertClass = tipo === "success" ? "alert-success" : "alert-danger";
  let mensajeHtml = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                          ${mensaje}
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>`;

  $("#modal-datos .modal-body .alert").remove();

  $("#modal-datos .modal-body").prepend(mensajeHtml);

  // Cerrar automáticamente mensaje en 2 segundos
  setTimeout(() => {
    $(".alert").alert("close");
  }, 3000);
}

var verbosSeleccionados = []; // IDs de verbos seleccionados
var nombresVerbos = []; // Nombres de verbos seleccionados
var verboEliminado = false;

// Función para actualizar la lista de verbos seleccionados
function actualizarListaVerbos() {
  var lista = $("#verbos-seleccionados");
  lista.empty(); // Limpiar la lista antes de actualizarla

  nombresVerbos.forEach(function (verbo, index) {
    lista.append(`
            <li class="list-group-item d-flex justify-content-between align-items-center">
                ${verbo} 
                <button type="button" class="btn btn-sm btn-danger eliminar-verbo" data-id="${verbosSeleccionados[index]}">X</button>
            </li>
        `);
  });

  // Si se eliminó un verbo, actualiza interfaz
  if (verboEliminado) {
    verboEliminado = false; // Reset variable
  }

  toggleBotonGuardar();
}

//able / disable botton guardar : verbo
function toggleBotonGuardar() {
  if (verbosSeleccionados.length === 0) {
    $("#btn-guardar").prop("disabled", true);
  } else {
    $("#btn-guardar").prop("disabled", false);
  }
}

$("#btn-atras").click(function () {
  location.reload();
});
//////////////////////////////////////////////////////////////////////////////
// Eliminan verbos de la lista
$(document).ready(function () {
  $('#modal-datos').on('show.bs.modal', function () {
    $("#btn-guardar").prop("disabled", true);
  });

  // Eliminan verbos seleccionados de la lista
  $(document).on("click", ".eliminar-verbo", function () {
    var verboId = $(this).data("id").toString();

    // Buscar el índice del verbo a eliminar
    var index = verbosSeleccionados.indexOf(verboId);
    if (index !== -1) {
      verbosSeleccionados.splice(index, 1);
      nombresVerbos.splice(index, 1);
    }

    verboEliminado = true;

    // Actualizar el campo oculto y la lista visual
    $("#verbo_id").val(verbosSeleccionados.join(","));
    actualizarListaVerbos();
  });

  // Detectar cambios en el select de verbos
  $("#verb-filter_popup").change(function () {
    var nuevosIds = $(this).val() || []; // Obtener los IDs seleccionados
    var nuevosNombres = $("#verb-filter_popup option:selected").map(function () {
      return $(this).text();
    }).get();

    // Agregar solo los verbos que no estén ya en la lista
    nuevosIds.forEach(function (id, index) {
      if (!verbosSeleccionados.includes(id.toString())) {
        verbosSeleccionados.push(id.toString());
        nombresVerbos.push(nuevosNombres[index]);
      }
    });

    // Actualizar campo oculto con los IDs seleccionados
    $("#verbo_id").val(verbosSeleccionados.join(","));

    // Actualizar lista de verbos seleccionados
    actualizarListaVerbos();
  });



  // Enviar formulario por AJAX
  $("#form-datos").submit(function (e) {
    e.preventDefault();

    var nombreMateria = $("#nombre-materia").val();
    let verbosEnviados = verbosSeleccionados.join(",");

    $.ajax({
      url: "http://localhost/proyectoGrado/public/Modulo_03_tabla_14/includes/agregar_verbo_materia.php",
      type: "POST",
      data: {
        nombreMateria: nombreMateria, // Enviar el nombre de la materia
        verbo_id: verbosEnviados, // Enviar la lista de verbos seleccionados
        otros_datos: $(this).serialize() // Enviar los demás datos del formulario
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          alert("Verbos y materia guardados correctamente.");
          $('#modal-datos').modal('hide');
          location.reload();
        } else {
          $("#error-message").removeClass("d-none").text(response.message);
        }
      },
      error: function (xhr) {
        //console.error("Error en AJAX:", xhr.responseText);
        $("#error-message").removeClass("d-none").text("Verifique que el verbo ya no este relacionado.");
      }
    });
  });
});

// para actualizar desde aqui
document.addEventListener("click", async function (e) {
  if (e.target.classList.contains("btn-actualizar")) {
    const nombreMateria = e.target.dataset.nombre;


    // Colocar el nombre de la materia en el input
    document.getElementById("nombre-materia").value = nombreMateria;

    // Llamar al fetch para cargar los verbos
    await cargarVerbosDeMateria(nombreMateria);
  }
});

async function cargarVerbosDeMateria(idMateria) {
  try {
    $("#btn-guardar").prop("disabled", true); // Desactiva botón al abrir
    const response = await fetch(`http://localhost/proyectoGrado/public/Modulo_03_tabla_14/includes/obtener_verbos_por_materia.php?idMateria=${idMateria}`);
    const data = await response.json();

    if (data.error) {
      console.error("Error desde PHP:", data.error);
      return;
    }
    const contenedor = document.getElementById("verbos-seleccionados");
    contenedor.innerHTML = "";

    data.verbos.forEach(verbo => {
      const li = document.createElement("li");
      li.classList.add("list-group-item", "d-flex", "justify-content-between", "align-items-center");

      li.innerHTML = `
        ${verbo.nombre}
        <button class="btn btn-danger btn-sm btn-eliminar-verbo" 
                data-id="${verbo.id_verbo}" 
                data-id-materia="${data.id_materia}"
          Eliminar
        </button>
      `;

      contenedor.appendChild(li);
    });


  } catch (error) {
    console.error("Error cargando verbos de materia:", error);
  }
}

document.addEventListener("click", async function (e) {
  if (e.target.classList.contains("btn-eliminar-verbo")) {
    const idVerbo = e.target.getAttribute("data-id");
    const idMateria = e.target.getAttribute("data-id-materia");

    const nombreMateria = document.getElementById("nombre-materia").value.trim();

    // Mostrar confirmación antes de eliminar
    const confirmacion = confirm("¿Estás seguro de que deseas eliminar este verbo?");
    if (!confirmacion) return;

    try {
      const response = await fetch("http://localhost/proyectoGrado/public/Modulo_03_tabla_14/includes/eliminar_verbo_materia.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          id_verbo: idVerbo,
          id_materia: idMateria
        })
      });

      const result = await response.json();
      if (result.success) {
        mostrarMensaje("success", "Verbo eliminado correctamente.");
        cargarVerbosDeMateria(nombreMateria);
        actualizarListaVerbos();
      } else {
        mostrarMensaje("error", result.message || "Error al eliminar el verbo.");
      }
    } catch (error) {
      console.error("Error al eliminar verbo:", error);
    }
  }
});

// Detección de celda verbo a div
document.addEventListener("DOMContentLoaded", function () {
  inicializarEventosDOM();

  // Evento para capturar clicks en la columna de verbos
  const tabla = document.getElementById("data-table");
  tabla.addEventListener("click", function (event) {
    const celda = event.target;

    // Verificamos clic en columna de verbos
    const fila = celda.closest("tr");
    const celdas = fila?.getElementsByTagName("td");

    if (celdas && celda === celdas[2]) {
      const verbosTexto = celda.textContent.trim();
      mostrarVerbosEnDescripcion(verbosTexto);
    }
  });
});

function mostrarVerbosEnDescripcion(verbosTexto) {
  const divDescripcion = document.getElementById("verb-description");

  if (!verbosTexto) {
    divDescripcion.innerHTML = "<p>No hay verbos asociados.</p>";
    return;
  }

  const verbosArray = verbosTexto.split(',').map(v => v.trim().toLowerCase());

  fetch("http://localhost/proyectoGrado/public/Modulo_03_tabla_14/includes/obtener_verbos.php")
    .then(response => response.json())
    .then(data => {
      const verbosFiltrados = data.filter(verbo =>
        verbosArray.includes(verbo.nombre.toLowerCase())
      );

      if (verbosFiltrados.length === 0) {
        divDescripcion.innerHTML = "<p>No se encontraron descripciones para los verbos.</p>";
        return;
      }

      let html = `<ul class="list-group list-group-flush">`;
      verbosFiltrados.forEach(verbo => {
        html += `<li class="list-group-item">
                    <strong>${verbo.nombre}</strong><br>
                    <small>${verbo.descripcion}</small>
                 </li>`;
      });
      html += "</ul>";

      divDescripcion.innerHTML = html;
      divDescripcion.classList.add("expandida");
    })
    .catch(error => {
      console.error("Error al obtener descripciones:", error);
      divDescripcion.innerHTML = "<p>Error al cargar las descripciones.</p>";
    });
}