<!-- Botón para abrir el modal -->

<!-- Modal -->
<div class="modal fade" id="modal-datos" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label">ACTUALIZAR</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="form-datos">
                    <!-- Nombre de la materia -->
                    <div class="mb-3">
                        <label for="nombre-materia" class="form-label">Nombre de la Materia:</label>
                        <input type="text" class="form-control" id="nombre-materia" name="nombre_materia" required autocomplete="off">
                        <div id="lista-sugerencias" class="dropdown-menu show" style="display: none;"></div>
                    </div>

                    <!-- Selección de verbos -->
                    <div class="mb-3">
                        <label for="verb-filter_popup" class="form-label">Verbos:</label>
                        <select id="verb-filter_popup" multiple class="form-control" size="5">
                            <option hidden>Selecciona uno o varios verbos</option>
                        </select>
                    </div>

                    <!-- Lista de verbos seleccionados -->
                    <div class="mb-3">
                        <label>Verbos seleccionados:</label>
                        <div style="max-height: 150px; overflow-y: auto;">
                            <ul id="verbos-seleccionados" class="list-group"></ul>
                        </div>
                    </div>

                    <!-- Campo oculto para IDs de los verbos -->
                    <input type="hidden" id="verbo_id" name="verbo_id">

                    <!-- Mensaje de error -->
                    <div id="error-message" class="alert alert-danger d-none" role="alert"></div>

                    <!-- Botones de acción -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Atras</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>