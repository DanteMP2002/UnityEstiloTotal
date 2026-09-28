<?php
// Mensajes de condiciones y avisos importantes
$mensajePromociones = "Las promociones y combos no son acumulables con otros descuentos y están sujetos a cambios sin previo aviso.";
$mensajeReserva = "Se recomienda reservar con anticipación para asegurar disponibilidad de horarios. Las reservas se confirman vía WhatsApp o llamada telefónica.";
$mensajeGeneral = "CONDICIONES GENERALES DE TARIFAS: Todos los precios mostrados en nuestro menú son precios base referenciales. Debido a que cada procedimiento es personalizado, el costo definitivo se determinará mediante un diagnóstico previo en el local. Le solicitamos cordialmente validar y llegar a un acuerdo sobre el presupuesto final con su estilista o especialista asignado antes de iniciar cualquier servicio. Agradecemos su comprensión.";
// SVG optimizado con tamaño adaptable (1em)

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNITY| INICIO</title>
    <link rel="icon" href="/public/img/logo.webp" type="image/x-icon">
    <link rel="preload" as="image" href="/public/img/hero1.webp" fetchpriority="high">
    <link rel="stylesheet" href="/public/css/estilo.css">
    <!-- Fuentes: Playfair Display para titulos elegantes, Inter para texto limpio -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<!--bootstrap 5.3.8 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<!-- icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>
<body>
<!--  HEADER: logo y navegación  -->
    <header id="encabezado">
        <div class="logo">
            <a href="https://unitycetpro.wuaze.com/">
                <img src="/public/img/logo-dorado.webp" alt="Logo Unity">
            </a>
        </div>
        <nav class="menu-principal">
            <ul>
                <li><a href="#inicio">Inicio</a></li>
                <li><a href="#servicios">Servicios</a></li>
                <li><a href="#nosotros">Nosotros</a></li>
                <li><a href="#contacto">Contacto</a></li>
            </ul>
        </nav>
    </header>

<!--  HERO: imagen principal arriba + texto con fondo abajo  -->
    <section id="inicio" class="hero">
    <!-- Zona 1: imagen principal visible, sin texto -->
        <div class="hero-imagen-principal">
            <img src="/public/img/hero-2.webp"
                 alt="Unity - Salón de belleza y barbería profesional en Zárate, San Juan de Lurigancho"
                 class="hero-img-principal"
                 fetchpriority="high"
                 loading="eager">
        </div>
    <!-- Zona 2: tarjeta de texto sobre fondo decorativo -->
        <div class="hero-fondo">
            <div class="hero-card">
                <div class="hero-badge">
                    <i class="bi bi-stars"></i> Belleza &amp; Barbería de Alta Gama en Zárate, SJL
                </div>
                <h1>Tu mejor<br><em>versión</em></h1>
                <p class="hero-descripcion">Peluquería profesional, barbería tradicional, tratamientos faciales y diseño de uñas. Un espacio creado para potenciar tu estilo único.</p>
                <div class="hero-acciones">
                    <a href="https://wa.me/51920134856?text=<?= rawurlencode('Hola Unity, deseo agendar una cita.') ?>" target="_blank" class="btn-hero-primary"><i class="bi bi-whatsapp"></i> Reservar Cita</a>
                    <a href="#servicios" class="btn-hero-secondary">Ver Servicios <i class="bi bi-arrow-down-short"></i></a>
                </div>
                <div class="hero-puntos">
                    <span><i class="bi bi-check2-circle"></i> Asesoría personalizada</span>
                    <span><i class="bi bi-check2-circle"></i> Productos de alta gama</span>
                    <span><i class="bi bi-check2-circle"></i> Protocolos garantizados</span>
                </div>
            </div>
        </div>
    </section>

