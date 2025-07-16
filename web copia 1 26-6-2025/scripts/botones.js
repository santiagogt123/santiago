document.addEventListener("DOMContentLoaded", () => {
  // Seleccionamos los enlaces del menú
  const navLinks = document.querySelectorAll(".nav-menu a");

  // Seleccionamos todas las secciones del main
  const sections = document.querySelectorAll("main section");

  // Seleccionamos el menú de navegación
  const nav = document.querySelector("nav");

  // Seleccionamos el botón con la flechita
  const btnVolver = document.getElementById("btn-volver-inicio");

  // Función para mostrar una sección específica
  function mostrarSeccion(idSeccion) {
    // Ocultamos todas las secciones
    sections.forEach(s => s.classList.remove("active"));

    // Buscamos la sección que queremos mostrar
    const seccion = document.getElementById(idSeccion);
    if (!seccion) return;

    // Mostramos la sección activándola
    seccion.classList.add("active");

    // Hacemos scroll suave hacia la sección
    seccion.scrollIntoView({ behavior: "smooth" });

    // Si es la sección de series, ocultamos la barra de navegación
    if (idSeccion === "series") {
      nav.style.display = "none";
      btnVolver.style.display = "block";
    } else {
      nav.style.display = "flex";
      btnVolver.style.display = "none";
    }
  }

  // Cuando se hace clic en los enlaces del menú
  navLinks.forEach(link => {
    link.addEventListener("click", e => {
      e.preventDefault();
      const targetId = link.getAttribute("data-section");
      mostrarSeccion(targetId);
    });
  });

  // Cuando se hace clic en el botón de volver al inicio
  btnVolver.addEventListener("click", () => {
    // Volvemos a mostrar la navegación
    nav.style.display = "flex";

    // Ocultamos el botón
    btnVolver.style.display = "none";

    // Quitamos cualquier sección activa
    sections.forEach(s => s.classList.remove("active"));

    // Hacemos scroll hacia arriba de la página
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
});
