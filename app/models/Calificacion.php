<?php
    class Calificacion extends Model {
    protected $table = 'calificacion';
    protected $primaryKey = 'id_calificacion';

    // Crear calificación y reseña como una sola acción
    public function crearConResena($idServicio, $idCliente, $puntuacion, $comentario = '') {
        $this->db->beginTransaction();

        // Crea la calificación (puede tener fecha, etc)
        $idCalif = $this->create([
            'id_servicio' => $idServicio,
            'id_cliente' => $idCliente,
            'calificacion' => $puntuacion
        ]);

        // Crear la reseña sólo si tiene comentario
        if (trim($comentario) !== '') {
            $this->exec(
                "INSERT INTO resena (id_calificacion, comentario) VALUES (?, ?)",
                [$idCalif, $comentario]
            );
        }

        $this->db->commit();
        return $idCalif;
    }

    // Chequear si ya existe una calificación para un cliente y servicio
    public function existeParaCliente($idServicio, $idCliente) {
        $sql = "SELECT 1 FROM {$this->table} WHERE id_servicio = ? AND id_cliente = ?";
        $row = $this->query($sql, [$idServicio, $idCliente]);
        return !empty($row);
    }

    // Obtener promedio del servicio
    public function obtenerPromedioServicio($idServicio) {
        $sql = "SELECT AVG(calificacion) as promedio FROM {$this->table} WHERE id_servicio = ?";
        $res = $this->query($sql, [$idServicio]);
        return round($res[0]['promedio'] ?? 0, 2);
    }

    // Obtener todas las calificaciones y reseñas de un servicio
    public function obtenerPorServicio($idServicio) {
        $sql = "SELECT c.*, r.comentario, u.nombre as cliente_nombre, u.apellido as cliente_apellido,
                       u.foto_perfil
                FROM {$this->table} c
                LEFT JOIN resena r ON c.id_calificacion = r.id_calificacion
                INNER JOIN cliente cl ON c.id_cliente = cl.id_cliente
                INNER JOIN usuario u ON cl.id_usuario = u.id_usuario
                WHERE c.id_servicio = ?
                ORDER BY c.id_calificacion DESC";
        return $this->query($sql, [$idServicio]);
    }
}
