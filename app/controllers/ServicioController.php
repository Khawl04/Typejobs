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
    // Nuevo: recoger filtros desde GET
    $busqueda = $_GET['busqueda'] ?? '';
    $orden = $_GET['orden'] ?? 'relevancia';

    // Usa el modelo para filtrar/ordenar según lo recibido
    $servicios = $this->servicioModel->obtenerConFiltros($busqueda, $orden);

    $categorias = $categoriaModel->obtenerActivas();

    $this->view('servicio/servicio', [
        'servicios' => $servicios,
        'categorias' => $categorias,
        'orden' => $orden // para el select
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
    // Tomar el id por GET o por POST (para reseñas nuevas)
    $idServicio = $_GET['id'] ?? $_POST['id_servicio'] ?? null;
    if (!$idServicio) die('ID de servicio no especificado');

    // GUARDAR RESEÑA al recibir POST con calificacion y texto
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calificacion'], $_POST['texto'])) {
        $idUsuario = $_SESSION['id_usuario'] ?? null;
        $calificacion = (int)$_POST['calificacion'];
        $texto = trim($_POST['texto']);
        if ($idUsuario && $calificacion >= 1 && $calificacion <= 5 && $texto !== '') {
            $this->servicioModel->guardarResena($idServicio, $idUsuario, $calificacion, $texto);
        }
        // Redirige tipo PRG para evitar doble submit
        header("Location: ".BASE_URL."/servicio/detalle?id=".$idServicio);
        exit;
    }

    $servicio = $this->servicioModel->obtenerPorId($idServicio);
    $imagenes = $this->servicioModel->obtenerImagenes($idServicio) ?? [];
    $proveedor = $this->proveedorModel->obtenerConUsuario($servicio['id_proveedor']);
    $resenas = $this->servicioModel->obtenerResenas($idServicio) ?? [];

    $puedeReseniar = false;
    if (isset($_SESSION['id_usuario'])) {
        $idUsuario = $_SESSION['id_usuario'];
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
public function likeResena() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_resena'])) {
        $idResena = (int)$_POST['id_resena'];

        // Leer cookie actual
        $cookieName = "likes_resena";
        $userLikes = isset($_COOKIE[$cookieName]) ? json_decode($_COOKIE[$cookieName], true) : [];

        // Toggle: si ya está, saca el like, si no, suma el like
        if (isset($userLikes[$idResena])) {
            // QUITAR like (restar contador)
            unset($userLikes[$idResena]);
            $this->servicioModel->updateLikeCount($idResena, -1);
        } else {
            // DAR like (sumar contador)
            $userLikes[$idResena] = 1;
            $this->servicioModel->updateLikeCount($idResena, 1);
        }
        // Actualizar la cookie, 30 días
        setcookie($cookieName, json_encode($userLikes), time() + (60 * 60 * 24 * 30), "/");

        // Redirige de vuelta al detalle:
        $idServicio = $this->servicioModel->getServicioIdByResena($idResena);
        header("Location: ".BASE_URL."/servicio/detalle?id=".$idServicio);
        exit;
    }
}



}
?>