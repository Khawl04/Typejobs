<?php
class ServicioController extends Controller {

    private $servicioModel;
    private $proveedorModel;

    public function __construct() {
        $this->servicioModel = $this->model('Servicio');
        $this->proveedorModel = $this->model('Proveedor');
    }

    // Ruta GET /servicio - Catálogo público de servicios (muestra todo y el form de alta)
    public function index() {
        $categoriaModel = $this->model('Categoria');
        $servicios = $this->servicioModel->obtenerTodos();
        $categorias = $categoriaModel->obtenerActivas();

        $this->view('servicio/servicio', [
            'servicios' => $servicios,
            'categorias' => $categorias
        ]);
    }

    // POST /servicio - Publicar nuevo servicio
    public function guardar() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect('/servicio');
    }

    // Instancias el modelo
    $categoriaModel = $this->model('Categoria');

    // Detectar si se selecciona 'nueva' y hay texto
    $idCategoriaForm = $_POST['id_categoria'];
    $nuevaCategoria = trim($_POST['nueva_categoria'] ?? '');

    if ($idCategoriaForm === 'nueva' && $nuevaCategoria !== '') {
        // Guardar la nueva categoría usando el modelo
        $idCategoria = $categoriaModel->crear($nuevaCategoria);
    } else {
        $idCategoria = (int)$idCategoriaForm;
    }

    // Preparar datos del servicio
    $data = [
        'titulo'            => $this->sanitize($_POST['titulo']),
        'descripcion'       => $this->sanitize($_POST['descripcion']),
        'id_categoria'      => $idCategoria,
        'precio'            => (float)$_POST['precio'],
        'duracion_estimada' => (int)$_POST['duracion_estimada'],
    ];

    try {
        // Obtener el id_proveedor a partir del usuario logueado
        $proveedorModel = $this->model('Proveedor');
        $proveedor = $proveedorModel->obtenerPorUsuario($_SESSION['id_usuario']);
        $idUsuario = $proveedor['id_usuario'];

        // Crear el servicio
        $idServicio = $this->servicioModel->crearServicio($idUsuario, $data);

        // Guardar hasta 3 imágenes si hay
        if (isset($_FILES['imagen_servicio']) && $_FILES['imagen_servicio']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['imagen_servicio']['tmp_name'];
            $extension = strtolower(pathinfo($_FILES['imagen_servicio']['name'], PATHINFO_EXTENSION));
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                $nombreArchivo = 'servicio_' . $idServicio . '_' . time() . '.' . $extension;
                $rutaDestino = UPLOAD_PATH . 'servicios/' . $nombreArchivo;
                if (!is_dir(UPLOAD_PATH . 'servicios/')) {
                    mkdir(UPLOAD_PATH . 'servicios/', 0755, true);
                }
                if (move_uploaded_file($tmpName, $rutaDestino)) {
                    $this->servicioModel->actualizarImagen($idServicio, 'uploads/servicios/' . $nombreArchivo);
                }
            }
        }

        $_SESSION['success'] = "Servicio creado exitosamente";
        $this->redirect('/servicio'); // Recarga catálogo
    } catch (Exception $e) {
        $_SESSION['error'] = "Error al crear servicio: " . $e->getMessage();
        $this->redirect('/servicio');
    }
}


    public function buscar() {
        $filtros = [
            'query'      => $_GET['q'] ?? '',
            'categoria'  => $_GET['categoria'] ?? null,
            'precio_min' => $_GET['precio_min'] ?? null,
            'precio_max' => $_GET['precio_max'] ?? null
        ];
        $servicios = $this->servicioModel->buscarServicios($filtros);
        $this->json([
            'success'   => true,
            'servicios' => $servicios
        ]);
    }

    // Ver detalle
    public function show($id) {
        $servicio = $this->servicioModel->obtenerConProveedor($id);
        $imagenModel = $this->model('ImagenServicio');
        $calificacionModel = $this->model('Calificacion');

        if (!$servicio) {
            $_SESSION['error'] = "Servicio no encontrado";
            $this->redirect('/servicio');
        }

        $data = [
            'servicio' => $servicio,
            'imagenes' => $imagenModel->obtenerPorServicio($id),
            'calificaciones' => $calificacionModel->obtenerPorProveedor($servicio['id_usuario'])
        ];
        $this->view('servicio/detalle', $data);
    }
 public function detalle() {
    $idServicio = $_GET['id'] ?? null;
    if (!$idServicio) {
        die('ID de servicio no especificado');
    }
    $servicio = $this->servicioModel->obtenerPorId($idServicio);
    $imagenes = $this->servicioModel->obtenerImagenes($idServicio) ?? [];
    $proveedor = $this->proveedorModel->obtenerConUsuario($servicio['id_proveedor']);
    $resenas = $this->servicioModel->obtenerResenas($idServicio) ?? [];

    // Aquí agregas la lógica para el permiso de reseñar:
    $puedeReseniar = false;
    if (isset($_SESSION['id_usuario'])) {
        $idUsuario = $_SESSION['id_usuario'];
        // Lógica: chequear si compró el servicio
        $puedeReseniar = $this->servicioModel->usuarioCompro($idUsuario, $idServicio);
    }

    $this->view('servicio/detalle', [
        'servicio' => $servicio,
        'imagenes' => $imagenes,
        'proveedor' => $proveedor,
        'resenas' => $resenas,
        'puedeReseniar' => $puedeReseniar
    ]);
}



}
?>