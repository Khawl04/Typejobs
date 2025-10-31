<?php
// ==========================================
// app/controllers/CalificacionController.php
// ==========================================

class CalificacionController extends Controller {
    
    private $calificacionModel;
    
    public function __construct() {
        $this->requireAuth();
        $this->requireRole('cliente');
        $this->calificacionModel = $this->model('Calificacion');
    }
    
    // RF-50, RF-51: Crear calificación y reseña
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/cliente/reservas');
        }
        
        $idReserva = (int)$_POST['id_reserva'];
        $puntuacion = (int)$_POST['puntuacion'];
        $comentario = $this->sanitize($_POST['comentario'] ?? '');
        
        // Verificar que no exista calificación
        if ($this->calificacionModel->existeParaReserva($idReserva)) {
            $_SESSION['error'] = "Ya has calificado este servicio";
            $this->redirect('/cliente/reservas');
        }
        
        try {
            $reservaModel = $this->model('Reserva');
            $reserva = $reservaModel->find($idReserva);
            
            $clienteModel = $this->model('Cliente');
            $cliente = $clienteModel->obtenerPorUsuario($_SESSION['user_id']);
            
            $servicioModel = $this->model('Servicio');
            $servicio = $servicioModel->find($reserva['id_servicio']);
            
            // Crear calificación
            $idCalificacion = $this->calificacionModel->crearCalificacion([
                'id_reserva' => $idReserva,
                'id_cliente' => $cliente['id_cliente'],
                'id_proveedor' => $servicio['id_proveedor'],
                'puntuacion' => $puntuacion
            ]);
            
            // Crear reseña si hay comentario
            if (!empty($comentario)) {
                $resenaModel = $this->model('Resena');
                $resenaModel->crearResena($idCalificacion, $comentario);
            }
            
            $_SESSION['success'] = "Calificación enviada exitosamente";
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
        
        $this->redirect('/cliente/reservas');
    }
}

?>