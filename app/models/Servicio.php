<?php
class Servicio extends Model {
    protected $table = 'servicio';
    protected $primaryKey = 'id_servicio';

    // Crear servicio, recibe datos desde el controlador
    public function crearServicio($idProveedor, $data) {
        return $this->create([
            'id_proveedor'      => $idProveedor,
            'id_categoria'      => $data['id_categoria'],
            'titulo'            => $data['titulo'],
            'descripcion'       => $data['descripcion'],
            'precio'            => $data['precio'],
            'duracion_estimada' => $data['duracion_estimada'] ?? null,
            'estado'            => 'disponible'
        ]);
    }

    // Trae todos los servicios activos para el catálogo con imagen principal
    public function obtenerTodos() {
        $sql = "SELECT s.*, 
            c.nombre_categoria as categoria, 
            (SELECT imagen FROM imagen_servicio im WHERE im.id_servicio = s.id_servicio AND im.principal = 1 LIMIT 1) as imagen_principal
            FROM {$this->table} s
            LEFT JOIN categoria c ON s.id_categoria = c.id_categoria
            WHERE s.estado = 'disponible'
            ORDER BY s.id_servicio DESC";
        return $this->query($sql);
    }

    // Buscar servicios con filtros para el home/catalogo
    public function buscarServicios($filtros = []) {
        $sql = "SELECT s.*, 
                      c.nombre_categoria as categoria,
                      (SELECT imagen FROM imagen_servicio im WHERE im.id_servicio = s.id_servicio AND im.principal = 1 LIMIT 1) as imagen_principal
                FROM {$this->table} s
                LEFT JOIN categoria c ON s.id_categoria = c.id_categoria
                WHERE s.estado = 'disponible'";
        $params = [];
        if (!empty($filtros['query'])) {
            $sql .= " AND (s.titulo LIKE ? OR s.descripcion LIKE ?)";
            $searchTerm = "%{$filtros['query']}%";
            $params[] = $searchTerm; $params[] = $searchTerm;
        }
        if (!empty($filtros['categoria'])) {
            $sql .= " AND s.id_categoria = ?";
            $params[] = $filtros['categoria'];
        }
        if (!empty($filtros['precio_min'])) {
            $sql .= " AND s.precio >= ?";
            $params[] = $filtros['precio_min'];
        }
        if (!empty($filtros['precio_max'])) {
            $sql .= " AND s.precio <= ?";
            $params[] = $filtros['precio_max'];
        }
        $sql .= " ORDER BY s.id_servicio DESC";
        return $this->query($sql, $params);
    }

    // Obtener detalles de 1 servicio con info de proveedor y categoría
    public function obtenerConProveedor($id) {
        $sql = "SELECT s.*, 
                      c.nombre_categoria as categoria,
                      p.id_proveedor,
                      u.nombre as proveedor_nombre,
                      u.email as proveedor_email,
                      u.telefono as proveedor_telefono,
                      u.direccion as proveedor_direccion,
                      (SELECT imagen FROM imagen_servicio im WHERE im.id_servicio = s.id_servicio AND im.principal = 1 LIMIT 1) as imagen_principal
                FROM {$this->table} s
                LEFT JOIN categoria c ON s.id_categoria = c.id_categoria
                INNER JOIN proveedor p ON s.id_proveedor = p.id_proveedor
                INNER JOIN usuario u ON p.id_usuario = u.id_usuario
                WHERE s.id_servicio = ?";
        $result = $this->query($sql, [$id]);
        return $result[0] ?? null;
    }

    // Obtener servicios de un proveedor (array)
    public function obtenerPorProveedor($idProveedor) {
    $sql = "SELECT s.*, s.imagen_servicio as imagen_principal
            FROM servicio s
            WHERE s.id_proveedor = ?";
    return $this->query($sql, [$idProveedor]);
}


