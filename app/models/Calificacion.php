<?php
    class Calificacion extends Model {
    protected $table = 'calificacion';
    protected $primaryKey = 'id_calificacion';
    
    // RF-50: Crear calificación
    public function crearCalificacion($data) {
        try {
            $this->db->beginTransaction();
            
            // Crear calificación
            $idCalificacion = $this->create([
                'id_reserva' => $data['id_reserva'],
                'id_cliente' => $data['id_cliente'],
                'id_proveedor' => $data['id_proveedor'],
                'puntuacion' => $data['puntuacion']
            ]);
            
            // Actualizar promedio del proveedor (RF-52)
            $this->actualizarPromedioProveedor($data['id_proveedor']);
            
            $this->db->commit();
            return $idCalificacion;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    // RF-52: Calcular y actualizar promedio
    private function actualizarPromedioProveedor($idProveedor) {
        $sql = "SELECT AVG(puntuacion) as promedio, COUNT(*) as total
                FROM {$this->table}
                WHERE id_proveedor = ?";
        
        $result = $this->query($sql, [$idProveedor]);
        $stats = $result[0];
        
        $stmt = $this->db->prepare("
            UPDATE proveedor 
            SET calificacion_promedio = ?,
                total_calificaciones = ?
            WHERE id_proveedor = ?
        ");
        
        $stmt->execute([
            round($stats['promedio'], 2),
            $stats['total'],
            $idProveedor
        ]);
    }
    
    // RF-53: Verificar si ya existe calificación para una reserva
    public function existeParaReserva($idReserva) {
        $result = $this->findBy('id_reserva', $idReserva);
        return $result !== false;
    }
    
    // Obtener calificaciones de un proveedor
    public function obtenerPorProveedor($idProveedor, $limit = 10) {
        $sql = "SELECT c.*, 
                       r.comentario,
                       r.fecha_resena,
                       u.nombre as cliente_nombre
                FROM {$this->table} c
                LEFT JOIN resena r ON c.id_calificacion = r.id_calificacion
                INNER JOIN cliente cl ON c.id_cliente = cl.id_cliente
                INNER JOIN usuario u ON cl.id_usuario = u.id_usuario
                WHERE c.id_proveedor = ?
                ORDER BY c.fecha_calificacion DESC
                LIMIT ?";
        
        return $this->query($sql, [$idProveedor, $limit]);
    }
}

?>

<?php
// ==========================================
// app/models/Resena.php
// ==========================================

class Resena extends Model {
    protected $table = 'resena';
    protected $primaryKey = 'id_resena';
    
    // RF-51: Crear reseña
    public function crearResena($idCalificacion, $comentario) {
        return $this->create([
            'id_calificacion' => $idCalificacion,
            'comentario' => $comentario
        ]);
    }
    
    // Obtener reseña por calificación
    public function obtenerPorCalificacion($idCalificacion) {
        return $this->findBy('id_calificacion', $idCalificacion);
    }
}

?>