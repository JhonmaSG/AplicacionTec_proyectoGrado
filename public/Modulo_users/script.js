import { subirArriba } from '/proyectoGrado/public/assets/js/desplazamiento.js';

// Elementos del DOM
const tbody = document.getElementById('tbody-usuarios');
const searchUser = document.getElementById('search-user');
const fechaInicio = document.getElementById('fecha-inicio');
const fechaFin = document.getElementById('fecha-fin');
const limitSelect = document.getElementById('limit-select');
const pagination = document.getElementById('pagination');
const btnSubir = document.getElementById('btnSubir');
const printTable = document.getElementById('print-table');
const registroInfo = document.getElementById('registro-info');

// Variables de paginación y filtros
let limit = 20;
let offset = 0;
let currentPage = 1;
let totalPages = 1;
let totalItems = 0;
let busqueda = '';
let fechaInicioVal = '';
let fechaFinVal = '';

// Inicializar eventos
function inicializarEventos() {
    if (btnSubir) {
        btnSubir.addEventListener('click', subirArriba);
        window.addEventListener('scroll', () => {
            btnSubir.style.display = window.scrollY > 100 ? 'block' : 'none';
        });
    }

    if (limitSelect) {
        limitSelect.addEventListener('change', () => {
            limit = parseInt(limitSelect.value);
            currentPage = 1;
            offset = 0;
            filtrarUsuarios();
        });
    }

    if (searchUser) {
        let timeout;
        searchUser.addEventListener('input', () => {
            clearTimeout(timeout);
            busqueda = searchUser.value.trim();
            console.log('Búsqueda ingresada:', busqueda);
            timeout = setTimeout(() => {
                console.log('Ejecutando búsqueda después de debounce');
                currentPage = 1;
                offset = 0;
                filtrarUsuarios();
            }, 500);
        });
    } else {
        console.error('Elemento #search-user no encontrado');
    }

    if (fechaInicio) {
        fechaInicio.addEventListener('input', () => {
            fechaInicioVal = fechaInicio.value;
            console.log('Fecha inicio seleccionada:', fechaInicioVal);
            currentPage = 1;
            offset = 0;
            filtrarUsuarios();
        });
    } else {
        console.error('Elemento #fecha-inicio no encontrado');
    }

    if (fechaFin) {
        fechaFin.addEventListener('input', () => {
            fechaFinVal = fechaFin.value;
            console.log('Fecha fin seleccionada:', fechaFinVal);
            currentPage = 1;
            offset = 0;
            filtrarUsuarios();
        });
    } else {
        console.error('Elemento #fecha-fin no encontrado');
    }

    if (printTable) {
        printTable.addEventListener('click', imprimirTabla);
    }
}

