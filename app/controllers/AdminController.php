<?php

class AdminController extends Controller {
    
    private $categoriaModel;
    private $servicioModel;
    private $usuarioModel;
    private $reporteModel;
    private $mantenimientoModel;
    
    public function __construct() {
        $this->requireAuth();
        $this->requireRole('administrador');
        
        $this->categoriaModel = $this->model('Categoria');
        $this->servicioModel = $this->model('Servicio');
        $this->usuarioModel = $this->model('Usuario');
        $this->reporteModel = $this->model('Reporte');
        $this->mantenimientoModel = $this->model('Mantenimiento');
    }
    
    // Dashboard principal
    public function dashboard() {
        $data = [
            'totalUsuarios' => $this->usuarioModel->contarTodos(),
            'totalServicios' => $this->servicioModel->contarActivos(),
            'reportesPendientes' => $this->reporteModel->contarPendientes(),
            'accionesRecientes' => $this->mantenimientoModel->obtenerAccionesRecientes(10)
        ];
        
        $this->view('admin/dashboard', $data);
    }
    
    // ==========================================
    // GESTIÓN DE CATEGORÍAS (RF-21)
    // ==========================================
    
    public function categorias() {
        $categorias = $this->categoriaModel->all();
        $this->view('admin/categorias/index', ['categorias' => $categorias]);
    }
    
