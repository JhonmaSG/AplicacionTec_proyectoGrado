// Función para botón de subirArriba
export function subirArriba() {
    // Desplazar la ventana al inicio
    window.scrollTo({ top: 0, behavior: 'smooth' });
  
    // Desplazar el contenedor .container si existe
    const container = document.querySelector('.container');
    if (container && container.scrollTop > 0) {
      container.scrollTo({ top: 0, behavior: 'smooth' });
    }
  
    // Ocultar el botón después de 800ms
    const btnSubir = document.getElementById('btnSubir');
    if (btnSubir) {
      setTimeout(() => {
        btnSubir.style.display = 'none';
      }, 800);
    }
  }
// Función para desplazamiento de gráfica
export function desplazamientoGrafica(evitarDesplazamientoGlobal) {
    if(!evitarDesplazamientoGlobal){
        const contenido = document.getElementById("container-btn-grafica");
        const graficaContainer = document.getElementById("grafica-container");
        const btnSubir = document.getElementById("btnSubir");
    
        if (contenido.classList.contains("d-none")) {
            contenido.classList.remove("d-none");
            contenido.classList.add("d-block");
            graficaContainer.classList.add("nuevo-grafica-container");
            graficaContainer.style.height = "700px";
        }
    
        graficaContainer.scrollIntoView({
            behavior: "smooth",
            block: "start"
        });
    
        btnSubir.style.display = "block";
    }
}