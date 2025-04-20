<?php
require_once '../assets/php/Auth.php';
require_once '../../app/config/ConfiguracionBD/ConexionBD.php';
require_once '../../app/config/Logger.php';
Logger::registrarAccesoModulo($conn, 'Gestion_de_usuarios');

Auth::iniciarSesion();
Auth::redirigirSiNoAutenticado();
Auth::evitarCache();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../assets/css/styles_menu.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        const ROL_USUARIO = <?= Auth::obtenerRol(); ?>;
    </script>



</head>

<body>

    <?php include '../assets/php/menu.php'; ?>

    <div class="container">
        <h1>Gestión de Usuarios del Sistema</h1>

        <!-- Filtros -->
        <div class="filters">
            <div class="filter-item" style="flex: 1 1 100%;">
                <label for="search-user"><b>Buscar usuario (por cédula o nombre):</b></label>
                <input type="text" id="search-user" placeholder="Ej. 123456 o Juan Pérez">
            </div>
        </div>

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
                        <th>Modulo</th>
                        <?php if (Auth::esSuperAdmin()): ?>
                            <th>Acción</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody id="tbody-usuarios">
                    <!-- Filas dinámicas cargadas por JS -->
                </tbody>
            </table>
        </div>

        <button id="btnSubir" onclick="subirArriba()">↑</button>

        <!-- Modal Editar Usuario -->
        <div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarUsuarioLabel">Editar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formEditarUsuario">
                            <input type="hidden" id="editarCedula" name="cedula">
                            <div class="mb-3">
                                <label for="editarNombre" class="form-label">Nombres</label>
                                <input type="text" class="form-control" id="editarNombre" name="nombres" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="editarApellido" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="editarApellido" name="apellidos" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="editarCorreo" class="form-label">Correo</label>
                                <input type="email" class="form-control" id="editarCorreo" name="correo" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="editarRol" class="form-label">Rol</label>
                                <select class="form-select" id="editarRol" name="rol">
                                    <option value="1">Administrador</option>
                                    <option value="2">Lector</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
    <script src="/proyectoGrado/public/assets/js/script_menu.js"></script>
</body>

</html>