// URL del archivo PDF
const url = 'InsumosAutoevaluacion_2025.pdf';

// Configuración inicial
let pdfDoc = null,
    pageNum = 1,
    pageIsRendering = false,
    pageNumIsPending = null;

const scale = 1.5,
    canvas = document.getElementById('pdf-render'),
    ctx = canvas.getContext('2d');

// Renderizar la página
const renderPage = (num) => {
    pageIsRendering = true;

    // Obtener la página
    pdfDoc.getPage(num).then((page) => {
        const viewport = page.getViewport({ scale });
        canvas.width = viewport.width;
        canvas.height = viewport.height;

        const renderCtx = {
            canvasContext: ctx,
            viewport,
        };

        page.render(renderCtx).promise.then(() => {
            pageIsRendering = false;

            if (pageNumIsPending !== null) {
                renderPage(pageNumIsPending);
                pageNumIsPending = null;
            }
        });

        // Actualizar el número de página
        document.getElementById('page-num').textContent = num;
    });
};

// Cargar el documento PDF
const loadPDF = async () => {
    const pdfjsLib = window['pdfjs-dist/build/pdf'];
    pdfDoc = await pdfjsLib.getDocument(url).promise;

    document.getElementById('page-count').textContent = pdfDoc.numPages;

    renderPage(pageNum);
};

// Cambiar página
const queueRenderPage = (num) => {
    if (pageIsRendering) {
        pageNumIsPending = num;
    } else {
        renderPage(num);
    }
};

const showPrevPage = () => {
    if (pageNum <= 1) return;
    pageNum--;
    queueRenderPage(pageNum);
};

const showNextPage = () => {
    if (pageNum >= pdfDoc.numPages) return;
    pageNum++;
    queueRenderPage(pageNum);
};

// Manejar clics en el índice
document.querySelectorAll('nav a').forEach((link) => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const targetPage = parseInt(e.target.dataset.page);
        if (!isNaN(targetPage)) {
            pageNum = targetPage;
            queueRenderPage(pageNum);
        }
    });
});

// Eventos de navegación
document.getElementById('prev-page').addEventListener('click', showPrevPage);
document.getElementById('next-page').addEventListener('click', showNextPage);

// Cargar el PDF
loadPDF();
