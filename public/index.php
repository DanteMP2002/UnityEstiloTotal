<?php
// Carga dinámica de servicios y mensajes desde data/servicios.json
$serviciosFilePath = dirname(__DIR__) . '/data/servicios.json';
$categorias = [];
$mensajePromociones = "Las promociones y combos no son acumulables con otros descuentos y están sujetos a cambios sin previo aviso.";
$mensajeReserva = "Se recomienda reservar con anticipación para asegurar disponibilidad de horarios. Las reservas se confirman vía WhatsApp o llamada telefónica.";
$mensajeGeneral = "CONDICIONES GENERALES DE TARIFAS: Todos los precios mostrados en nuestro menú son precios base referenciales. Debido a que cada procedimiento es personalizado, el costo definitivo se determinará mediante un diagnóstico previo en el local. Le solicitamos cordialmente validar y llegar a un acuerdo sobre el presupuesto final con su estilista o especialista asignado antes de iniciar cualquier servicio. Agradecemos su comprensión.";

if (file_exists($serviciosFilePath)) {
    $serviciosJson = @file_get_contents($serviciosFilePath);
    if ($serviciosJson !== false) {
        $serviciosData = json_decode($serviciosJson, true);
        if (is_array($serviciosData)) {
            if (!empty($serviciosData['categorias'])) {
                $categorias = $serviciosData['categorias'];
            }
            if (!empty($serviciosData['mensajes']['promociones'])) {
                $mensajePromociones = $serviciosData['mensajes']['promociones'];
            }
            if (!empty($serviciosData['mensajes']['reserva'])) {
                $mensajeReserva = $serviciosData['mensajes']['reserva'];
            }
            if (!empty($serviciosData['mensajes']['general'])) {
                $mensajeGeneral = $serviciosData['mensajes']['general'];
            }
        }
    }
}

// Control de versiones de assets basado en mtime local (robusto e independiente de la raíz del servidor)
$cssPath = __DIR__ . '/css/estilo.css';
$jsPath = __DIR__ . '/js/main.js';
$v_css = file_exists($cssPath) ? filemtime($cssPath) : time();
$v_js = file_exists($jsPath) ? filemtime($jsPath) : time();

