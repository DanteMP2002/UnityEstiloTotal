<?php
/**
 * Unity Estilo Total - Panel de Control Administrativo (CRUD de Servicios)
 * Experiencia Dark Luxury optimizada para móvil y escritorio
 */

require_once __DIR__ . '/auth.php';

// Exigir autenticación activa
require_auth();

$data = load_servicios_data();
$categorias = $data['categorias'] ?? [];
$mensajes = $data['mensajes'] ?? [];

$notificacion = '';
$tipoNotificacion = 'success';

// Manejo de mensajes vía GET (Post-Redirect-Get)
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'creado':
            $notificacion = 'Servicio agregado exitosamente al catálogo.';
            break;
        case 'actualizado':
            $notificacion = 'Servicio y tarifas actualizados correctamente.';
            break;
        case 'eliminado':
            $notificacion = 'Servicio eliminado correctamente.';
            break;
        case 'mensajes_actualizados':
            $notificacion = 'Mensajes y políticas generales guardados con éxito.';
            break;
        case 'error':
            $notificacion = 'Ocurrió un error al procesar la solicitud. Por favor verifica los datos.';
            $tipoNotificacion = 'danger';
            break;
    }
}

// =============================================================================
// PROCESAMIENTO DE PETICIONES POST (CRUD)
// =============================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($csrfToken)) {
        header('Location: index.php?msg=error');
        exit;
    }

    $accion = $_POST['accion'] ?? '';
    $catId = trim($_POST['categoria_id'] ?? '');

    // 1. AGREGAR SERVICIO
    if ($accion === 'agregar_servicio') {
        $nombre = sanitize_text($_POST['nombre'] ?? '');
        $precio = sanitize_text($_POST['precio'] ?? '');
        $duracion = sanitize_text($_POST['duracion'] ?? '');
        $precioLabel = sanitize_text($_POST['precio_label'] ?? 'Precio');
        $descripcion = sanitize_text($_POST['descripcion'] ?? '');
        $notaAdicional = sanitize_text($_POST['nota_adicional'] ?? '');
        $destacado = !empty($_POST['destacado']);

        if (empty($catId) || empty($nombre) || empty($precio)) {
            header('Location: index.php?msg=error');
            exit;
        }

        // Buscar categoría e insertar
        $categoriaEncontrada = false;
        foreach ($data['categorias'] as &$cat) {
            if ($cat['id'] === $catId) {
                $categoriaEncontrada = true;
                $nuevoId = strtolower(substr($catId, 0, 3)) . '-' . (count($cat['servicios']) + 1) . '-' . substr(md5(uniqid()), 0, 4);

                $nuevoServicio = [
                    'id'             => $nuevoId,
                    'nombre'         => $nombre,
                    'descripcion'    => $descripcion,
                    'duracion'       => $duracion,
                    'precio_label'   => $precioLabel ?: 'Precio',
                    'precio'         => $precio,
                    'destacado'      => $destacado,
                    'nota_adicional' => $notaAdicional
                ];

                $cat['servicios'][] = $nuevoServicio;
                break;
            }
        }
        unset($cat);

        if ($categoriaEncontrada && save_servicios_data($data)) {
            header('Location: index.php?cat=' . urlencode($catId) . '&msg=creado');
            exit;
        } else {
            header('Location: index.php?msg=error');
            exit;
        }
    }

    // 2. EDITAR SERVICIO
    if ($accion === 'editar_servicio') {
        $servicioId = trim($_POST['servicio_id'] ?? '');
        $nombre = sanitize_text($_POST['nombre'] ?? '');
        $precio = sanitize_text($_POST['precio'] ?? '');
        $duracion = sanitize_text($_POST['duracion'] ?? '');
        $precioLabel = sanitize_text($_POST['precio_label'] ?? 'Precio');
        $descripcion = sanitize_text($_POST['descripcion'] ?? '');
        $notaAdicional = sanitize_text($_POST['nota_adicional'] ?? '');
        $destacado = !empty($_POST['destacado']);

        if (empty($catId) || empty($servicioId) || empty($nombre) || empty($precio)) {
            header('Location: index.php?msg=error');
            exit;
        }

        $editado = false;
        foreach ($data['categorias'] as &$cat) {
            if ($cat['id'] === $catId) {
                foreach ($cat['servicios'] as &$srv) {
                    if ($srv['id'] === $servicioId) {
                        $srv['nombre'] = $nombre;
                        $srv['precio'] = $precio;
                        $srv['duracion'] = $duracion;
                        $srv['precio_label'] = $precioLabel ?: 'Precio';
                        $srv['descripcion'] = $descripcion;
                        $srv['nota_adicional'] = $notaAdicional;
                        $srv['destacado'] = $destacado;
                        $editado = true;
                        break;
                    }
                }
                unset($srv);
                break;
            }
        }
        unset($cat);

        if ($editado && save_servicios_data($data)) {
            header('Location: index.php?cat=' . urlencode($catId) . '&msg=actualizado');
            exit;
        } else {
            header('Location: index.php?msg=error');
            exit;
        }
    }

    // 3. ELIMINAR SERVICIO
    if ($accion === 'eliminar_servicio') {
        $servicioId = trim($_POST['servicio_id'] ?? '');

        if (empty($catId) || empty($servicioId)) {
            header('Location: index.php?msg=error');
            exit;
        }

        $eliminado = false;
        foreach ($data['categorias'] as &$cat) {
            if ($cat['id'] === $catId) {
                $iniciales = count($cat['servicios']);
                $cat['servicios'] = array_values(array_filter($cat['servicios'], function ($srv) use ($servicioId) {
                    return $srv['id'] !== $servicioId;
                }));
                if (count($cat['servicios']) < $iniciales) {
                    $eliminado = true;
                }
                break;
            }
        }
        unset($cat);

        if ($eliminado && save_servicios_data($data)) {
            header('Location: index.php?cat=' . urlencode($catId) . '&msg=eliminado');
            exit;
        } else {
            header('Location: index.php?msg=error');
            exit;
        }
    }

    // 4. ACTUALIZAR MENSAJES GENERALES
    if ($accion === 'guardar_mensajes') {
        $data['mensajes']['promociones'] = sanitize_text($_POST['mensaje_promociones'] ?? '');
        $data['mensajes']['reserva'] = sanitize_text($_POST['mensaje_reserva'] ?? '');
        $data['mensajes']['general'] = sanitize_text($_POST['mensaje_general'] ?? '');

        if (save_servicios_data($data)) {
            header('Location: index.php?msg=mensajes_actualizados');
            exit;
        } else {
            header('Location: index.php?msg=error');
            exit;
        }
    }
}

