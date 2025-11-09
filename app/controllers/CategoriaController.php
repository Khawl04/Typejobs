<?php

class CategoriaController extends Controller
{
    private $categoriaModel;

    public function __construct() {
        $this->categoriaModel = $this->model('Categoria');
    }

    // Listado de todas las categorías (para admin o select async)
    public function index() {
        $categorias = $this->categoriaModel->obtenerActivas();
        $this->view('categoria/index', ['categorias' => $categorias]);
    }

    // Crear categoría (GET muestra form, POST procesa)
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $this->sanitize($_POST['nombre_categoria']);
            $descripcion = $this->sanitize($_POST['descripcion'] ?? '');
            try {
                $this->categoriaModel->crear($nombre, $descripcion);
                $_SESSION['success'] = "Categoría creada correctamente";
                $this->redirect('/categoria');
            } catch(Exception $e) {
                $_SESSION['error'] = "Error al crear: " . $e->getMessage();
                $this->redirect('/categoria/crear');
            }
        }
        $this->view('categoria/crear');
    }

    // Editar categoría (GET y POST)
    public function editar($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $this->sanitize($_POST['nombre_categoria']);
            $descripcion = $this->sanitize($_POST['descripcion'] ?? '');
            try {
                $this->categoriaModel->editar($id, $nombre, $descripcion);
                $_SESSION['success'] = "Categoría actualizada";
                $this->redirect('/categoria');
            } catch(Exception $e) {
                $_SESSION['error'] = "Error al editar: " . $e->getMessage();
                $this->redirect("/categoria/editar/$id");
            }
        }
        $categoria = $this->categoriaModel->find($id);
        $this->view('categoria/editar', ['categoria' => $categoria]);
    }

    // Eliminar categoría
    public function eliminar($id) {
        try {
            $this->categoriaModel->eliminar($id);
            $_SESSION['success'] = "Categoría eliminada";
        } catch(Exception $e) {
            $_SESSION['error'] = "Error al eliminar: " . $e->getMessage();
        }
        $this->redirect('/categoria');
    }

    // API para traer todas las categorías en JSON (opcional, útil en selects dinámicos)
    public function listarJson() {
        $categorias = $this->categoriaModel->obtenerActivas();
        $this->json(['success' => true, 'categorias' => $categorias]);
    }
}
