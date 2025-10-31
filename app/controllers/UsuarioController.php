<?php

class UsuarioController extends Controller {
    private $usuarioModel;
    private $clienteModel;
    private $proveedorModel;

    public function __construct() {
        $this->usuarioModel = $this->model('Usuario');
        $this->clienteModel = $this->model('Cliente');
        $this->proveedorModel = $this->model('Proveedor');
    }

    // Muestra el formulario de registro
    public function registro() {
        $this->view('usuario/registro');
    }

    // Procesa el registro de usuario, creando cliente/proveedor según opción
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'email'                => $_POST['email'] ?? '',
                'nombre'               => $_POST['nombre'] ?? '',
                'apellido'             => $_POST['apellido'] ?? '',
                'nomusuario'           => $_POST['nomusuario'] ?? '',
                'telefono'             => $_POST['telefono'] ?? '',
                'contrasena'           => $_POST['contrasena'] ?? '',
                'confirmarcontrasena'  => $_POST['confirmarcontrasena'] ?? '',
                'tipo_usuario'         => $_POST['tipo_usuario'] ?? 'cliente',
                'foto_perfil'          => $_POST['foto_perfil'] ?? ''
            ];

            try {
                // 1. Crear usuario principal
                $id_usuario = $this->usuarioModel->registrar($data);

                // 2. Crear registro en cliente o proveedor según tipo_usuario
                if ($id_usuario && strtolower($data['tipo_usuario']) === 'cliente') {
                    $this->clienteModel->create(['id_usuario' => $id_usuario]);
                }
                if ($id_usuario && strtolower($data['tipo_usuario']) === 'proveedor') {
                    $this->proveedorModel->create(['id_usuario' => $id_usuario]);
                }

                // 3. Setear sesión y redirigir al dashboard correspondiente
                $_SESSION['user_id'] = $id_usuario;
                $_SESSION['tipo_usuario'] = strtolower($data['tipo_usuario']);
                $_SESSION['nombre'] = $data['nombre'];
                $_SESSION['apellido'] = $data['apellido'];
                $_SESSION['email'] = $data['email'];
                $_SESSION['foto_perfil'] = $data['foto_perfil'];
                $_SESSION['telefono'] = $data['telefono'];

                if (strtolower($data['tipo_usuario']) === 'proveedor') {
                    header('Location: /proveedor/dashboard');
                    exit;
                } else {
                    header('Location: /cliente/dashboard');
                    exit;
                }

            } catch (Exception $e) {
                $this->view('auth/register', ['mensaje_error' => $e->getMessage()]);
            }
        } else {
            $this->view('usuario/registro');
        }
    }

    // Login idéntico a tu lógica actual
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
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Formato de correo inválido";
            $this->redirect('/login');
        }
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
                $_SESSION['id_usuario']   = $user['id_usuario'];
                $_SESSION['nombre']       = $user['nombre'];
                $_SESSION['apellido']     = $user['apellido'];
                $_SESSION['email']        = $user['email'];
                $_SESSION['tipo_usuario'] = $user['tipo_usuario'];
                $_SESSION['foto_perfil']  = $user['foto_perfil'];
                $_SESSION['nomusuario']   = $user['nomusuario'];
                $_SESSION['success'] = "Bienvenido, {$user['nombre']} {$user['apellido']}";
                
                switch ($user['tipo_usuario']) {
                    case 'administrador':
                        $this->redirect('/admin/dashboard'); break;
                    case 'proveedor':
                        $this->redirect('/proveedor/dashboard'); break;
                    case 'cliente':
                        $this->redirect('/cliente/dashboard'); break;
                    case 'soporte':
                        $this->redirect('/soporte/dashboard'); break;
                    default: $this->redirect('/');
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
}