// Helper de escape HTML contra inyecciones XSS
function esc(?string $str): string {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNITY | INICIO</title>
    <link rel="icon" href="img/logo.webp" type="image/x-icon">
    <link rel="preload" as="image" href="img/hero-2.webp" fetchpriority="high">
    <link rel="stylesheet" href="css/estilo.css?v=<?= $v_css ?>">
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
                <img src="img/logo-dorado.webp" alt="Logo Unity">
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
            <img src="img/hero-2.webp"
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
            <?php foreach ($categorias as $cat): ?>
                <?php 
                    $imgFondo = ltrim($cat['imagen_fondo'] ?? '', '/');
                    if (str_starts_with($imgFondo, 'public/')) {
                        $imgFondo = substr($imgFondo, 7);
                    }
                ?>
                <article class="servicio-card col-12 col-md-6" 
                         style="background-image: url('<?= esc($imgFondo) ?>');" 
                         data-bs-target="#<?= esc($cat['modal_id']) ?>" 
                         data-bs-toggle="modal" 
                         role="button" 
                         tabindex="0">
                    <div class="servicio-overlay">
                        <h2><?= esc($cat['titulo']) ?></h2>
                        <p><?= esc($cat['descripcion']) ?></p>
                        <?php if (!empty($cat['tags']) && is_array($cat['tags'])): ?>
                            <div class="servicio-tags">
                                <?php foreach ($cat['tags'] as $tag): ?>
                                    <span><?= esc($tag) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <span class="btn-servicio">Ver tarifas & agendar <i class="bi bi-arrow-right"></i></span>
                    </div>
                </article>
            <?php endforeach; ?>
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
                        <img src="img/logo-dorado.webp" alt="Logo Unity Estilo Total">
                    </a>
                    <p class="footer-descripcion">
                        Tu espacio exclusivo en Zárate, SJL. Especialistas en corte unisex, barbería tradicional, coloración, tratamientos faciales y diseño de uñas.
                    </p>
                <!-- Redes Sociales -->
                    <div class="footer-redes">
                        <span class="redes-titulo">Síguenos en Nuestras Redes</span>
                        <div class="redes-iconos">
                            <a href="https://www.facebook.com/profile.php?id=61594175556210" target="_blank" rel="noopener noreferrer" class="red-btn red-facebook" aria-label="Facebook">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="https://www.instagram.com/unity.estilototal/" target="_blank" rel="noopener noreferrer" class="red-btn red-instagram" aria-label="Instagram">
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a href="https://www.tiktok.com/@unity.estilototal" target="_blank" rel="noopener noreferrer" class="red-btn red-tiktok" aria-label="TikTok">
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
                        <?php foreach ($categorias as $cat): ?>
                            <li> 
                                <a href="#" data-bs-toggle="modal" data-bs-target="#<?= esc($cat['modal_id']) ?>">
                                    <i class="bi bi-chevron-right"></i> <?= esc($cat['titulo']) ?>
                                </a> 
                            </li>
                        <?php endforeach; ?>
                    <!-- No es necesario el modal y el id-modal porque lo hace el js -->
                        <li> <a class="promo-evento" onclick="iniciarModalEventos(true)"><i class="bi bi-chevron-right"></i> Promociones </a> </li>

                        <li> <a href="#nosotros"> <i class="bi bi-chevron-right"></i> Sobre Nosotros </a> </li>
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

<!-- MODALES DE SERVICIOS (Renderizado Dinámico) -->
    <?php foreach ($categorias as $cat): ?>
    <div class="fade modal modal-luxury" id="<?= esc($cat['modal_id']) ?>" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-titulo-wrap">
                        <span class="modal-cat-badge"><i class="bi <?= esc($cat['badge_icono'] ?? 'bi-stars') ?>"></i> <?= esc($cat['badge_texto'] ?? 'Especialidad') ?></span>
                        <h5 class="modal-title"><?= esc($cat['titulo_modal'] ?? $cat['titulo']) ?></h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <?php if (!empty($cat['modal_subtexto'])): ?>
                        <p class="modal-subtexto"><?= esc($cat['modal_subtexto']) ?></p>
                    <?php endif; ?>
                    <div class="servicios-lista-precios">
                        <?php if (!empty($cat['servicios']) && is_array($cat['servicios'])): ?>
                            <?php foreach ($cat['servicios'] as $srv): ?>
                                <div class="servicio-fila-precio<?= !empty($srv['destacado']) ? ' destacado' : '' ?>">
                                    <div class="servicio-datos">
                                        <h6><?= esc($srv['nombre']) ?></h6>
                                        <?php if (!empty($srv['descripcion'])): ?>
                                            <p><?= esc($srv['descripcion']) ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($srv['nota_adicional'])): ?>
                                            <p><strong><?= esc($srv['nota_adicional']) ?></strong></p>
                                        <?php endif; ?>
                                        <?php if (!empty($srv['duracion'])): ?>
                                            <span class="duracion-pill"><i class="bi bi-clock"></i> <?= esc($srv['duracion']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="servicio-precio-box">
                                        <span class="precio-label"><?= esc($srv['precio_label'] ?: 'Precio') ?></span>
                                        <span class="precio-monto"><?= esc($srv['precio']) ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($cat['aviso_especifico'])): ?>
                        <div class="modal-aviso-box"> 
                            <i class="bi bi-info-circle-fill"></i>
                            <span><?= esc($cat['aviso_especifico']) ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="modal-aviso-box">
                        <i class="bi bi-clock"></i>
                        <span><?= $mensajeReserva ?></span>
                    </div>

                    <div class="modal-aviso-box">
                        <i class="bi bi-info-circle-fill"></i>
                        <span><?= $mensajeGeneral ?></span>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cerrar" data-bs-dismiss="modal">Cerrar</button>
                    <a href="https://wa.me/51920134856?text=<?= rawurlencode($cat['whatsapp_mensaje'] ?: 'Hola Unity, deseo solicitar información o reservar una cita.') ?>" target="_blank" class="btn-modal-reservar">
                        <i class="bi bi-whatsapp"></i><span>Reservar por WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

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
    <script src="js/main.js?v=<?= $v_js ?>"></script>
</body>
</html>
