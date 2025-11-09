<?php

// Clase sin usar

class Busqueda extends Model {
    // Este modelo es un helper para búsquedas avanzadas
    protected $table = 'servicio'; // Usamos servicio como base
    
    // Búsqueda con relevancia (scoring)
    public function buscarConRelevancia($query) {
        $sql = "SELECT s.*,
                       c.nombre as categoria_nombre,
                       p.calificacion_promedio,
                       p.total_calificaciones,
                       u.nombre as proveedor_nombre,
                       u.direccion as proveedor_ubicacion,
                       -- Scoring de relevancia
                       (
                           CASE WHEN s.nombre LIKE ? THEN 10 ELSE 0 END +
                           CASE WHEN s.descripcion LIKE ? THEN 5 ELSE 0 END +
                           CASE WHEN u.nombre LIKE ? THEN 3 ELSE 0 END +
                           (p.calificacion_promedio * 2) +
                           (p.total_calificaciones / 10)
                       ) as relevancia
                FROM servicio s
                INNER JOIN categoria c ON s.id_categoria = c.id_categoria
                INNER JOIN proveedor p ON s.id_proveedor = p.id_proveedor
                INNER JOIN usuario u ON p.id_usuario = u.id_usuario
                WHERE s.estado = 'disponible'
                AND (s.nombre LIKE ? OR s.descripcion LIKE ? OR u.nombre LIKE ?)
                ORDER BY relevancia DESC, p.calificacion_promedio DESC";
        
        $searchTerm = "%{$query}%";
        $params = [
            $searchTerm, $searchTerm, $searchTerm,  // Para scoring
            $searchTerm, $searchTerm, $searchTerm   // Para WHERE
        ];
        
        return $this->query($sql, $params);
    }
    
    // Búsqueda por proximidad geográfica (simplificado)
    public function buscarPorProximidad($ubicacion, $radio = 10) {
        // En producción, usar PostGIS o calcular distancia real
        $sql = "SELECT s.*, 
                       c.nombre as categoria_nombre,
                       p.calificacion_promedio,
                       u.nombre as proveedor_nombre,
                       u.direccion as proveedor_ubicacion
                FROM servicio s
                INNER JOIN categoria c ON s.id_categoria = c.id_categoria
                INNER JOIN proveedor p ON s.id_proveedor = p.id_proveedor
                INNER JOIN usuario u ON p.id_usuario = u.id_usuario
                WHERE s.estado = 'disponible'
                AND u.direccion LIKE ?
                ORDER BY p.calificacion_promedio DESC";
        
        return $this->query($sql, ["%{$ubicacion}%"]);
    }
    
    // Búsquedas populares (trending)
    public function obtenerBusquedasPopulares($limit = 10) {
        // Esto requeriría una tabla de log de búsquedas
        // Por ahora retornamos las categorías más usadas
        $sql = "SELECT c.nombre, COUNT(s.id_servicio) as total
                FROM categoria c
                INNER JOIN servicio s ON c.id_categoria = s.id_categoria
                WHERE s.estado = 'disponible'
                GROUP BY c.id_categoria
                ORDER BY total DESC
                LIMIT ?";
        
        return $this->query($sql, [$limit]);
    }
    
    // Servicios recomendados para un cliente
    public function obtenerRecomendados($idCliente, $limit = 6) {
        // Basado en reservas anteriores y calificaciones
        $sql = "SELECT DISTINCT s.*, 
                       c.nombre as categoria_nombre,
                       p.calificacion_promedio,
                       u.nombre as proveedor_nombre,
                       (SELECT ruta_imagen FROM imagen_servicio 
                        WHERE id_servicio = s.id_servicio AND es_principal = 1 
                        LIMIT 1) as imagen_principal
                FROM servicio s
                INNER JOIN categoria c ON s.id_categoria = c.id_categoria
                INNER JOIN proveedor p ON s.id_proveedor = p.id_proveedor
                INNER JOIN usuario u ON p.id_usuario = u.id_usuario
                WHERE s.estado = 'disponible'
                AND s.id_categoria IN (
                    SELECT DISTINCT s2.id_categoria
                    FROM reserva r
                    INNER JOIN servicio s2 ON r.id_servicio = s2.id_servicio
                    WHERE r.id_cliente = ?
                )
                ORDER BY p.calificacion_promedio DESC, s.fecha_creacion DESC
                LIMIT ?";
        
        return $this->query($sql, [$idCliente, $limit]);
    }
    
    // Autocompletado de búsqueda
    public function autocompletar($query, $limit = 5) {
        $sql = "SELECT DISTINCT nombre as sugerencia, 'servicio' as tipo
                FROM servicio
                WHERE nombre LIKE ?
                AND estado = 'disponible'
                
                UNION
                
                SELECT DISTINCT nombre as sugerencia, 'categoria' as tipo
                FROM categoria
                WHERE nombre LIKE ?
                AND activo = 1
                
                UNION
                
                SELECT DISTINCT u.nombre as sugerencia, 'proveedor' as tipo
                FROM usuario u
                INNER JOIN proveedor p ON u.id_usuario = p.id_usuario
                WHERE u.nombre LIKE ?
                
                LIMIT ?";
        
        $searchTerm = "{$query}%";
        return $this->query($sql, [$searchTerm, $searchTerm, $searchTerm, $limit]);
    }
}

?>