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

// Button up moved
const btnSubir = document.getElementById("btnSubir");
window.onscroll = function () {
  if (document.documentElement.scrollTop > 300) {
    btnSubir.style.display = "flex";
  } else {
    btnSubir.style.display = "none";
  }
};

function subirArriba() {
  window.scrollTo({ top: 0, behavior: "smooth" });
}

// Evento para mostrar la gráfica con datos filtrados
const showChartButton = document.getElementById("show-chart");
if (showChartButton) {
  showChartButton.addEventListener("click", () => {
    filtrarMaterias(); // Se actualizará la gráfica con los datos filtrados
  });
}
showChartButton.addEventListener("click", function () {
  document.getElementById("chart-container").scrollIntoView({ behavior: "smooth" });
});



// Evento para imprimir la tabla
document.getElementById('print-table').addEventListener('click', () => {
  const printWindow = window.open('', '', 'height=600,width=800');
  const tableHtml = document.getElementById('data-table').outerHTML;

  printWindow.document.write(`
      <html>
      <head>
          <title>Tabla de Deserción</title>
          <style>
              table { width: 100%; border-collapse: collapse; }
              th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
          </style>
      </head>
      <body>
          <h1>Tabla de Deserción</h1>
          ${tableHtml}
      </body>
      </html>
  `);
  printWindow.document.close();
  printWindow.print();
});

// Referencias a los elementos del DOM
const tableBody = document.getElementById("table-body");
const areaFilter = document.getElementById("area-filter");
const searchMateria = document.getElementById("search-materia");
const graficaContainer = document.getElementById("grafica-container");
let materias = [];


// Función para preparar datos de la gráfica desde la tabla copia a la que 
// estaba en el js original de wanumen 
function generarDatosGrafica(materiasFiltradas) {
  const seriesData = materiasFiltradas.map((materia) => {
    return {
      name: materia.nombre,
      data: materia.datos.map((dato) => ({
        name: dato.periodo,
        y: parseFloat(dato.tasa_reprobacion),
      })),
    };
  });

  return seriesData;
}
////////////////////////////////////////////////////////


// Función para actualizar la gráfica cada que se hace una busqueda
function actualizarGrafica(materiasFiltradas) {
  const seriesData = generarDatosGrafica(materiasFiltradas);

  // Filtrar valores inválidos
  const categorias = [...new Set(seriesData
    .flatMap(materia => materia.data.map(d => d.name))
    .filter(name => name !== null && name !== undefined)
  )].sort();

  //console.log("Categorías del eje X:", categorias);

  // Destruir gráfica anterior si existe
  if (Highcharts.charts && Highcharts.charts.length > 0) {
    Highcharts.charts.forEach(chart => {
      if (chart && chart.renderTo === graficaContainer) {
        chart.destroy();
      }
    });
  }

  Highcharts.chart(graficaContainer, {
    chart: {
      type: "line",
    },
    title: {
      text: "Comportamiento de la Tasa de Reprobación",
    },
    xAxis: {
      categories: categorias, // Se pasa directamente las categorías
      title: {
        text: "Semestre",
      },
    },
    yAxis: {
      title: {
        text: "Tasa de Reprobación (%)",
      },
      min: 0,
      max: 100,
    },
    tooltip: {
      pointFormat: "<b>{point.y:.2f}%</b>",
    },
    series: seriesData.map(materia => ({
      name: materia.name,
      data: materia.data.map(d => ({
        name: d.name, // Se deja el nombre como está
        y: d.y
      }))
    })),
    exporting: {
      enabled: true,
    },
    credits: {
      enabled: false,
    },
  });
}

////////////////////////////////////////////////////////