$csrfToken = get_csrf_token();
$activeCat = $_GET['cat'] ?? ($categorias[0]['id'] ?? '');

// Métricas de resumen rápido para el dashboard
$totalCategorias = count($categorias);
$totalServicios = 0;
$totalDestacados = 0;
foreach ($categorias as $cat) {
    if (!empty($cat['servicios'])) {
        $totalServicios += count($cat['servicios']);
        foreach ($cat['servicios'] as $srv) {
            if (!empty($srv['destacado'])) {
                $totalDestacados++;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Panel de Administración | Unity Estilo Total</title>
    <link rel="icon" href="../public/img/logo.webp" type="image/x-icon">
    <!-- Google Fonts: Playfair Display & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CDN y Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --negro: #0a0908;
            --bg-principal: #0d0c0a;
            --bg-card: #141311;
            --bg-subcard: #1b1916;
            --bg-input: #1f1d19;
            --dorado: #c9a84c;
            --dorado-hover: #dfbd5a;
            --dorado-suave: rgba(201, 168, 76, 0.12);
            --dorado-borde: rgba(201, 168, 76, 0.28);
            --border-color: rgba(255, 255, 255, 0.08);
            --texto-claro: #f8f5f0;
            --texto-mutado: #a29f98;
            --fuente-titulo: 'Playfair Display', Georgia, serif;
            --fuente-cuerpo: 'Inter', Arial, sans-serif;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-principal);
            background-image: 
                radial-gradient(circle at 10% 0%, rgba(201, 168, 76, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 90% 100%, rgba(201, 168, 76, 0.03) 0%, transparent 40%);
            background-attachment: fixed;
            color: var(--texto-claro);
            font-family: var(--fuente-cuerpo);
            min-height: 100vh;
            margin: 0;
            padding-bottom: 3rem;
        }

        /* NAVBAR SUPERIOR */
        .navbar-admin {
            background-color: rgba(14, 13, 11, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--dorado-borde);
            position: sticky;
            top: 0;
            z-index: 1030;
            padding: 0.75rem 1rem;
        }

        .navbar-brand-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .navbar-brand-wrap img {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: 1px solid var(--dorado-borde);
            background: rgba(201, 168, 76, 0.1);
            padding: 2px;
        }

        .navbar-brand-title {
            font-family: var(--fuente-titulo);
            color: var(--texto-claro);
            font-weight: 700;
            font-size: 1.15rem;
            letter-spacing: 0.04em;
            line-height: 1.1;
            margin: 0;
        }

        .navbar-brand-title span {
            color: var(--dorado);
        }

        .admin-pill-badge {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--dorado);
            background: var(--dorado-suave);
            border: 1px solid var(--dorado-borde);
            padding: 2px 8px;
            border-radius: 20px;
        }

        /* BOTONES GLOBALES LUXURY */
        .btn-gold {
            background: linear-gradient(135deg, #c9a84c 0%, #b8933b 100%);
            border: 1px solid #d8b85b;
            color: #0d0c0a;
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 0.02em;
            padding: 0.55rem 1.1rem;
            border-radius: 8px;
            box-shadow: 0 4px 14px rgba(201, 168, 76, 0.25);
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-gold:hover {
            background: linear-gradient(135deg, #dfbd5a 0%, #c9a84c 100%);
            color: #000;
            box-shadow: 0 6px 20px rgba(201, 168, 76, 0.4);
            transform: translateY(-1px);
        }

        .btn-outline-gold {
            background: transparent;
            border: 1px solid var(--dorado-borde);
            color: var(--dorado);
            font-weight: 600;
            font-size: 0.88rem;
            padding: 0.55rem 1rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-outline-gold:hover {
            background: var(--dorado-suave);
            border-color: var(--dorado);
            color: #fff;
        }

        /* TARJETAS RESUMEN DE MÉTRICAS */
        .metric-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: transform 0.2s ease, border-color 0.2s ease;
        }

        .metric-card:hover {
            border-color: var(--dorado-borde);
            transform: translateY(-2px);
        }

        .metric-icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: var(--dorado-suave);
            border: 1px solid var(--dorado-borde);
            color: var(--dorado);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .metric-label {
            font-size: 0.75rem;
            color: var(--texto-mutado);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 2px;
        }

        .metric-value {
            font-size: 1.35rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.1;
        }

        /* PESTAÑAS DE CATEGORÍAS (TABS RESPONSIVE CON SCROLL HORIZONTAL) */
        .tabs-nav-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding-bottom: 4px;
            margin-bottom: 1.25rem;
        }

        .tabs-nav-container::-webkit-scrollbar {
            display: none;
        }

        .nav-tabs-custom {
            display: flex;
            flex-wrap: nowrap;
            gap: 25px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 8px;
            min-width: max-content;
        }

        .nav-tabs-custom .nav-link {
            color: var(--texto-mutado);
            background: var(--bg-subcard);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.65rem 1.15rem;
            font-weight: 500;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .nav-tabs-custom .nav-link:hover {
            color: var(--dorado);
            border-color: var(--dorado-borde);
            background: rgba(201, 168, 76, 0.05);
        }

        .nav-tabs-custom .nav-link.active {
            color: #000;
            background: var(--dorado);
            border-color: var(--dorado);
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(201, 168, 76, 0.3);
        }

        .nav-tabs-custom .nav-link.active .badge-count {
            background: #000;
            color: var(--dorado);
            border-color: #000;
        }

        .badge-count {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        /* TARJETA CONTENEDORA DE SERVICIOS */
        .admin-card {
            background: var(--bg-card);
            border: 1px solid var(--dorado-borde);
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
            position: relative;
            overflow: hidden;
        }

        .admin-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: rgba(201, 168, 76, 0.03);
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .cat-title {
            font-family: var(--fuente-titulo);
            font-size: 1.35rem;
            font-weight: 700;
            color: #fff;
            margin: 0;
        }

        .cat-badge {
            background: var(--dorado-suave);
            color: var(--dorado);
            border: 1px solid var(--dorado-borde);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        /* BUSCADOR RÁPIDO DENTRO DE LA CATEGORÍA */
        .search-box-wrap {
            position: relative;
            max-width: 280px;
            width: 100%;
        }

        .search-box-wrap i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--texto-mutado);
            pointer-events: none;
            font-size: 0.9rem;
        }

        .search-box-input {
            background-color: var(--bg-input);
            border: 1px solid var(--border-color);
            color: #fff;
            padding: 0.5rem 0.75rem 0.5rem 2.2rem;
            font-size: 0.88rem;
            border-radius: 8px;
            width: 100%;
            transition: all 0.2s ease;
        }

        .search-box-input:focus {
            outline: none;
            border-color: var(--dorado);
            background-color: #24221d;
            box-shadow: 0 0 0 2px rgba(201, 168, 76, 0.2);
        }

        /* TABLA DE SERVICIOS (DESKTOP) */
        .table-dark-custom {
            --bs-table-bg: transparent;
            --bs-table-color: var(--texto-claro);
            --bs-table-border-color: var(--border-color);
            margin-bottom: 0;
        }

        .table-dark-custom thead th {
            background-color: rgba(255, 255, 255, 0.02);
            color: var(--texto-mutado);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            border-bottom: 1px solid var(--border-color);
            padding: 0.85rem 1.25rem;
        }

        .table-dark-custom tbody td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .table-dark-custom tbody tr:hover {
            background-color: rgba(201, 168, 76, 0.035);
        }

        /* VISTA EN TARJETAS PARA MÓVIL (< 768px) */
        .mobile-services-grid {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .mobile-service-card {
            background: var(--bg-subcard);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 1rem;
            transition: border-color 0.2s ease;
        }

        .mobile-service-card.highlighted {
            border-color: var(--dorado-borde);
            background: rgba(201, 168, 76, 0.04);
        }

        .mobile-service-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 0.5rem;
        }

        .mobile-service-name {
            font-weight: 600;
            font-size: 1rem;
            color: #fff;
            margin: 0;
        }

        .mobile-price-tag {
            text-align: right;
            flex-shrink: 0;
        }

        .mobile-price-tag .label {
            font-size: 0.7rem;
            color: var(--texto-mutado);
            text-transform: uppercase;
            display: block;
        }

        .mobile-price-tag .amount {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--dorado);
            line-height: 1.1;
        }

        .mobile-actions-bar {
            display: flex;
            gap: 8px;
            margin-top: 0.9rem;
            padding-top: 0.8rem;
            border-top: 1px solid var(--border-color);
        }

        .mobile-actions-bar .btn {
            flex: 1;
            padding: 0.55rem;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        /* PILLS Y ETIQUETAS DE ATRIBUTOS */
        .badge-destacado {
            background-color: var(--dorado);
            color: #000;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 2px 7px;
            border-radius: 4px;
        }

        .duracion-badge {
            background-color: rgba(255, 255, 255, 0.06);
            color: #d0cec7;
            border: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.78rem;
            padding: 3px 8px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .price-display {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dorado);
        }

        /* MODALES LUXURY */
        .modal-content-admin {
            background-color: var(--bg-card);
            color: var(--texto-claro);
            border: 1px solid var(--dorado-borde);
            border-radius: 14px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8), 0 0 25px rgba(201, 168, 76, 0.15);
            overflow: visible;
        }

        .modal-header-admin {
            background: rgba(201, 168, 76, 0.06);
            border-bottom: 1px solid var(--dorado-borde);
            padding: 1.1rem 1.4rem;
        }

        .modal-header-admin .modal-title {
            font-family: var(--fuente-titulo);
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.02em;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-body-admin {
            padding: 1.4rem;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            max-height: 65vh;
        }

        .modal-footer-admin {
            background: rgba(0, 0, 0, 0.2);
            border-top: 1px solid var(--border-color);
            padding: 1rem 1.4rem;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        /* Scroll en modales en pantallas pequeñas */
            @media (max-height: 700px) {
                .modal-body-admin {
                    max-height: 55vh;
                }
            }
            @media (max-height: 500px) {
                .modal-body-admin {
                    max-height: 45vh;
                }
            }

        .form-label-admin {
            color: #d6d3cc;
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 0.35rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .form-control-admin {
            background-color: var(--bg-input);
            border: 1px solid var(--border-color);
            color: #fff;
            padding: 0.65rem 0.9rem;
            font-size: 1rem; /* Previene auto-zoom molesto en iOS */
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .form-control-admin:focus {
            background-color: #24221d;
            border-color: var(--dorado);
            color: #fff;
            box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.22);
            outline: none;
        }

        .form-control-admin::placeholder {
            color: #64615b;
            font-size: 0.88rem;
        }

        .form-check-admin {
            background: rgba(201, 168, 76, 0.05);
            border: 1px solid var(--dorado-borde);
            border-radius: 8px;
            padding: 0.75rem 1rem 0.75rem 2.2rem;
            margin-top: 0.5rem;
        }

        .form-check-admin .form-check-input {
            margin-left: -1.6rem;
            margin-top: 0.25rem;
            border-color: var(--dorado-borde);
            background-color: var(--bg-input);
        }

        .form-check-admin .form-check-input:checked {
            background-color: var(--dorado);
            border-color: var(--dorado);
        }

        /* ALERTAS DE SISTEMA */
        .alert-dismissible {
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background-color: rgba(25, 135, 84, 0.15);
            border: 1px solid rgba(25, 135, 84, 0.4);
            color: #75e0a3;
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.15);
            border: 1px solid rgba(220, 53, 69, 0.4);
            color: #ff8b94;
        }

        /* BOTONES DE ACCIÓN EN TABLA */
        .btn-action-edit {
            background: rgba(13, 202, 240, 0.1);
            border: 1px solid rgba(13, 202, 240, 0.3);
            color: #0dcaf0;
            padding: 0.4rem 0.75rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .btn-action-edit:hover {
            background: #0dcaf0;
            color: #000;
        }

        .btn-action-delete {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #dc3545;
            padding: 0.4rem 0.75rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .btn-action-delete:hover {
            background: #dc3545;
            color: #fff;
        }

        /* RESPONSIVE AJUSTES */
        @media (max-width: 767.98px) {
            .navbar-brand-title {
                font-size: 1rem;
            }
            .admin-pill-badge {
                display: none;
            }
            .header-actions-group {
                width: 100%;
                display: flex;
                flex-direction: column;
                gap: 8px;
            }
            .header-actions-group .btn {
                width: 100%;
            }
            .admin-card-header {
                padding: 1rem;
            }
            .search-box-wrap {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    <!-- NAVBAR SUPERIOR LUXURY -->
    <header class="navbar navbar-admin" role="banner">
        <div class="container-fluid px-2 px-md-3">
            <a class="navbar-brand-wrap" href="index.php" title="Panel de Administración Unity">
                <img src="../public/img/logo-dorado-v.webp" alt="Unity Logo" width="36" height="36">
                <div>
                    <h1 class="navbar-brand-title">UNITY <span>ESTILO TOTAL</span></h1>
                </div>
                <span class="admin-pill-badge"><i class="bi bi-shield-check"></i> Admin</span>
            </a>

            <div class="d-flex align-items-center gap-2 gap-md-3">
                <a href="../public/index.php" target="_blank" rel="noopener" class="btn btn-outline-gold btn-sm" title="Abrir la página web en una pestaña nueva">
                    <i class="bi bi-globe2"></i>
                    <span class="d-none d-sm-inline">Ver Sitio Web</span>
                </a>
                
                <div class="text-secondary small d-none d-md-flex align-items-center gap-1">
                    <i class="bi bi-person-circle text-warning"></i>
                    <span class="text-light fw-medium"><?= e($_SESSION['admin_user'] ?? 'Administrador') ?></span>
                </div>

                <a href="logout.php" class="btn btn-outline-danger btn-sm" title="Cerrar sesión de forma segura">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="d-none d-sm-inline">Salir</span>
                </a>
            </div>
        </div>
    </header>

    <!-- CONTENEDOR PRINCIPAL -->
    <main class="container-xl py-3 py-md-4" role="main">

        <!-- NOTIFICACIONES CON DISEÑO LUXURY -->
        <?php if (!empty($notificacion)): ?>
            <div class="alert alert-<?= $tipoNotificacion ?> alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi <?= ($tipoNotificacion === 'success') ? 'bi-check-circle-fill' : 'bi-exclamation-octagon-fill' ?> fs-5 me-2 flex-shrink-0"></i>
                <div class="flex-grow-1">
                    <strong><?= ($tipoNotificacion === 'success') ? '¡Completado!' : 'Atención:' ?></strong> <?= e($notificacion) ?>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>

        <!-- ENCABEZADO Y BOTONES DE ACCIÓN RÁPIDA -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h2 class="h4 mb-1 text-white fw-bold" style="font-family: var(--fuente-titulo);">Gestión de Catálogo & Tarifas</h2>
                <p class="text-secondary mb-0 small">Control centralizado de especialidades, precios, duraciones y políticas informativas.</p>
            </div>
            <div class="header-actions-group d-flex gap-2">
                <button type="button" class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#modalNuevoServicio">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span>Nuevo Servicio</span>
                </button>
                <button type="button" class="btn btn-outline-gold" data-bs-toggle="modal" data-bs-target="#modalMensajes">
                    <i class="bi bi-chat-quote-fill"></i>
                    <span>Políticas y Mensajes</span>
                </button>
            </div>
        </div>

        <!-- BARRA RESUMEN DE MÉTRICAS / KPIS -->
        <div class="row g-2 g-md-3 mb-4">
            <div class="col-6 col-md-4">
                <div class="metric-card">
                    <div class="metric-icon-wrap">
                        <i class="bi bi-grid-fill"></i>
                    </div>
                    <div>
                        <div class="metric-label">Especialidades</div>
                        <div class="metric-value"><?= $totalCategorias ?></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="metric-card">
                    <div class="metric-icon-wrap">
                        <i class="bi bi-scissors"></i>
                    </div>
                    <div>
                        <div class="metric-label">Servicios Totales</div>
                        <div class="metric-value"><?= $totalServicios ?></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="metric-card">
                    <div class="metric-icon-wrap">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <div>
                        <div class="metric-label">Servicios Destacados</div>
                        <div class="metric-value"><?= $totalDestacados ?> <span class="badge bg-warning text-dark ms-1 small" style="font-size: 0.65rem;">En Vitrina</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PESTAÑAS DE CATEGORÍAS (SCROLL HORIZONTAL OPTIMIZADO PARA MÓVIL) -->
        <div class="tabs-nav-container">
            <ul class="nav-tabs-custom" id="catTabs" role="tablist">
                <?php foreach ($categorias as $index => $cat): ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= ($cat['id'] === $activeCat) ? 'active' : '' ?>" 
                                id="tab-btn-<?= e($cat['id']) ?>" 
                                data-bs-toggle="tab" 
                                data-bs-target="#tab-<?= e($cat['id']) ?>" 
                                type="button" 
                                role="tab" 
                                aria-controls="tab-<?= e($cat['id']) ?>" 
                                aria-selected="<?= ($cat['id'] === $activeCat) ? 'true' : 'false' ?>">
                            <i class="bi <?= e($cat['badge_icono'] ?? 'bi-tag') ?>"></i>
                            <span><?= e($cat['titulo']) ?></span>
                            <span class="badge-count"><?= count($cat['servicios']) ?></span>
                        </button>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- CONTENIDO DE LAS PESTAÑAS -->
        <div class="tab-content" id="catTabsContent">
            <?php foreach ($categorias as $index => $cat): ?>
                <div class="tab-pane fade <?= ($cat['id'] === $activeCat) ? 'show active' : '' ?>" 
                     id="tab-<?= e($cat['id']) ?>" 
                     role="tabpanel" 
                     aria-labelledby="tab-btn-<?= e($cat['id']) ?>">
                    
                    <div class="admin-card">
                        <!-- Cabecera de la categoría con buscador rápido -->
                        <div class="admin-card-header">
                            <div>
                                <h3 class="cat-title"><?= e($cat['titulo']) ?></h3>
                                <div class="mt-1 d-flex align-items-center gap-2">
                                    <span class="cat-badge"><i class="bi <?= e($cat['badge_icono'] ?? 'bi-stars') ?>"></i> <?= e($cat['badge_texto']) ?></span>
                                    <span class="text-secondary small d-none d-sm-inline">• <?= count($cat['servicios']) ?> servicios configurados</span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2 flex-grow-1 flex-md-grow-0 justify-content-end">
                                <!-- Buscador interactivo en vivo -->
                                <div class="search-box-wrap">
                                    <i class="bi bi-search"></i>
                                    <input type="text" 
                                           class="search-box-input" 
                                           placeholder="Filtrar por nombre..." 
                                           data-category="<?= e($cat['id']) ?>" 
                                           oninput="filtrarServicios(this, '<?= e($cat['id']) ?>')"
                                           aria-label="Buscar servicio en <?= e($cat['titulo']) ?>">
                                </div>

                                <button type="button" 
                                        class="btn btn-gold btn-sm flex-shrink-0" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalNuevoServicio" 
                                        onclick="setModalCategoria('<?= e($cat['id']) ?>')">
                                    <i class="bi bi-plus-lg"></i>
                                    <span class="d-none d-sm-inline">Añadir aquí</span>
                                </button>
                            </div>
                        </div>

                        <?php if (empty($cat['servicios'])): ?>
                            <div class="text-center py-5 text-secondary">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-warning opacity-50"></i>
                                <h4 class="h6 text-white mb-1">No hay servicios registrados</h4>
                                <p class="small text-secondary mb-3">Comienza añadiendo los tratamientos y tarifas de esta especialidad.</p>
                                <button type="button" class="btn btn-gold btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoServicio" onclick="setModalCategoria('<?= e($cat['id']) ?>')">
                                    <i class="bi bi-plus-circle me-1"></i> Añadir primer servicio
                                </button>
                            </div>
                        <?php else: ?>
                            <!-- VISTA EN TABLA PARA ESCRITORIO (>= 768px) -->
                            <div class="table-responsive d-none d-md-block">
                                <table class="table table-dark-custom align-middle" id="table-<?= e($cat['id']) ?>">
                                    <thead>
                                        <tr>
                                            <th style="width: 28%;">Servicio & Atributos</th>
                                            <th style="width: 32%;">Descripción</th>
                                            <th style="width: 15%;">Duración</th>
                                            <th style="width: 13%;">Tarifa</th>
                                            <th style="width: 12%; text-align: right;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-<?= e($cat['id']) ?>">
                                        <?php foreach ($cat['servicios'] as $srv): ?>
                                            <tr class="service-item-row" data-name="<?= strtolower(e($srv['nombre'])) ?>">
                                                <td>
                                                    <div class="fw-bold text-white fs-6"><?= e($srv['nombre']) ?></div>
                                                    <div class="d-flex align-items-center gap-2 mt-1">
                                                        <?php if (!empty($srv['destacado'])): ?>
                                                            <span class="badge-destacado"><i class="bi bi-star-fill me-1"></i> Destacado</span>
                                                        <?php endif; ?>
                                                        <?php if (!empty($srv['nota_adicional'])): ?>
                                                            <span class="text-warning small fst-italic"><i class="bi bi-tag-fill me-1"></i> <?= e($srv['nota_adicional']) ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td class="text-secondary small" style="line-height: 1.45;">
                                                    <?= e($srv['descripcion'] ?: '—') ?>
                                                </td>
                                                <td>
                                                    <span class="duracion-badge">
                                                        <i class="bi bi-clock-history text-warning"></i> <?= e($srv['duracion'] ?: 'No especificada') ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="small text-secondary text-uppercase" style="font-size: 0.72rem;"><?= e($srv['precio_label'] ?: 'Precio') ?></div>
                                                    <div class="price-display"><?= e($srv['precio']) ?></div>
                                                </td>
                                                <td style="text-align: right;">
                                                    <div class="d-inline-flex gap-1">
                                                        <button type="button" 
                                                                class="btn-action-edit" 
                                                                title="Editar este servicio"
                                                                onclick='abrirModalEditar(<?= json_encode($cat['id']) ?>, <?= json_encode($srv) ?>)'>
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        <form method="POST" action="index.php" style="display: inline;" onsubmit="return confirm('¿Seguro que deseas eliminar «<?= addslashes($srv['nombre']) ?>» del catálogo?');">
                                                            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                                                            <input type="hidden" name="accion" value="eliminar_servicio">
                                                            <input type="hidden" name="categoria_id" value="<?= e($cat['id']) ?>">
                                                            <input type="hidden" name="servicio_id" value="<?= e($srv['id']) ?>">
                                                            <button type="submit" class="btn-action-delete" title="Eliminar servicio permanentemente">
                                                                <i class="bi bi-trash3"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- VISTA EN TARJETAS PARA DISPOSITIVOS MÓVILES (< 768px) -->
                            <div class="mobile-services-grid d-md-none" id="mobile-list-<?= e($cat['id']) ?>">
                                <?php foreach ($cat['servicios'] as $srv): ?>
                                    <div class="mobile-service-card <?= !empty($srv['destacado']) ? 'highlighted' : '' ?> service-item-row" data-name="<?= strtolower(e($srv['nombre'])) ?>">
                                        <div class="mobile-service-header">
                                            <div>
                                                <h4 class="mobile-service-name"><?= e($srv['nombre']) ?></h4>
                                                <?php if (!empty($srv['destacado'])): ?>
                                                    <span class="badge-destacado mt-1 d-inline-block"><i class="bi bi-star-fill me-1"></i> Destacado</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="mobile-price-tag">
                                                <span class="label"><?= e($srv['precio_label'] ?: 'Precio') ?></span>
                                                <span class="amount"><?= e($srv['precio']) ?></span>
                                            </div>
                                        </div>

                                        <?php if (!empty($srv['descripcion'])): ?>
                                            <p class="text-secondary small mb-2" style="line-height: 1.4;"><?= e($srv['descripcion']) ?></p>
                                        <?php endif; ?>

                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                            <?php if (!empty($srv['duracion'])): ?>
                                                <span class="duracion-badge">
                                                    <i class="bi bi-clock text-warning"></i> <?= e($srv['duracion']) ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if (!empty($srv['nota_adicional'])): ?>
                                                <span class="text-warning small fst-italic">
                                                    <i class="bi bi-tag-fill me-1"></i> <?= e($srv['nota_adicional']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="mobile-actions-bar">
                                            <button type="button" 
                                                    class="btn btn-action-edit" 
                                                    onclick='abrirModalEditar(<?= json_encode($cat['id']) ?>, <?= json_encode($srv) ?>)'>
                                                <i class="bi bi-pencil-square"></i> Editar
                                            </button>
                                            <form method="POST" action="index.php" style="flex: 1;" onsubmit="return confirm('¿Seguro que deseas eliminar «<?= addslashes($srv['nombre']) ?>»?');">
                                                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                                                <input type="hidden" name="accion" value="eliminar_servicio">
                                                <input type="hidden" name="categoria_id" value="<?= e($cat['id']) ?>">
                                                <input type="hidden" name="servicio_id" value="<?= e($srv['id']) ?>">
                                                <button type="submit" class="btn btn-action-delete w-100">
                                                    <i class="bi bi-trash3"></i> Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </main>

    <!-- =======================================================================
         MODAL 1: NUEVO SERVICIO
         ======================================================================= -->
    <div class="modal fade" id="modalNuevoServicio" tabindex="-1" aria-labelledby="modalNuevoServicioLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content modal-content-admin">
                <div class="modal-header modal-header-admin">
                    <h5 class="modal-title" id="modalNuevoServicioLabel">
                        <i class="bi bi-plus-circle-fill text-warning"></i> Registrar Nuevo Servicio
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form method="POST" action="index.php">
                    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                    <input type="hidden" name="accion" value="agregar_servicio">
                    
                    <div class="modal-body modal-body-admin">
                        <div class="mb-3">
                            <label class="form-label-admin" for="nuevo_categoria_id">
                                <i class="bi bi-folder-fill text-warning"></i> Especialidad / Categoría *
                            </label>
                            <select class="form-select form-control-admin" name="categoria_id" id="nuevo_categoria_id" required>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= e($cat['id']) ?>" <?= ($cat['id'] === $activeCat) ? 'selected' : '' ?>>
                                        <?= e($cat['titulo']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-admin" for="nuevo_nombre">
                                <i class="bi bi-scissors text-warning"></i> Nombre del Servicio *
                            </label>
                            <input type="text" class="form-control form-control-admin" id="nuevo_nombre" name="nombre" required placeholder="Ej: Balayage Premium, Corte Fade...">
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label-admin" for="nuevo_precio">
                                    <i class="bi bi-cash-stack text-warning"></i> Tarifa / Precio *
                                </label>
                                <input type="text" class="form-control form-control-admin" id="nuevo_precio" name="precio" required placeholder="Ej: S/ 35, S/ 50 a S/ 90">
                            </div>
                            <div class="col-6">
                                <label class="form-label-admin" for="nuevo_precio_label">
                                    <i class="bi bi-tag text-warning"></i> Etiqueta
                                </label>
                                <input type="text" class="form-control form-control-admin" id="nuevo_precio_label" name="precio_label" placeholder="Ej: A partir de, Precio">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-admin" for="nuevo_duracion">
                                <i class="bi bi-clock-fill text-warning"></i> Tiempo Estimado / Duración
                            </label>
                            <input type="text" class="form-control form-control-admin" id="nuevo_duracion" name="duracion" placeholder="Ej: 45 min, 2 a 3 horas">
                        </div>
                        <div class="mb-3">
                            <label class="form-label-admin" for="nuevo_descripcion">
                                <i class="bi bi-card-text text-warning"></i> Descripción del Servicio
                            </label>
                            <textarea class="form-control form-control-admin" id="nuevo_descripcion" name="descripcion" rows="2" placeholder="Detalle del procedimiento o productos incluidos..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-admin" for="nuevo_nota">
                                <i class="bi bi-info-circle text-warning"></i> Nota Adicional / Promo
                            </label>
                            <input type="text" class="form-control form-control-admin" id="nuevo_nota" name="nota_adicional" placeholder="Ej: ¡Ahorra hasta 5 soles!">
                        </div>
                        <div class="form-check form-check-admin">
                            <input class="form-check-input" type="checkbox" name="destacado" id="nuevo_destacado" value="1">
                            <label class="form-check-label text-white small fw-medium" for="nuevo_destacado">
                                <i class="bi bi-star-fill text-warning me-1"></i> Marcar como servicio destacado / promoción visual
                            </label>
                            <div class="text-secondary small mt-1" style="font-size: 0.76rem;">
                                Se resaltará visualmente en el menú público de tarifas con un borde dorado brillante.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer modal-footer-admin">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-gold btn-sm"><i class="bi bi-save me-1"></i> Guardar Servicio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- =======================================================================
         MODAL 2: EDITAR SERVICIO
         ======================================================================= -->
    <div class="modal fade" id="modalEditarServicio" tabindex="-1" aria-labelledby="modalEditarServicioLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content modal-content-admin">
                <div class="modal-header modal-header-admin">
                    <h5 class="modal-title" id="modalEditarServicioLabel">
                        <i class="bi bi-pencil-square text-info"></i> Editar Servicio y Tarifa
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form method="POST" action="index.php">
                    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                    <input type="hidden" name="accion" value="editar_servicio">
                    <input type="hidden" name="categoria_id" id="edit_categoria_id">
                    <input type="hidden" name="servicio_id" id="edit_servicio_id">
                    
                    <div class="modal-body modal-body-admin">
                        <div class="mb-3">
                            <label class="form-label-admin" for="edit_nombre">
                                <i class="bi bi-scissors text-warning"></i> Nombre del Servicio *
                            </label>
                            <input type="text" class="form-control form-control-admin" name="nombre" id="edit_nombre" required>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label-admin" for="edit_precio">
                                    <i class="bi bi-cash-stack text-warning"></i> Tarifa / Precio *
                                </label>
                                <input type="text" class="form-control form-control-admin" name="precio" id="edit_precio" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label-admin" for="edit_precio_label">
                                    <i class="bi bi-tag text-warning"></i> Etiqueta
                                </label>
                                <input type="text" class="form-control form-control-admin" name="precio_label" id="edit_precio_label">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-admin" for="edit_duracion">
                                <i class="bi bi-clock-fill text-warning"></i> Tiempo Estimado / Duración
                            </label>
                            <input type="text" class="form-control form-control-admin" name="duracion" id="edit_duracion">
                        </div>
                        <div class="mb-3">
                            <label class="form-label-admin" for="edit_descripcion">
                                <i class="bi bi-card-text text-warning"></i> Descripción del Servicio
                            </label>
                            <textarea class="form-control form-control-admin" name="descripcion" id="edit_descripcion" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-admin" for="edit_nota_adicional">
                                <i class="bi bi-info-circle text-warning"></i> Nota Adicional / Promo
                            </label>
                            <input type="text" class="form-control form-control-admin" name="nota_adicional" id="edit_nota_adicional">
                        </div>
                        <div class="form-check form-check-admin">
                            <input class="form-check-input" type="checkbox" name="destacado" id="edit_destacado" value="1">
                            <label class="form-check-label text-white small fw-medium" for="edit_destacado">
                                <i class="bi bi-star-fill text-warning me-1"></i> Marcar como servicio destacado / promoción visual
                            </label>
                            <div class="text-secondary small mt-1" style="font-size: 0.76rem;">
                                Resaltará este servicio con acabado de alta visibilidad en la web pública.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer modal-footer-admin">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-gold btn-sm"><i class="bi bi-arrow-repeat me-1"></i> Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- =======================================================================
         MODAL 3: MENSAJES Y CONDICIONES GENERALES
         ======================================================================= -->
    <div class="modal fade" id="modalMensajes" tabindex="-1" aria-labelledby="modalMensajesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content modal-content-admin">
                <div class="modal-header modal-header-admin">
                    <h5 class="modal-title" id="modalMensajesLabel">
                        <i class="bi bi-chat-left-quote-fill text-warning"></i> Políticas y Avisos Informativos
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form method="POST" action="index.php">
                    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                    <input type="hidden" name="accion" value="guardar_mensajes">

                    <div class="modal-body modal-body-admin">
                        <div class="mb-3">
                            <label class="form-label-admin">
                                <i class="bi bi-percent text-warning"></i> Aviso de Promociones y Descuentos
                            </label>
                            <textarea class="form-control form-control-admin" name="mensaje_promociones" rows="2"><?= e($mensajes['promociones'] ?? '') ?></textarea>
                            <div class="text-secondary small mt-1" style="font-size: 0.76rem;">Visible en notas informativas del sitio.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-admin">
                                <i class="bi bi-calendar2-check text-warning"></i> Aviso de Reservas y Disponibilidad
                            </label>
                            <textarea class="form-control form-control-admin" name="mensaje_reserva" rows="2"><?= e($mensajes['reserva'] ?? '') ?></textarea>
                            <div class="text-secondary small mt-1" style="font-size: 0.76rem;">Aparece al pie de cada modal de especialidad.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-admin">
                                <i class="bi bi-shield-exclamation text-warning"></i> Condiciones Generales de Tarifas (Diagnóstico y Acuerdo)
                            </label>
                            <textarea class="form-control form-control-admin" name="mensaje_general" rows="3"><?= e($mensajes['general'] ?? '') ?></textarea>
                            <div class="text-secondary small mt-1" style="font-size: 0.76rem;">Texto legal y aclaratorio sobre diagnóstico personalizado en el local.</div>
                        </div>
                    </div>
                    <div class="modal-footer modal-footer-admin">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-gold btn-sm"><i class="bi bi-save me-1"></i> Guardar Políticas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preselecciona la categoría en el modal de nuevo servicio
        function setModalCategoria(catId) {
            const select = document.getElementById('nuevo_categoria_id');
            if (select) {
                select.value = catId;
            }
        }

        // Carga los datos en el modal de edición
        function abrirModalEditar(catId, servicio) {
            document.getElementById('edit_categoria_id').value = catId;
            document.getElementById('edit_servicio_id').value = servicio.id;
            document.getElementById('edit_nombre').value = servicio.nombre || '';
            document.getElementById('edit_precio').value = servicio.precio || '';
            document.getElementById('edit_precio_label').value = servicio.precio_label || '';
            document.getElementById('edit_duracion').value = servicio.duracion || '';
            document.getElementById('edit_descripcion').value = servicio.descripcion || '';
            document.getElementById('edit_nota_adicional').value = servicio.nota_adicional || '';
            document.getElementById('edit_destacado').checked = !!servicio.destacado;

            const modal = new bootstrap.Modal(document.getElementById('modalEditarServicio'));
            modal.show();
        }

        // Filtro rápido de servicios en tiempo real (Desktop y Móvil)
        function filtrarServicios(inputElem, catId) {
            const query = (inputElem.value || '').trim().toLowerCase();
            const tabPane = document.getElementById('tab-' + catId);
            if (!tabPane) return;

            const rows = tabPane.querySelectorAll('.service-item-row');
            rows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                if (!query || name.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
