// ==========================================
// 🍔 MENÚ HAMBURGUESA
// Muestra/oculta el menú en móvil al hacer clic
// ==========================================

const header = document.getElementById('encabezado');
const menuLista = document.querySelector('.menu-principal ul');

// Creamos el botón hamburguesa dinámicamente (no ensucia el HTML)
const btnHamburguesa = document.createElement('button');
btnHamburguesa.classList.add('btn-hamburguesa');
btnHamburguesa.setAttribute('aria-label', 'Abrir menú');
btnHamburguesa.setAttribute('aria-expanded', 'false');
btnHamburguesa.innerHTML = `
    <span></span>
    <span></span>
    <span></span>
`;
header.appendChild(btnHamburguesa);

// Alterna la clase 'abierto' al hacer clic — el CSS maneja la animación
btnHamburguesa.addEventListener('click', () => {
    const estaAbierto = menuLista.classList.toggle('menu-abierto');
    btnHamburguesa.classList.toggle('activo');
    btnHamburguesa.setAttribute('aria-expanded', estaAbierto);
});

// Cierra el menú al hacer clic en cualquier enlace del menú
document.querySelectorAll('.menu-principal a').forEach(enlace => {
    enlace.addEventListener('click', () => {
        menuLista.classList.remove('menu-abierto');
        btnHamburguesa.classList.remove('activo');
        btnHamburguesa.setAttribute('aria-expanded', 'false');
    });
});

// Cierra el menú si el usuario hace clic fuera del header
document.addEventListener('click', (e) => {
    if (!header.contains(e.target)) {
        menuLista.classList.remove('menu-abierto');
        btnHamburguesa.classList.remove('activo');
        btnHamburguesa.setAttribute('aria-expanded', 'false');
    }
});


// ==========================================
// 📌 HEADER SCROLL
// Añade clase 'scrolled' al header cuando el usuario baja
// Permite hacer el header más compacto con CSS
// ==========================================

window.addEventListener('scroll', () => {
    if (window.scrollY > 60) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
}, { passive: true }); // passive: true mejora el rendimiento del scroll


// ==========================================
// 🎯 RESALTAR ENLACE ACTIVO EN EL MENÚ
// Detecta qué sección está visible y marca su enlace en el menú
// ==========================================

const secciones = document.querySelectorAll('section[id], footer[id]');
const enlacesMenu = document.querySelectorAll('.menu-principal a');

// IntersectionObserver es más eficiente que escuchar el scroll
const observadorSecciones = new IntersectionObserver((entradas) => {
    entradas.forEach(entrada => {
        if (entrada.isIntersecting) {
            // Quitamos 'activo' de todos los enlaces
            enlacesMenu.forEach(a => a.classList.remove('activo'));
            // Marcamos el enlace que corresponde a la sección visible
            const enlaceActivo = document.querySelector(`.menu-principal a[href="#${entrada.target.id}"]`);
            if (enlaceActivo) enlaceActivo.classList.add('activo');
        }
    });
}, {
    rootMargin: '-40% 0px -55% 0px' // Activa cuando la sección está centrada en pantalla
});

secciones.forEach(sec => observadorSecciones.observe(sec));


// ==========================================
// 👁️ ANIMACIÓN DE ENTRADA EN TARJETAS DE SERVICIOS
// Las tarjetas aparecen con fade+slide al entrar en pantalla
// ==========================================

const tarjetasServicio = document.querySelectorAll('.servicio-card');

const observadorTarjetas = new IntersectionObserver((entradas) => {
    entradas.forEach((entrada, i) => {
        if (entrada.isIntersecting) {
            // Pequeño delay escalonado para cada tarjeta (efecto cascada)
            setTimeout(() => {
                entrada.target.classList.add('visible');
            }, i * 100);
            // Una vez animada, dejamos de observarla (no se repite)
            observadorTarjetas.unobserve(entrada.target);
        }
    });
}, { threshold: 0.15 });

tarjetasServicio.forEach(tarjeta => observadorTarjetas.observe(tarjeta));


// ==========================================
// 🔝 BOTÓN "VOLVER ARRIBA"
// Aparece al bajar 400px y lleva al inicio al hacer clic
// ==========================================

const btnArriba = document.createElement('button');
btnArriba.classList.add('btn-arriba');
btnArriba.setAttribute('aria-label', 'Volver arriba');
btnArriba.innerHTML = '↑';
document.body.appendChild(btnArriba);

window.addEventListener('scroll', () => {
    // Muestra el botón solo si el usuario bajó suficiente
    btnArriba.classList.toggle('visible', window.scrollY > 350);
}, { passive: true });

btnArriba.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});


// ==========================================
// 📅 AÑO DINÁMICO EN EL COPYRIGHT
// Actualiza el año automáticamente sin tocar el HTML
// ==========================================

const copyright = document.querySelector('.footer-copy p');
if (copyright) {
    // Reemplaza el año hardcodeado por el año actual del sistema
    copyright.innerHTML = copyright.innerHTML.replace(
        /\d{4}/,
        new Date().getFullYear()
    );
}