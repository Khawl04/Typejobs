<?php
class CalificacionController extends Controller {

    public function __construct() {
        $this->requireAuth();
        $this->requireRole('cliente');
        $this->calificacionModel = $this->model('Calificacion');
        $this->servicioModel = $this->model('Servicio');
        $this->proveedorModel = $this->model('Proveedor');
    }

    // Crear o actualizar calificación y reseña de un servicio
    public function crear() {
        // Asume método POST y que tienes los siguientes datos
        $idServicio = (int)$_POST['id_servicio'];
        $idCliente = $_SESSION['user_id'];
        $calificacion = (int)$_POST['calificacion'];
        $comentario = trim($_POST['comentario'] ?? '');

        // 1. Guardar la calificación y reseña
        $this->calificacionModel->crearConResena($idServicio, $idCliente, $calificacion, $comentario);

        // 2. Actualizar el promedio de estrellas en el servicio
        $this->servicioModel->actualizarPromedio($idServicio);

        // 3. Actualizar el promedio en proveedor
        $servicio = $this->servicioModel->obtenerPorId($idServicio);
        $idProveedor = $servicio['id_proveedor'] ?? null;
        if ($idProveedor) {
            $this->proveedorModel->actualizarPromedio($idProveedor);
        }

        // Redirigir a la vista detalle
        $this->redirect('/servicio/detalle?id=' . $idServicio);
    }
}

