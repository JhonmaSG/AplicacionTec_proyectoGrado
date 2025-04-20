//logout
function cerrarSesion() {
    // Petición al servidor para cerrar sesión
    fetch('/proyectoGrado/app/Acceso/logout.php', {
        method: 'POST',
        credentials: 'include'
      })
      .then(response => {
        if (response.ok) {
          window.location.href = '/proyectoGrado/app/Acceso/logout.php';
        } else {
          alert("Error al cerrar sesión");
        }
      })
      .catch(error => console.error('Error:', error));
  }