<!--  SERVICIOS: cabecera + tarjetas interactivas  -->
    <section id="servicios" class="seccion-servicios-area">
        <div class="servicios-header-box">
            <span class="servicios-subtitulo">Experiencias Exclusivas</span>
            <h2 class="servicios-titulo">Nuestros Servicios</h2>
            <div class="titulo-adorno"></div>
            <p class="servicios-intro">
                Elige cualquiera de nuestras especialidades para consultar precios detallados, tiempos de atención y agendar tu visita.
            </p>
        </div>
        <div class="servicios-grid-cards row gx-0">
        <!-- Peluquería -->
            <article class="servicio-card col-12 col-md-6" style="background-image: url('/public/img/peluqueria-fondo-2.webp');" data-bs-target="#modal-peluqueria" data-bs-toggle="modal" role="button" tabindex="0">
                <div class="servicio-overlay">
                    <h2>Peluquería & Color</h2>
                        <p>Cortes modernos, balayage, tintes, peinados y tratamientos capilares intensivos.</p>
                    <div class="servicio-tags">
                        <span>Cortes</span><span>Coloración</span><span>Keratina</span>
                    </div>
                    <span class="btn-servicio">Ver tarifas & agendar <i class="bi bi-arrow-right"></i></span>
                </div>
            </article>

        <!-- Barbería -->
            <article class="servicio-card col-12 col-md-6" style="background-image: url('/public/img/barberia-fondo-1.jpg');" data-bs-target="#modal-barberia" data-bs-toggle="modal" role="button" tabindex="0">
                <div class="servicio-overlay">
                    <h2>Barbería Clásica</h2>
                        <p>Cortes en degradé, perfilado, arreglo de barba y tratamiento con toalla caliente.</p>
                    <div class="servicio-tags">
                        <span>Fade</span><span>Barba</span><span>Pack Completo</span>
                    </div>
                    <span class="btn-servicio">Ver tarifas & agendar <i class="bi bi-arrow-right"></i></span>
                </div>
            </article>

        <!-- Salón de Belleza -->
            <article class="servicio-card col-12 col-md-6" style="background-image: url('/public/img/salon-fondo-2.webp');" data-bs-target="#modal-belleza" data-bs-toggle="modal" role="button" tabindex="0">
                <div class="servicio-overlay">
                    <h2>Salón & Estética</h2>
                        <p>Limpieza facial profunda, maquillaje profesional, depilación con hilo y cejas.</p>
                    <div class="servicio-tags">
                        <span>Faciales</span><span>Maquillaje</span><span>Cejas</span>
                    </div>
                    <span class="btn-servicio">Ver tarifas & agendar <i class="bi bi-arrow-right"></i></span>
                </div>
            </article>

        <!-- Diseño de Uñas -->
            <article class="servicio-card col-12 col-md-6" style="background-image: url('/public/img/unas-fondo-3.webp');" data-bs-target="#modal-unias" data-bs-toggle="modal" role="button" tabindex="0">
                <div class="servicio-overlay">
                    <h2>Diseño de Uñas</h2>
                        <p>Esmaltado semipermanente, uñas acrílicas, baño de gel y pedicure spa regenerativo.</p>
                    <div class="servicio-tags">
                        <span>Semipermanente</span><span>Acrílicas</span><span>Pedicure</span>
                    </div>
                    <span class="btn-servicio">Ver tarifas & agendar <i class="bi bi-arrow-right"></i></span>
                </div>
            </article>
        </div>
    </section>

