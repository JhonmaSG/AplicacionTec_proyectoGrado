<?php
require_once '../assets/php/Auth.php';
require_once '../../app/config/ConfiguracionBD/ConexionBD.php';
require_once '../../app/config/Logger.php';
Logger::registrarAccesoModulo($conn, 'Gestion_de_usuarios');

Auth::iniciarSesion();
Auth::redirigirSiNoAutenticado();

// Bloquear acceso a Lector
if (Auth::obtenerRol() === 2) {
    header('Location: /proyectoGrado/unauthorized.php');
    exit;
}

Auth::evitarCache();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="icon" href="http://localhost/proyectoGrado/app/Acceso/img/icon_page.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../assets/css/styles_menu.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const ROLES = {
            SUPERADMIN: 0,
            ADMIN: 1,
            LECTOR: 2
        };
        window.rolUsuario = <?php echo json_encode(Auth::obtenerRol()); ?>;
    </script>
</head>

<body>
    <?php include '../assets/php/menu.php'; ?>

    <div class="container">
        <h1>Gestión de Usuarios</h1>

        <!-- Filtros -->
        <div class="filters">
            <!-- Campo de búsqueda -->
            <div class="row mb-3">
                <div class="col-12">
                    <label for="search-user" class="form-label"><b>Buscar usuario (por cédula o nombre):</b></label>
                    <input type="text" id="search-user" class="form-control" placeholder="Ej. 123456 o Juan Pérez">
                </div>
            </div>
            <!-- Campos de fechas -->
            <div class="row">
                <div class="col-md-6 col-12 mb-3">
                    <label for="fecha-inicio" class="form-label"><b>Fecha inicio:</b></label>
                    <input type="date" id="fecha-inicio" class="form-control">
                </div>
                <div class="col-md-6 col-12 mb-3">
                    <label for="fecha-fin" class="form-label"><b>Fecha fin:</b></label>
                    <input type="date" id="fecha-fin" class="form-control">
                </div>
            </div>
        </div>


        <!-- Acciones -->
        <div class="actions mb-3">
            <button id="print-table" class="btn btn-info">Imprimir Tabla</button>
        </div>

        <!-- Paginación -->
        <nav aria-label="Page navigation" id="numRegistros" class="d-flex justify-content-center align-items-center mb-4">
            <span id="registro-info" class="me-3"></span>
            <div class="d-flex align-items-center">
                <label for="limit-select" class="me-2">Registros por página:</label>
                <select id="limit-select" class="form-select form-select-sm" style="width: auto;">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20" selected>20</option>
                    <option value="50">50</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                </select>
            </div>
            <ul id="pagination" class="pagination mb-0 ms-3 justify-content-center"></ul>
        </nav>

        <!-- Tabla de Usuarios -->
        <div class="table-responsive-custom">
            <table id="tabla-usuarios" class="table">
                <thead>
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Correo</th>
                        <th>Último Acceso</th>
                        <th>Rol Actual</th>
                        <th>Módulo</th>
                        <?php if (Auth::esSuperAdmin()): ?>
                            <th>Acción</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody id="tbody-usuarios"></tbody>
            </table>
        </div>

        <button id="btnSubir">↑</button>

        <?php include 'includes/popup.php'; ?>
    </div>

    <script type="module" src="/proyectoGrado/public/assets/js/desplazamiento.js"></script>
    <script type="module" src="script.js"></script>
    <script src="/proyectoGrado/public/assets/js/script_menu.js"></script>
</body>

</html>