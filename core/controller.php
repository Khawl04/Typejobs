<?php

class Controller {
    
    // Cargar una vista
    protected function view($view, $data = []) {
        extract($data);
        
        $viewPath = __DIR__ . "/../app/views/{$view}.php";
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("Vista no encontrada: {$view}");
        }
    }
    
    // Cargar un modelo
    protected function model($model) {
        $modelPath = __DIR__ . "/../app/models/{$model}.php";
        
        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $model();
        } else {
            die("Modelo no encontrado: {$model}");
        }
    }
    
    // Redireccionar
    protected function redirect($url) {
        header("Location: " . BASE_URL . $url);
        exit();
    }
    
    // Retornar JSON
    protected function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    
    // Validar si el usuario está autenticado
    protected function requireAuth() {
        if (!isset($_SESSION['id_usuario'])) {
            $this->redirect('/login');
        }
    }
    
    // Validar rol de usuario
    protected function requireRole($role) {
        if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== $role) {
            $this->redirect('/');
        }
    }
    
    // Sanitizar input
    protected function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
}