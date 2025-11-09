<?php
/**
 * Carga automática de clases siguiendo la estructura MVC de tu proyecto
 * ¡Simple y efectiva para PHP nativo sin Composer!
 */

spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../app/controllers/',
        __DIR__ . '/../app/models/',
        __DIR__ . '/../core/',           // Tus clases base (DB, Auth, etc)
    ];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
