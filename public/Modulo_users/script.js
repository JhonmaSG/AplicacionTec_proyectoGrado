$(document).ready(function () {
    let timeout;

    function cargarUsuarios(busqueda = '') {
        $.ajax({
            url: 'includes/obtener_usuarios.php',
            method: 'GET',
            data: { busqueda: busqueda },
            dataType: 'json',
            success: function (response) {
                const tbody = $('#tbody-usuarios');
                tbody.empty();

                if (response.success) {
                    const usuarios = response.usuarios;

                    if (usuarios.length === 0) {
                        tbody.append('<tr><td colspan="8">No se encontraron usuarios.</td></tr>');
                        return;
                    }

                    usuarios.forEach(usuario => {
                        let fila = `
                          <tr>
                              <td>${usuario.cedula}</td>
                              <td>${usuario.nombres}</td>
                              <td>${usuario.apellidos}</td>
                              <td>${usuario.correo}</td>
                              <td>${usuario.fecha_acceso || 'Sin acceso registrado'}</td>
                              <td>${usuario.rol_nombre}</td>
                              <td>${usuario.modulo || 'N/A'}</td>`;
                        if (ROL_USUARIO === 0){
                            fila += `
                                <td>
                                    <button class="btn btn-sm btn-warning btn-editar" 
                                         data-cedula="${usuario.cedula}"
                                        data-nombres="${usuario.nombres}"
                                        data-apellidos="${usuario.apellidos}"
                                        data-correo="${usuario.correo}"
                                        data-rol="${usuario.rol_id}">
                                    Editar
                                    </button>
                                </td>`;
                        }
                        fila += `</tr>`;
                        tbody.append(fila);
                    });
                } else {
                    alert('Error al obtener los usuarios: ' + response.error);
                }
            }
        });
    }

    // Cargar todos
    cargarUsuarios();

    $('#search-user').on('keyup', function () {
        clearTimeout(timeout);
        const busqueda = $(this).val();
        timeout = setTimeout(function () {
            cargarUsuarios(busqueda);
        }, 1000);
    });
});

                  
// Mostrar el modal con datos del usuario
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
                cargarUsuarios();
            } else {
                alert('Error al actualizar usuario: ' + response.error);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error AJAX:', status, error);
            console.error('Respuesta del servidor:', xhr.responseText);
            alert('Error de conexión con el servidor.');
        }
    });
});
 

