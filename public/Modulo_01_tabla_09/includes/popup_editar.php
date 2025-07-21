<!-- Modal de Edición -->
<div class="modal fade" id="modal-datos-actualizar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="form-datos-actualizar">
                    <input type="hidden" name="id_dato" id="editar-id-dato">

                    <div class="mb-3">
                        <label for="editar-nombre-materia" class="form-label">Nombre de la Materia:</label>
                        <input type="text" class="form-control" id="editar-nombre-materia" name="nombre_materia" placeholder='Digita el nombre de la materia' readonly>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="editar-anio" class="form-label">Año:</label>
                            <input type="number" class="form-control" id="editar-anio" name="anio" placeholder='Ej: 2025' required>
                        </div>
                        <div class="col-md-6">
                            <label for="editar-periodo" class="form-label">Período:</label>
                            <select class="form-select" id="editar-periodo" name="periodo" required>
                                <option value="1">1</option>
                                <option value="3">3</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="editar-inscritos" class="form-label">Inscritos:</label>
                            <input type="number" class="form-control" id="editar-inscritos" name="inscritos" min="0" step="1" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editar-reprobados" class="form-label">Reprobados:</label>
                            <input type="number" class="form-control" id="editar-reprobados" name="reprobados" min="0" step="1" required>
                        </div>
                    </div>

                    <div id="error-message-editar" class="alert alert-danger d-none mt-3" role="alert"></div>

                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>