// evento listener para invocar los cambios en tiempo real 
document.addEventListener("DOMContentLoaded", function () {
  // Eventos para filtrar cuando cambian los valores del filtro o el campo de búsqueda
  areaFilter.addEventListener("change", filtrarMaterias);
  searchMateria.addEventListener("input", filtrarMaterias);

  buscador_materias_datos("nombre-materia", "http://localhost/proyectoGrado/Modulo_01_tabla_09/includes/buscar_materia.php");

  //Modal (abrir, cerrar)
  let modal = document.getElementById("modal-datos");
  let btn = document.getElementById("btnAbrir");

  // Función para abrir el modal
  btn.onclick = function () {
    modal.style.display = "block";
  };

  // Función para cerrar el modal al hacer clic en la "X"
  function cerrarModal() {
    modal.style.display = "none";
  }

  // Cerrar al hacer clic fuera del modal
  window.onclick = function (event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  };

  // Exponer la función cerrarModal para que funcione en el popup.php
  window.cerrarModal = cerrarModal;
  // Inicialización de la página
  cargarAreas();
  cargarMaterias();
});
///////////////////////////////////////////////////////

// filtra las materias por Area y por el search que tenemos 
function filtrarMaterias() {
  const areaSeleccionada = areaFilter.value;
  const textoBusqueda = searchMateria.value.toLowerCase();

  // console.log("Ejecutando filtrarMaterias - Área seleccionada:", areaSeleccionada, "Texto:", textoBusqueda);

  // Si aún no se han cargado las materias, obtenerlas desde la BD
  if (!materias || materias.length === 0) {
    fetch("http://localhost/proyectoGrado/Modulo_01_tabla_09/includes/obtener_materias.php")
      .then(response => {
        if (!response.ok) {
          throw new Error("Error al obtener las materias desde la base de datos");
        }
        return response.json();
      })
      .then(data => {
        materias = data; // Guardamos las materias obtenidas
        aplicarFiltro(materias, areaSeleccionada, textoBusqueda);
      })
      .catch(error => console.error("Error cargando las materias:", error));
  } else {
    aplicarFiltro(materias, areaSeleccionada, textoBusqueda);
  }
}
////////////////////////////////////////////////////////
// Función auxiliar para aplicar el filtro y actualizar tabla/gráfica sin este ocurre un error al actualizar las materias 
function aplicarFiltro(materias, areaSeleccionada, textoBusqueda) {

  // Convertir a número para hacer comparación correcta
  const areaSeleccionadaNum = areaSeleccionada ? parseInt(areaSeleccionada) : null;

  const materiasFiltradas = materias.filter(materia => {
    return (
      (areaSeleccionadaNum === null || materia.id_area_materia == areaSeleccionadaNum) &&
      (textoBusqueda === "" || materia.nombre.toLowerCase().includes(textoBusqueda.toLowerCase()))
    );
  });

  if (materiasFiltradas.length === 0) {
    tableBody.innerHTML = "<tr><td colspan='6'>No se encontraron resultados</td></tr>";
  } else {
    cargarTabla(materiasFiltradas);
    actualizarGrafica(materiasFiltradas);
  }
}
////////////////////////////////////////////////////////

// Función para cargar materias desde el php  o api xd
async function cargarMaterias() {
  try {
    const response = await fetch("http://localhost/proyectoGrado/Modulo_01_tabla_09/includes/obtener_materias.php");
    if (!response.ok) throw new Error("Error al obtener las materias");
    materias = await response.json();
    cargarTabla(materias);
    //actualizarGrafica(materias);
  } catch (error) {
    console.error("Error cargando materias:", error);
  }
}

////////////////////////////////////////////////////////
// Cargar áreas desde el php
function cargarAreas() {
  fetch("http://localhost/proyectoGrado/Modulo_01_tabla_09/includes/obtener_areas.php")
    .then(response => {
      if (!response.ok) {
        throw new Error("Error en la respuesta del servidor");
      }
      return response.json();
    })
    .then(data => {
      if (!Array.isArray(data)) {
        throw new Error("Respuesta inesperada del servidor");
      }

      const areaFilter = document.getElementById("area-filter");
      if (!areaFilter) {
        throw new Error("Elemento 'area-filter' no encontrado en el DOM");
      }

      areaFilter.innerHTML = '<option value="">Seleccione un área</option>';
      data.forEach(area => {
        const option = document.createElement("option");
        option.value = area.Id_area;
        option.textContent = area.nombre; // nombre del campo coincida
        areaFilter.appendChild(option);
      });
    })
    .catch(error => console.error("Error cargando las áreas:", error));
}

