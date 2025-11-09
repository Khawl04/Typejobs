<?php

require_once __DIR__ . '/../../vendor/autoload.php';
use MercadoPago\SDK;
use MercadoPago\Preference;

class PagoController extends Controller {
    private $pagoModel;
    private $reservaModel;
    private $notificacionModel;

    public function __construct() {
        $this->requireAuth();
        $this->requireRole('cliente');
        $this->pagoModel = $this->model('Pago');
        $this->reservaModel = $this->model('Reserva');
        $this->notificacionModel = $this->model('Notificacion');
    }

    // Muestra el formulario de pago
    public function pago() {
    $idReserva = $_GET['id_reserva'] ?? $_POST['id_reserva'] ?? null;
    if (!$idReserva) {
        $_SESSION['error'] = "No se seleccionó reserva";
        $this->redirect('/cliente/reservas');
    }
    $reserva = $this->reservaModel->obtenerCompleta($idReserva);
    if (!$reserva) {
        $_SESSION['error'] = "Reserva no encontrada";
        $this->redirect('/cliente/reservas');
    }
    if ($reserva['id_cliente'] != $_SESSION['id_usuario']) {
        $_SESSION['error'] = "No tienes permiso";
        $this->redirect('/cliente/reservas');
    }
    $pago = $this->pagoModel->obtenerPorReserva($idReserva);
    if ($pago && $pago['estado'] === 'completado') {
        $_SESSION['info'] = "Esta reserva ya se encuentra paga";
        $this->redirect('/cliente/reservas');
    }
    if (!$pago) {
        $this->pagoModel->crearPago($idReserva, $_SESSION['id_usuario'], $reserva['servicio_precio']);
        $pago = $this->pagoModel->obtenerPorReserva($idReserva);
    }
    $reserva['pago'] = $pago;
    $this->view('pago/pago', ['reserva' => $reserva]);
}

public function orden() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $idReserva = $_GET['id_reserva'] ?? null;
        $reserva = null;
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        if ($idReserva) {
            $reserva = $this->reservaModel->obtenerCompleta($idReserva);
        }
        $mp_init_point = $_SESSION['mp_init_point'] ?? null;
        unset($_SESSION['mp_init_point']);
        $this->view('pago/orden', [
            'reserva' => $reserva,
            'error' => $error,
            'mp_init_point' => $mp_init_point,
        ]);
        return;
    }

    // POST: procesar pago
    $idReserva = $_POST['id_reserva'] ?? null;
    $metodoPago = $_POST['metodo_pago'] ?? '';
    $submetodo = $_POST['submetodo_transferencia'] ?? '';

    $reserva = $this->reservaModel->obtenerCompleta($idReserva);

    // Validación
    if (!$idReserva || !$metodoPago || !$reserva) {
        $_SESSION['error'] = !$reserva ? "Reserva no encontrada" : "Campos requeridos faltantes";
        $this->view('pago/pago', [ 'reserva' => $reserva, 'error' => $_SESSION['error'] ]);
        return;
    }
    if (!isset($reserva['id_proveedor'])) {
        $_SESSION['error'] = "La reserva no tiene proveedor asignado.";
        $this->view('pago/orden', [ 'reserva' => $reserva, 'error' => $_SESSION['error'], 'mp_init_point' => null ]);
        return;
    }

    // ---- MERCADO PAGO ----
    if ($metodoPago === 'transferencia' && strtolower($submetodo) === 'mercadopago') {
    require_once __DIR__ . '/../../vendor/autoload.php';

        \MercadoPago\SDK::setAccessToken('TEST-2710623870693388-102923-7cec28c87a9d11381c99f9c60789bdc7-1328163732');
        $preference = new \MercadoPago\Preference();

        $item = new \stdClass();
        $item->title = "Reserva Servicio";
        $item->quantity = 1;
        $item->currency_id = "UYU";
        $item->unit_price = floatval($reserva['servicio_precio']);

        $payer = new \stdClass();
        $payer->email = $_SESSION['email']; // Usar email de sesión, robusto

        $preference->items = [$item];
        $preference->payer = $payer;
        $preference->external_reference = $idReserva;

        $preference->save();

        header('Location: ' . $preference->init_point);
exit;

    }

    try {
        $pago = $this->pagoModel->obtenerPorReserva($idReserva);
        $transaccionId = 'TXN-' . time() . '-' . rand(1000, 9999);
        $this->pagoModel->procesarPago($pago['id_pago'], $metodoPago, $transaccionId);

        if ($metodoPago === 'tarjeta' || ($metodoPago === 'transferencia' && strtolower($submetodo) !== 'mercadopago')) {
            $resultado = $this->reservaModel->update($idReserva, ['estado' => 'confirmada']);
            $reserva_actual = $this->reservaModel->obtenerCompleta($idReserva);
            $_SESSION['success'] = "Pago procesado y reserva confirmada.";
        } else if ($metodoPago === 'efectivo') {
            $_SESSION['success'] = "Pago por efectivo registrado. Presenta el comprobante al proveedor para completar el pago.";
        }

        $this->redirect('/pago/orden?id_reserva=' . $idReserva);
        return;
    } catch (Exception $e) {
        $_SESSION['error'] = "Error al procesar el pago: " . $e->getMessage();
        $reserva = $this->reservaModel->obtenerCompleta($idReserva);
        $this->view('pago/pago', [ 'reserva' => $reserva, 'error' => $_SESSION['error'] ]);
        return;
    }
}


    // Ver historial de pagos
  public function obtenerHistorialCliente($id_usuario) {
    $sql = "SELECT * FROM pago WHERE id_usuario = ?";
    return $this->query($sql, [$id_usuario]);
}
}
?>