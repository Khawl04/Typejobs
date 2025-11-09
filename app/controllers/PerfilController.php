    <?php

    class PerfilController extends Controller {

    private $proveedorModel;
    private $servicioModel; // AGREGA ESTA LINEA

    public function __construct() {
        $this->proveedorModel = $this->model('Proveedor');
        $this->servicioModel = $this->model('Servicio'); // Y ESTA LINEA
    }

    public function index() {
        $idProveedor = $_GET['id'] ?? null;
        if (!$idProveedor) { die('ID de proveedor no especificado'); }
        $proveedor = $this->proveedorModel->obtenerPorId($idProveedor);
        if (!$proveedor) { die('Proveedor no encontrado'); }
        $servicios = $this->servicioModel->obtenerPorProveedor($idProveedor);
        $this->view('perfil/perfil', [
            'proveedor' => $proveedor,
            'servicios' => $servicios
        ]);
    }
}