////////////////////////////////////////////////////////
// Función para cargar datos en la tabla este utiliza el cargar materias 
function cargarTabla(materiasFiltradas) {

  //console.log("bandera del cargado de materias "+materiasFiltradas);

  tableBody.innerHTML = "";
  materiasFiltradas.forEach(materia => {
    materia.datos.forEach(dato => {
      tableBody.innerHTML += `
              <tr>
                  <td>${materia.nombre}</td>
                  <td>${materia.area}</td>
                  <td>${materia.semestre || 'N/A'}</td>
                  <td>${dato.inscritos || '0'}</td>
                  <td>${dato.reprobados || '0'}</td>
                  <td>${dato.tasa_reprobacion || '0'}%</td>
              </tr>`;
    });
  });
}
////////////////////////////////////////////////////////

// Eventos para filtrar
tableBody.addEventListener("change", filtrarMaterias);//tableBody - areaFilter
searchMateria.addEventListener("input", filtrarMaterias);


//funcion para buscar la materia mas rapido en la parte de agregar datos xd
function buscador_materias_datos(inputId, apiUrl) {
  const inputElemento = document.getElementById(inputId);
  const listaSugerencias = document.createElement("div");
  listaSugerencias.classList.add("dropdown-menu", "show");
  listaSugerencias.style.display = "none";
  inputElemento.parentNode.appendChild(listaSugerencias);

  inputElemento.addEventListener("input", function () {
    const query = this.value.trim();

    if (query.length === 0) {
      listaSugerencias.style.display = "none";
      return;
    }

    fetch(`${apiUrl}?q=${query}`)
      .then(response => response.json())
      .then(data => {

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
      })
      .catch(error => console.error("Error en la solicitud:", error));
  });

  document.addEventListener("click", function (e) {
    if (!inputElemento.contains(e.target) && !listaSugerencias.contains(e.target)) {
      listaSugerencias.style.display = "none";
    }
  });
}
////////////////////////////////////////////////////////

$(document).ready(function () {
  $("#form-datos").submit(function (e) {
    e.preventDefault();
    guardarMateria();
  });
});

function guardarMateria() {
  let formData = $("#form-datos").serialize(); // Serializa los datos del formulario

  console.log("Datos enviados:", formData);
  $.ajax({
    url: "http://localhost:3000/AplicacionTec_ProyectoDeGrado-Nicolas/Modulo_01_tabla_09/agregar_datos_materia.php",
    type: "POST",
    data: $("#form-datos").serialize(),
    dataType: "json",
    success: function (response) {
      if (response.success) {
        mostrarMensaje("success", response.message);
        $("#modal-datos").modal("hide"); // Cierra el modal
        setTimeout(() => location.reload(), 2000); // Recarga después de 1.5s
      } else {
        mostrarMensaje("error", response.message);
      }
    },
    error: function (xhr, status, error) {
      console.error(" Error ajax:", status, error); // 🔍 Ver errores AJAX
      mostrarMensaje("error", "repitio el periodo y el año en una.");

    }
  });
}

function mostrarMensaje(tipo, mensaje) {
  let alertClass = tipo === "success" ? "alert-success" : "alert-danger";
  let mensajeHtml = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                          ${mensaje}
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>`;

  $("#modal-datos .modal-body").prepend(mensajeHtml);

  setTimeout(() => {
    $(".alert").alert("close");
  }, 3000);
}
// Inicialización de la página
cargarAreas();
cargarMaterias();