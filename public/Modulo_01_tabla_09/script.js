/////////////////////////////////////////////////////////////////////
//Materias.php
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
const areaFilter = document.getElementById("area-filter");
const carreraFilter = document.getElementById("carrera-filter");
const semestreFilter = document.getElementById("semestre-filter");
const searchMateria = document.getElementById("search-materia");
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
      const chart = Highcharts.charts.find(chart => chart && chart.renderTo === graficaContainer);
      if (chart) {
        chart.fullscreen.toggle();
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

  // Eventos de filtrado
  searchMateria.addEventListener("input", filtrarMaterias);
  areaFilter.addEventListener("change", filtrarMaterias);
  carreraFilter.addEventListener("change", filtrarMaterias);
  semestreFilter.addEventListener("change", filtrarMaterias);

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

  // Eventos de botones dinamicos de filtro Graphics
  document.querySelectorAll(".btn-filtro").forEach(btn => {
    btn.addEventListener("click", function () {
      state.tipoFiltro = this.getAttribute("data-filtro"); // Obtiene el tipo de filtro del atributo data-filtro
      filtrarMaterias();
    });
  });

  // Modal
  configurarModal();

  // Inicialización de la página
  cargarAreas();
  cargarCarreras();
  cargarSemestres();
  cargarMaterias();
}

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

  window.cerrarModal = cerrarModal;
}

function imprimirTabla() {
  const printWindow = window.open('', '', 'height=600,width=800');
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

  printWindow.document.write(`
      <html>
      <head>
          <title>Tabla de Deserción</title>
          <style>
              table { width: 100%; border-collapse: collapse; }
              th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
              h1 { text-align: center; margin-bottom: 10px; }
              .fecha-hora { text-align: center; font-size: 14px; color: #555; margin-bottom: 20px; }
          </style>
      </head>
      <body>
          <h1>Tabla de Deserción</h1>
          <p class="fecha-hora">Generado el ${fechaHora}</p>
          ${table.outerHTML}
      </body>
      </html>
  `);
  printWindow.document.close();
  printWindow.print();
}

// Ejecutar la inicialización al cargar la página
document.addEventListener("DOMContentLoaded", inicializarEventosDOM);
const tableBody = document.getElementById("table-body");

