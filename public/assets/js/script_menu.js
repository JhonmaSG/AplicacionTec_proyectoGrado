// Obtener los elementos del DOM
const menuBtn = document.getElementById('menu-btn');
const menu = document.getElementById('menu');

// Función para alternar la visibilidad del menú
menuBtn.addEventListener('click', () => {
  menu.classList.toggle('show');
});
