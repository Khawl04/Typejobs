<?php

class AdminController extends Controller
{
    private $categoriaModel;
    private $servicioModel;
    private $usuarioModel;

    public function __construct()
    {
        $this->requireAuth();
        $this->requireRole('administrador');

        $this->categoriaModel = $this->model('Categoria');
        $this->servicioModel  = $this->model('Servicio');
        $this->usuarioModel   = $this->model('Usuario');
    }

    // ============ DASHBOARD PRINCIPAL ============
    
    public function dashboard()
    {
        
        $data = [
            'totalUsuarios'     => $this->usuarioModel->contarTodos(),
            'totalServicios'    => $this->servicioModel->contarActivos(),
            'totalCategorias'   => $this->categoriaModel->contarTodas(),
            'totalMensajes'     => $this->model('Mensaje')->contarTodos(),
            'accionesRecientes' => []
        ];
        $this->view('admin/dashboard', $data);
    }

    // ============= CATEGORÍAS =============
    public function categorias()
    {
        // Verificar si viene una acción por GET (editar)
        $accion = $_GET['action'] ?? null;
        $id = $_GET['id'] ?? null;
        
        // Si la acción es "editar", cargar la categoría para edición
        if ($accion === 'editar' && $id) {
            $categoriaEditar = $this->categoriaModel->find($id);
            if (!$categoriaEditar) {
                $_SESSION['mensaje'] = "Categoría no encontrada";
                $_SESSION['tipo_mensaje'] = "danger";
                $this->redirect('/admin/categorias');
                return;
            }
            $categorias = $this->categoriaModel->obtenerTodas();
            $this->view('admin/categorias', [
                'categorias' => $categorias,
                'categoriaEditar' => $categoriaEditar
            ]);
            return;
        }
        
        // Vista normal de listado
        $categorias = $this->categoriaModel->obtenerTodas();
        $this->view('admin/categorias', ['categorias' => $categorias]);
    }
    