///////////////////////////////////////////////////////////////////
// Función para preparar datos de la gráfica desde la tabla
function generarDatosGrafica(materiasFiltradas) {
  return materiasFiltradas.map(materia => ({
    name: materia.nombre,
    data: materia.datos.map(dato => ({
      name: dato.periodo,
      y: parseFloat(dato.tasa_reprobacion),
      inscritos: parseInt(dato.inscritos),
      reprobados: parseInt(dato.reprobados)
    }))
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

  let seriesData = generarDatosGrafica(materiasFiltradas);
  let tipoCategoria = [];
  let tituloX = "";
  let tipoChart = "line";
  let serieFinal = [];

  switch (state.tipoFiltro) {
    case 'materias':
      tituloX = "Año - Periodo";
      tipoCategoria = [...new Set(seriesData
        .flatMap(materia => materia.data.map(d => d.name))
        .filter(name => name !== null && name !== undefined)
      )].sort();
      serieFinal = seriesData.map(materia => ({
        name: materia.name,
        data: materia.data.map(d => ({
          name: d.name,
          y: d.y,
          inscritos: d.inscritos,
          reprobados: d.reprobados
        }))
      }))
      break;
    case 'area':
      tituloX = "Área";
      tipoChart = "bar";
      tipoCategoria = [...new Set(materiasFiltradas
        .map(m => m.area)
        .filter(area => area && isNaN(area) && area.trim() !== ""),
      )];
      serieFinal = datosArea(materiasFiltradas)
      break;
    default:
      break;
  }

  // Tooltip dinámico según tipo de filtro
  let tooltipConfig = state.tipoFiltro === 'materias' ? {
    formatter: function () {
      return `<b>${this.series.name}</b><br>
              <b>${tituloX}:</b> ${this.point.name}<br>
              <b>Tasa de Reprobación: ${this.point.y.toFixed(2)}%</b><br>
              <b>Inscritos:</b> ${this.point.inscritos}<br>
              <b>Reprobados:</b> ${this.point.reprobados}`;
    }
  } : {
    formatter: function () {
      return `<b>${this.series.name}</b><br>
              <b>${tituloX}:</b> ${this.point.name}<br>
              <b>Tasa de Reprobación: ${this.point.y.toFixed(2)}%</b><br>
              <b>Promedio Inscritos:</b> ${this.point.inscritos}<br>
              <b>Promedio Reprobados:</b> ${this.point.reprobados}`;
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
    chart: { type: `${tipoChart}` },
    title: { text: "Comportamiento de la Tasa de Reprobación" },
    xAxis: {
      categories: tipoCategoria,
      title: { text: tituloX },
    },
    yAxis: {
      title: { text: "Tasa de Reprobación (%)" },
      min: 0, max: 100,
    },
    tooltip: tooltipConfig,
    series: serieFinal,
    exporting: { enabled: true },
    credits: { enabled: false },
  });
}
// Funcion para generar Datos de Area en Grafica
function datosArea(materiasFiltradas) {
  const datosPorArea = materiasFiltradas.reduce((acc, materia) => {
    const area = materia.area || "Sin Área";

    if (!acc[area]) {
      acc[area] = { totalTasa: 0, totalInscritos: 0, totalReprobados: 0, totalMaterias: 0 };
    }

    materia.datos.forEach(d => {
      acc[area].totalTasa += parseFloat(d.tasa_reprobacion) || 0;
      acc[area].totalInscritos += isNaN(parseInt(d.inscritos)) ? 0 : parseInt(d.inscritos);
      acc[area].totalReprobados += isNaN(parseInt(d.reprobados)) ? 0 : parseInt(d.reprobados);
      acc[area].totalMaterias += 1;
    });

    return acc;
  }, {});

  return [{
    name: "Promedio de Tasa de Reprobación",
    data: Object.entries(datosPorArea).map(([area, datos]) => ({
      name: area,
      y: datos.totalTasa / datos.totalMaterias,
      inscritos: Math.round(datos.totalInscritos / datos.totalMaterias),
      reprobados: Math.round(datos.totalReprobados / datos.totalMaterias)
    }))
  }];
}

/////////////////////////////////////////////////////////////
// filtra las materias por Area y por el search que tenemos 
async function filtrarMaterias() {
  const areaSeleccionada = areaFilter.value;
  const carreraSeleccionada = carreraFilter.value;
  const semestreSeleccionado = semestreFilter.value;
  const textoBusqueda = searchMateria.value.toLowerCase();

  /// Activa/Agrega botónes dinámicos (Por Materia / Por Área) solo si la gráfica está activa
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
      const response = await fetch("http://localhost/proyectoGrado/public/Modulo_01_tabla_09/includes/obtener_materia_datos.php");
      if (!response.ok) {
        throw new Error("Error al obtener las materias desde la base de datos");
      }
      state.materias = await response.json();
      aplicarFiltro(state.materias, areaSeleccionada, carreraSeleccionada, semestreSeleccionado, textoBusqueda);
    } catch (error) {
      console.error("Error cargando las materias:", error);
    }
  } else {
    aplicarFiltro(state.materias, areaSeleccionada, carreraSeleccionada, semestreSeleccionado, textoBusqueda);
  }
  desplazamientoGrafica(evitarDesplazamientoGlobal);
}
////////////////////////////////////////////////////////
// Función auxiliar para aplicar el filtro y actualizar tabla/gráfica sin este ocurre un error al actualizar las materias 
function aplicarFiltro(materias, areaSeleccionada, carreraSeleccionada, semestreSeleccionado, textoBusqueda) {
  // Validar que materias sea un array
  if (!Array.isArray(materias)) {
    // Obtener elementos del DOM
    const pagination = document.getElementById("pagination");
    
    console.error('Materias no es un array:', materias);
    tableBody.innerHTML = "<tr><td colspan='7'>Error en los datos: materias no válidas</td></tr>";
    if (pagination) pagination.innerHTML = "";
    return;
  }
  
  // Convertir a número para hacer comparación correcta
  const areaSeleccionadaNum = areaSeleccionada ? parseInt(areaSeleccionada) : null;
  const carreraSeleccionadaNum = carreraSeleccionada ? parseInt(carreraSeleccionada) : null;
  const semestreSeleccionadoNum = semestreSeleccionado ? parseInt(semestreSeleccionado) : null;

  const materiasFiltradas = materias.filter(materia => {
    return (
      (areaSeleccionadaNum === null || materia.id_area_materia == areaSeleccionadaNum) &&
      (carreraSeleccionadaNum === null || materia.id_carrera == carreraSeleccionadaNum) &&
      (semestreSeleccionadoNum === null || materia.semestre == semestreSeleccionadoNum) &&
      (textoBusqueda === "" || materia.nombre.toLowerCase().includes(textoBusqueda.toLowerCase()))
    );
  });

  // Calcular total de filas para paginación (cada dato es una fila)
  let totalItems = materiasFiltradas.reduce((total, materia) => {
    return total + (materia.datos && Array.isArray(materia.datos) ? materia.datos.length : 0);
  }, 0);

  // Actualizar totalPages
  totalPages = Math.max(1, Math.ceil(totalItems / limit)); // Asegura al menos 1 página

  // Ajustar currentPage si es mayor que totalPages
  if (currentPage > totalPages) {
    currentPage = totalPages;
    offset = (currentPage - 1) * limit;
  }

  // Paginar los datos
  const startIndex = (currentPage - 1) * limit;
  let currentIndex = 0;
  let paginatedData = [];

  for (let materia of materiasFiltradas) {
    if (materia.datos && Array.isArray(materia.datos)) {
      for (let dato of materia.datos) {
        if (currentIndex >= startIndex && currentIndex < startIndex + limit) {
          paginatedData.push({ materia, dato });
        }
        currentIndex++;
      }
    }
  }

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
////////////////////////////////////////////////////////////////////////
// Función para cargar materias desde el php/api
async function cargarMaterias() {
  try {
    const response = await fetch("http://localhost/proyectoGrado/public/Modulo_01_tabla_09/includes/obtener_materia_datos.php");
    if (!response.ok) throw new Error("Error al obtener las materias");
    state.materias = await response.json();
    aplicarFiltro(state.materias, "", "", "", ""); // Carga inicial con filtros vacíos
  } catch (error) {
    console.error("Error cargando materias:", error);
    tableBody.innerHTML = "<tr><td colspan='7'>Error al cargar materias</td></tr>";
  }
}

////////////////////////////////////////////////////////////////////////
// Cargar áreas desde PHP
async function cargarAreas() {
  try {
    const response = await fetch("http://localhost/proyectoGrado/public/Modulo_01_tabla_09/includes/obtener_areas.php");
    if (!response.ok) throw new Error("Error en la respuesta del servidor");

    const data = await response.json();
    if (!Array.isArray(data)) throw new Error("Respuesta inesperada del servidor");

    const areaFilter = document.getElementById("area-filter");
    if (!areaFilter) throw new Error("Elemento 'area-filter' no encontrado en el DOM");

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

// Función para Cargar Carreras desde PHP
async function cargarCarreras() {
  try {
    const response = await fetch("http://localhost/proyectoGrado/public/Modulo_01_tabla_09/includes/obtener_carreras.php");
    if (!response.ok) throw new Error("Error en la respuesta del servidor");

    const data = await response.json();
    if (!Array.isArray(data)) throw new Error("Respuesta inesperada del servidor");

    const carreraFilter = document.getElementById("carrera-filter");
    if (!carreraFilter) throw new Error("Elemento 'carrera-filter' no encontrado en el DOM");

    carreraFilter.innerHTML = '<option value="">TSD/IT</option>';
    data.forEach(carrera => {
      const option = document.createElement("option");
      option.value = carrera.Id_carrera;
      option.textContent = carrera.nombre;
      carreraFilter.appendChild(option);
    });
  } catch (error) {
    console.error("Error cargando las Carreras:", error);
  }
}
// Función para Cargar Semestres desde PHP
function cargarSemestres() {
  try {
    const semestreFilter = document.getElementById("semestre-filter");
    if (!semestreFilter) throw new Error("Elemento 'semestre-filter' no encontrado en el DOM");

    // Si "Todos" ya está presente
    const tieneTodos = Array.from(semestreFilter.options).some(option => option.value === "");
    if (!tieneTodos) {
      const todosOption = document.createElement("option");
      todosOption.value = ""; // Valor vacío para representar "Todos"
      todosOption.textContent = "Todos"; // Texto visible al usuario
      semestreFilter.insertBefore(todosOption, semestreFilter.firstChild);
    }
  } catch (error) {
    console.error("Error cargando los Semestres:", error);
  }
}
///////////////////////////////////////////////////////////////////////
// Función para cargar datos en la tabla este utiliza el cargar materias 
function cargarTabla(paginatedData) {
  tableBody.innerHTML = "";
  if (!Array.isArray(paginatedData)) {
    console.error("paginatedData no es un array:", paginatedData);
    tableBody.innerHTML = "<tr><td colspan='7'>Error en los datos</td></tr>";
    return;
  }

  paginatedData.forEach(({ materia, dato }) => {
    tableBody.innerHTML += `
      <tr>
        <td>${materia.nombre || 'N/A'}</td>
        <td>${materia.area || 'N/A'}</td>
        <td>${materia.semestre || 'N/A'}</td>
        <td>${dato.periodo || 'N/A'}</td>
        <td>${dato.inscritos || '0'}</td>
        <td>${dato.reprobados || '0'}</td>
        <td>${dato.tasa_reprobacion || '0'}%</td>
      </tr>`;
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

  // Botón "Anterior"
  const prevLi = document.createElement("li");
  prevLi.className = `page-item ${currentPage === 1 ? "disabled" : ""}`;
  prevLi.innerHTML = `<a class="page-link" href="#">Anterior</a>`;
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

///////////////////////////////////////////////////////////////////////
// Eventos para filtrar
tableBody.addEventListener("change", filtrarMaterias); //tableBody - areaFilter

// Funcion para buscar la materia mas rapido en la parte de agregar datos
async function buscador_materias_datos(inputId, apiUrl) {
  const inputElemento = document.getElementById(inputId);
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
/////////////////////////////////////////////////////////////////////////
// Enviar formulario por AJAX
$(document).ready(function () {
  $("#form-datos").submit(function (e) {
    e.preventDefault();
    guardarMateria();
  });
});

// Función para guardar Materia
function guardarMateria() {
  let formData = $("#form-datos").serialize(); // Serializa los datos del formulario

  $.ajax({
    url: "http://localhost/proyectoGrado/public/Modulo_01_tabla_09/includes/agregar_datos_materia.php",
    type: "POST",
    data: $("#form-datos").serialize(),
    dataType: "json",
    success: function (response) {
      if (response.success) {
        mostrarMensaje("success", response.message);
        $("#modal-datos").modal("hide"); // Cierra el modal
        setTimeout(() => location.reload(), 1000); // Recarga
      } else {
        mostrarMensaje("error", response.message);
      }
    },
    error: function (xhr, status, error) {
      //console.error(" Error ajax:", status, error); // 🔍 Ver errores AJAX
      mostrarMensaje("error", "Escriba datos validos");

    }
  });
}

// Función para mostrarMensajes
function mostrarMensaje(tipo, mensaje) {
  let alertClass = tipo === "success" ? "alert-success" : "alert-danger";
  let mensajeHtml = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                          ${mensaje}
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>`;

  $("#modal-datos .modal-body").prepend(mensajeHtml);

  setTimeout(() => {
    $(".alert").alert("close");
  }, 2000);
}