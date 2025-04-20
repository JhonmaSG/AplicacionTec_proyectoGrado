export function subirArriba() {
    window.scrollTo({ top: 0, behavior: "smooth" });

    const container = document.querySelector(".container");
    if (container && container.scrollTop > 0) {
        container.scrollTo({ top: 0, behavior: "smooth" });
    }

    const btnSubir = document.getElementById("btnSubir");
    setTimeout(() => {
        btnSubir.style.display = "none";
    }, 800);
}

export function desplazamientoGrafica() {
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