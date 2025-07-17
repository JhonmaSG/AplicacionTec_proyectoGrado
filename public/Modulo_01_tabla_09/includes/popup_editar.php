<!-- Modal de Edición -->
<div class="modal fade" id="modal-datos-actualizar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="form-datos-actualizar">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarLabel">Editar Registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_dato" id="editar-id-dato">

                    <div class="mb-3">
                        <label for="editar-nombre-materia" class="form-label">Materia</label>
                        <input type="text" class="form-control" id="editar-nombre-materia" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="editar-anio" class="form-label">Año</label>
                        <input type="number" class="form-control" id="editar-anio" name="anio" required>
                    </div>

                    <div class="mb-3">
                        <label for="editar-periodo" class="form-label">Periodo</label>
                        <select class="form-select" id="editar-periodo" name="periodo" required>
                            <option value="1">1</option>
                            <option value="3">3</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editar-inscritos" class="form-label">Inscritos</label>
                        <input type="number" class="form-control" id="editar-inscritos" name="inscritos" required>
                    </div>

                    <div class="mb-3">
                        <label for="editar-reprobados" class="form-label">Reprobados</label>
                        <input type="number" class="form-control" id="editar-reprobados" name="reprobados" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar cambios</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>