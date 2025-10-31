<?php
class Asistencia extends Model {
    protected $table = 'asistencia';
    protected $primaryKey = 'id_ticket';

    // Crear ticket de asistencia
    public function crearTicket($data) {
        return $this->create([
            'id_usuario'    => $data['id_usuario'],
            'asunto'        => $data['asunto'],
            'descripcion'   => $data['descripcion'],
            'prioridad'     => $data['prioridad'] ?? 'media',
            'estado'        => 'abierto'
        ]);
    }

    // Obtener información completa de un ticket, usuario y soporte involucrado
    public function obtenerConUsuario($id) {
        $sql = "SELECT t.*, 
                       u.nombre AS usuario_nombre, u.email AS usuario_email, u.telefono AS usuario_telefono,
                       s.nombre AS soporte_nombre, s.email AS soporte_email
                  FROM {$this->table} t
                  INNER JOIN usuario u ON t.id_usuario = u.id_usuario
                  LEFT JOIN soporte sop ON t.id_soporte = sop.id_soporte
                  LEFT JOIN usuario s ON sop.id_usuario = s.id_usuario
                  WHERE t.id_ticket = ?";
        $result = $this->query($sql, [$id]);
        return $result[0] ?? null;
    }

    // Obtener todos los tickets con datos de usuarios y soporte
    public function obtenerTodos() {
        $sql = "SELECT t.*, 
                       u.nombre AS usuario_nombre, u.email AS usuario_email,
                       s.nombre AS soporte_nombre, s.email AS soporte_email
                  FROM {$this->table} t
                  INNER JOIN usuario u ON t.id_usuario = u.id_usuario
                  LEFT JOIN soporte sop ON t.id_soporte = sop.id_soporte
                  LEFT JOIN usuario s ON sop.id_usuario = s.id_usuario
                  ORDER BY FIELD(t.prioridad, 'urgente', 'alta', 'media', 'baja'), t.fecha_creacion DESC";
        return $this->query($sql);
    }

    // Obtener tickets por estado
    public function obtenerPorEstado($estado) {
        $sql = "SELECT t.*, 
                       u.nombre AS usuario_nombre, u.email AS usuario_email
                  FROM {$this->table} t
                  INNER JOIN usuario u ON t.id_usuario = u.id_usuario
                  WHERE t.estado = ?
                  ORDER BY t.fecha_creacion DESC";
        return $this->query($sql, [$estado]);
    }

    // Obtener tickets asignados a un soporte específico
    public function obtenerTicketsAsignados($idSoporte) {
        $sql = "SELECT t.*, 
                       u.nombre AS usuario_nombre, u.email AS usuario_email
                  FROM {$this->table} t
                  INNER JOIN usuario u ON t.id_usuario = u.id_usuario
                  WHERE t.id_soporte = ?
                    AND t.estado NOT IN ('cerrado', 'resuelto')
                  ORDER BY t.fecha_actualizacion DESC";
        return $this->query($sql, [$idSoporte]);
    }

    // Contar tickets por estado
    public function contarPorEstado($estado) {
        $sql = "SELECT COUNT(*) AS total FROM {$this->table} WHERE estado = ?";
        $result = $this->query($sql, [$estado]);
        return $result[0]['total'] ?? 0;
    }
}
?>
