<?php
class ReservaController extends Controller {
    private $reservaModel;
    private $clienteModel;

    public function __construct() {
        $this->requireAuth();
        $this->reservaModel = $this->model('Reserva');
        $this->clienteModel = $this->model('Cliente');
    }

    // Mostrar formulario reserva antes del pago (flujo comprar ahora)
    public function nuevaReserva() {
        $this->requireRole('cliente');
        $id_servicio = $_GET['id_servicio'] ?? null;
        if (!$id_servicio) {
            $_SESSION['error'] = "No se ha seleccionado el servicio.";
            $this->redirect('/');
        }
        $servicioModel = $this->model('Servicio');
        $servicio = $servicioModel->obtenerPorId($id_servicio);
        $this->view('reserva/reserva', ['servicio' => $servicio]);
    }

    // Crear reserva después de seleccionar fecha/hora y redirigir a pago
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_servicio   = $_POST['id_servicio'];
            $fecha_reserva = $_POST['fecha_reserva'];
            $hora_inicio   = $_POST['hora_inicio'];
            $hora_fin      = $_POST['hora_fin'];
            $notas         = $_POST['notas'] ?? null;
            $id_cliente    = $_SESSION['id_usuario'];

            // Verificar disponibilidad (opcional)
            $servicioModel = $this->model('Servicio');
            $servicio = $servicioModel->obtenerPorId($id_servicio);
            $id_proveedor = $servicio['id_proveedor'];
            $disponible = $this->reservaModel->verificarDisponibilidad($id_proveedor, $fecha_reserva, $hora_inicio);
            if (!$disponible) {
                $_SESSION['error'] = "El horario seleccionado no está disponible";
                $this->redirect('/reserva?id_servicio=' . $id_servicio);
                return;
            }

            // Crear reserva
            $data = [
                'id_cliente'    => $id_cliente,
                'id_servicio'   => $id_servicio,
                'fecha_reserva' => $fecha_reserva,
                'hora_inicio'   => $hora_inicio,
                'hora_fin'      => $hora_fin,
                'notas'         => $notas
            ];
            $nuevaReservaId = $this->reservaModel->crearReserva($data);

            // Redirigir al pago
            $this->redirect('/pago?id_reserva=' . $nuevaReservaId);
        }
    }

    // Listar reservas del cliente logueado
    public function misReservas() {
        $this->requireRole('cliente');
        $cliente = $this->clienteModel->obtenerPorUsuario($_SESSION['id_usuario']);
        $reservas = $this->reservaModel->obtenerPorCliente($cliente['id_usuario']);
        $this->view('cliente/reservas', ['reservas' => $reservas]);
    }

    // Listar reservas del proveedor logueado
    public function reservasProveedor() {
        $this->requireRole('proveedor');
        $proveedorModel = $this->model('Proveedor');
        $proveedor = $proveedorModel->obtenerPorUsuario($_SESSION['id_usuario']);
        $reservas = $this->reservaModel->obtenerPorProveedor($proveedor['id_proveedor']);
        $this->view('proveedor/reservas', ['reservas' => $reservas]);
    }

    // Cancelación por cliente
    public function cancelarCliente($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/cliente/reservas');
        }
        $razon = $this->sanitize($_POST['razon']);
        try {
            $this->reservaModel->cancelar($id, 'cliente', $razon);
            $_SESSION['success'] = "Reserva cancelada";
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
        $this->redirect('/cliente/reservas');
    }

    // Cancelación por proveedor
    public function cancelarProveedor($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/proveedor/reservas');
        }
        $razon = $this->sanitize($_POST['razon']);
        try {
            $this->reservaModel->cancelar($id, 'proveedor', $razon);
            $_SESSION['success'] = "Reserva cancelada";
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
        $this->redirect('/proveedor/reservas');
    }

    // Finalizar servicio por proveedor
    public function finalizarProveedor($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/proveedor/reservas');
        }
        try {
            $this->reservaModel->finalizar($id);
            $_SESSION['success'] = "Servicio finalizado";
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
        $this->redirect('/proveedor/reservas');
    }
    public function eliminarReserva() {
    if (isset($_POST['eliminar_reserva'])) {
        $reservaModel = new Reserva($this->db); // pasa la conexión si tu sistema lo requiere
        $reservaModel->delete($_POST['eliminar_reserva']);
        $_SESSION['success'] = "Reserva eliminada.";
        header("Location: " . BASE_URL . "/cliente/reservas");
        exit;
    }
}


}
?>
