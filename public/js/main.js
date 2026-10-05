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
// 📢 BOTÓN FLOTANTE "EVENTOS Y PROMOCIONES"
// Abre el modal de eventos manualmente al hacer clic
// ==========================================

const btnAbrirModal = document.createElement('button');
btnAbrirModal.classList.add('btn-abrir-modal-eventos');
btnAbrirModal.setAttribute('aria-label', 'Ver eventos y promociones');
btnAbrirModal.setAttribute('title', 'Ver eventos y promociones');
btnAbrirModal.innerHTML = '<i class="bi bi-megaphone-fill"></i>';
document.body.appendChild(btnAbrirModal);

// Sincronizar visibilidad con el scroll (igual que btnArriba)
window.addEventListener('scroll', () => {
    btnAbrirModal.classList.toggle('visible', window.scrollY > 1);
}, { passive: true });

// Al hacer clic, abre el modal de eventos (forzando apertura)
btnAbrirModal.addEventListener('click', () => {
    iniciarModalEventos(true);
});

// ==========================================
// 🔝 BOTÓN "VOLVER ARRIBA"
// Aparece al bajar 375px y lleva al inicio al hacer clic
// ==========================================

const btnArriba = document.createElement('button');
btnArriba.classList.add('btn-arriba');
btnArriba.setAttribute('aria-label', 'Volver arriba');
btnArriba.innerHTML = '↑';
document.body.appendChild(btnArriba);

