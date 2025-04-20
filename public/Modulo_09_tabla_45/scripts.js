document.addEventListener('DOMContentLoaded', function() {
    const clickableCells = document.querySelectorAll('.clickable');
    const justificationElement = document.querySelector('#justification p');
    const descriptionElement = document.querySelector('#description p');
    const rows = document.querySelectorAll('#tabla tbody tr');
    const btnFullScreen = document.getElementById('btnFullScreen');
    const btnPDF = document.getElementById('btnPDF');
    const btnNightMode = document.getElementById('btnNightMode');
    const body = document.body;

    clickableCells.forEach(cell => {
        cell.addEventListener('click', function() {
            // Quitar la clase "active" de todas las celdas
            clickableCells.forEach(c => c.classList.remove('active'));
            
            // Agregar la clase "active" solo a la celda seleccionada
            this.classList.add('active');

            // Mostrar la justificación
            const justificationText = this.getAttribute('data-justification');
            justificationElement.textContent = justificationText;
        });
    });

    rows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            const descriptionText = this.getAttribute('data-description');
            descriptionElement.textContent = descriptionText;
        });
        row.addEventListener('mouseleave', function() {
            descriptionElement.textContent = 'Pasa el mouse sobre una fila para ver su descripción.';
        });
    });

    btnFullScreen.addEventListener('click', () => {
        const elem = document.documentElement;
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        }
    });

    btnNightMode.addEventListener('click', () => {
        body.classList.toggle('night-mode');
    });

    btnPDF.addEventListener('click', () => {
        const pdfContent = document.querySelector('#pdf-content');
        const options = {
            margin: 1,
            filename: 'tabla_interactiva.pdf',
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
        };
        html2pdf().from(pdfContent).set(options).save();
    });
});
