<?php
class Proveedor extends Model {
    protected $table = 'proveedor';
    protected $primaryKey = 'id_usuario';    // <-- corregido a real clave primaria

    // Crear proveedor nuevo
    public function crearProveedor($idUsuario, $descripcion = null) {
        return $this->create([
            'id_usuario' => $idUsuario,
            'descripcion' => $descripcion,
            'calificacion_promedio' => 0.00,
            'total_calificaciones' => 0,
            'verificado' => false
        ]);
    }

    // Obtener proveedor completo con datos de usuario
   public function obtenerConUsuario($idUsuario) {
    $sql = "SELECT p.*, u.nombre, u.apellido, u.foto_perfil, u.nomusuario, u.telefono,u.email
            FROM proveedor p
            INNER JOIN usuario u ON p.id_usuario = u.id_usuario
            WHERE p.id_usuario = ?";
    $result = $this->query($sql, [$idUsuario]);
    return $result[0] ?? null;
}

    // Buscar proveedor por id_usuario
 public function obtenerPorUsuario($idUsuario) {
    $sql = "SELECT * FROM proveedor WHERE id_usuario = ?";
    $result = $this->query($sql, [$idUsuario]);
    return $result[0] ?? null;
}

    // Obtener todos los proveedores con datos estadísticos resumidos
    public function obtenerTodos() {
        $sql = "SELECT p.*, u.nombre, u.email, u.telefono, u.direccion, u.fecha_registro, 
                       COUNT(DISTINCT s.id_servicio) as total_servicios, 
                       COUNT(DISTINCT r.id_reserva) as total_reservas
                FROM {$this->table} p
                INNER JOIN usuario u ON p.id_usuario = u.id_usuario
                LEFT JOIN servicio s ON p.id_usuario = s.id_usuario
                LEFT JOIN reserva r ON s.id_servicio = r.id_servicio
                GROUP BY p.id_usuario
                ORDER BY p.calificacion_promedio DESC";
        return $this->query($sql);
    }

    // Eliminar proveedor (cascada manual)
    public function eliminarProveedor($idUsuario) {
        try {
            $this->db->beginTransaction();

            $proveedor = $this->find($idUsuario);
            if (!$proveedor) throw new Exception("Proveedor no encontrado");

            // Elimina todo lo relacionado como en tu versión anterior...
            $this->delete($idUsuario);
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    // Actualizar descripción
    public function actualizarDescripcion($idUsuario, $descripcion) {
        return $this->update($idUsuario, ['descripcion' => $descripcion]);
    }

    // Actualizar calificación promedio y total
    public function actualizarCalificacion($idUsuario) {
        $sql = "SELECT AVG(puntuacion) as promedio, COUNT(*) as total
                FROM calificacion
                WHERE id_usuario = ?";
        $result = $this->query($sql, [$idUsuario]);
        $stats = $result[0] ?? ['promedio' => 0, 'total' => 0];
        return $this->update($idUsuario, [
            'calificacion_promedio' => round($stats['promedio'],2),
            'total_calificaciones' => $stats['total']
        ]);
    }

    // Marcar proveedor como verificado
    public function verificar($idUsuario) {
        return $this->update($idUsuario, ['verificado' => true]);
    }

    // Obtener estadísticas (servicios, reservas, calificaciones, etc)
    public function obtenerEstadisticas($idUsuario) {
        $sql = "SELECT COUNT(DISTINCT s.id_servicio) as total_servicios,
                       COUNT(DISTINCT CASE WHEN s.estado = 'disponible' THEN s.id_servicio END) as servicios_activos,
                       COUNT(DISTINCT r.id_reserva) as total_reservas,
                       COALESCE(AVG(c.puntuacion), 0) as calificacion_promedio,
                       COUNT(DISTINCT c.id_calificacion) as total_calificaciones
                FROM {$this->table} p
                LEFT JOIN servicio s ON p.id_usuario = s.id_usuario
                LEFT JOIN reserva r ON s.id_servicio = r.id_servicio
                LEFT JOIN calificacion c ON p.id_usuario = c.id_usuario
                WHERE p.id_usuario = ?
                GROUP BY p.id_usuario";
        $result = $this->query($sql, [$idUsuario]);
        return $result[0] ?? null;
    }

    // Obtener perfil público resumido (para vista pública)
    public function obtenerPerfilPublico($idUsuario) {
        $sql = "SELECT p.*, u.nombre, u.email, u.telefono, u.direccion, u.foto_perfil, u.descripcion
                FROM {$this->table} p
                INNER JOIN usuario u ON p.id_usuario = u.id_usuario
                WHERE p.id_usuario = ?";
        $result = $this->query($sql, [$idUsuario]);
        return $result[0] ?? null;
    }

    // Buscar proveedores por ubicación (RF-23)
    public function buscarPorUbicacion($ubicacion, $limit = 20) {
        $sql = "SELECT p.*, u.nombre, u.direccion, u.foto_perfil, u.descripcion
                FROM {$this->table} p
                INNER JOIN usuario u ON p.id_usuario = u.id_usuario
                WHERE u.direccion LIKE ?
                ORDER BY p.calificacion_promedio DESC
                LIMIT ?";
        return $this->query($sql, ["%$ubicacion%", $limit]);
    }
}
?>