window.addEventListener('scroll', () => {
    // Muestra el botón solo si el usuario bajó suficiente
    btnArriba.classList.toggle('visible', window.scrollY > 375);
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


// ==========================================
// 🎯 MODAL EVENTOS (STORIES STYLE)
// ==========================================

// Array de imágenes del evento — agregar/quitar según existan en img/
const EVENTO_SLIDES = [
    //Formato: { src: 'ruta/imagen.webp', orientacion: 'vertical' | 'horizontal' }
  // Agregar más según corresponda
  { src: 'img/promo-catalogo-01.webp', orientacion: 'vertical' },
  { src: 'img/evento-v-02.webp', orientacion: 'vertical' },
  { src: 'img/evento-v-03.webp', orientacion: 'vertical' },
  //{ src: 'img/evento-h-03.webp', orientacion: 'horizontal' },
  { src: 'img/evento-v-04.webp', orientacion: 'vertical' },
  //{ src: 'img/evento-h-04.webp', orientacion: 'horizontal' }
  { src: 'img/evento-v-05.webp', orientacion: 'vertical' },
];

const SLIDE_DURACION = 5000; // ms por slide (ritmo suave, ajustable)
const SESSION_KEY = 'unity_modal_evento_visto';

// Variables de estado interno
let slideActual = 0;
let animacionFrameId = null;
let tiempoTranscurrido = 0;
let ultimoTimestamp = null;
let estaPausado = false;
let modalActivo = false;
let listenersRegistrados = false;
let elementoFocusPrevio = null;

/**
 * Renderiza o inicializa las barras de progreso según EVENTO_SLIDES
 */
function inicializarBarrasProgreso() {
    const barraWrap = document.querySelector('.modal-ev-barra-wrap');
    if (!barraWrap) return;

    // Si ya existen las barras en el DOM, las usamos; si no, las creamos dinámicamente
    const items = barraWrap.querySelectorAll('.modal-ev-barra-item');
    if (items.length !== EVENTO_SLIDES.length) {
        barraWrap.innerHTML = '';
        EVENTO_SLIDES.forEach((_, idx) => {
            const item = document.createElement('div');
            item.classList.add('modal-ev-barra-item');

            const fill = document.createElement('div');
            fill.classList.add('modal-ev-barra-fill');
            item.appendChild(fill);

            // Permite saltar a un slide específico haciendo clic en su barra
            item.addEventListener('click', (e) => {
                e.stopPropagation();
                mostrarSlide(idx);
            });

            barraWrap.appendChild(item);
        });
    }
}

/**
 * Anima la barra del slide activo con requestAnimationFrame
 * Al alcanzar SLIDE_DURACION avanza automáticamente al siguiente slide
 */
function pasoProgreso(timestamp) {
    if (!modalActivo) return;

    if (!ultimoTimestamp) {
        ultimoTimestamp = timestamp;
    }

    if (!estaPausado) {
        const delta = timestamp - ultimoTimestamp;
        tiempoTranscurrido += delta;

        const porcentaje = Math.min((tiempoTranscurrido / SLIDE_DURACION) * 100, 100);

        const fills = document.querySelectorAll('.modal-ev-barra-fill');
        if (fills[slideActual]) {
            fills[slideActual].style.width = `${porcentaje}%`;
        }

        if (tiempoTranscurrido >= SLIDE_DURACION) {
            animacionFrameId = null;
            avanzarSlide();
            return;
        }
    }

    ultimoTimestamp = timestamp;
    animacionFrameId = requestAnimationFrame(pasoProgreso);
}

/**
 * Inicia la animación de progreso del slide activo
 */
function iniciarProgreso() {
    detenerProgreso();
    ultimoTimestamp = null;
    animacionFrameId = requestAnimationFrame(pasoProgreso);
}

/**
 * Cancela cualquier animación de progreso en ejecución
 */
function detenerProgreso() {
    if (animacionFrameId) {
        cancelAnimationFrame(animacionFrameId);
        animacionFrameId = null;
    }
}

/**
 * Pausa temporalmente el avance del slide (ej. hover o toque en pantalla)
 */
function pausarProgreso() {
    estaPausado = true;
}

/**
 * Reanuda el avance del slide activo tras una pausa
 */
function reanudarProgreso() {
    if (estaPausado) {
        estaPausado = false;
        ultimoTimestamp = performance.now();
    }
}

/**
 * Muestra el slide en el índice indicado, actualiza imagen, barritas y flechas
 * @param {number} index - Índice del slide a mostrar
 */
function mostrarSlide(index) {
    if (!EVENTO_SLIDES || EVENTO_SLIDES.length === 0) return;
    if (index < 0 || index >= EVENTO_SLIDES.length) return;

    detenerProgreso();
    slideActual = index;
    tiempoTranscurrido = 0;
    ultimoTimestamp = null;
    estaPausado = false;

    const slide = EVENTO_SLIDES[slideActual];
    const imgElement = document.querySelector('.modal-ev-imagen');
    const contenedor = document.querySelector('.modal-ev-contenedor');

    if (imgElement) {
        imgElement.src = slide.src;
        imgElement.alt = `Evento especial ${slideActual + 1}`;
    }

    if (contenedor && slide.orientacion) {
        contenedor.setAttribute('data-orientacion', slide.orientacion);
    }

    // Actualizar barras de progreso (pasadas al 100%, futuras al 0%, activa a 0%)
    const fills = document.querySelectorAll('.modal-ev-barra-fill');
    fills.forEach((fill, idx) => {
        if (idx < slideActual) {
            fill.style.width = '100%';
        } else {
            fill.style.width = '0%';
        }
    });

    // Actualizar estado de flechas de navegación
    const btnAnterior = document.querySelector('.modal-ev-anterior');
    if (btnAnterior) {
        btnAnterior.disabled = (slideActual === 0);
        btnAnterior.classList.toggle('deshabilitado', slideActual === 0);
    }

    // Iniciar progreso automático
    iniciarProgreso();
}

/**
 * Avanza al siguiente slide en bucle infinito (vuelve al inicio si llegó al final)
 */
function avanzarSlide() {
    if (slideActual + 1 < EVENTO_SLIDES.length) {
        mostrarSlide(slideActual + 1);
    } else {
        mostrarSlide(0);
    }
}

/**
 * Retrocede al slide anterior si existe
 */
function retrocederSlide() {
    if (slideActual > 0) {
        mostrarSlide(slideActual - 1);
    } else {
        // Si está en el primer slide, reinicia el tiempo del slide 0
        mostrarSlide(0);
    }
}

/**
 * Cierra el modal, guarda en sessionStorage y detiene la animación
 */
function cerrarModal() {
    modalActivo = false;
    detenerProgreso();

    const overlay = document.querySelector('.modal-ev-overlay');
    if (overlay) {
        overlay.classList.remove('activo');
        overlay.setAttribute('aria-hidden', 'true');
        overlay.setAttribute('hidden', '');
    }

    document.body.classList.remove('modal-ev-abierto');

    if (elementoFocusPrevio) elementoFocusPrevio.focus();

    try {
        sessionStorage.setItem(SESSION_KEY, '1');
    } catch (e) {
        // En caso de modo incógnito restrictivo o almacenamiento deshabilitado
        console.warn('No se pudo guardar en sessionStorage:', e);
    }
}

/**
 * Registra los event listeners del modal una sola vez
 */
function registrarEventListenersModal() {
    if (listenersRegistrados) return;

    const overlay = document.querySelector('.modal-ev-overlay');
    const contenedor = document.querySelector('.modal-ev-contenedor');
    const btnCerrar = document.querySelector('.modal-ev-cerrar');
    const btnSiguiente = document.querySelector('.modal-ev-siguiente');
    const btnAnterior = document.querySelector('.modal-ev-anterior');

    // Botón cerrar
    if (btnCerrar) {
        btnCerrar.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            cerrarModal();
        });
    }

    // Flechas de navegación
    if (btnSiguiente) {
        btnSiguiente.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            avanzarSlide();
        });
    }

    if (btnAnterior) {
        btnAnterior.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            retrocederSlide();
        });
    }

    // Clic en overlay (fuera del contenedor) para cerrar
    if (overlay) {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                cerrarModal();
            }
        });
    }

    // Pausar en hover sobre el modal
    if (contenedor) {
        contenedor.addEventListener('mouseenter', pausarProgreso);
        contenedor.addEventListener('mouseleave', reanudarProgreso);

        // Soporte para interacción táctil en dispositivos móviles
        contenedor.addEventListener('touchstart', pausarProgreso, { passive: true });
        contenedor.addEventListener('touchend', reanudarProgreso, { passive: true });
        contenedor.addEventListener('touchcancel', reanudarProgreso, { passive: true });
    }

    // Tecla Escape para cerrar
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modalActivo) {
            cerrarModal();
        }
    });

    listenersRegistrados = true;
}

