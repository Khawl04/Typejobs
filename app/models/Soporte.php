<?php
//Clase sin usar

class Soporte extends Model {
    protected $table = 'soporte';
    protected $primaryKey = 'id_soporte';
    
    // RF-08: Crear usuario de soporte
    public function crearSoporte($idUsuario, $especialidad = null) {
        return $this->create([
            'id_usuario' => $idUsuario,
            'especialidad' => $especialidad
        ]);
    }
    
    // Obtener soporte con información de usuario
    public function obtenerConUsuario($idSoporte) {
        $sql = "SELECT s.*, u.*
                FROM {$this->table} s
                INNER JOIN usuario u ON s.id_usuario = u.id_usuario
                WHERE s.id_soporte = ?";
        
        $result = $this->query($sql, [$idSoporte]);
        return $result[0] ?? null;
    }
    
    // Obtener por id de usuario
    public function obtenerPorUsuario($idUsuario) {
        return $this->findBy('id_usuario', $idUsuario);
    }
    
    // Obtener todos los usuarios de soporte
    public function obtenerTodos() {
        $sql = "SELECT s.*, u.nombre, u.email, u.telefono
                FROM {$this->table} s
                INNER JOIN usuario u ON s.id_usuario = u.id_usuario
                ORDER BY u.nombre";
        
        return $this->query($sql);
    }
    
    // Obtener estadísticas de soporte
    public function obtenerEstadisticas($idSoporte) {
        $sql = "SELECT 
                    COUNT(*) as total_tickets,
                    SUM(CASE WHEN estado = 'resuelto' THEN 1 ELSE 0 END) as resueltos,
                    SUM(CASE WHEN estado IN ('abierto', 'en_proceso') THEN 1 ELSE 0 END) as activos,
                    AVG(TIMESTAMPDIFF(HOUR, fecha_creacion, fecha_resolucion)) as tiempo_promedio_horas
                FROM asistencia
                WHERE id_soporte = ?";
        
        $result = $this->query($sql, [$idSoporte]);
        return $result[0] ?? null;
    }
}

?>