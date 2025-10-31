<?php

class Mantenimiento extends Model {
    protected $table = 'mantenimiento';
    protected $primaryKey = 'id_mantenimiento';
    
    /**
     * Registrar acción administrativa
     * RF-58: El sistema debe registrar todas las acciones de administración
     */
    public function registrarAccion($idAdmin, $tipoAccion, $entidadAfectada, $idEntidad, $descripcion, $datosAnteriores = null, $datosNuevos = null) {
        $data = [
            'id_admin' => $idAdmin,
            'tipo_accion' => $tipoAccion,
            'entidad_afectada' => $entidadAfectada,
            'id_entidad' => $idEntidad,
            'descripcion' => $descripcion,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ];
        
        if ($datosAnteriores !== null) {
            $data['datos_anteriores'] = json_encode($datosAnteriores);
        }
        
        if ($datosNuevos !== null) {
            $data['datos_nuevos'] = json_encode($datosNuevos);
        }
        
        return $this->create($data);
    }
    
    /**
     * Obtener historial de acciones de un administrador
     */
    public function obtenerHistorialAdmin($idAdmin, $limit = 50) {
        $sql = "SELECT m.*, u.nombre as admin_nombre 
                FROM {$this->table} m
                INNER JOIN administrador a ON m.id_admin = a.id_admin
                INNER JOIN usuario u ON a.id_usuario = u.id_usuario
                WHERE m.id_admin = ?
                ORDER BY m.fecha_accion DESC
                LIMIT ?";
        
        return $this->query($sql, [$idAdmin, $limit]);
    }
    
    /**
     * Obtener todas las acciones recientes (para auditoría)
     */
    public function obtenerAccionesRecientes($limit = 100, $tipoAccion = null) {
        $sql = "SELECT m.*, 
                       u.nombre as admin_nombre,
                       u.email as admin_email
                FROM {$this->table} m
                INNER JOIN administrador a ON m.id_admin = a.id_admin
                INNER JOIN usuario u ON a.id_usuario = u.id_usuario";
        
        $params = [];
        
        if ($tipoAccion) {
            $sql .= " WHERE m.tipo_accion = ?";
            $params[] = $tipoAccion;
        }
        
        $sql .= " ORDER BY m.fecha_accion DESC LIMIT ?";
        $params[] = $limit;
        
        return $this->query($sql, $params);
    }
    
    /**
     * Obtener estadísticas de acciones administrativas
     */
    public function obtenerEstadisticas($fechaInicio = null, $fechaFin = null) {
        $sql = "SELECT 
                    tipo_accion,
                    COUNT(*) as total,
                    COUNT(DISTINCT id_admin) as admins_diferentes
                FROM {$this->table}";
        
        $params = [];
        
        if ($fechaInicio && $fechaFin) {
            $sql .= " WHERE fecha_accion BETWEEN ? AND ?";
            $params = [$fechaInicio, $fechaFin];
        }
        
        $sql .= " GROUP BY tipo_accion
                  ORDER BY total DESC";
        
        return $this->query($sql, $params);
    }
    
    /**
     * Buscar acciones sobre una entidad específica
     */
    public function buscarPorEntidad($entidadAfectada, $idEntidad) {
        $sql = "SELECT m.*, 
                       u.nombre as admin_nombre
                FROM {$this->table} m
                INNER JOIN administrador a ON m.id_admin = a.id_admin
                INNER JOIN usuario u ON a.id_usuario = u.id_usuario
                WHERE m.entidad_afectada = ? 
                AND m.id_entidad = ?
                ORDER BY m.fecha_accion DESC";
        
        return $this->query($sql, [$entidadAfectada, $idEntidad]);
    }
}