<?php

class ProveedorController extends Controller {

    private $proveedorModel;
    private $servicioModel;
    private $reservaModel;
    private $usuarioModel;
    private $mensajeModel;

    public function __construct() {
        $this->requireAuth();
        $this->requireRole('proveedor');
        $this->proveedorModel = $this->model('Proveedor');
        $this->servicioModel = $this->model('Servicio');
        $this->reservaModel = $this->model('Reserva');
        $this->usuarioModel = $this->model('Usuario');
        $this->mensajeModel = $this->model('Mensaje');
    }

    // /proveedor -> listado de servicios propios
    public function index() {
        $proveedor = $this->proveedorModel->obtenerPorUsuario($_SESSION['id_usuario']);
        $servicios = $this->servicioModel->obtenerPorProveedor($proveedor['id_usuario']);
        $this->view('servicio/servicio', ['servicios' => $servicios]);
    }

    public function reservas() {
        $proveedor = $this->proveedorModel->obtenerPorUsuario($_SESSION['id_usuario']);
        $reservas = $this->reservaModel->obtenerPorProveedor($proveedor['id_usuario']);
        $this->view('proveedor/reservas', ['reservas' => $reservas]);
    }
            public function servicios() {
    $proveedor = $this->proveedorModel->obtenerPorUsuario($_SESSION['id_usuario']);
    $servicios = $this->servicioModel->obtenerPorProveedor($proveedor['id_usuario']);
    $categoriaModel = $this->model('Categoria');
    $categorias = $categoriaModel->obtenerActivas();

    $this->view('proveedor/servicios', [
        'servicios' => $servicios,
        'categorias' => $categorias // <-- Esto es clave!
    ]);
}
public function dashboard() {
    $proveedor = $this->proveedorModel->obtenerConUsuario($_SESSION['id_usuario']);
    $totalServicios = $this->servicioModel->totalPorProveedor($proveedor['id_usuario']);
    $reservasPendientes = $this->reservaModel->pendientesPorProveedor($proveedor['id_usuario']);
    $mensajesNoLeidos = $this->mensajeModel->noLeidosPorUsuario($_SESSION['id_usuario']);
    $serviciosPropios = $this->servicioModel->obtenerPorProveedor($proveedor['id_usuario']);

    $this->view('proveedor/dashboard', [
        'proveedor' => $proveedor,
        'totalServicios' => $totalServicios,
        'reservasPendientes' => $reservasPendientes,
        'mensajesNoLeidos' => $mensajesNoLeidos,
        'serviciosPropios' => $serviciosPropios
    ]);
}
    public function eliminarCuenta() {
    $this->proveedorModel->eliminarProveedor($_SESSION['id_usuario']);
    session_unset();
    session_destroy();
    $this->redirect('/');
}

public function borrarServicio() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_servicio'])) {
        $id = intval($_POST['id_servicio']);
        $this->servicioModel->borrar($id);
        $_SESSION['success'] = "Servicio borrado correctamente.";
    }
    $this->redirect('/proveedor/editarservicio');
}

