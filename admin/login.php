<?php
/**
 * Unity Estilo Total - Inicio de Sesión Administrativo
 * Interfaz Dark Luxury optimizada para móvil y escritorio
 */

require_once __DIR__ . '/auth.php';

start_secure_session();

// Si ya tiene sesión activa, redirigir directo al panel
if (is_authenticated()) {
    header('Location: index.php');
    exit;
}

$error = '';
$info = '';

if (isset($_GET['msg']) && $_GET['msg'] === 'logged_out') {
    $info = 'Has cerrado la sesión de forma segura.';
}

// Procesar formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? '';

    if (!verify_csrf_token($submittedToken)) {
        $error = 'Solicitud no válida o token de seguridad expirado. Inténtalo nuevamente.';
    } else {
        $usuario = trim($_POST['usuario'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($usuario) || empty($password)) {
            $error = 'Por favor ingresa tanto el usuario como la contraseña.';
        } elseif (verify_credentials($usuario, $password)) {
            login_user($usuario);
            header('Location: index.php');
            exit;
        } else {
            $error = 'Credenciales incorrectas. Por favor verifica tus datos de acceso.';
        }
    }
}

$csrfToken = get_csrf_token();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Acceso Administrativo | Unity Estilo Total</title>
    <link rel="icon" href="../public/img/logo.webp" type="image/x-icon">
    <!-- Google Fonts: Playfair Display & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CDN & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --negro: #0a0908;
            --bg-card: #141312;
            --bg-input: #1a1917;
            --dorado: #c9a84c;
            --dorado-hover: #dfbd5a;
            --dorado-suave: rgba(201, 168, 76, 0.15);
            --dorado-borde: rgba(201, 168, 76, 0.32);
            --blanco-roto: #f8f5f0;
            --gris-texto: #a3a099;
            --fuente-titulo: 'Playfair Display', Georgia, serif;
            --fuente-cuerpo: 'Inter', Arial, sans-serif;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        body {
            background-color: var(--negro);
            background-image: 
                radial-gradient(circle at 50% 10%, rgba(201, 168, 76, 0.09) 0%, transparent 60%),
                radial-gradient(circle at 80% 90%, rgba(201, 168, 76, 0.05) 0%, transparent 50%);
            background-attachment: fixed;
            color: var(--blanco-roto);
            font-family: var(--fuente-cuerpo);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
            margin: 0;
            position: relative;
        }

        /* Contenedor central con efecto Dark Luxury */
        .login-card {
            background: var(--bg-card);
            border: 1px solid var(--dorado-borde);
            border-radius: 16px;
            width: 100%;
            max-width: 440px;
            padding: 2.5rem 2.2rem;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.8), 0 0 35px rgba(201, 168, 76, 0.1);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            animation: fadeInCard 0.45s ease-out;
        }

        @keyframes fadeInCard {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Línea dorada decorativa en el borde superior */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--dorado), transparent);
        }

        /* Cabecera del login */
        .login-brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 76px;
            height: 76px;
            border-radius: 50%;
            background: rgba(201, 168, 76, 0.08);
            border: 1px solid var(--dorado-borde);
            margin-bottom: 1.1rem;
            box-shadow: 0 4px 18px rgba(201, 168, 76, 0.15);
            transition: transform 0.3s ease;
        }

        .login-logo-wrap:hover {
            transform: scale(1.05);
        }

        .login-brand img {
            width: 48px;
            height: auto;
            display: block;
        }

        .badge-auth {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--dorado);
            background: var(--dorado-suave);
            border: 1px solid var(--dorado-borde);
            padding: 4px 12px;
            border-radius: 50px;
            margin-bottom: 0.6rem;
        }

        .login-brand h1 {
            font-family: var(--fuente-titulo);
            font-size: 1.45rem;
            font-weight: 700;
            color: var(--blanco-roto);
            letter-spacing: 0.04em;
            margin: 0;
        }

        .login-brand h1 span {
            color: var(--dorado);
        }

        .login-brand p {
            color: var(--gris-texto);
            font-size: 0.86rem;
            margin-top: 0.4rem;
            margin-bottom: 0;
        }

        /* Formularios y campos de entrada */
        .form-label {
            color: #d8d5cf;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 0.45rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-label i {
            color: var(--dorado);
        }

        .input-group-custom {
            position: relative;
            display: flex;
            align-items: stretch;
            width: 100%;
        }

        .input-group-text-dark {
            background-color: #171614;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-right: none;
            color: var(--dorado);
            padding: 0.75rem 0.95rem;
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
            display: flex;
            align-items: center;
            font-size: 1.05rem;
        }

        .form-control-luxury {
            background-color: var(--bg-input);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            padding: 0.75rem 1rem;
            font-size: 1rem; /* Previene auto-zoom en iOS Safari */
            border-radius: 0 8px 8px 0;
            transition: all 0.25s ease;
        }

        .form-control-luxury.with-toggle {
            border-right: none;
            border-radius: 0;
        }

        .form-control-luxury:focus {
            background-color: #1f1e1a;
            border-color: var(--dorado);
            color: #fff;
            box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.22);
            outline: none;
        }

        .form-control-luxury::placeholder {
            color: #6a6760;
            font-size: 0.88rem;
        }

        .btn-toggle-pass {
            background-color: #171614;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-left: none;
            color: var(--gris-texto);
            padding: 0.75rem 0.95rem;
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
            cursor: pointer;
            transition: color 0.2s ease, border-color 0.2s ease;
        }

        .btn-toggle-pass:hover {
            color: var(--dorado);
        }

        /* Botón de acceso Luxury */
        .btn-gold-luxury {
            background: linear-gradient(135deg, #c9a84c 0%, #b8933b 100%);
            border: 1px solid #d8b85b;
            color: #0d0c0a;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.04em;
            padding: 0.82rem 1.2rem;
            width: 100%;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 6px 20px rgba(201, 168, 76, 0.25);
            transition: all 0.25s ease;
            cursor: pointer;
            margin-top: 0.5rem;
        }

        .btn-gold-luxury:hover {
            background: linear-gradient(135deg, #dfbd5a 0%, #c9a84c 100%);
            color: #000;
            box-shadow: 0 8px 25px rgba(201, 168, 76, 0.4);
            transform: translateY(-1px);
        }

        .btn-gold-luxury:active {
            transform: translateY(0);
        }

        /* Alertas personalizadas */
        .alert-luxury {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.35);
            color: #ff8b94;
            border-radius: 8px;
            padding: 0.8rem 1rem;
            font-size: 0.86rem;
            margin-bottom: 1.4rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-luxury-success {
            background: rgba(25, 135, 84, 0.12);
            border-color: rgba(25, 135, 84, 0.4);
            color: #75e0a3;
        }

        /* Enlace de regreso */
        .volver-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            text-align: center;
            margin-top: 1.5rem;
            color: var(--gris-texto);
            font-size: 0.85rem;
            text-decoration: none;
            transition: color 0.2s ease;
            padding: 6px;
        }

        .volver-link:hover {
            color: var(--dorado);
        }

        /* Adaptabilidad en pantallas pequeñas */
        @media (max-width: 480px) {
            .login-card {
                padding: 1.8rem 1.3rem;
                border-radius: 14px;
            }

            .login-brand h1 {
                font-size: 1.3rem;
            }

            .login-logo-wrap {
                width: 66px;
                height: 66px;
                margin-bottom: 0.8rem;
            }

            .login-brand img {
                width: 40px;
            }
        }
    </style>
</head>
<body>
    <main class="login-card" role="main">
        <header class="login-brand">
            <div class="login-logo-wrap">
                <img src="../public/img/logo-dorado.webp" alt="Unity Logo">
            </div>
            <div>
                <span class="badge-auth"><i class="bi bi-shield-lock"></i> Panel Administrativo</span>
            </div>
            <h1>UNITY <span>ESTILO TOTAL</span></h1>
            <p>Gestión de Catálogo, Precios y Políticas</p>
        </header>

        <?php if (!empty($error)): ?>
            <div class="alert-luxury" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            </div>
        <?php endif; ?>

        <?php if (!empty($info)): ?>
            <div class="alert-luxury alert-luxury-success" role="alert">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div><?= htmlspecialchars($info, ENT_QUOTES, 'UTF-8') ?></div>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

            <div class="mb-3">
                <label for="usuario" class="form-label">
                    <i class="bi bi-person-fill"></i> Usuario
                </label>
                <div class="input-group-custom">
                    <span class="input-group-text-dark" aria-hidden="true">
                        <i class="bi bi-person"></i>
                    </span>
                    <input type="text" 
                           class="form-control form-control-luxury" 
                           id="usuario" 
                           name="usuario" 
                           required 
                           autofocus 
                           placeholder="Ingresa tu usuario"
                           autocomplete="username"
                           aria-required="true">
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">
                    <i class="bi bi-key-fill"></i> Contraseña
                </label>
                <div class="input-group-custom">
                    <span class="input-group-text-dark" aria-hidden="true">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" 
                           class="form-control form-control-luxury with-toggle" 
                           id="password" 
                           name="password" 
                           required 
                           placeholder="Ingresa tu contraseña"
                           autocomplete="current-password"
                           aria-required="true">
                    <button type="button" 
                            class="btn-toggle-pass" 
                            id="togglePasswordBtn" 
                            title="Mostrar u ocultar contraseña" 
                            aria-label="Mostrar u ocultar contraseña">
                        <i class="bi bi-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-gold-luxury">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Ingresar al Panel</span>
            </button>
        </form>

        <a href="../public/index.php" class="volver-link">
            <i class="bi bi-arrow-left"></i>
            <span>Volver al sitio web principal</span>
        </a>
    </main>

    <script>
        // Toggle para mostrar/ocultar contraseña en móviles y computadoras
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (toggleBtn && passwordInput && toggleIcon) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                toggleIcon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
            });
        }
    </script>
</body>
</html>
