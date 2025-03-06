/* eslint-disable */
// publicapis.dev
/*
// Datos simulados
const chartData = {
  semesters: ['2020-1', '2020-2', '2021-1', '2021-2'],
  rates: [15, 20, 10, 5]
};

// Función para renderizar la gráfica
function renderChart(data) {
  Highcharts.chart('chart-container', {
    chart: {
      type: 'line'
    },
    title: {
      text: 'Tasa de Reprobación por Semestre'
    },
    xAxis: {
      categories: data.semesters
    },
    yAxis: {
      title: {
        text: 'Tasa de Reprobación (%)'
      }
    },
    series: [{
      name: 'Tasa de Reprobación',
      data: data.rates
    }],
    exporting: {
      enabled: true
    }
  });
}

// Renderizar gráfica al cargar la página
renderChart(chartData);
*/

// Fullscreen para la gráfica
document.getElementById('fullscreen-chart').addEventListener('click', () => {
  const chart = Highcharts.charts[0];
  chart.fullscreen.toggle();
});

// Evento para mostrar gráfica con datos dinámicos (enlace a filtros)
document.getElementById('show-chart').addEventListener('click', () => {
  document.getElementById("grafica-container").scrollIntoView({ behavior: "smooth" });
  // Lógica para obtener datos filtrados y llamar renderChart
  renderChart(chartData);
});



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


