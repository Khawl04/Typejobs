<?php
// ==============
// Modelo: Reporte
// ==============
class Reporte extends Model {
    protected $table = 'reporte';
    protected $primaryKey = 'id_reporte';

    // Crear un reporte nuevo
    public function crear($data) {
        return $this->create([
            'id_usuario_reporta'   => $data['id_usuario_reporta'],
            'id_usuario_reportado' => $data['id_usuario_reportado'] ?? null,
            'id_servicio'          => $data['id_servicio'] ?? null,
            'tipo'                 => $data['tipo'],
            'motivo'               => $data['motivo'],
            'descripcion'          => $data['descripcion'],
            'estado'               => 'pendiente'
        ]);
    }

    // Listar todos los reportes (con nombres y estados)
    public function listarTodo() {
        $sql = "SELECT r.*,
                       u1.nombre AS nombre_reporta,
                       u2.nombre AS nombre_reportado,
                       s.nombre AS nombre_servicio,
                       ua.nombre AS nombre_admin
                  FROM {$this->table} r
                  INNER JOIN usuario u1 ON r.id_usuario_reporta = u1.id_usuario
                  LEFT JOIN usuario u2 ON r.id_usuario_reportado = u2.id_usuario
                  LEFT JOIN servicio s ON r.id_servicio = s.id_servicio
                  LEFT JOIN administrador a ON r.id_admin = a.id_admin
                  LEFT JOIN usuario ua ON a.id_usuario = ua.id_usuario
                 ORDER BY 
                    FIELD(r.estado, 'pendiente', 'en_revision', 'resuelto', 'cerrado'),
                    r.fecha_reporte DESC";
        return $this->query($sql);
    }

    // Contar reportes pendientes
    public function contarPendientes() {
        $sql = "SELECT COUNT(*) AS total FROM {$this->table} WHERE estado = 'pendiente'";
        $result = $this->query($sql);
        return $result[0]['total'] ?? 0;
    }
}
?>
