<?php

class Notificacion extends Model {
    protected $table = 'notificacion';
    protected $primaryKey = 'id_notificacion';

    // Crear notificación (flexible según columnas)
    public function crearNotificacion($data) {
        // Ajusta estos campos a los que tiene realmente tu tabla en la base de datos
        $insert = [
            'id_usuario' => $data['id_usuario'],
            'titulo'     => $data['titulo'],
            'contenido'  => $data['contenido'],
            'url_accion' => $data['url_accion'] ?? null
        ];
        // Descomenta si tu tabla tiene el campo tipo
        // $insert['tipo'] = $data['tipo'] ?? null;
        
        return $this->create($insert);
    }

    // Obtener notificaciones de un usuario (últimas 20, opcional solo no leídas)
    public function obtenerPorUsuario($idUsuario, $soloNoLeidas = false) {
        $sql = "SELECT * FROM {$this->table} WHERE id_usuario = ?";
        $params = [$idUsuario];
        
        if ($soloNoLeidas) {
            $sql .= " AND leida = 0";
        }
        
        $sql .= " ORDER BY fecha_creacion DESC LIMIT 20";
        return $this->query($sql, $params);
    }

    // Marcar una notificación como leída
    public function marcarLeida($id) {
        return $this->update($id, ['leida' => 1]);
    }

    // Marcar todas como leídas para un usuario dado
    public function marcarTodasLeidas($idUsuario) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET leida = 1 WHERE id_usuario = ?");
        $stmt->bind_param("i", $idUsuario);
        return $stmt->execute();
    }

    // Contar notificaciones no leídas para un usuario
    public function contarNoLeidas($idUsuario) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE id_usuario = ? AND leida = 0";
        $result = $this->query($sql, [$idUsuario]);
        return $result[0]['total'] ?? 0;
    }
}
?>
