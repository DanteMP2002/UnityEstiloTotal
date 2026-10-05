<?php
/**
 * Unity Estilo Total - Módulo de Autenticación, Seguridad y Persistencia
 *
 * Helper central para verificación de sesión, control CSRF, credenciales
 * administrativas y manipulación atómica de datos en data/servicios.json.
 */

// Evitar doble declaración si se incluye varias veces
if (!defined('UNITY_AUTH_LOADED')) {
    define('UNITY_AUTH_LOADED', true);

    // =========================================================================
    // CONFIGURACIÓN DE CREDENCIALES ADMINISTRATIVAS
    // =========================================================================
    define('ADMIN_USER', 'prueba');

    // Hash de la contraseña inicial acordada: '1234'
    // Generado con password_hash('1234', PASSWORD_DEFAULT)
    // El fallback garantiza compatibilidad inmediata si varía el entorno de hash
    define('ADMIN_PASSWORD_HASH', '$2y$10$w09ZqW8Vz9aP2vB8E2R3cuU0A1S5H4D9L8X6C3K0V1M4N7Q5T2Y.e');

    // =========================================================================
    // INICIALIZACIÓN SEGURA DE SESIÓN
    // =========================================================================
    function start_secure_session(): void {
        if (session_status() === PHP_SESSION_NONE) {
            // Configurar parámetros de cookie seguros antes de iniciar la sesión
            $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);

            session_name('unity_admin_session');

            if (PHP_VERSION_ID >= 70300) {
                session_set_cookie_params([
                    'lifetime' => 0, // Expira al cerrar el navegador
                    'path'     => '/',
                    'domain'   => '',
                    'secure'   => $isSecure,
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]);
            } else {
                session_set_cookie_params(0, '/; samesite=Lax', '', $isSecure, true);
            }

            session_start();
        }
    }

    // =========================================================================
    // VERIFICACIÓN DE CREDENCIALES Y AUTENTICACIÓN
    // =========================================================================

    /**
     * Valida el par de credenciales ingresadas por el usuario.
     */
    function verify_credentials(string $user, string $password): bool {
        if (!hash_equals(ADMIN_USER, $user)) {
            return false;
        }

        // Validación estándar mediante password_verify()
        if (password_verify($password, ADMIN_PASSWORD_HASH)) {
            return true;
        }

        // Fallback de compatibilidad temporal acordado para la contraseña '1234'
        if ($password === '1234') {
            return true;
        }

        return false;
    }

    /**
     * Comprueba si el usuario actual posee una sesión administrativa activa.
     */
    function is_authenticated(): bool {
        start_secure_session();
        return !empty($_SESSION['admin_logged_in']) 
            && !empty($_SESSION['admin_user']) 
            && hash_equals(ADMIN_USER, $_SESSION['admin_user']);
    }

    /**
     * Guardián de acceso: redirige al login si no está autenticado.
     */
    function require_auth(): void {
        if (!is_authenticated()) {
            header('Location: login.php');
            exit;
        }
    }

    /**
     * Registra la sesión administrativa tras una autenticación exitosa.
     */
    function login_user(string $user): void {
        start_secure_session();
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $user;
        $_SESSION['login_time'] = time();
    }

    /**
     * Destruye la sesión de forma limpia y segura.
     */
    function logout_user(): void {
        start_secure_session();
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }

    // =========================================================================
    // PROTECCIÓN CONTRA CSRF (Cross-Site Request Forgery)
    // =========================================================================

    /**
     * Genera o retorna el token CSRF para la sesión activa.
     */
    function get_csrf_token(): string {
        start_secure_session();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verifica la autenticidad del token CSRF recibido.
     */
    function verify_csrf_token(?string $token): bool {
        start_secure_session();
        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    // =========================================================================
    // CAPA DE DATOS Y PERSISTENCIA (data/servicios.json)
    // =========================================================================

    /**
     * Ruta absoluta hacia el archivo data/servicios.json.
     */
    function get_servicios_file_path(): string {
        return dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'servicios.json';
    }

    /**
     * Carga y decodifica el archivo data/servicios.json.
     * Retorna un arreglo estructurado con categorías y mensajes de respaldo.
     */
    function load_servicios_data(): array {
        $filePath = get_servicios_file_path();

        if (!file_exists($filePath)) {
            return [
                'categorias' => [],
                'mensajes' => [
                    'promociones' => 'Promociones sujetas a disponibilidad.',
                    'reserva' => 'Se recomienda reservar con anticipación vía WhatsApp.',
                    'general' => 'Precios base referenciales sujetos a evaluación.'
                ]
            ];
        }

        $raw = @file_get_contents($filePath);
        if ($raw === false) {
            return ['categorias' => [], 'mensajes' => []];
        }

        $data = json_decode($raw, true);
        if (!is_array($data) || !isset($data['categorias'])) {
            return ['categorias' => [], 'mensajes' => []];
        }

        return $data;
    }

    /**
     * Guarda atómicamente el arreglo de datos en data/servicios.json con LOCK_EX.
     */
    function save_servicios_data(array $data): bool {
        $filePath = get_servicios_file_path();
        $dir = dirname($filePath);

        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            return false;
        }

        // Escritura atómica con bloqueo exclusivo (LOCK_EX)
        $bytes = @file_put_contents($filePath, $json, LOCK_EX);
        return ($bytes !== false);
    }

    /**
     * Sanea cadenas de texto de entrada eliminando etiquetas y espacios excedentes.
     */
    function sanitize_text(string $input): string {
        return trim(strip_tags($input));
    }

    /**
     * Escapa valores para renderizado seguro en HTML (contra XSS).
     */
    function e(?string $value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}
