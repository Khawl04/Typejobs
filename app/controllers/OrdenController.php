<?php
class OrdenController extends Controller {
    private $reservaModel;
    private $servicioModel;
    private $pagoModel;
    private $notificacionModel;

    public function __construct() {
        $this->requireAuth();
        $this->reservaModel = $this->model('Reserva');
        $this->servicioModel = $this->model('Servicio');
        $this->pagoModel = $this->model('Pago');
        $this->notificacionModel = $this->model('Notificacion');
    }

    // Crear nueva reserva (POST)
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $_SESSION['tipo_usuario'] !== 'cliente') {
            $this->redirect('/');
        }

        $idServicio = (int)$_POST['id_servicio'];
        $fechaReserva = $_POST['fecha_reserva'];
        $horaInicio = $_POST['hora_inicio'];
        $notas = $this->sanitize($_POST['notas'] ?? '');

        try {
            $servicio = $this->servicioModel->obtenerConProveedor($idServicio);
            if (!$servicio || $servicio['estado'] !== 'disponible') {
                throw new Exception("Servicio no disponible");
            }
            if (!$this->reservaModel->verificarDisponibilidad($servicio['id_proveedor'], $fechaReserva, $horaInicio)) {
                throw new Exception("El horario seleccionado no está disponible");
            }

            $clienteModel = $this->model('Cliente');
            $cliente = $clienteModel->obtenerPorUsuario($_SESSION['user_id']);

            $duracion = $servicio['duracion_estimada'] ?? 60;
            $horaFin = date('H:i:s', strtotime($horaInicio) + ($duracion * 60));

            // Crear reserva
            $idReserva = $this->reservaModel->crearReserva([
                'id_cliente'      => $cliente['id_cliente'],
                'id_servicio'     => $idServicio,
                'fecha_reserva'   => $fechaReserva,
                'hora_inicio'     => $horaInicio,
                'hora_fin'        => $horaFin,
                'notas'           => $notas
            ]);

            // Crear pago pendiente (estado inicial)
            $this->pagoModel->crearPago($idReserva, $servicio['precio']);

            // Notificar al proveedor
            $this->notificacionModel->crearNotificacion([
                'id_usuario' => $servicio['id_usuario'],
                'tipo'       => 'reserva',
                'titulo'     => 'Nueva reserva recibida',
                'contenido'  => "Tienes una nueva reserva para {$servicio['nombre']} el {$fechaReserva}",
                'url_accion' => '/proveedor/reservas'
            ]);

            // Redirige AL FLUJO DE PAGO (siempre, todo va ahí)
            $_SESSION['success'] = "Reserva creada exitosamente";
            $this->redirect('/pago?id_reserva=' . $idReserva);

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('/servicio?id=' . $idServicio);
        }
    }

    // Ver detalle de orden/reserva
    public function ver($id) {
        $reserva = $this->reservaModel->obtenerCompleta($id);

        if (!$reserva) {
            $_SESSION['error'] = "Reserva no encontrada";
            $this->redirect('/' . $_SESSION['tipo_usuario'] . '/reservas');
        }

        // Verificar permisos
        $puedeVer = (
            ($_SESSION['tipo_usuario'] === 'cliente'   && $reserva['cliente_email'] == $_SESSION['email'])
            || ($_SESSION['tipo_usuario'] === 'proveedor' && $reserva['proveedor_email'] == $_SESSION['email'])
            || $_SESSION['tipo_usuario'] === 'administrador'
        );
        if (!$puedeVer) {
            $_SESSION['error'] = "No tienes permiso para ver esta reserva";
            $this->redirect('/');
        }

        // Adjuntar pago (si existe)
        $pago = $this->pagoModel->obtenerPorReserva($id);
        $reserva['pago'] = $pago;

        $this->view('orden/detalle', ['reserva' => $reserva]);
    }

}
?>
