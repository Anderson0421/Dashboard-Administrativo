// Obtener el menú lateral
const sideMenu = document.querySelector("aside");

// Obtener el botón para mostrar el menú
const menuBtn = document.querySelector("#menu-btn");
menuBtn.addEventListener('click', () => {
    sideMenu.style.display = 'block';
});

// Obtener el botón para cerrar el menú
const closeBtn = document.querySelector("#close-btn");
    closeBtn.addEventListener('click', () => {
    sideMenu.style.display = 'none';
});

// Obtener el elemento para cambiar el tema
const themeToggler = document.querySelector(".theme-toggler");
themeToggler.addEventListener('click', () => {
    document.body.classList.toggle('dark-theme-variables');
    themeToggler.querySelector('span:nth-child(1)').classList.toggle('active');
    themeToggler.querySelector('span:nth-child(2)').classList.toggle('active');
});

// Obtener los enlaces del menú de navegación
const links = document.querySelectorAll('aside a');

// Obtener las secciones
const sections = document.querySelectorAll('.section');


// Agregar controladores de eventos a los enlaces
links.forEach(link => {
link.addEventListener('click', e => {
    e.preventDefault(); // Evitar el comportamiento predeterminado de los enlaces

    // Obtener el id de la sección a la que se enlaza el enlace
    const target = link.getAttribute('href');

    // Ocultar todas las secciones
    sections.forEach(section => {
    section.classList.remove('active');
    });

    // Mostrar la sección objetivo
    document.querySelector(target).classList.add('active');
    });
});


// Obtener los enlaces del menú de navegación
const links1 = document.querySelectorAll('.sidebar a');

// Agregar controladores de eventos a los enlaces
links1.forEach(link => {
  link.addEventListener('click', e => {
    // Eliminar la clase "active" de todos los enlaces
    links1.forEach(link => {
      link.classList.remove('active');
    });

    // Agregar la clase "active" al enlace seleccionado
    link.classList.add('active');
  });
});
