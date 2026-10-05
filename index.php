<?php
/**
 * Unity Estilo Total - Enrutador Raíz (Fallback)
 *
 * Redirecciona las peticiones entrantes hacia el directorio público (webroot),
 * asegurando compatibilidad con servidores sin soporte activo de mod_rewrite.
 */

header('Location: public/index.php');
exit;