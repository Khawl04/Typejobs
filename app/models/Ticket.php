<?php
// ==================
// Modelo: RespuestaTicket
// ==================
class Ticket extends Model {
    protected $table = 'ticket';
    protected $primaryKey = 'id_respuesta';

    // Crear respuesta para un ticket
    public function crear($idTicket, $idUsuario, $mensaje) {
        return $this->create([
            'id_ticket'  => $idTicket,
            'id_usuario' => $idUsuario,
            'mensaje'    => $mensaje
        ]);
    }

    // Obtener todas las respuestas de un ticket (con usuario y foto)
    public function obtenerPorTicket($idTicket) {
        $sql = "SELECT r.*, 
                       u.nombre AS usuario_nombre,
                       u.foto_perfil AS usuario_foto
                  FROM {$this->table} r
                  INNER JOIN usuario u ON r.id_usuario = u.id_usuario
                 WHERE r.id_ticket = ?
              ORDER BY r.fecha_respuesta ASC";
        return $this->query($sql, [$idTicket]);
    }
}
?>