    public function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/categorias');
            return;
        }
        
        $nombre      = $this->sanitize($_POST['nombre'] ?? '');
        $descripcion = $this->sanitize($_POST['descripcion'] ?? '');
        
        if (empty($nombre)) {
            $_SESSION['mensaje'] = "El nombre de la categoría es obligatorio";
            $_SESSION['tipo_mensaje'] = "danger";
            $this->redirect('/admin/categorias');
            return;
        }
        
        try {
            $resultado = $this->categoriaModel->crear([
                'nombre' => $nombre,
                'descripcion' => $descripcion
            ]);
            
            if ($resultado) {
                $_SESSION['mensaje'] = "Categoría creada exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al crear la categoría";
                $_SESSION['tipo_mensaje'] = "danger";
            }
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error al crear categoría: " . $e->getMessage();
            $_SESSION['tipo_mensaje'] = "danger";
        }
        
        $this->redirect('/admin/categorias');
    }
    
    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/categorias');
            return;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['mensaje'] = "ID de categoría no especificado";
            $_SESSION['tipo_mensaje'] = "danger";
            $this->redirect('/admin/categorias');
            return;
        }
        
        $nombre      = $this->sanitize($_POST['nombre'] ?? '');
        $descripcion = $this->sanitize($_POST['descripcion'] ?? '');
        
        if (empty($nombre)) {
            $_SESSION['mensaje'] = "El nombre de la categoría es obligatorio";
            $_SESSION['tipo_mensaje'] = "danger";
            $this->redirect('/admin/categorias');
            return;
        }
        
        try {
            $resultado = $this->categoriaModel->editar($id, [
                'nombre' => $nombre,
                'descripcion' => $descripcion
            ]);
            
            if ($resultado) {
                $_SESSION['mensaje'] = "Categoría actualizada exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al actualizar la categoría";
                $_SESSION['tipo_mensaje'] = "danger";
            }
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error al actualizar categoría: " . $e->getMessage();
            $_SESSION['tipo_mensaje'] = "danger";
        }
        
        $this->redirect('/admin/categorias');
    }
    
    public function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/categorias');
            return;
        }
        
        $id = $_POST['id_categoria'] ?? null;
        
        if (!$id) {
            $_SESSION['mensaje'] = "ID de categoría no especificado";
            $_SESSION['tipo_mensaje'] = "danger";
            $this->redirect('/admin/categorias');
            return;
        }
        
        try {
            $resultado = $this->categoriaModel->eliminar($id);
            
            if ($resultado) {
                $_SESSION['mensaje'] = "Categoría eliminada exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "No se puede eliminar: la categoría tiene servicios asociados";
                $_SESSION['tipo_mensaje'] = "danger";
            }
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error al eliminar categoría: " . $e->getMessage();
            $_SESSION['tipo_mensaje'] = "danger";
        }
        
        $this->redirect('/admin/categorias');
    }

    // ============= SERVICIOS =============
    public function servicios()
    {
        $servicios = $this->servicioModel->obtenerTodosConProveedor();
        $this->view('admin/servicios', ['servicios' => $servicios]);
    }
    
    public function eliminarServicio()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/servicios');
            return;
        }
        
        $id = $_POST['id_servicio'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = "ID de servicio no especificado";
            $this->redirect('/admin/servicios');
            return;
        }
        
        $servicio = $this->servicioModel->find($id);
        
        if (!$servicio) {
            $_SESSION['error'] = "Servicio no encontrado";
            $this->redirect('/admin/servicios');
            return;
        }
        
        try {
            $this->servicioModel->borrar($id);
            $_SESSION['success'] = "Servicio eliminado exitosamente";
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al eliminar servicio: " . $e->getMessage();
        }
        
        $this->redirect('/admin/servicios');
    }

    // ============= USUARIOS =============
    public function usuarios()
    {
        $usuarios = $this->usuarioModel->obtenerTodosConTipo();
        $this->view('admin/usuarios', ['usuarios' => $usuarios]);
    }
    
    public function eliminarUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/usuarios');
            return;
        }
        
        $id = $_POST['id_usuario'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = "ID de usuario no especificado";
            $this->redirect('/admin/usuarios');
            return;
        }
        
        $usuario = $this->usuarioModel->find($id);
        
        if (!$usuario) {
            $_SESSION['error'] = "Usuario no encontrado";
            $this->redirect('/admin/usuarios');
            return;
        }
        
        $tipoUsuario = $usuario['tipo_usuario'];
        
        try {
            if ($tipoUsuario === 'cliente') {
                $clienteModel = $this->model('Cliente');
                $clienteModel->eliminarCliente($id);
            } elseif ($tipoUsuario === 'proveedor') {
                $proveedorModel = $this->model('Proveedor');
                $proveedorModel->eliminarProveedor($id);
            } else {
                $this->usuarioModel->eliminarUsuario($id);
            }
            
            $_SESSION['success'] = "Usuario eliminado exitosamente";
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al eliminar usuario: " . $e->getMessage();
        }
        
        $this->redirect('/admin/usuarios');
    }
    // ============= MENSAJES =============
public function mensajes()
{
    $estado = $_GET['estado'] ?? null;
    $buscar = $_GET['buscar'] ?? null;
    $accion = $_GET['action'] ?? null;
    $id = $_GET['id'] ?? null;

    // Si la acción es "ver", cargar el mensaje para detalles
    if ($accion === 'ver' && $id) {
        $mensajeDetalle = $this->model('Mensaje')->find($id);
        if (!$mensajeDetalle) {
            $_SESSION['mensaje'] = "Mensaje no encontrado";
            $_SESSION['tipo_mensaje'] = "danger";
            $this->redirect('/admin/mensajes');
            return;
        }
        
        // Marcar como leído
        $this->model('Mensaje')->update($id, ['estado' => 'leido']);
        
        $mensajes = $this->model('Mensaje')->obtenerTodos($estado, $buscar);
        $this->view('admin/mensajes', [
            'mensajes' => $mensajes,
            'mensajeDetalle' => $mensajeDetalle
        ]);
        return;
    }

    // Vista normal de listado
    $mensajes = $this->model('Mensaje')->obtenerTodos($estado, $buscar);
    $this->view('admin/mensajes', ['mensajes' => $mensajes]);
}

