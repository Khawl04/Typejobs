   <?php
class Orden extends Model {
    protected $table = 'reserva';
    protected $primaryKey = 'id_reserva';

    // Crear orden solo crea la reserva (el Pago lo maneja el flujo de PagoController)
    public function crearOrden($data) {
        return $this->create([
            'id_cliente'      => $data['id_cliente'],
            'id_servicio'     => $data['id_servicio'],
            'fecha_reserva'   => $data['fecha_reserva'],
            'hora_inicio'     => $data['hora_inicio'],
            'hora_fin'        => $data['hora_fin'],
            'notas'           => $data['notas'] ?? null,
            'estado'          => 'pendiente'
        ]);
    }

    // Verificar disponibilidad horario/proveedor
    public function verificarDisponibilidad($idProveedor, $fecha, $horaInicio) {
        $sql = "SELECT COUNT(*) as count
                FROM {$this->table} r
                INNER JOIN servicio s ON r.id_servicio = s.id_servicio
                WHERE s.id_proveedor = ?
                  AND r.fecha_reserva = ?
                  AND r.hora_inicio = ?
                  AND r.estado NOT IN ('cancelada', 'finalizada')";
        $result = $this->query($sql, [$idProveedor, $fecha, $horaInicio]);
        return $result[0]['count'] == 0;
    }

    // Obtener todos los datos completos de la orden/reserva
    public function obtenerOrdenCompleta($idOrden) {
        $sql = "SELECT 
                    r.*,
                    s.titulo as servicio_titulo,
                    s.descripcion as servicio_descripcion,
                    s.precio as servicio_precio,
                    uc.nombre as cliente_nombre,
                    uc.apellido as cliente_apellido,
                    uc.email as cliente_email,
                    uc.telefono as cliente_telefono,
                    up.nombre as proveedor_nombre,
                    up.apellido as proveedor_apellido,
                    up.email as proveedor_email,
                    up.telefono as proveedor_telefono,
                    up.direccion as proveedor_direccion,
                    pago.id_pago,
                    pago.monto as pago_monto,
                    pago.metodo_pago as pago_metodo,
                    pago.estado as pago_estado,
                    pago.transaccion_id,
                    pago.fecha_pago
                FROM {$this->table} r
                INNER JOIN servicio s ON r.id_servicio = s.id_servicio
                INNER JOIN cliente c ON r.id_cliente = c.id_usuario
                INNER JOIN usuario uc ON c.id_usuario = uc.id_usuario
                INNER JOIN proveedor p ON s.id_proveedor = p.id_usuario
                INNER JOIN usuario up ON p.id_usuario = up.id_usuario
                LEFT JOIN pago ON r.id_reserva = pago.id_reserva
                WHERE r.id_reserva = ?";
        $result = $this->query($sql, [$idOrden]);
        return $result[0] ?? null;
    }

    // Cambia estado de la orden/reserva
    public function actualizarEstado($idOrden, $estado) {
        return $this->update($idOrden, [
            'estado' => $estado,
            'fecha_finalizacion' => ($estado == 'finalizada') ? date('Y-m-d H:i:s') : null
        ]);
    }

    // (Otras funciones: cancelar, historial ordenes, etc. puedes agregarlas según necesidades puntuales)
}
