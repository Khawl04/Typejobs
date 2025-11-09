<?php

//Ruta principal
// Cargar config (que ya inicia sesión internamente)
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/autoload.php';
require_once __DIR__ . '/../core/Router.php';

$router = new Router();

//Rutas layouts
$router->get('/layouts/sobre', 'DefaultController@sobre');
$router->get('/layouts/contacto', 'DefaultController@contacto');
$router->post('/layouts/contacto', 'DefaultController@contacto');

// Ruta principal
$router->get('/', function() {
    require_once __DIR__ . '/../app/views/index.php';
});

// Ruta admin
$router->get('/admin', 'AdminController@dashboard');
$router->get('/admin/usuarios', 'AdminController@usuarios');
$router->post('/admin/eliminarUsuario', 'AdminController@eliminarUsuario');
$router->get('/admin/servicios', 'AdminController@servicios');
$router->post('/admin/eliminarServicio', 'AdminController@eliminarServicio');
$router->get('/admin/categorias', 'AdminController@categorias');
$router->get('/admin/categorias/editar/{id}', 'AdminController@categorias');
$router->get('/admin/categorias/eliminar/{id}', 'AdminController@categorias');
$router->post('/admin/categorias/crear', 'AdminController@crear');
$router->post('/admin/categorias/actualizar', 'AdminController@actualizar');
$router->post('/admin/categorias/eliminar', 'AdminController@eliminar');
$router->get('/admin/mensajes', 'AdminController@mensajes');
$router->post('/admin/mensajes/eliminar', 'AdminController@eliminarMensaje');
$router->get('/admin/perfil', 'AdminController@editarPerfil');
$router->post('/admin/perfil', 'AdminController@editarPerfil');




// Ruta soporte
$router->get('/soporte', function() {
    require_once __DIR__ . '/../app/views/soporte/soporte.php';
});

//Ruta perfil
$router->get('/perfil', 'PerfilController@index');

// Ruta cliente
$router->get('/cliente', 'ClienteController@dashboard');
$router->get('/cliente/perfil', 'ClienteController@perfil');
$router->post('/cliente/perfil', 'ClienteController@perfil');
$router->get('/cliente/reservas', 'ClienteController@reservas');
$router->post('/cliente/reservas', 'ClienteController@reservas');
$router->get('/cliente/pagos', 'ClienteController@pagos');
$router->post('/cliente/eliminarCuenta', 'ClienteController@eliminarCuenta');



// Ruta proveedor
$router->get('/proveedor', 'ProveedorController@dashboard');
$router->get('/proveedor/editarservicio', 'ProveedorController@editarServicio');
$router->post('/proveedor/editarservicio', 'ProveedorController@editarServicio');
$router->post('/proveedor/borrarServicio', 'ProveedorController@borrarServicio');
$router->get('/proveedor/servicios', 'ProveedorController@servicios');
$router->get('/proveedor/perfil', 'ProveedorController@perfil');
$router->post('/proveedor/perfil', 'ProveedorController@perfil');
$router->get('/proveedor/reservas', 'ProveedorController@reservas');
$router->post('/proveedor/eliminarCuenta', 'ProveedorController@eliminarCuenta');


// Ruta servicio
$router->get('/servicio', 'ServicioController@index'); 
$router->post('/servicio', 'ServicioController@guardar');
$router->get('/servicio/detalle', 'ServicioController@detalle');
$router->post('/servicio/detalle', 'ServicioController@detalle'); // POST para guardar reseña
$router->post('/servicio/likeResena', 'ServicioController@likeResena'); // POST para dar like a reseña


//Ruta reserva
$router->get('/reserva', 'ReservaController@nuevaReserva');
$router->post('/reserva', 'ReservaController@crear');


// Ruta mensaje
$router->get('/mensaje', 'MensajeController@mensaje');
$router->post('/mensaje', 'MensajeController@enviar');


// Ruta orden
$router->get('/pago/orden', 'PagoController@orden');
$router->post('/pago/orden', 'PagoController@orden');


// Ruta pago
$router->get('/pago', 'PagoController@pago');


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