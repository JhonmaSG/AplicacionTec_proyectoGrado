document.addEventListener("DOMContentLoaded", function () {
    // Datos de ejemplo
    const data = [
        { subject: 'Cálculo Diferencial', area: 'Matemáticas', verbs: ['Guardar', 'Actualizar'] },
        { subject: 'Álgebra Lineal', area: 'Matemáticas', verbs: ['Borrar', 'Actualizar'] },
        { subject: 'Programación', area: 'Programación', verbs: ['Guardar', 'Sincronizar'] },
        { subject: 'Física I', area: 'Ciencias Básicas', verbs: ['Sincronizar', 'Borrar'] },
        { subject: 'Electromagnetismo', area: 'Ciencias Básicas', verbs: ['Guardar', 'Sincronizar', 'Borrar'] }
    ];

    // Descripciones de los verbos
    const verbDescriptions = {
        'Guardar': 'Guardar: Acción de almacenar datos en una base de datos o sistema.',
        'Borrar': 'Borrar: Eliminar datos de una base de datos o sistema.',
        'Sincronizar': 'Sincronizar: Asegurar que los datos sean consistentes en diferentes sistemas.',
        'Actualizar': 'Actualizar: Modificar o agregar información en un sistema para mantenerla al día.'
    };

    // Referencias a los elementos HTML
    const areaFilter = document.getElementById('area-filter');
    const verbFilter = document.getElementById('verb-filter');
    const multiAreaFilter = document.getElementById('multi-area-filter');
    const dataTable = document.getElementById('data-table').getElementsByTagName('tbody')[0];
    const verbDescription = document.getElementById('verb-description');

    // Función para filtrar los datos según los filtros seleccionados
    function filterResults() {
        const selectedArea = areaFilter.value;
        const selectedVerb = verbFilter.value;
        const isMultiAreaChecked = multiAreaFilter.checked;

        let filteredData = data;

        // Filtrar por área seleccionada
        if (selectedArea !== 'Todos') {
            filteredData = filteredData.filter(item => item.area === selectedArea);
        }

        // Filtrar por verbo seleccionado
        if (selectedVerb !== 'Todos') {
            filteredData = filteredData.filter(item => item.verbs.includes(selectedVerb));
        }

        // Filtrar verbos en varias áreas
        if (isMultiAreaChecked) {
            filteredData = filteredData.filter(item => item.area === 'Ciencias Básicas' && item.verbs.length > 1);
        }

        generateTable(filteredData);
    }

    // Función para generar la tabla
    function generateTable(filteredData) {
        dataTable.innerHTML = ''; // Limpiar la tabla

        filteredData.forEach(item => {
            item.verbs.forEach(verb => {
                const row = dataTable.insertRow();

                const subjectCell = row.insertCell(0);
                subjectCell.textContent = item.subject;

                const verbCell = row.insertCell(1);
                verbCell.textContent = verb;
                verbCell.dataset.verb = verb; // Añadir verbo como data-atributo

                const areaCell = row.insertCell(2);
                areaCell.textContent = item.area;
            });
        });
    }

    // Función para mostrar la descripción del verbo al hacer clic en una celda
    function showVerbDescription(event) {
        const cell = event.target;
        if (cell.dataset.verb) {
            const verb = cell.dataset.verb;
            verbDescription.textContent = `Descripción para el verbo "${verb}": ${verbDescriptions[verb]}`;
        }
    }

    // Event listeners para los filtros
    areaFilter.addEventListener('change', filterResults);
    verbFilter.addEventListener('change', filterResults);
    multiAreaFilter.addEventListener('change', filterResults);

    // Event listener para la tabla (cuando se hace clic en una celda de verbo)
    dataTable.addEventListener('click', showVerbDescription);

    // Inicializar la tabla con todos los datos
    filterResults();
});