/**
 * Inicializa el modal de eventos stories-style al cargar la página o al forzar apertura
 * @param {boolean} [forzar=false] - Si es true, ignora sessionStorage y abre el modal
 */
function iniciarModalEventos(forzar = false) {
    // Si ya fue visto en esta sesión y no se fuerza explícitamente, no hacer nada
    if (forzar !== true) {
        try {
            if (sessionStorage.getItem(SESSION_KEY)) {
                return;
            }
        } catch (e) {
            console.warn('No se pudo acceder a sessionStorage:', e);
        }
    }

    const overlay = document.querySelector('.modal-ev-overlay');
    if (!overlay || !EVENTO_SLIDES || EVENTO_SLIDES.length === 0) {
        return;
    }

    // Registrar eventos y preparar barras
    registrarEventListenersModal();
    inicializarBarrasProgreso();

    // Guardar elemento con foco previo antes de abrir el modal
    elementoFocusPrevio = document.activeElement;

    // Abrir modal y mostrar primer slide
    modalActivo = true;
    overlay.removeAttribute('hidden');
    overlay.classList.add('activo');
    overlay.setAttribute('aria-hidden', 'false');
    document.body.classList.add('modal-ev-abierto');

    mostrarSlide(0);

    const btnCerrar = document.querySelector('.modal-ev-cerrar');
    if (btnCerrar) btnCerrar.focus();
}

// Inicialización automática al cargar el DOM
document.addEventListener('DOMContentLoaded', iniciarModalEventos);

// Exposición pública opcional para depuración o llamadas externas
window.UnityModalEventos = {
    iniciar: iniciarModalEventos,
    abrir: () => iniciarModalEventos(true),
    cerrar: cerrarModal,
    mostrarSlide,
    avanzarSlide,
    retrocederSlide,
    pausar: pausarProgreso,
    reanudar: reanudarProgreso
};