public function perfil() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $proveedor = $this->proveedorModel->obtenerConUsuario($_SESSION['id_usuario']);

        // ---- DATOS DE USUARIO -----
        $dataUsuario = [];
        if (isset($_POST['telefono']))     $dataUsuario['telefono'] = $_POST['telefono'];
        if (isset($_POST['email']))        $dataUsuario['email'] = $_POST['email'];
        if (isset($_POST['nomusuario']))   $dataUsuario['nomusuario'] = $_POST['nomusuario'];

        // FOTO DE PERFIL
        if (!empty($_FILES['foto_perfil']['name'])) {
            $nombreArchivo = 'perfil_' . $proveedor['id_usuario'] . '_' . time() . '.jpg';
            $rutaDestino = __DIR__ . '/../../public/uploads/perfiles/' . $nombreArchivo;
            move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $rutaDestino);
            $dataUsuario['foto_perfil'] = $nombreArchivo;
            $_SESSION['foto_perfil'] = $nombreArchivo;
        }

        // CAMBIO DE CONTRASEÑA
        if (!empty($_POST['nueva_contrasena'])) {
            $contrasenaActual = $_POST['contrasena_actual'] ?? '';
            $id_usuario = $_SESSION['id_usuario'];
            $userDB = $this->usuarioModel->findById($id_usuario);
            $hashGuardado = $userDB['contrasena'];
            if (password_verify($contrasenaActual, $hashGuardado)) {
                $dataUsuario['contrasena'] = password_hash($_POST['nueva_contrasena'], PASSWORD_DEFAULT);
            } else {
                $_SESSION['error'] = 'La contraseña actual es incorrecta.';
                $this->redirect('/proveedor/perfil');
                return;
            }
        }

        // ---- DATOS DE PROVEEDOR -----
        $dataProveedor = [];
        if (isset($_POST['direccion']))    $dataProveedor['direccion'] = $_POST['direccion'];
        if (isset($_POST['descripcion']))  $dataProveedor['descripcion'] = $_POST['descripcion'];
        // Solo agrega aquí los campos que SÍ existen en la tabla proveedor.

        // ACTUALIZAR TABLAS
        if (!empty($dataUsuario)) {
            $this->usuarioModel->update($proveedor['id_usuario'], $dataUsuario);
        }
        if (!empty($dataProveedor)) {
            $this->proveedorModel->update($proveedor['id_usuario'], $dataProveedor);
        }

        $_SESSION['success'] = 'Perfil actualizado correctamente.';
        $this->redirect('/proveedor/perfil');
        return;
    }

    // Solo GET, muestra la vista
    $proveedor = $this->proveedorModel->obtenerConUsuario($_SESSION['id_usuario']);
    $this->view('proveedor/perfil', [
        'proveedor' => $proveedor
    ]);
}
// Solo para editar (update)
public function editarServicio() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Validar todos los campos necesarios antes de armar $data:
        if (
            !isset(
                $_POST['titulo'],
                $_POST['descripcion'],
                $_POST['precio'],
                $_POST['id_categoria'],
                $_POST['duracion_estimada']
            )
        ) {
            $_SESSION['error'] = 'Faltan campos en el formulario de edición.';
            $this->redirect('/proveedor/editarservicio');
            return;
        }

        $id = intval($_POST['id_servicio']);
        $data = [
            'titulo' => $_POST['titulo'],
            'descripcion' => $_POST['descripcion'],
            'precio' => $_POST['precio'],
            'id_categoria' => $_POST['id_categoria'],
            'duracion_estimada' => $_POST['duracion_estimada'],
        ];

        // Para la imagen:
        if (!empty($_FILES['imagen_servicio']['name'])) {
            $nombreArchivo = 'servicio_' . $id . '_' . time() . '.jpg';
            $rutaDestino = __DIR__ . '/../../public/uploads/servicios/' . $nombreArchivo;
            if (move_uploaded_file($_FILES['imagen_servicio']['tmp_name'], $rutaDestino)) {
                $data['imagen_servicio'] = 'uploads/servicios/' . $nombreArchivo;
            }
        }

        $this->servicioModel->update($id, $data);
        $_SESSION['success'] = 'Servicio actualizado correctamente.';
        $this->redirect('/proveedor/editarservicio');
        return;
    }
    // SOLO mostrar la vista en GET:
    $proveedor = $this->proveedorModel->obtenerPorUsuario($_SESSION['id_usuario']);
    $serviciosPropios = $this->servicioModel->obtenerPorProveedor($proveedor['id_usuario']);
    $categoriaModel = $this->model('Categoria');
    $categorias = $categoriaModel->obtenerActivas();
    require_once __DIR__ . '/../views/proveedor/editarservicio.php';
}



}
?>