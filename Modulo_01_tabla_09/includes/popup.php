<!-- Botón para abrir el modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-datos">
    Agregar Registro
</button>
<!-- Modal -->
<div class="modal fade" id="modal-datos" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label">Ingrese los Datos de la Materia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="form-datos">
                    <div class="mb-3">
                        <label for="nombre-materia" class="form-label">Nombre de la Materia:</label>
                        <input type="text" class="form-control" id="nombre-materia" name="nombre_materia" required autocomplete="off">
                        <div id="lista-sugerencias" class="dropdown-menu show" style="display: none;"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="anio" class="form-label">Año:</label>
                            <input type="number" class="form-control" id="anio" name="anio" required>
                        </div>
                        <div class="col-md-6">
                            <label for="periodo" class="form-label">Período:</label>
                            <input type="number" class="form-control" id="periodo" name="periodo" min="1" max="3" step="2" required>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="inscritos" class="form-label">Inscritos:</label>
                            <input type="number" class="form-control" id="inscritos" name="inscritos" required>
                        </div>
                        <div class="col-md-6">
                            <label for="reprobados" class="form-label">Reprobados:</label>
                            <input type="number" class="form-control" id="reprobados" name="reprobados" required>
                        </div>
                    </div>
                    <div id="error-message" class="alert alert-danger d-none" role="alert"></div>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS (para que el modal funcione) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>