<!--  SOBRE NOSOTROS  -->
    <section id="nosotros" class="seccion-nosotros">
        <div class="nosotros-contenedor">
            
        <!-- Encabezado de la sección -->
            <div class="nosotros-header">
                <span class="nosotros-subtitulo">Conoce Nuestra Esencia</span>
                <h2 class="nosotros-titulo">Sobre Nosotros</h2>
                <div class="titulo-adorno"></div>
            </div>

        <!-- Fila principal: Imagen representativa + Historia -->
            <div class="nosotros-grid-principal">
                <div class="nosotros-img-wrapper">
                    <div class="nosotros-img-marco">
                        <img src="/public/img/perfil.webp" alt="Instalaciones de Unity Estilo Total" class="nosotros-img">
                        <div class="nosotros-badge-flotante">
                            <i class="bi bi-award-fill"></i>
                            <div>
                                <strong>Calidad & Pasión</strong>
                                <span>Atención personalizada en Zárate</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="nosotros-historia">
                    <h3>Donde tu estilo se encuentra con la excelencia</h3>
                    <p class="nosotros-lead">
                        En <strong class="text-uppercase">Unity Estilo Total</strong> nacimos con una misión clara: brindar una experiencia integral de belleza y cuidado personal en un solo lugar, con los más altos estándares profesionales.
                    </p>
                    <p>
                        Ubicados en el corazón de Zárate, San Juan de Lurigancho, combinamos técnicas tradicionales de barbería con las últimas tendencias internacionales en peluquería, coloración, tratamientos faciales y diseño de uñas. Cada detalle está pensado para que te sientas cómodo, renovado y seguro de tu mejor versión.
                    </p>

                <!-- Estadísticas / Métricas de confianza -->
                    <div class="nosotros-stats">
                        <div class="stat-item">
                            <span class="stat-numero">+3</span>
                            <span class="stat-etiqueta">Años de experiencia</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-numero">+1.5K</span>
                            <span class="stat-etiqueta">Clientes satisfechos</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-numero">4 en 1</span>
                            <span class="stat-etiqueta">Servicios integrales</span>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Fila secundaria: 4 Pilares / Valores -->
            <div class="nosotros-valores-grid">
                <div class="valor-card">
                    <div class="valor-icono">
                        <i class="bi bi-scissors"></i>
                    </div>
                    <h4>Profesionales Expertos</h4>
                    <p>Especialistas dedicados y en constante actualización en tendencias de corte, barbería y estética.</p>
                </div>

                <div class="valor-card">
                    <div class="valor-icono">
                        <i class="bi bi-stars"></i>
                    </div>
                    <h4>Productos de Alta Gama</h4>
                    <p>Cuidamos tu cabello y piel con marcas de prestigio que aseguran acabados duraderos y saludables.</p>
                </div>

                <div class="valor-card">
                    <div class="valor-icono">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h4>Higiene y Bioseguridad</h4>
                    <p>Herramientas rigurosamente esterilizadas y protocolos estrictos para garantizar tu tranquilidad.</p>
                </div>

                <div class="valor-card">
                    <div class="valor-icono">
                        <i class="bi bi-cup-hot"></i>
                    </div>
                    <h4>Ambiente Confortable</h4>
                    <p>Espacios modernos y acogedores para que tu visita sea un momento de auténtico relax.</p>
                </div>
            </div>

        <!-- Llamado a la acción con redes -->
            <div class="nosotros-social-banner">
                <div class="social-banner-contenido">
                    <h4>¿Quieres ver nuestros últimos trabajos y transformaciones?</h4>
                    <p>Sigue nuestro día a día, promociones y videos en vivo en nuestras redes sociales oficiales.</p>
                </div>
                <div class="social-banner-botones">
                    <a href="https://www.instagram.com/unity.estilototal/" target="_blank" rel="noopener noreferrer" class="btn-social-banner btn-ig">
                        <i class="bi bi-instagram"></i> Instagram
                    </a>
                    <a href="https://www.facebook.com/profile.php?id=61594175556210" target="_blank" rel="noopener noreferrer" class="btn-social-banner btn-fb">
                        <i class="bi bi-facebook"></i> Facebook
                    </a>
                    <a href="https://www.tiktok.com/@unity.estilototal" target="_blank" rel="noopener noreferrer" class="btn-social-banner btn-tt">
                        <i class="bi bi-tiktok"></i> TikTok
                    </a>
                </div>
            </div>

        </div>
    </section>

