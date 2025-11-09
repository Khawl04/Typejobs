<?php
// ==============================
// CONFIGURACIÓN GLOBAL TYPEJOBS
// ==============================

// URL base (pública)
define('BASE_URL', 'http://localhost:8080/TYPEJOBS/public');

// Ruta raíz del proyecto (interna, para PHP)
define('ROOT_PATH', dirname(__DIR__));

// ==============================
// Configuración general
// ==============================

// Zona horaria
date_default_timezone_set('America/Montevideo');

// Mostrar errores (solo en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ==============================
// Sesiones seguras (ANTES de session_start)
// ==============================
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // poner en 1 si usás HTTPS

// Iniciar sesión después de configurarla
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==============================
// Archivos y uploads
// ==============================
define('UPLOAD_PATH', ROOT_PATH . '/public/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024);
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif','pdf', 'doc', 'docx','odt','txt','rar','zip','mp3','mp4']);

// ==============================
// Paginación
// ==============================
define('ITEMS_PER_PAGE', 12);
