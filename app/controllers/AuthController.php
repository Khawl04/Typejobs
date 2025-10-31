<?php

class AuthController extends Controller {
    
    private $usuarioModel;
    
    public function __construct() {
        $this->usuarioModel = $this->model('Usuario');
    }
    
    // Mostrar formulario de login
    public function showLogin() {
        // Si ya está logueado, redirigir al dashboard
        if (isset($_SESSION['id_usuario'])) {
            $this->redirect('/dashboard');
        }
        
        $this->view('auth/login');
    }
    
    // RF-14: Procesar login con protección SQLi
    public function login($email, $contrasena) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }
      
        $email = $this->sanitize($_POST['email'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';
        
        if (empty($email) || empty($contrasena)) {
            $_SESSION['error'] = "Por favor complete todos los campos";
            $this->redirect('/login');
        }
        
        // Validar formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Formato de correo inválido";
            $this->redirect('/login');
        }
        
        // MEJORA: Detección de SQLi
        $sqli_patterns = [
            '/(\bor\b|\band\b)\s+\d+=\d+/i', 
            '/(\'|")\s*--/', 
            '/union\s+select/i', 
            '/drop\s+table/i', 
            '/insert\s+into/i', 
            '/delete\s+from/i', 
            '/--|;|#/'
        ];

        $sqli_detected = false;

        foreach ($sqli_patterns as $pattern) {
            if (preg_match($pattern, $email) || preg_match($pattern, $contrasena)) {
                $sqli_detected = true;
                break;
            }
        }

        if ($sqli_detected) {
            $_SESSION['error'] = "Intento de inyección SQL detectado";
            error_log("SQLi detectado - IP: " . $_SERVER['REMOTE_ADDR'] . " - Email: " . $email);
            $this->redirect('/login');
        }
        
        try {
            $user = $this->usuarioModel->login($email, $contrasena);

            if ($user) {
                // Establecer sesión
                $_SESSION['id_usuario'] = $user['id_usuario'];
                $_SESSION['nombre'] = $user['nombre'];
                $_SESSION['apellido'] = $user['apellido'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['tipo_usuario'] = $user['tipo_usuario'];
                $_SESSION['foto_perfil'] = $user['foto_perfil'];
                $_SESSION['success'] = "Bienvenido, {$user['nombre']} {$user['apellido']}";
                
                error_log('REAL LOGIN SESSION: ' . print_r($_SESSION, true));


                session_write_close();

                // Redirigir según tipo de usuario
                switch ($user['tipo_usuario']) {
                    case 'administrador':
                        $this->redirect('/admin/dashboard');
                        break;
                    case 'proveedor':
                        $this->redirect('/proveedor/dashboard');
                        break;
                    case 'cliente':
                        $this->redirect('/cliente/dashboard');
                        break;
                    case 'soporte':
                        $this->redirect('/soporte/dashboard');
                        break;
                    default:
                        $this->redirect('/');
                }
            } else {
                $_SESSION['error'] = "Credenciales inválidas";
                $this->redirect('/login');
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al iniciar sesión: " . $e->getMessage();
            $this->redirect('/login');
        }
    }
    
    // Mostrar formulario de registro
    public function showRegister() {
        if (isset($_SESSION['id_usuario'])) {
            $this->redirect('/dashboard');
        }
        
        $this->view('auth/register');
    }
    
    // RF-02: Registrar cliente con validaciones mejoradas
    public function registerCliente() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
        }
        
        $data = [
            'nombre' => $this->sanitize($_POST['nombre'] ?? ''),
            'email' => $this->sanitize($_POST['email'] ?? ''),
            'contrasena' => $_POST['contrasena'] ?? '',
            'contrasena_confirmacion' => $_POST['contrasena_confirmacion'] ?? '',
            'telefono' => $this->sanitize($_POST['telefono'] ?? ''),
            'direccion' => $this->sanitize($_POST['direccion'] ?? ''),
        ];
        
        // Validaciones
        $errors = [];
        
        if (empty($data['nombre'])) {
            $errors[] = "El nombre es obligatorio";
        }
        
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email inválido";
        }
        
        // MEJORA: Validación de longitud mínima
        if (empty($data['contrasena']) || strlen($data['contrasena']) < 6) {
            $errors[] = "La contraseña debe tener al menos 6 caracteres";
        }
        
        if ($data['contrasena'] !== $data['contrasena_confirmacion']) {
            $errors[] = "Las contraseñas no coinciden";
        }
        
        // MEJORA: Validación de términos
        if (!$data['terms']) {  
            $errors[] = "Debe aceptar los términos y condiciones";
        }
        
        // MEJORA: Validación de teléfono
        if (empty($data['telefono'])) {
            $errors[] = "El teléfono es obligatorio";
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            $this->redirect('/register');
        }
        
        try {
            // MEJORA: Hash de contraseña explícito
            $data['contrasena'] = password_hash($data['contrasena'], PASSWORD_DEFAULT);
            
            $userId = $this->usuarioModel->registrarCliente($data);
            
            $_SESSION['success'] = "Registro exitoso. Por favor inicie sesión";
            $this->redirect('/login');
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['old'] = $data;
            $this->redirect('/register');
        }
    }
    
    // RF-05: Registrar proveedor con validaciones mejoradas
    public function registerProveedor() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register/proveedor');
        }
        
        $data = [
            'nombre' => $this->sanitize($_POST['nombre'] ?? ''),
            'email' => $this->sanitize($_POST['email'] ?? ''),
            'contrasena' => $_POST['contrasena'] ?? '',
            'contrasena_confirmacion' => $_POST['contrasena_confirmacion'] ?? '',
            'telefono' => $this->sanitize($_POST['telefono'] ?? ''),
            'direccion' => $this->sanitize($_POST['direccion'] ?? ''),
            'descripcion' => $this->sanitize($_POST['descripcion'] ?? ''),
        ];
        
        // Validaciones
        $errors = [];
        
        if (empty($data['nombre'])) {
            $errors[] = "El nombre es obligatorio";
        }
        
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email inválido";
        }
        
        // MEJORA: Validación de longitud mínima
        if (empty($data['contrasena']) || strlen($data['contrasena']) < 6) {
            $errors[] = "La contraseña debe tener al menos 6 caracteres";
        }
        
        if ($data['contrasena'] !== $data['contrasena_confirmacion']) {
            $errors[] = "Las contraseñas no coinciden";
        }

        // MEJORA: Validación de teléfono
        if (empty($data['telefono'])) {
            $errors[] = "El teléfono es obligatorio";
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            $this->redirect('/register/proveedor');
        }
        
        try {
            // MEJORA: Hash de contraseña explícito
            $data['contrasena'] = password_hash($data['contrasena'], PASSWORD_DEFAULT);
            
            $userId = $this->usuarioModel->registrarProveedor($data);
            
            $_SESSION['success'] = "Registro de proveedor exitoso. Por favor inicie sesión";
            $this->redirect('/login');
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['old'] = $data;
            $this->redirect('/register/proveedor');
        }
    }
    
    // RF-15: Logout
    public function logout() {
        session_destroy();
        session_start();
        $_SESSION['success'] = "Sesión cerrada exitosamente";
        $this->redirect('/login');
    }
}