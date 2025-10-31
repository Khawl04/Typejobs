<?php

//Ruta principal
// Cargar config (que ya inicia sesión internamente)
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/autoload.php';
require_once __DIR__ . '/../core/Router.php';

$router = new Router();

// Ruta principal
$router->get('/', function() {
    require_once __DIR__ . '/../app/views/index.php';
});

// Ruta admin
$router->get('/admin', function() {
    require_once __DIR__ . '/../app/views/admin/index.php';
});

// Ruta cliente
$router->get('/cliente', 'ClienteController@dashboard');
$router->get('/cliente/perfil', 'ClienteController@perfil');
$router->post('/cliente/perfil', 'ClienteController@perfil');
$router->get('/cliente/reservas', 'ClienteController@reservas');
$router->post('/cliente/reservas', 'ClienteController@reservas');
$router->get('/cliente/pagos', 'ClienteController@pagos');


// Ruta proveedor
$router->get('/proveedor', 'ProveedorController@dashboard');
$router->post('/proveedor', 'ProveedorController@borrarServicio');
$router->get('/proveedor/servicios', 'ProveedorController@servicios');
$router->get('/proveedor/perfil', 'ProveedorController@perfil');
$router->post('/proveedor/perfil', 'ProveedorController@perfil');
$router->get('/proveedor/reservas', 'ProveedorController@reservas');

// Ruta servicio
$router->get('/servicio', 'ServicioController@index'); 
$router->post('/servicio', 'ServicioController@guardar');
$router->get('/servicio/detalle', 'ServicioController@detalle');

//Ruta reserva
$router->get('/reserva', 'ReservaController@nuevaReserva');
$router->post('/reserva', 'ReservaController@crear');


// Ruta mensaje
$router->get('/mensaje/mensaje', 'MensajeController@mensaje');

// Ruta orden
$router->get('/pago/orden', 'PagoController@orden');
$router->post('/pago/orden', 'PagoController@orden');


// Ruta pago
$router->get('/pago', 'PagoController@pago');


// Ruta soporte
$router->get('/soporte', function() {
    require_once __DIR__ . '/../app/views/soporte/index.php';
});

// RUTAS PARA REGISTER
$router->get('/register', function() {
    require_once __DIR__ . '/../app/views/auth/register.php';
});

$router->post('/register', 'UsuarioController@registrar');


// RUTAS PARA LOGIN
$router->post('/login', function() {
    require_once __DIR__ . '/../app/models/Usuario.php';
    $usuario = new Usuario();

    $usuarioOEmail = $_POST['usuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    try {
        $user = $usuario->login($usuarioOEmail, $contrasena);

        error_log('ARRAY LOGIN: ' . print_r($user, true));
        
        if ($user) {
            // Puedes guardar los datos en $_SESSION e ir a la pantalla principal
            $_SESSION['id_usuario']    = $user['id_usuario'];
            $_SESSION['nombre']        = $user['nombre'];
            $_SESSION['apellido']      = $user['apellido'];
            $_SESSION['tipo_usuario']  = strtolower($user['tipo_usuario']);
            $_SESSION['foto_perfil']   = $user['foto_perfil'];    // <-- AGREGA ESTO
            $_SESSION['nomusuario']    = $user['nomusuario'];     // <-- Y ESTO SI TU DB LO TIENE
            $_SESSION['email']         = $user['email'];          // Email // mejor en minúsculas
            if ($user) {
                
    if ($_SESSION['tipo_usuario'] === 'cliente') {
        header('Location: ' . BASE_URL . '/cliente');
        die('redirigiendo');
        exit;
    }
    if ($_SESSION['tipo_usuario'] === 'proveedor') {
        header('Location: ' . BASE_URL . '/proveedor');
        exit;
    }
    // Si quisieras agregar admin u otro perfil, lo agregás aquí
    header('Location: ' . BASE_URL . '/');
    exit;
}

        } else {
            // Login incorrecto
            $mensaje_error = 'Usuario/email o contraseña incorrectos.';
            require_once __DIR__ . '/../app/views/auth/login.php';
        }
    } catch (Exception $e) {
        $mensaje_error = "Error: " . $e->getMessage();
        require_once __DIR__ . '/../app/views/auth/login.php';
    }
});
$router->get('/login', function() {
    require_once __DIR__ . '/../app/views/auth/login.php';
});



// Logout
$router->get('/logout', function() {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION = [];
    session_unset();
    session_destroy();
    var_dump($_SESSION); // Vacío otra vez
    header('Location: ' . BASE_URL . '/');
    exit;
});

// 404
$router->setNotFound(function() {
    http_response_code(404);
    echo "<h1>404 - Página no encontrada</h1>";
    echo "<h1>Puede que estés tratando de acceder a una ruta que no existe</h1>";
});

// otras rutas
$router->run();