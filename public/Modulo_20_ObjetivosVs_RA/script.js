$(document).ready(function() {
    // Al hacer clic en una fila de objetivos de programa
    $('.objective-row').on('click', function() {
        var objectiveId = $(this).data('id'); // Obtener el ID de la fila seleccionada
        var resultRow = $('#results-' + objectiveId); // Buscar la fila correspondiente de los resultados

        // Alternar la visibilidad de los resultados
        if (resultRow.is(':visible')) {
            resultRow.slideUp(); // Si está visible, se oculta
        } else {
            resultRow.slideDown(); // Si está oculta, se muestra
        }
    });
});
