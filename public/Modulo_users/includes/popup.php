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