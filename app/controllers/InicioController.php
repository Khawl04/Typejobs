<?php

class InicioController {
    public function index() {
        // Cargar la vista desde la ruta interna del servidor
        require ROOT_PATH . '/app/views/inicio.php';
    }
}
