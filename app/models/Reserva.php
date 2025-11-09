<?php
class Reserva extends Model {
    protected $table = 'reserva';
    protected $primaryKey = 'id_reserva';

    // Crear nueva reserva
    public function crearReserva($data) {
        return $this->create([
            'id_cliente'    => $data['id_cliente'],
            'id_servicio'   => $data['id_servicio'],
            'fecha_reserva' => $data['fecha_reserva'],
            'hora_inicio'   => $data['hora_inicio'],
            'hora_fin'      => $data['hora_fin'],
            'notas'         => $data['notas'],
            'estado'        => 'pendiente'
        ]);
    }

    // Verificar disponibilidad
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

    // Obtener reservas de un cliente
    public function obtenerPorCliente($idCliente) {
        $sql = "SELECT r.*,
                s.titulo         as servicio_titulo,
                s.precio         as servicio_precio,
                uc.nombre        as proveedor_nombre
            FROM {$this->table} r
            INNER JOIN servicio s ON r.id_servicio = s.id_servicio
            INNER JOIN proveedor p ON s.id_proveedor = p.id_usuario
            INNER JOIN usuario uc ON p.id_usuario = uc.id_usuario
            WHERE r.id_cliente = ?
            ORDER BY r.fecha_reserva DESC, r.hora_inicio DESC";
        return $this->query($sql, [$idCliente]);
    }

    // Obtener reservas de un proveedor
    public function obtenerPorProveedor($idProveedor) {
        $sql = "SELECT r.*,
                s.titulo     as servicio_titulo,
                uc.nombre    as cliente_nombre,
                uc.telefono  as cliente_telefono,
                uc.email     as cliente_email
            FROM {$this->table} r
            INNER JOIN servicio s ON r.id_servicio = s.id_servicio
            INNER JOIN cliente c ON r.id_cliente = c.id_usuario
            INNER JOIN usuario uc ON c.id_usuario = uc.id_usuario
            WHERE s.id_proveedor = ?
            ORDER BY r.fecha_reserva DESC, r.hora_inicio DESC";
        return $this->query($sql, [$idProveedor]);
    }

    // Cancelar reserva
    public function cancelar($id, $canceladoPor, $razon) {
        return $this->update($id, [
            'estado'            => 'cancelada',
            'cancelada_por'     => $canceladoPor,
            'razon_cancelacion' => $razon
        ]);
    }

    // Finalizar reserva
    public function finalizar($id) {
        return $this->update($id, [
            'estado'               => 'finalizada',
            'fecha_finalizacion'   => date('Y-m-d H:i:s')
        ]);
    }

    // Obtener reserva completa (para pago)
    public function obtenerCompleta($id) {
        $sql = "SELECT r.*,
                s.titulo         as servicio_titulo,
                s.descripcion    as servicio_descripcion,
                s.precio         as servicio_precio,
                c.id_usuario     as cliente_id_usuario,
                uc.nombre        as cliente_nombre,
                uc.email         as cliente_email,
                uc.telefono      as cliente_telefono,
                p.id_usuario     as id_proveedor,
                up.nombre        as proveedor_nombre,
                up.apellido      as proveedor_apellido,
                up.email         as proveedor_email,
                up.telefono      as proveedor_telefono,
                p.direccion      as proveedor_direccion
            FROM reserva r
            INNER JOIN servicio s ON r.id_servicio = s.id_servicio
            INNER JOIN cliente c ON r.id_cliente = c.id_usuario
            INNER JOIN usuario uc ON c.id_usuario = uc.id_usuario
            INNER JOIN proveedor p ON s.id_proveedor = p.id_usuario
            INNER JOIN usuario up ON p.id_usuario = up.id_usuario
            WHERE r.id_reserva = ?";
        $result = $this->query($sql, [$id]);
        return $result[0] ?? null;
    }

    // Contar por estado
    public function contarPorEstado($idCliente, $estado) {
        $sql = "SELECT COUNT(*) as total
            FROM {$this->table}
            WHERE id_cliente = ? AND estado = ?";
        $result = $this->query($sql, [$idCliente, $estado]);
        return $result[0]['total'] ?? 0;
    }

    // Reservas pendientes por proveedor
    public function pendientesPorProveedor($idProveedor) {
        $sql = "SELECT COUNT(*) as total FROM reserva
            WHERE id_servicio IN (SELECT id_servicio FROM servicio WHERE id_proveedor = ?)
              AND estado = 'pendiente'";
        $res = $this->query($sql, [$idProveedor]);
        return $res[0]['total'] ?? 0;
    }
    public function delete($idReserva) {
    // Elimina todos los pagos de la reserva
    $pagoModel = new Pago($this->db); // pasa la conexión si lo requiere tu modelo
    $pagoModel->eliminarPorReserva($idReserva);

    // Ahora elimina la reserva
    $query = "DELETE FROM reserva WHERE id_reserva = ?";
    $stmt = $this->db->prepare($query);
    $stmt->bind_param('i', $idReserva);
    $stmt->execute();
    $stmt->close();
}



    
}
?>