// Datos de ejemplo
const materias = [
  { 
    nombre: "1 - CALCULO DIFERENCIAL", 
    area: "Ciencias Básicas",
    datos: [
      { semestre: "2020-1", inscritos: 180, reprobados: 36 },
      { semestre: "2020-3", inscritos: 149, reprobados: 86 },
      { semestre: "2021-1", inscritos: 138, reprobados: 53 },
      { semestre: "2021-3", inscritos: 166, reprobados: 98 },
      { semestre: "2022-1", inscritos: 176, reprobados: 101 },
      { semestre: "2022-3", inscritos: 175, reprobados: 118 },
      { semestre: "2023-1", inscritos: 225, reprobados: 136 },
      { semestre: "2023-2", inscritos: 26, reprobados: 4 },
      { semestre: "2023-3", inscritos: 192, reprobados: 178 },
      { semestre: "2024-1", inscritos: 216, reprobados: 81 },      
    ]
  },
  {
    nombre: "3 - FISICA I: MECANICA NEWTONIANA",
    area: "Ciencias Básicas",
    datos: [
      { semestre: "2020-1", inscritos: 180, reprobados: 36 },
      { semestre: "2020-3", inscritos: 124, reprobados: 40 },
      { semestre: "2021-1", inscritos: 133, reprobados: 39 },
      { semestre: "2021-3", inscritos: 218, reprobados: 48 },
      { semestre: "2022-1", inscritos: 113, reprobados: 52 },
      { semestre: "2022-3", inscritos: 86, reprobados: 40 },
      { semestre: "2023-1", inscritos: 77, reprobados: 16 },
      { semestre: "2023-3", inscritos: 106, reprobados: 20 },
      { semestre: "2024-1", inscritos: 120, reprobados: 38 },
    ]
  },
  {
    nombre: "4 - CATEDRA FRANCISCO JOSE DE CALDAS",
    area: "Humanidades",
    datos: [
      { semestre: "2020-1", inscritos: 126, reprobados: 18 },
      { semestre: "2020-3", inscritos: 135, reprobados: 25 },
      { semestre: "2021-1", inscritos: 116, reprobados: 24 },
      { semestre: "2021-3", inscritos: 138, reprobados: 37 },
      { semestre: "2022-1", inscritos: 129, reprobados: 33 },
      { semestre: "2022-3", inscritos: 121, reprobados: 37 },
      { semestre: "2023-1", inscritos: 158, reprobados: 30 },
      { semestre: "2023-2", inscritos: 2, reprobados: 0 },
      { semestre: "2023-3", inscritos: 132, reprobados: 37 },
      { semestre: "2024-1", inscritos: 186, reprobados: 36 },
    ]
  },
  {
    nombre: "7 - CALCULO INTEGRAL",
    area: "Ciencias Básicas",
    datos: [
      { semestre: "2020-1", inscritos: 91, reprobados: 7 },
      { semestre: "2020-3", inscritos: 142, reprobados: 21 },
      { semestre: "2021-1", inscritos: 114, reprobados: 30 },
      { semestre: "2021-3", inscritos: 118, reprobados: 61 },
      { semestre: "2022-1", inscritos: 110, reprobados: 65 },
      { semestre: "2022-3", inscritos: 120, reprobados: 58 },
      { semestre: "2023-1", inscritos: 104, reprobados: 58 },
      { semestre: "2023-3", inscritos: 166, reprobados: 91 },
      { semestre: "2024-1", inscritos: 180, reprobados: 95 },
    ]
  },
  {
    nombre: "8 - HISTORIA Y CULTURA COLOMBIANA",
    area: "Humanidades - Electiva",
    datos: [
      { semestre: "2020-1", inscritos: 1, reprobados: 0 },
      { semestre: "2020-3", inscritos: 1, reprobados: 0 },
      { semestre: "2021-1", inscritos: 1, reprobados: 0 },
      { semestre: "2022-1", inscritos: 3, reprobados: 0 },
      { semestre: "2022-3", inscritos: 1, reprobados: 0 },
      { semestre: "2023-3", inscritos: 3, reprobados: 0 },
      { semestre: "2024-1", inscritos: 5, reprobados: 1 },
    ]
  },
  {
    nombre: "9 - ALGEBRA LINEAL",
    area: "Ciencias Básicas",
    datos: [
      { semestre: "2020-1", inscritos: 171, reprobados: 53 },
      { semestre: "2020-3", inscritos: 156, reprobados: 49 },
      { semestre: "2021-1", inscritos: 145, reprobados: 50 },
      { semestre: "2021-3", inscritos: 158, reprobados: 78 },
      { semestre: "2022-1", inscritos: 166, reprobados: 79 },
      { semestre: "2022-3", inscritos: 154, reprobados: 96 },
      { semestre: "2023-1", inscritos: 211, reprobados: 123 },
      { semestre: "2023-3", inscritos: 203, reprobados: 90 },
      { semestre: "2024-1", inscritos: 224, reprobados: 116 },      
    ]
  },
  {
    nombre: "12 - CATEDRA DEMOCRACIA Y CIUDADANIA",
    area: "Humanidades",
    datos: [
      { semestre: "2020-1", inscritos: 122, reprobados: 12 },
      { semestre: "2020-3", inscritos: 126, reprobados: 27 },
      { semestre: "2021-1", inscritos: 108, reprobados: 13 },
      { semestre: "2021-3", inscritos: 134, reprobados: 35 },
      { semestre: "2022-1", inscritos: 135, reprobados: 27 },
      { semestre: "2022-3", inscritos: 117, reprobados: 36 },
      { semestre: "2023-1", inscritos: 161, reprobados: 35 },
      { semestre: "2023-3", inscritos: 130, reprobados: 28 },
      { semestre: "2024-1", inscritos: 180, reprobados: 38 },      
    ]
  },
  {
    nombre: "13 - FISICA II: ELECTROMAGNETISMO",
    area: "Ciencias Básicas",
    datos: [
      { semestre: "2020-1", inscritos: 61, reprobados: 1 },
      { semestre: "2020-3", inscritos: 55, reprobados: 4 },
      { semestre: "2021-1", inscritos: 82, reprobados: 9 },
      { semestre: "2021-3", inscritos: 67, reprobados: 16 },
      { semestre: "2022-1", inscritos: 53, reprobados: 19 },
      { semestre: "2022-3", inscritos: 62, reprobados: 19 },
      { semestre: "2023-1", inscritos: 78, reprobados: 17 },
      { semestre: "2023-3", inscritos: 73, reprobados: 18 },
      { semestre: "2024-1", inscritos: 72, reprobados: 37 },      
    ]
  },
  {
    nombre: "1054 - PRODUCCION Y COMPRENSION DE TEXTOS I",
    area: "Humanidades",
    datos: [
      { semestre: "2020-1", inscritos: 139, reprobados: 21 },
      { semestre: "2020-3", inscritos: 133, reprobados: 21 },
      { semestre: "2021-1", inscritos: 117, reprobados: 30 },
      { semestre: "2021-3", inscritos: 147, reprobados: 46 },
      { semestre: "2022-1", inscritos: 137, reprobados: 43 },
      { semestre: "2022-3", inscritos: 128, reprobados: 31 },
      { semestre: "2023-1", inscritos: 155, reprobados: 53 },
      { semestre: "2023-3", inscritos: 151, reprobados: 32 },
      { semestre: "2024-1", inscritos: 186, reprobados: 33 },      
    ]
  },
  {
    nombre: "1056 - PRODUCCION Y COMPRENSION DE TEXTOS II",
    area: "Humanidades",
    datos: [
      { semestre: "2020-1", inscritos: 105, reprobados: 14 },
      { semestre: "2020-3", inscritos: 123, reprobados: 17 },
      { semestre: "2021-1", inscritos: 109, reprobados: 26 },
      { semestre: "2021-3", inscritos: 98, reprobados: 23 },
      { semestre: "2022-1", inscritos: 101, reprobados: 27 },
      { semestre: "2022-3", inscritos: 100, reprobados: 19 },
      { semestre: "2023-1", inscritos: 99, reprobados: 16 },
      { semestre: "2023-3", inscritos: 110, reprobados: 19 },
      { semestre: "2024-1", inscritos: 104, reprobados: 21 },      
    ]
  },
  {
    nombre: "1060 - CIENCIA TECNOLOGIA Y SOCIEDAD",
    area: "Humanidades",
    datos: [
      { semestre: "2020-1", inscritos: 79, reprobados: 6 },
      { semestre: "2020-3", inscritos: 89, reprobados: 7 },
      { semestre: "2021-1", inscritos: 106, reprobados: 14 },
      { semestre: "2021-3", inscritos: 101, reprobados: 25 },
      { semestre: "2022-1", inscritos: 88, reprobados: 17 },
      { semestre: "2022-3", inscritos: 81, reprobados: 18 },
      { semestre: "2023-1", inscritos: 101, reprobados: 15 },
      { semestre: "2023-3", inscritos: 80, reprobados: 14 },
      { semestre: "2024-1", inscritos: 86, reprobados: 12 },      
    ]
  },
  {
    nombre: "1072 - ADMINISTRACION",
    area: "Económico Administrativa",
    datos: [
      { semestre: "2020-1", inscritos: 113, reprobados: 15 },
      { semestre: "2020-3", inscritos: 123, reprobados: 19 },
      { semestre: "2021-1", inscritos: 101, reprobados: 23 },
      { semestre: "2021-3", inscritos: 97, reprobados: 33 },
      { semestre: "2022-1", inscritos: 100, reprobados: 33 },
      { semestre: "2022-3", inscritos: 111, reprobados: 18 },
      { semestre: "2023-1", inscritos: 91, reprobados: 15 },
      { semestre: "2023-3", inscritos: 126, reprobados: 35 },
      { semestre: "2024-1", inscritos: 120, reprobados: 35 },      
    ]
  },
  {
    nombre: "1075 - ETICA Y SOCIEDAD",
    area: "Humanidades",
    datos: [
      { semestre: "2020-1", inscritos: 83, reprobados: 1 },
      { semestre: "2020-3", inscritos: 84, reprobados: 6 },
      { semestre: "2021-1", inscritos: 83, reprobados: 10 },
      { semestre: "2021-3", inscritos: 100, reprobados: 14 },
      { semestre: "2022-1", inscritos: 87, reprobados: 8 },
      { semestre: "2022-3", inscritos: 61, reprobados: 8 },
      { semestre: "2023-1", inscritos: 79, reprobados: 9 },
      { semestre: "2023-3", inscritos: 87, reprobados: 13 },
      { semestre: "2024-1", inscritos: 77, reprobados: 14 },      
    ]
  },
  {
    nombre: "1082 - CATEDRA DE CONTEXTO",
    area: "Humanidades",
    datos: [
      { semestre: "2020-1", inscritos: 86, reprobados: 1 },
      { semestre: "2020-3", inscritos: 117, reprobados: 4 },
      { semestre: "2021-1", inscritos: 104, reprobados: 4 },
      { semestre: "2021-3", inscritos: 82, reprobados: 6 },
      { semestre: "2022-1", inscritos: 76, reprobados: 11 },
      { semestre: "2022-3", inscritos: 86, reprobados: 5 },
      { semestre: "2023-1", inscritos: 73, reprobados: 8 },
      { semestre: "2023-3", inscritos: 90, reprobados: 9 },
      { semestre: "2024-1", inscritos: 82, reprobados: 5 },      
    ]
  },
  {
    nombre: "1138 - EMPRENDIMIENTO",
    area: "Económico Administrativa",
    datos: [
      { semestre: "2020-1", inscritos: 13, reprobados: 0 },
      { semestre: "2020-3", inscritos: 17, reprobados: 1 },
      { semestre: "2021-1", inscritos: 21, reprobados: 2 },
      { semestre: "2021-3", inscritos: 24, reprobados: 9 },
      { semestre: "2022-1", inscritos: 25, reprobados: 9 },
      { semestre: "2022-3", inscritos: 21, reprobados: 7 },
      { semestre: "2023-1", inscritos: 23, reprobados: 12 },
      { semestre: "2023-3", inscritos: 16, reprobados: 4 },
      { semestre: "2024-1", inscritos: 17, reprobados: 8 },      
    ]
  },
  {
    nombre: "1503 - CONTABILIDAD GENERAL",
    area: "Económico Administrativa",
    datos: [
      { semestre: "2020-1", inscritos: 59, reprobados: 5 },
      { semestre: "2020-3", inscritos: 57, reprobados: 3 },
      { semestre: "2021-1", inscritos: 32, reprobados: 7 },
      { semestre: "2021-3", inscritos: 42, reprobados: 6 },
      { semestre: "2022-1", inscritos: 37, reprobados: 5 },
      { semestre: "2022-3", inscritos: 23, reprobados: 4 },
      { semestre: "2023-1", inscritos: 50, reprobados: 14 },
      { semestre: "2023-3", inscritos: 38, reprobados: 7 },
      { semestre: "2024-1", inscritos: 42, reprobados: 13 },      
    ]
  },
  {
    nombre: "1507 - INTRODUCCION A ALGORITMOS",
    area: "Profesional Básica",
    datos: [
      { semestre: "2020-1", inscritos: 133, reprobados: 38 },
      { semestre: "2020-3", inscritos: 141, reprobados: 53 },
      { semestre: "2021-1", inscritos: 145, reprobados: 62 },
      { semestre: "2021-3", inscritos: 179, reprobados: 94 },
      { semestre: "2022-1", inscritos: 179, reprobados: 71 },
      { semestre: "2022-3", inscritos: 147, reprobados: 73 },
      { semestre: "2023-1", inscritos: 184, reprobados: 84 },
      { semestre: "2023-3", inscritos: 175, reprobados: 82 },
      { semestre: "2024-1", inscritos: 219, reprobados: 81 },      
    ]
  },
  {
    nombre: "1508 - LOGICA MATEMATICA",
    area: "Ciencias Básicas",
    datos: [
      { semestre: "2020-1", inscritos: 127, reprobados: 19 },
      { semestre: "2020-3", inscritos: 134, reprobados: 45 },
      { semestre: "2021-1", inscritos: 128, reprobados: 34 },
      { semestre: "2021-3", inscritos: 151, reprobados: 55 },
      { semestre: "2022-1", inscritos: 146, reprobados: 46 },
      { semestre: "2022-3", inscritos: 127, reprobados: 51 },
      { semestre: "2023-1", inscritos: 169, reprobados: 52 },
      { semestre: "2023-3", inscritos: 148, reprobados: 52 },
      { semestre: "2024-1", inscritos: 202, reprobados: 41 },      
    ]
  },
  {
    nombre: "1509 - FUNDAMENTOS DE ORGANIZACION",
    area: "Económico Administrativa",
    datos: [
      { semestre: "2020-1", inscritos: 55, reprobados: 2 },
      { semestre: "2020-3", inscritos: 49, reprobados: 1 },
      { semestre: "2021-1", inscritos: 55, reprobados: 7 },
      { semestre: "2021-3", inscritos: 61, reprobados: 9 },
      { semestre: "2022-1", inscritos: 47, reprobados: 8 },
      { semestre: "2022-3", inscritos: 51, reprobados: 5 },
      { semestre: "2023-1", inscritos: 49, reprobados: 7 },
      { semestre: "2023-3", inscritos: 46, reprobados: 8 },
      { semestre: "2024-1", inscritos: 50, reprobados: 8 },      
    ]
  },
  {
    nombre: "1510 - FUNDAMENTOS DE ECONOMIA",
    area: "Económico Administrativa",
    datos: [
      { semestre: "2020-1", inscritos: 22, reprobados: 1 },
      { semestre: "2020-3", inscritos: 25, reprobados: 0 },
      { semestre: "2021-1", inscritos: 25, reprobados: 2 },
      { semestre: "2021-3", inscritos: 17, reprobados: 4 },
      { semestre: "2022-1", inscritos: 15, reprobados: 9 },
      { semestre: "2022-3", inscritos: 16, reprobados: 3 },
      { semestre: "2023-1", inscritos: 18, reprobados: 3 },
      { semestre: "2023-3", inscritos: 16, reprobados: 4 },
      { semestre: "2024-1", inscritos: 14, reprobados: 2 },      
    ]
  },
  {
    nombre: "1511 - TIC_S EN LAS ORGANIZACIONES",
    area: "Humanidades",
    datos: [
      { semestre: "2020-1", inscritos: 55, reprobados: 4 },
      { semestre: "2020-3", inscritos: 43, reprobados: 3 },
      { semestre: "2021-1", inscritos: 56, reprobados: 5 },
      { semestre: "2021-3", inscritos: 48, reprobados: 12 },
      { semestre: "2022-1", inscritos: 45, reprobados: 4 },
      { semestre: "2022-3", inscritos: 61, reprobados: 5 },
      { semestre: "2023-1", inscritos: 51, reprobados: 7 },
      { semestre: "2023-3", inscritos: 45, reprobados: 4 },
      { semestre: "2024-1", inscritos: 37, reprobados: 8 },      
    ]
  },
  {
    nombre: "1512 - MATEMATICAS ESPECIALES",
    area: "Ciencias Básicas",
    datos: [
      { semestre: "2020-1", inscritos: 17, reprobados: 1 },
      { semestre: "2020-3", inscritos: 14, reprobados: 0 },
      { semestre: "2021-1", inscritos: 8, reprobados: 0 },
      { semestre: "2021-3", inscritos: 18, reprobados: 3 },
      { semestre: "2022-1", inscritos: 11, reprobados: 5 },
      { semestre: "2022-3", inscritos: 16, reprobados: 8 },
      { semestre: "2023-1", inscritos: 16, reprobados: 4 },
      { semestre: "2023-3", inscritos: 11, reprobados: 3 },
      { semestre: "2024-1", inscritos: 12, reprobados: 3 },      
    ]
  },
  {
    nombre: "1513 - ESTRUCTURA DE DATOS",
    area: "Profesional Básica",
    datos: [
      { semestre: "2020-1", inscritos: 102, reprobados: 10 },
      { semestre: "2020-3", inscritos: 110, reprobados: 24 },
      { semestre: "2021-1", inscritos: 111, reprobados: 17 },
      { semestre: "2021-3", inscritos: 179, reprobados: 94 },
      { semestre: "2022-1", inscritos: 179, reprobados: 71 },
      { semestre: "2022-3", inscritos: 147, reprobados: 73 },
      { semestre: "2023-1", inscritos: 184, reprobados: 84 },
      { semestre: "2023-3", inscritos: 175, reprobados: 82 },
      { semestre: "2024-1", inscritos: 219, reprobados: 81 },      
    ]
  },
];

