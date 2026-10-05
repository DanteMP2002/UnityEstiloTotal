<?php
/**
 * Unity Estilo Total - Cierre de Sesión Administrativa
 */

require_once __DIR__ . '/auth.php';

logout_user();

// Redireccionar al formulario de inicio de sesión
header('Location: login.php?msg=logged_out');
exit;
