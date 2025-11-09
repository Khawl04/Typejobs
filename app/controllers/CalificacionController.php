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
    $idServicio = (int)$_POST['id_servicio'];
    $idCliente = $_SESSION['user_id'];
    $calificacion = (int)$_POST['calificacion'];
    $comentario = trim($_POST['comentario'] ?? '');

    // Guarda la reseña/calificación (en tu tabla de calificaciones/resenas)
    $this->calificacionModel->crearConResena($idServicio, $idCliente, $calificacion, $comentario);

    // ¡AQUÍ VA LA LÍNEA CLAVE QUE ACTUALIZA EL SERVICIO!
    $this->servicioModel->update($idServicio, ['calificacion' => $calificacion]);

    // Recalcula promedio del proveedor si quieres mantenerlo actualizado
    $servicio = $this->servicioModel->obtenerPorId($idServicio);
    $idProveedor = $servicio['id_proveedor'] ?? null;
    if ($idProveedor) {
        $this->proveedorModel->actualizarPromedio($idProveedor);
    }

    $this->redirect('/servicio/detalle?id=' . $idServicio);
}


}