    // Contar servicios de un proveedor
    public function totalPorProveedor($idProveedor) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE id_proveedor = ?";
        $res = $this->query($sql, [$idProveedor]);
        return $res[0]['total'] ?? 0;
    }

    // Para admin: traer todos
    public function obtenerTodosConProveedor() {
        $sql = "SELECT s.*, 
                      c.nombre_categoria as categoria,
                      u.nombre as proveedor_nombre
                FROM {$this->table} s
                LEFT JOIN categoria c ON s.id_categoria = c.id_categoria
                INNER JOIN proveedor p ON s.id_proveedor = p.id_proveedor
                INNER JOIN usuario u ON p.id_usuario = u.id_usuario
                ORDER BY s.id_servicio DESC";
        return $this->query($sql);
    }
    public function actualizarImagen($idServicio, $url) {
    $sql = "UPDATE servicio SET imagen_servicio = ? WHERE id_servicio = ?";
    return $this->exec($sql, [$url, $idServicio]);
}
public function borrar($idServicio) {
    $sql = "DELETE FROM servicio WHERE id_servicio = ?";
    return $this->exec($sql, [$idServicio]); // Usa exec como definimos antes
}
public function obtenerPorId($idServicio) {
    $sql = "SELECT * FROM servicio WHERE id_servicio = ?";
    $result = $this->query($sql, [$idServicio]);
    return $result[0] ?? null;
}
    public function obtenerImagenes($idServicio) {
    $servicio = $this->obtenerPorId($idServicio);
    if ($servicio && !empty($servicio['imagen_servicio'])) {
        return [ $servicio['imagen_servicio'] ];
    }
    return [];
}
public function obtenerResenas($idServicio) {
    $sql = "SELECT r.*, u.nombre AS nombre_cliente, u.apellido AS apellido_cliente, u.foto_perfil
            FROM resena r
            INNER JOIN usuario u ON r.id_cliente = u.id_usuario
            WHERE r.id_servicio = ?
            ORDER BY r.fecha DESC";
    return $this->query($sql, [$idServicio]);
}



public function usuarioCompro($idUsuario, $idServicio) {
    $sql = "SELECT 1
            FROM pago p
            JOIN reserva r ON p.id_reserva = r.id_reserva
            WHERE p.id_cliente = ? AND r.id_servicio = ?";
    $result = $this->query($sql, [$idUsuario, $idServicio]);
    return !empty($result);
}
public function obtenerConFiltros($busqueda = '', $orden = 'relevancia') {
    $sql = "SELECT s.*, 
                c.nombre_categoria as categoria, 
                (SELECT imagen FROM imagen_servicio im WHERE im.id_servicio = s.id_servicio AND im.principal = 1 LIMIT 1) as imagen_principal,
                (SELECT AVG(r.calificacion) FROM resena r WHERE r.id_servicio = s.id_servicio) as calificacion
            FROM {$this->table} s
            LEFT JOIN categoria c ON s.id_categoria = c.id_categoria
            WHERE s.estado = 'disponible'";
    $params = [];

    if ($busqueda !== '') {
        $sql .= " AND (s.titulo LIKE ? OR s.descripcion LIKE ? OR c.nombre_categoria LIKE ?)";
        $like = "%$busqueda%";
        $params = [$like, $like, $like];
    }

    // Ordenamientos
    switch ($orden) {
        case 'precio_asc':
            $sql .= " ORDER BY s.precio ASC";
            break;
        case 'precio_desc':
            $sql .= " ORDER BY s.precio DESC";
            break;
        case 'estrellas_desc':
            $sql .= " ORDER BY calificacion DESC";
            break;
        default:
            $sql .= " ORDER BY s.id_servicio DESC";
            break;
    }

    return $this->query($sql, $params);
}
// Guarda una nueva reseña
public function guardarResena($idServicio, $idUsuario, $calificacion, $texto) {
    $sql = "INSERT INTO resena (id_servicio, id_cliente, calificacion, texto, fecha)
            VALUES (?, ?, ?, ?, NOW())";
    $this->exec($sql, [$idServicio, $idUsuario, $calificacion, $texto]);
}

// Suma/quita el like de la reseña (toggle por usuario)
public function toggleLikeResena($idResena) {
    error_log("ID para dar like: $idResena");
    // Simplemente suma uno al campo 'likes' de la tabla resena
    $this->exec("UPDATE resena SET likes = likes + 1 WHERE id_resena = ?", [$idResena]);
}



// Devuelve el id_servicio de una reseña
public function getServicioIdByResena($idResena) {
    $sql = "SELECT id_servicio FROM resena WHERE id_resena = ?";
    $resultado = $this->query($sql, [$idResena]);
    return $resultado[0]['id_servicio'] ?? null;
}
public function updateLikeCount($idResena, $delta) {
    if ($delta > 0) {
        $this->exec("UPDATE resena SET likes = likes + 1 WHERE id_resena = ?", [$idResena]);
    } else {
        $this->exec("UPDATE resena SET likes = GREATEST(likes - 1, 0) WHERE id_resena = ?", [$idResena]);
    }
}




}
?>