    public function crearCategoria() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/categorias');
        }
        
        $nombre = $this->sanitize($_POST['nombre'] ?? '');
        $descripcion = $this->sanitize($_POST['descripcion'] ?? '');
        
        try {
            $idCategoria = $this->categoriaModel->create([
                'nombre' => $nombre,
                'descripcion' => $descripcion
            ]);
            
            // Registrar en mantenimiento (AUDITORÍA)
            $this->mantenimientoModel->registrarAccion(
                $_SESSION['admin_id'],
                'crear_categoria',
                'categoria',
                $idCategoria,
                "Categoría creada: {$nombre}",
                null,
                ['nombre' => $nombre, 'descripcion' => $descripcion]
            );
            
            $_SESSION['success'] = "Categoría creada exitosamente";
            $this->redirect('/admin/categorias');
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al crear categoría: " . $e->getMessage();
            $this->redirect('/admin/categorias');
        }
    }
    
    public function actualizarCategoria($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/categorias');
        }
        
        // Obtener datos anteriores para auditoría
        $categoriaAnterior = $this->categoriaModel->find($id);
        
        $nombre = $this->sanitize($_POST['nombre'] ?? '');
        $descripcion = $this->sanitize($_POST['descripcion'] ?? '');
        
        try {
            $this->categoriaModel->update($id, [
                'nombre' => $nombre,
                'descripcion' => $descripcion
            ]);
            
            // Registrar en mantenimiento
            $this->mantenimientoModel->registrarAccion(
                $_SESSION['admin_id'],
                'modificar_categoria',
                'categoria',
                $id,
                "Categoría modificada: {$nombre}",
                $categoriaAnterior,
                ['nombre' => $nombre, 'descripcion' => $descripcion]
            );
            
            $_SESSION['success'] = "Categoría actualizada exitosamente";
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al actualizar categoría: " . $e->getMessage();
        }
        
        $this->redirect('/admin/categorias');
    }
    
    public function eliminarCategoria($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/categorias');
        }
        
        try {
            // Obtener datos antes de eliminar
            $categoria = $this->categoriaModel->find($id);
            
            $this->categoriaModel->delete($id);
            
            // Registrar en mantenimiento
            $this->mantenimientoModel->registrarAccion(
                $_SESSION['admin_id'],
                'eliminar_categoria',
                'categoria',
                $id,
                "Categoría eliminada: {$categoria['nombre']}",
                $categoria,
                null
            );
            
            $_SESSION['success'] = "Categoría eliminada exitosamente";
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al eliminar categoría: " . $e->getMessage();
        }
        
        $this->redirect('/admin/categorias');
    }
    
    // ==========================================
    // GESTIÓN DE SERVICIOS (RF-26)
    // ==========================================
    
    public function servicios() {
        $servicios = $this->servicioModel->obtenerTodosConProveedor();
        $this->view('admin/servicios/index', ['servicios' => $servicios]);
    }
    
    public function eliminarServicio($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/servicios');
        }
        
        $motivo = $this->sanitize($_POST['motivo'] ?? 'No especificado');
        
        try {
            // Obtener datos del servicio antes de eliminar
            $servicio = $this->servicioModel->find($id);
            
            $this->servicioModel->delete($id);
            
            // Registrar en mantenimiento
            $this->mantenimientoModel->registrarAccion(
                $_SESSION['admin_id'],
                'eliminar_servicio',
                'servicio',
                $id,
                "Servicio eliminado: {$servicio['nombre']}. Motivo: {$motivo}",
                $servicio,
                null
            );
            
            $_SESSION['success'] = "Servicio eliminado exitosamente";
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al eliminar servicio: " . $e->getMessage();
        }
        
        $this->redirect('/admin/servicios');
    }
    
    // ==========================================
    // GESTIÓN DE USUARIOS (RF-60)
    // ==========================================
    
    public function usuarios() {
        $usuarios = $this->usuarioModel->obtenerTodosConTipo();
        $this->view('admin/usuarios/index', ['usuarios' => $usuarios]);
    }
    
    public function banearUsuario($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/usuarios');
        }
        
        $razon = $this->sanitize($_POST['razon'] ?? '');
        $duracion = $_POST['duracion'] ?? 'permanente'; // temporal o permanente
        
        try {
            $usuario = $this->usuarioModel->find($id);
            
            $this->usuarioModel->banearUsuario($id, $razon, $duracion);
            
            // Registrar en mantenimiento
            $this->mantenimientoModel->registrarAccion(
                $_SESSION['admin_id'],
                'banear_usuario',
                'usuario',
                $id,
                "Usuario baneado: {$usuario['nombre']}. Razón: {$razon}. Duración: {$duracion}",
                ['estado' => 'activo'],
                ['estado' => 'baneado', 'razon' => $razon]
            );
            
            $_SESSION['success'] = "Usuario baneado exitosamente";
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al banear usuario: " . $e->getMessage();
        }
        
        $this->redirect('/admin/usuarios');
    }
    
    // ==========================================
    // GESTIÓN DE REPORTES (RF-59)
    // ==========================================
    
    public function reportes() {
        $reportes = $this->reporteModel->obtenerTodos();
        $this->view('admin/reportes/index', ['reportes' => $reportes]);
    }
    
    public function actualizarReporte($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/reportes');
        }
        
        $estado = $_POST['estado'] ?? '';
        $notasAdmin = $this->sanitize($_POST['notas_admin'] ?? '');
        
        try {
            $reporteAnterior = $this->reporteModel->find($id);
            
            $this->reporteModel->update($id, [
                'estado' => $estado,
                'notas_admin' => $notasAdmin,
                'id_admin' => $_SESSION['admin_id'],
                'fecha_resolucion' => date('Y-m-d H:i:s')
            ]);
            
            // Registrar en mantenimiento
            $this->mantenimientoModel->registrarAccion(
                $_SESSION['admin_id'],
                'resolver_reporte',
                'reporte',
                $id,
                "Reporte actualizado. Estado: {$estado}",
                $reporteAnterior,
                ['estado' => $estado, 'notas' => $notasAdmin]
            );
            
            $_SESSION['success'] = "Reporte actualizado exitosamente";
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al actualizar reporte: " . $e->getMessage();
        }
        
        $this->redirect('/admin/reportes');
    }
    
    // ==========================================
    // AUDITORÍA Y MANTENIMIENTO
    // ==========================================
    
    public function auditoria() {
        $filtro = $_GET['tipo'] ?? null;
        $acciones = $this->mantenimientoModel->obtenerAccionesRecientes(100, $filtro);
        $estadisticas = $this->mantenimientoModel->obtenerEstadisticas();
        
        $this->view('admin/auditoria', [
            'acciones' => $acciones,
            'estadisticas' => $estadisticas
        ]);
    }
    
    public function historialEntidad($tipo, $id) {
        $historial = $this->mantenimientoModel->buscarPorEntidad($tipo, $id);
        
        $this->json([
            'success' => true,
            'historial' => $historial
        ]);
    }
}