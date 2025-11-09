<?php

class Categoria extends Model
{
    protected $table = 'categoria';
    protected $primaryKey = 'id_categoria';

    // Trae todas las categorías con conteo de servicios
    public function obtenerTodas() {
        $query = "SELECT 
                    c.id_categoria,
                    c.nombre_categoria,
                    c.descripcion,
                    c.fecha_creacion,
                    COUNT(s.id_servicio) as total_servicios
                  FROM categoria c
                  LEFT JOIN servicio s ON c.id_categoria = s.id_categoria
                  GROUP BY c.id_categoria, c.nombre_categoria, c.descripcion, c.fecha_creacion
                  ORDER BY c.id_categoria DESC";
        
        return $this->query($query);
    }

    // Trae todas las categorías activas ordenadas por nombre
    public function obtenerActivas() {
        return $this->query("SELECT * FROM categoria ORDER BY nombre_categoria ASC");
    }

    // Obtener una categoría por ID
    public function obtenerPorId($id) {
        return $this->find($id);
    }

    // Crear nueva categoría
    public function crear($datos) {
        return $this->create([
            'nombre_categoria' => $datos['nombre'],
            'descripcion' => $datos['descripcion'] ?? '',
            'fecha_creacion' => date('Y-m-d H:i:s')
        ]);
    }

    // Editar categoría existente
    public function editar($id, $datos) {
        return $this->update($id, [
            'nombre_categoria' => $datos['nombre'],
            'descripcion' => $datos['descripcion'] ?? ''
        ]);
    }

    // Eliminar categoría
    public function eliminar($id) {
        // Verificar que no tenga servicios asociados antes de eliminar
        $query = "SELECT COUNT(*) as total FROM servicio WHERE id_categoria = ?";
        $resultado = $this->query($query, [$id]);
        
        if ($resultado && $resultado[0]['total'] > 0) {
            return false; // No se puede eliminar si tiene servicios
        }
        
        return $this->delete($id);
    }

    // Contar todas las categorías
    public function contarTodas() {
        $query = "SELECT COUNT(*) as total FROM categoria";
        $resultado = $this->query($query);
        return $resultado ? $resultado[0]['total'] : 0;
    }

    // Contar servicios de una categoría
    public function contarServicios($id) {
        $query = "SELECT COUNT(*) as total FROM servicio WHERE id_categoria = ?";
        $resultado = $this->query($query, [$id]);
        return $resultado ? $resultado[0]['total'] : 0;
    }
}