<!--  FOOTER: información completa, navegación, horarios, mapa y contacto  -->
    <footer id="contacto" class="footer-sitio">
        <div class="footer-contenedor">
            <div class="footer-grid">

            <!-- Columna 1: Marca & Redes Sociales -->
                <div class="footer-col footer-marca">
                    <a href="#inicio" class="footer-logo">
                        <img src="/public/img/logo-dorado.webp" alt="Logo Unity Estilo Total">
                    </a>
                    <p class="footer-descripcion">
                        Tu espacio exclusivo en Zárate, SJL. Especialistas en corte unisex, barbería tradicional, coloración, tratamientos faciales y diseño de uñas.
                    </p>
                <!-- Redes Sociales -->
                    <div class="footer-redes">
                        <span class="redes-titulo">Síguenos en Nuestras Redes</span>
                        <div class="redes-iconos">
                            <a href="https://www.facebook.com/profile.php?id=61594175556210" target="_blank" rel="noopener noreferrer" class="red-btn" aria-label="Facebook">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="https://www.instagram.com/unity.estilototal/" target="_blank" rel="noopener noreferrer" class="red-btn" aria-label="Instagram">
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a href="https://www.tiktok.com/@unity.estilototal" target="_blank" rel="noopener noreferrer" class="red-btn" aria-label="TikTok">
                                <i class="bi bi-tiktok"></i>
                            </a>
                            <a href="https://wa.me/51920134856?text=<?= rawurlencode('Hola Unity, deseo solicitar información o reservar una cita.') ?>" target="_blank" rel="noopener noreferrer" class="red-btn red-wsp" aria-label="WhatsApp">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>

            <!-- Columna 2: Servicios & Navegación -->
                <div class="footer-col footer-servicios">
                    <h3 class="footer-titulo">Nuestros Servicios</h3>
                    <ul class="footer-links">
                        <li>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modal-peluqueria"><i class="bi bi-chevron-right"></i> Peluquería </a>
                        </li>
                        <li>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modal-barberia"><i class="bi bi-chevron-right"></i> Barbería </a>
                        </li>
                        <li>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modal-belleza"><i class="bi bi-chevron-right"></i> Salón de Belleza </a>
                        </li>
                        <li>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modal-unias"><i class="bi bi-chevron-right"></i> Diseño de Uñas </a>
                        </li>
                        <li>
                            <a href="#nosotros">
                                <i class="bi bi-chevron-right"></i> Sobre Nosotros
                            </a>
                        </li>
                    </ul>
                </div>

            <!-- Columna 3: Horarios de Atención -->
                <div class="footer-col footer-horarios">
                    <h3 class="footer-titulo">Horario de Atención</h3>
                    <div class="horarios-card">
                        <div class="horario-item">
                            <span class="horario-dias"><i class="bi bi-clock"></i> Lunes a Sábado:</span>
                            <span class="horario-horas">9:00 AM – 9:00 PM</span>
                        </div>
                        <div class="horario-item">
                            <span class="horario-dias"><i class="bi bi-clock-history"></i> Domingos:</span>
                            <span class="horario-horas">10:00 AM – 6:00 PM</span>
                        </div>
                    </div>
                    <div class="footer-cita-box">
                        <p class="cita-info"><i class="bi bi-check2-circle"></i> Atención previa reserva</p>
                        <a href="https://wa.me/51920134856?text=<?= rawurlencode('Hola Unity, deseo agendar una cita.') ?>" target="_blank" class="btn-footer-cita">
                            <i class="bi bi-whatsapp"></i> Reservar Cita
                        </a>
                    </div>
                </div>

            <!-- Columna 4: Contacto & Ubicación con Mapa -->
                <div class="footer-col footer-contacto">
                    <h3 class="footer-titulo">Contacto & Ubicación</h3>
                    <ul class="contacto-lista">
                        <li>
                            <i class="bi bi-geo-alt-fill contacto-icono"></i>
                            <div>
                                <span class="contacto-label">Ubícanos</span>
                                <a href="https://maps.google.com/?q=Cajamarquilla+905+SJL" target="_blank" rel="noopener noreferrer">
                                    Cajamarquilla 905, Zárate, SJL
                                </a>
                            </div>
                        </li>
                        <li>
                            <i class="bi bi-telephone-fill contacto-icono"></i>
                            <div>
                                <span class="contacto-label">Teléfono / WhatsApp</span>
                                <a href="https://wa.me/51920134856" target="_blank">
                                    +51 920 134 856
                                </a>
                            </div>
                        </li>
                        <li>
                            <i class="bi bi-envelope-fill contacto-icono"></i>
                            <div>
                                <span class="contacto-label">Correo Electrónico</span>
                                <a href="mailto:contacto.unityestilos@gmail.com">
                                    contacto.unityestilos@gmail.com
                                </a>
                            </div>
                        </li>
                    </ul>

                <!-- Mini Mapa Embebido -->
                    <div class="footer-mapa-box">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d975.5755348798444!2d-76.99953313045808!3d-12.02270829926326!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105c5ee01df6595%3A0xe6913a74573c28ca!2sJiron%20Cajamarquilla%20905%2C%20San%20Juan%20de%20Lurigancho%2015401!5e0!3m2!1ses-419!2spe!4v1789063935753!5m2!1ses-419!2spe"
                            title="Mapa de ubicación Unity Estilo Total"
                            allowfullscreen="" loading="lazy"
                            referrerpolicy="strict-origin-when-cross-origin">
                        </iframe>
                        <a href="https://maps.google.com/?q=Cajamarquilla+905+SJL" target="_blank" class="mapa-enlace">
                            <i class="bi bi-box-arrow-up-right"></i> Ver en Google Maps
                        </a>
                    </div>
                </div>

            </div>
        </div>

    <!-- Línea de copyright y pie de página -->
        <div class="footer-copy">
            <div class="footer-copy-inner">
                <p>&copy; 2026 Unity Estilo Total &middot; Todos los derechos reservados.</p><p class="developer-credit">Desarrollado por <a href="#" target="_blank" rel="noopener">Dante</a></p>
                <div class="footer-copy-links">
                    <span>San Juan de Lurigancho, Lima</span>
                    <span class="sep">&middot;</span>
                    <a href="#inicio">Ir arriba</a>
                </div>
            </div>
        </div>
    </footer>