// Cargar y renderizar tabla
function cargarTabla(usuarios) {
    if (!tbody) {
        console.error('Elemento #tbody-usuarios no encontrado');
        return;
    }
    console.log('Usuarios recibidos para renderizar:', usuarios);
    tbody.innerHTML = '';

    if (usuarios.length === 0) {
        tbody.innerHTML = '<tr><td colspan="' + (window.rolUsuario === ROLES.SUPERADMIN ? 8 : 7) + '"><center>No se encontraron usuarios.</center></td></tr>';
        return;
    }

    usuarios.forEach(usuario => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${usuario.cedula || 'N/A'}</td>
            <td>${usuario.nombres || 'N/A'}</td>
            <td>${usuario.apellidos || 'N/A'}</td>
            <td>${usuario.correo || 'N/A'}</td>
            <td>${usuario.fecha_acceso || 'Sin acceso registrado'}</td>
            <td>${usuario.rol_nombre || 'N/A'}</td>
            <td>${usuario.modulo || 'N/A'}</td>
        `;
        if (window.rolUsuario === ROLES.SUPERADMIN) {
            row.innerHTML += `
                <td>
                    <button class="btn btn-sm btn-warning btn-editar" 
                            data-cedula="${usuario.cedula}"
                            data-nombres="${usuario.nombres}"
                            data-apellidos="${usuario.apellidos}"
                            data-correo="${usuario.correo}"
                            data-rol="${usuario.rol_id}">
                        Editar
                    </button>
                </td>
            `;
        }
        tbody.appendChild(row);
    });
}

// Filtrar usuarios con paginación
function filtrarUsuarios() {
    console.log('Filtrando usuarios con:', { busqueda, fechaInicio: fechaInicioVal, fechaFin: fechaFinVal, limit, offset });
    $.ajax({
        url: 'includes/obtener_usuarios.php',
        method: 'GET',
        data: {
            busqueda: busqueda || '',
            fecha_inicio: fechaInicioVal || '',
            fecha_fin: fechaFinVal || '',
            limit,
            offset
        },
        dataType: 'json',
        beforeSend: function () {
            console.log('Enviando petición AJAX con URL:', `includes/obtener_usuarios.php?busqueda=${encodeURIComponent(busqueda)}&fecha_inicio=${fechaInicioVal}&fecha_fin=${fechaFinVal}&limit=${limit}&offset=${offset}`);
        },
        success: function (response) {
            console.log('Respuesta de obtener_usuarios.php:', response);
            if (response.success) {
                totalItems = response.total || 0;
                totalPages = Math.max(1, Math.ceil(totalItems / limit));
                cargarTabla(response.usuarios);
                renderPagination();
            } else {
                console.error('Error al obtener usuarios:', response.error);
                tbody.innerHTML = '<tr><td colspan="' + (window.rolUsuario === ROLES.SUPERADMIN ? 8 : 7) + '">Error al cargar usuarios.</td></tr>';
                if (registroInfo) registroInfo.textContent = 'Error al cargar usuarios';
            }
        },
        error: function (xhr, status, error) {
            console.error('Error AJAX:', status, error, xhr.responseText);
            tbody.innerHTML = '<tr><td colspan="' + (window.rolUsuario === ROLES.SUPERADMIN ? 8 : 7) + '">Error de conexión.</td></tr>';
            if (registroInfo) registroInfo.textContent = 'Error de conexión';
        }
    });
}

// Renderizar paginación
function renderPagination() {
    if (!pagination) {
        console.error('Elemento #pagination no encontrado');
        return;
    }
    pagination.innerHTML = '';

    const prevLi = document.createElement('li');
    prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
    prevLi.innerHTML = '<a class="page-link" href="#">Anterior</a>';
    prevLi.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage > 1) {
            currentPage--;
            offset = (currentPage - 1) * limit;
            filtrarUsuarios();
        }
    });
    pagination.appendChild(prevLi);

    const maxButtons = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
    let endPage = Math.min(totalPages, startPage + maxButtons - 1);
    if (endPage - startPage + 1 < maxButtons) {
        startPage = Math.max(1, endPage - maxButtons + 1);
    }

    for (let i = startPage; i <= endPage; i++) {
        const li = document.createElement('li');
        li.className = `page-item ${i === currentPage ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
        li.addEventListener('click', (e) => {
            e.preventDefault();
            currentPage = i;
            offset = (currentPage - 1) * limit;
            filtrarUsuarios();
        });
        pagination.appendChild(li);
    }

    const nextLi = document.createElement('li');
    nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
    nextLi.innerHTML = '<a class="page-link" href="#">Siguiente</a>';
    nextLi.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage < totalPages) {
            currentPage++;
            offset = (currentPage - 1) * limit;
            filtrarUsuarios();
        }
    });
    pagination.appendChild(nextLi);
}

// Imprimir tabla con fecha/hora
function imprimirTabla() {
    const printWindow = window.open('', '', 'height=600,width=800');
    
    const table = document.getElementById('tabla-usuarios').cloneNode(true);
    
    if (window.rolUsuario === ROLES.SUPERADMIN) {
        const headerRow = table.querySelector('thead tr');
        if (headerRow && headerRow.cells.length > 0) {
            headerRow.deleteCell(-1);
        }
        const bodyRows = table.querySelectorAll('tbody tr');
        bodyRows.forEach(row => {
            if (row.cells.length > 0) {
                row.deleteCell(-1);
            }
        });
    }

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
            <title>Tabla de Usuarios</title>
            <style>
                table { width: 100%; border-collapse: collapse; }
                th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
                h1 { text-align: center; margin-bottom: 10px; }
                .fecha-hora { text-align: center; font-size: 14px; color: #555; margin-bottom: 20px; }
            </style>
        </head>
        <body>
            <h1>Tabla de Usuarios</h1>
            <p class="fecha-hora">Generado el ${fechaHora}</p>
            ${table.outerHTML}
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

// Manejar edición de usuarios
function inicializarEdicion() {
    $(document).on('click', '.btn-editar', function () {
        const cedula = $(this).data('cedula');
        const nombres = $(this).data('nombres');
        const apellidos = $(this).data('apellidos');
        const correo = $(this).data('correo');
        const rol = $(this).data('rol');

        $('#editarCedula').val(cedula);
        $('#editarNombre').val(nombres);
        $('#editarApellido').val(apellidos);
        $('#editarCorreo').val(correo);
        $('#editarRol').val(rol);

        const modal = new bootstrap.Modal(document.getElementById('modalEditarUsuario'));
        modal.show();
    });

    $('#formEditarUsuario').on('submit', function (e) {
        e.preventDefault();

        const datos = {
            nid: $('#editarCedula').val(),
            rol: $('#editarRol').val()
        };

        $.ajax({
            url: 'includes/actualizar_usuario.php',
            method: 'POST',
            data: datos,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditarUsuario'));
                    modal.hide();
                    filtrarUsuarios();
                } else {
                    alert('Error al actualizar usuario: ' + response.error);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error AJAX:', status, error, xhr.responseText);
                alert('Error de conexión con el servidor.');
            }
        });
    });
}

// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    console.log('Página cargada, inicializando eventos');
    inicializarEventos();
    inicializarEdicion();
    filtrarUsuarios();
});