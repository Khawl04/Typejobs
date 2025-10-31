<?php
// ==========================================
// app/models/Notificacion.php
// ==========================================

class Notificacion extends Model {
    protected $table = 'notificacion';
    protected $primaryKey = 'id_notificacion';
    
    // RF-55: Crear notificación
    public function crearNotificacion($data) {
        return $this->create([
            'id_usuario' => $data['id_usuario'],
            'tipo' => $data['tipo'],
            'titulo' => $data['titulo'],
            'contenido' => $data['contenido'],
            'url_accion' => $data['url_accion'] ?? null
        ]);
    }
    
    // Obtener notificaciones de un usuario
    public function obtenerPorUsuario($idUsuario, $soloNoLeidas = false) {
        $sql = "SELECT * FROM {$this->table} WHERE id_usuario = ?";
        $params = [$idUsuario];
        
        if ($soloNoLeidas) {
            $sql .= " AND leida = 0";
        }
        
        $sql .= " ORDER BY fecha_creacion DESC LIMIT 20";
        
        return $this->query($sql, $params);
    }
    
    // Marcar como leída
    public function marcarLeida($id) {
        return $this->update($id, ['leida' => 1]);
    }
    
    // Marcar todas como leídas
    public function marcarTodasLeidas($idUsuario) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET leida = 1 WHERE id_usuario = ?");
        return $stmt->execute([$idUsuario]);
    }
    
    // Contar no leídas
    public function contarNoLeidas($idUsuario) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                WHERE id_usuario = ? AND leida = 0";
        
        $result = $this->query($sql, [$idUsuario]);
        return $result[0]['total'] ?? 0;
    }
}

?>