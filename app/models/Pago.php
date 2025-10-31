<?php
// app/models/Pago.php

class Pago extends Model {
    protected $table = 'pago';
    protected $primaryKey = 'id_pago';
    
    // Crear un pago pendiente vinculado a una reserva
    public function crearPago($idReserva,$idCliente, $monto, $metodo = 'pendiente') {
        return $this->create([
            'id_reserva'   => $idReserva,
            'monto'        => $monto,
            'metodo_pago'  => $metodo,
            'id_cliente'   => $idCliente,
            'estado'       => 'pendiente'
        ]);
    }
    
    // Procesar el pago (marcar como completado)
    public function procesarPago($idPago, $metodoPago, $transaccionId = null) {
        return $this->update($idPago, [
            'estado'        => 'completado',
            'metodo_pago'   => $metodoPago,
            'transaccion_id'=> $transaccionId,
            'fecha_pago'    => date('Y-m-d H:i:s')
        ]);
    }

    // Obtener pagos de un cliente por reservas propias
    public function obtenerHistorialCliente($idCliente) {
        $sql = "SELECT p.*, r.fecha_reserva, s.nombre as servicio_nombre, u.nombre as proveedor_nombre
                FROM pago p
                INNER JOIN reserva r ON p.id_reserva = r.id_reserva
                INNER JOIN servicio s ON r.id_servicio = s.id_servicio
                INNER JOIN proveedor pr ON s.id_proveedor = pr.id_proveedor
                INNER JOIN usuario u ON pr.id_usuario = u.id_usuario
                WHERE r.id_cliente = ?
                ORDER BY p.fecha_pago DESC";
        return $this->query($sql, [$idCliente]);
    }

    // Obtener pago de una reserva puntual
    public function obtenerPorReserva($idReserva) {
        return $this->findBy('id_reserva', $idReserva);
    }

    // RF extra: Validar si reserva está pagada
    public function reservaPagada($idReserva) {
        $sql = "SELECT 1 FROM pago WHERE id_reserva = ? AND estado = 'completado'";
        $result = $this->query($sql, [$idReserva]);
        return !empty($result);
    }
    public function eliminarPorReserva($id_reserva) {
    $sql = "DELETE FROM pago WHERE id_reserva = ?";
    $this->query($sql, [$id_reserva]);
}

}
?>