public function marcarLeido()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect('/admin/mensajes');
        return;
    }

    $id = $_POST['id_mensaje'] ?? null;

    if (!$id) {
        $_SESSION['mensaje'] = "ID de mensaje no especificado";
        $_SESSION['tipo_mensaje'] = "danger";
        $this->redirect('/admin/mensajes');
        return;
    }

    try {
        $this->model('Mensaje')->update($id, ['estado' => 'leido']);
        $_SESSION['mensaje'] = "Mensaje marcado como leído";
        $_SESSION['tipo_mensaje'] = "success";
    } catch (Exception $e) {
        $_SESSION['mensaje'] = "Error al marcar mensaje: " . $e->getMessage();
        $_SESSION['tipo_mensaje'] = "danger";
    }

    $this->redirect('/admin/mensajes');
}

public function eliminarMensaje()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect('/admin/mensajes');
        return;
    }

    $id = $_POST['id_mensaje'] ?? null;

    if (!$id) {
        $_SESSION['mensaje'] = "ID de mensaje no especificado";
        $_SESSION['tipo_mensaje'] = "danger";
        $this->redirect('/admin/mensajes');
        return;
    }

    try {
        $this->model('Mensaje')->delete($id);
        $_SESSION['mensaje'] = "Mensaje eliminado exitosamente";
        $_SESSION['tipo_mensaje'] = "success";
    } catch (Exception $e) {
        $_SESSION['mensaje'] = "Error al eliminar mensaje: " . $e->getMessage();
        $_SESSION['tipo_mensaje'] = "danger";
    }

    $this->redirect('/admin/mensajes');
}
public function editarPerfil() {
    $usuarioModel = $this->model('Usuario');
    $id = $_SESSION['id_usuario'];

    // Eliminar foto de perfil si se pidió
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['eliminar_foto'])) {
        $usuario = $usuarioModel->find($id);
        if (!empty($usuario['foto_perfil'])) {
            $rutaFoto = __DIR__ . '/../../public/uploads/perfiles/' . $usuario['foto_perfil'];
            if (file_exists($rutaFoto)) unlink($rutaFoto);
            $usuarioModel->update($id, ['foto_perfil' => null]);
            $_SESSION['foto_perfil'] = null;
        }
        header("Location: " . BASE_URL . "/admin/perfil");
        exit;
    }

    // Subir/actualizar nueva foto de perfil
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
        $archivo = $_FILES['foto'];
        if ($archivo['error'] === UPLOAD_ERR_OK) {
            // Borra la foto anterior si existe
            $usuario = $usuarioModel->find($id);
            if (!empty($usuario['foto_perfil'])) {
                $oldPath = __DIR__ . '/../../public/uploads/perfiles/' . $usuario['foto_perfil'];
                if (file_exists($oldPath)) unlink($oldPath);
            }

            $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
            $nuevoNombre = 'perfil_' . $id . '_' . time() . '.' . $extension;
            $carpeta = __DIR__ . '/../../public/uploads/perfiles/';
            if (!is_dir($carpeta)) mkdir($carpeta, 0755, true);
            move_uploaded_file($archivo['tmp_name'], $carpeta . $nuevoNombre);
            $usuarioModel->update($id, ['foto_perfil' => $nuevoNombre]);
            $_SESSION['foto_perfil'] = $nuevoNombre;
        }
        header("Location: " . BASE_URL . "/admin/perfil");
        exit;
    }

    $usuario = $usuarioModel->find($id);
    $this->view('admin/perfil', ['usuario' => $usuario]);
}




}