// Referencias a los elementos de la página
const tableBody = document.getElementById("table-body");
const areaFilter = document.getElementById("area-filter");
const searchMateria = document.getElementById("search-materia");

// Función para calcular la tasa de reprobación
function calcularTasa(inscritos, reprobados) {
  return ((reprobados / inscritos) * 100).toFixed(2);
}

// Función para cargar datos en la tabla
function cargarTabla(materiasFiltradas) {
  tableBody.innerHTML = ""; // Limpiar tabla
  materiasFiltradas.forEach((materia) => {
    materia.datos.forEach((dato) => {
      const row = `
        <tr>
          <td>${materia.nombre}</td>
          <td>${materia.area}</td>
          <td>${dato.semestre}</td>
          <td>${dato.inscritos}</td>
          <td>${dato.reprobados}</td>
          <td>${calcularTasa(dato.inscritos, dato.reprobados)}%</td>
        </tr>`;
      tableBody.innerHTML += row;
    });
  });
}

// Función para cargar las áreas en el filtro
function cargarAreas() {
  const areas = [...new Set(materias.map((materia) => materia.area))];
  areaFilter.innerHTML = "<option value=''>Todas las áreas</option>";
  areas.forEach((area) => {
    areaFilter.innerHTML += `<option value="${area}">${area}</option>`;
  });
}