<!-- MODALES DE SERVICIOS  -->
    <!-- 1. MODAL PELUQUERÍA -->
    <div class="fade modal modal-luxury" id="modal-peluqueria" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-titulo-wrap">
                        <span class="modal-cat-badge"><i class="bi bi-scissors"></i> Especialidad</span>
                        <h5 class="modal-title">Peluquería & Color</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-subtexto">Servicios profesionales para damas y caballeros con diagnóstico personalizado:</p>
                    <div class="servicios-lista-precios">
                        <div class="servicio-fila-precio">
                            <div class="servicio-datos">
                                <h6>Corte de Cabello Unisex</h6>
                                <p>Cortes de cabello para damas, caballeros y niños.</p>
                                <span class="duracion-pill"><i class="bi bi-clock"></i> 30 - 45 min</span>
                            </div>
                            <div class="servicio-precio-box">
                                <span class="precio-label">A partir de</span>
                                <span class="precio-monto">S/ 10 </span>
                            </div>
                        </div>

                        <div class="servicio-fila-precio">
                            <div class="servicio-datos">
                                <h6>Coloración Capilar Completa & Tintes</h6>
                                <p>El precio dependera del tipo de coloración y el tamaño del cabello.</p>
                                <span class="duracion-pill"><i class="bi bi-clock"></i> 2 horas</span>
                            </div>
                            <div class="servicio-precio-box">
                                <span class="precio-label">A partir de</span>
                                <span class="precio-monto">S/ 30</span>
                            </div>
                        </div>

                        <div class="servicio-fila-precio">
                            <div class="servicio-datos">
                                <h6>Tratamiento capilar</h6>
                                <p>Alisado progresivo, hidratación profunda y eliminación total del frizz.</p>
                                <span class="duracion-pill"><i class="bi bi-clock"></i> 2.5 horas</span>
                            </div>
                            <div class="servicio-precio-box">
                                <span class="precio-label">A partir de</span>
                                <span class="precio-monto">S/ 20</span>
                            </div>
                        </div>

                        <div class="servicio-fila-precio">
                            <div class="servicio-datos">
                                <h6>TEÑIDO DE CEJAS</h6>
                                <p>Diseño y pigmentación semipermanente para dar volumen, definición y color natural a tus cejas.</p>
                                <span class="duracion-pill"><i class="bi bi-clock"></i> 25 min</span>
                            </div>
                            <div class="servicio-precio-box">
                                <span class="precio-label">Precio</span>
                                <span class="precio-monto">S/ 15</span>
                            </div>
                        </div>

                        <div class="servicio-fila-precio">
                            <div class="servicio-datos">
                                <h6>CEPILLADOS</h6>
                                <p>Alaciado profesional y moldeado térmico con cepillo y secadora para un cabello con brillo, movimiento y libre de frizz.</p>
                                <span class="duracion-pill"><i class="bi bi-clock"></i> 25 min</span>
                            </div>
                            <div class="servicio-precio-box">
                                <span class="precio-label">A partir de</span>
                                <span class="precio-monto">S/ 20 - 40</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-aviso-box"> 
                        <i class="bi bi-info-circle-fill"></i>
                        <span>*Los precios en tintes y alisados pueden variar según largo y volumen del cabello previa evaluación.</span>
                    </div>

                    <div class="modal-aviso-box">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>*<?= $mensajeReserva ?></span>
                    </div>

                    <div class="modal-aviso-box">
                        <i class="bi bi-info-circle-fill"></i>
                        <span><?= $mensajeGeneral ?></span>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cerrar" data-bs-dismiss="modal">Cerrar</button>
                    <a href="https://wa.me/51920134856?text=<?= rawurlencode('Hola Unity, deseo consultar y agendar una cita para Peluquería.') ?>" target="_blank" class="btn-modal-reservar">
                        <i class="bi bi-whatsapp"></i><span>Reservar por WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. MODAL BARBERÍA -->
    <div class="fade modal modal-luxury" id="modal-barberia" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-titulo-wrap">
                        <span class="modal-cat-badge"><i class="bi bi-person-badge"></i> Barbería</span>
                        <h5 class="modal-title">Barbería Tradicional & Moderna</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-subtexto">Técnicas de barbería clásica y urbana con navaja descartable y esterilización:</p>
                    <div class="servicios-lista-precios">
                        <div class="servicio-fila-precio">
                            <div class="servicio-datos">
                                <h6>Corte Clásico o Degradé (Fade)</h6>
                                <p>Low, Mid o High Fade con perfilado limpio y producto fijador mate o brillante.</p>
                                <span class="duracion-pill"><i class="bi bi-clock"></i> 30 - 40 min</span>
                            </div>
                            <div class="servicio-precio-box">
                                <span class="precio-label">Precio</span>
                                <span class="precio-monto">S/ 30</span>
                            </div>
                        </div>

                        <div class="servicio-fila-precio">
                            <div class="servicio-datos">
                                <h6>Perfilado & Arreglo de Barba</h6>
                                <p>Delimitación con navaja, toalla caliente emoliente y aceites aromáticos para barba.</p>
                                <span class="duracion-pill"><i class="bi bi-clock"></i> 20 - 25 min</span>
                            </div>
                            <div class="servicio-precio-box">
                                <span class="precio-label">Precio</span>
                                <span class="precio-monto">S/ 25</span>
                            </div>
                        </div>

                        <div class="servicio-fila-precio destacado">
                            <div class="servicio-datos">
                                <h6>Pack Completo (Corte + Barba Spa)</h6>
                                <p>Experiencia total: Corte en degradé, arreglo de barba, toalla caliente y lavado.</p>
                                <span class="duracion-pill"><i class="bi bi-clock"></i> 50 min</span>
                            </div>
                            <div class="servicio-precio-box">
                                <span class="precio-label">Promoción</span>
                                <span class="precio-monto">S/ 50</span>
                            </div>
                        </div>

                        <div class="servicio-fila-precio">
                            <div class="servicio-datos">
                                <h6>Black Mask (Mascarilla Puntos Negros)</h6>
                                <p>Limpieza express para eliminar impurezas y puntos negros de la zona T facial.</p>
                                <span class="duracion-pill"><i class="bi bi-clock"></i> 15 min</span>
                            </div>
                            <div class="servicio-precio-box">
                                <span class="precio-label">Precio</span>
                                <span class="precio-monto">S/ 20</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-aviso-box">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>*Utilizamos navajas 100% descartables y máquinas desinfectadas entre cada cliente.</span>
                    </div>

                    <div class="modal-aviso-box">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>*<?= $mensajeReserva ?></span>
                    </div>

                    <div class="modal-aviso-box">
                        <i class="bi bi-info-circle-fill"></i>
                        <span><?= $mensajeGeneral ?></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cerrar" data-bs-dismiss="modal">Cerrar</button>
                    <a href="https://wa.me/51920134856?text=<?= rawurlencode('Hola Unity, deseo agendar un turno para Barbería.') ?>" target="_blank" class="btn-modal-reservar">
                        <i class="bi bi-whatsapp"></i><span>Reservar por WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. MODAL SALÓN DE BELLEZA -->
    <div class="fade modal modal-luxury" id="modal-belleza" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-titulo-wrap">
                        <span class="modal-cat-badge"><i class="bi bi-stars"></i> Estética Facial</span>
                        <h5 class="modal-title">Salón de Belleza & Tratamientos</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-subtexto">Tratamientos faciales e imagen integral para realzar tu belleza natural:</p>
                    <div class="servicios-lista-precios">
                        <div class="servicio-fila-precio">
                            <div class="servicio-datos">
                                <h6>LIMPIEZA FACIAL PROFUNDA</h6>
                                <p>Vapor de ozono, exfoliación, extracción de impurezas, alta frecuencia y mascarilla calmante.</p>
                                <span class="duracion-pill"><i class="bi bi-clock"></i> 60 min</span>
                            </div>
                            <div class="servicio-precio-box">
                                <span class="precio-label">Precio</span>
                                <span class="precio-monto">S/ 35</span>
                            </div>
                        </div>

                        <div class="servicio-fila-precio">
                            <div class="servicio-datos">
                                <h6>Maquillaje</h6>
                                <p>Preparación de piel, técnicas de contorno de alta durabilidad y pestañas de tira incluidas.</p>
                                <span class="duracion-pill"><i class="bi bi-clock"></i> 45 - 60 min</span>
                            </div>
                            <div class="servicio-precio-box">
                                <span class="precio-label">Entre</span>
                                <span class="precio-monto">S/ 30 | S/ 50</span>
                            </div>
                        </div>

                        <div class="servicio-fila-precio">
                            <div class="servicio-datos">
                                <h6>Lifting & Rizado de Pestañas</h6>
                                <p>Curvatura y color natural desde la raíz, efecto máscara duradero por semanas.</p>
                                <span class="duracion-pill"><i class="bi bi-clock"></i> 45 min</span>
                            </div>
                            <div class="servicio-precio-box">
                                <span class="precio-label">Precio</span>
                                <span class="precio-monto">S/ 50</span>
                            </div>
                        </div>

                        <div class="servicio-fila-precio">
                            <div class="servicio-datos">
                                <h6>DEPILACIÓN CON HILO, PINZA y NAVAJA </h6>
                                <p>Diseño y perfilado facial adaptado a tus facciones mediante técnicas de hilo orgánico, pinza de precisión o navaja.</p>
                                <span class="duracion-pill"><i class="bi bi-clock"></i>20 min</span>
                            </div>
                            <div class="servicio-precio-box">
                                <span class="precio-label">Precio</span>
                                <span class="precio-monto">S/ 10</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-aviso-box">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>*Trabajamos con productos hipoalergénicos dermatológicamente testeados.</span>
                    </div>

                    <div class="modal-aviso-box">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>*<?= $mensajeReserva ?></span>
                    </div>

                    <div class="modal-aviso-box">
                        <i class="bi bi-info-circle-fill"></i>
                        <span><?= $mensajeGeneral ?></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cerrar" data-bs-dismiss="modal">Cerrar</button>
                    <a href="https://wa.me/51920134856?text=<?= rawurlencode('Hola Unity, deseo agendar una cita para Salón y Tratamiento Facial.') ?>" target="_blank" class="btn-modal-reservar">
                        <i class="bi bi-whatsapp"></i><span>Reservar por WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. MODAL DISEÑO DE UÑAS -->
    <div class="fade modal modal-luxury" id="modal-unias" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-titulo-wrap">
                        <span class="modal-cat-badge"><i class="bi bi-hand-index-thumb"></i> Manicure & Pedicure</span>
                        <h5 class="modal-title">Diseño de Uñas & Spa</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-subtexto">Cuidado estético, esculpido y nail art personalizado para manos y pies:</p>
                    <div class="servicios-lista-precios">
                        <div class="servicio-fila-precio">
                            <div class="servicio-datos">
                                <h6>DISEÑO DE UÑAS A COLOR CON DISEÑO</h6>
                                <p>Combinación de esmaltado en gel de alta pigmentación con decoraciones personalizadas.</p>
                                <span class="duracion-pill"><i class="bi bi-clock"></i> 45 min</span>
                            </div>
                            <div class="servicio-precio-box">
                                <span class="precio-label">A partir de</span>
                                <span class="precio-monto">S/ 25</span>
                            </div>
                        </div>

                        <div class="servicio-fila-precio">
                            <div class="servicio-datos">
                                <h6>DISEÑO DE UÑAS EN GEL</h6>
                                <p>Refuerzo protector sobre tu uña natural para evitar quiebres y permitir su crecimiento.</p>
                                <span class="duracion-pill"><i class="bi bi-clock"></i> 50 min</span>
                            </div>
                            <div class="servicio-precio-box">
                                <span class="precio-label">Precio</span>
                                <span class="precio-monto">S/ 40</span>
                            </div>
                        </div>

                        <div class="servicio-fila-precio">
                            <div class="servicio-datos">
                                <h6>DISEÑO DE UÑAS ACRÍLICAS</h6>
                                <p>Esculpidas a mano con técnicas de vanguardia. Máxima resistencia, brillo impecable y acabados personalizados.</p>
                                <span class="duracion-pill"><i class="bi bi-clock"></i> 2 horas</span>
                            </div>
                            <div class="servicio-precio-box">
                                <span class="precio-label">Precio</span>
                                <span class="precio-monto">S/ 45</span>
                            </div>
                        </div> 

                        <div class="servicio-fila-precio">
                            <div class="servicio-datos">
                                <h6>DISEÑO DE UÑAS OJO DE GATO</h6>
                                <p>Esculpidas a mano con técnicas de vanguardia. Máxima resistencia, brillo impecable y acabados personalizados.</p>
                                <span class="duracion-pill"><i class="bi bi-clock"></i> 2 horas</span>
                            </div>
                            <div class="servicio-precio-box">
                                <span class="precio-label">A partir de</span>
                                <span class="precio-monto">S/ 25</span>
                            </div>
                        </div>

                        <div class="servicio-fila-precio">
                            <div class="servicio-datos">
                                <h6>Pedicure</h6>
                                <p>Exfoliación con sales, retiro de asperezas, masaje hidratante y esmaltado completo.</p>
                                <span class="duracion-pill"><i class="bi bi-clock"></i> 60 min</span>
                            </div>
                            <div class="servicio-precio-box">
                                <span class="precio-label">Precio</span>
                                <span class="precio-monto">S/ 40</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-aviso-box">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>*Diseños especiales de Nail Art o pedrería se cotizan según complejidad.</span>
                    </div>

                    <div class="modal-aviso-box">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>*<?= $mensajeReserva ?></span>
                    </div>

                    <div class="modal-aviso-box">
                        <i class="bi bi-info-circle-fill"></i>
                        <span><?= $mensajeGeneral ?></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cerrar" data-bs-dismiss="modal">Cerrar</button>
                    <a href="https://wa.me/51920134856?text=<?= rawurlencode('Hola Unity, deseo agendar una cita para Diseño de Uñas o Pedicure.') ?>" target="_blank" class="btn-modal-reservar">
                        <i class="bi bi-whatsapp"></i><span>Reservar por WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL DE EVENTOS: Aparece al entrar a la página, estilo stories -->
    <div class="modal-ev-overlay" id="modal-eventos" role="dialog" aria-modal="true" aria-label="Eventos y promociones" hidden>
        <div class="modal-ev-contenedor" data-orientacion="vertical">
            <!-- Barra de progreso stories (una barra por slide) -->
            <div class="modal-ev-barra-wrap" role="progressbar" aria-label="Progreso del slide"></div>

            <!-- Botón cerrar -->
            <button class="modal-ev-cerrar" aria-label="Cerrar anuncios">
                <i class="bi bi-x-lg"></i>
            </button>

            <!-- Imagen del evento -->
            <img class="modal-ev-imagen" src="" alt="Evento Unity" draggable="false">

            <!-- Navegación: flechas -->
            <button class="modal-ev-anterior" aria-label="Slide anterior">
                <i class="bi bi-arrow-left"></i>
            </button>
            <button class="modal-ev-siguiente" aria-label="Siguiente slide">
                <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </div>
    
<!-- Script principal: hamburguesa, scroll, animaciones -->    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="/public/js/main.js"></script>
</body>
</html>