const graficaContainer = document.getElementById("grafica-container");
// Función para preparar datos de la gráfica desde la tabla
function generarDatosGrafica(materiasFiltradas) {
  const seriesData = materiasFiltradas.map((materia) => {
    return {
      name: materia.nombre,
      data: materia.datos.map((dato) => ({
        name: dato.semestre,
        y: parseFloat(calcularTasa(dato.inscritos, dato.reprobados)),
      })),
    };
  });

  return seriesData;
}

// Función para actualizar la gráfica
function actualizarGrafica(materiasFiltradas) {
  const seriesData = generarDatosGrafica(materiasFiltradas);

  Highcharts.chart(graficaContainer, {
    chart: {
      type: "line",
    },
    title: {
      text: "Comportamiento de la Tasa de Reprobación",
    },
    xAxis: {
      type: "category",
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
      borderRadius: 8,
      pointFormat: "<b>{point.y:.2f}%</b>",
    },
    series: seriesData,
    exporting: {
      enabled: true,
    },
    credits: {
      enabled: false,
    },
    plotOptions: {
      series: {
        animation: {
            duration: 2500
          }
      }
    }
    });
}


// Filtrar materias por área y búsqueda
function filtrarMaterias() {
  const areaSeleccionada = areaFilter.value.toLowerCase();
  const textoBusqueda = searchMateria.value.toLowerCase();

  const materiasFiltradas = materias.filter((materia) => {
    const perteneceArea =
      areaSeleccionada === "" || materia.area.toLowerCase() === areaSeleccionada;
    const coincideTexto =
      textoBusqueda === "" ||
      materia.nombre.toLowerCase().includes(textoBusqueda);
    return perteneceArea && coincideTexto;
  });

  cargarTabla(materiasFiltradas);
  actualizarGrafica(materiasFiltradas); // Sincroniza la gráfica
}

// Inicialización de la página
cargarAreas();
cargarTabla(materias);
actualizarGrafica(materias); // Inicializa la gráfica

// Eventos para filtrar
areaFilter.addEventListener("change", filtrarMaterias);
searchMateria.addEventListener("input", filtrarMaterias);


