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
            (SELECT imagen FROM imagen_servicio im WHERE im.id_servicio = s.id_servicio AND im.principal = 1 LIMIT 1) as imagen_principal,
            (SELECT AVG(r.calificacion) FROM resena r WHERE r.id_servicio = s.id_servicio) as calificacion
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
    $sql = "SELECT s.*, c.nombre_categoria
            FROM servicio s
            JOIN categoria c ON s.id_categoria = c.id_categoria
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
                  p.id_usuario as id_proveedor,
                  u.nombre as proveedor_nombre,
                  u.apellido as proveedor_apellido,
                  u.email as proveedor_email
            FROM {$this->table} s
            LEFT JOIN categoria c ON s.id_categoria = c.id_categoria
            LEFT JOIN proveedor p ON s.id_proveedor = p.id_usuario
            LEFT JOIN usuario u ON p.id_usuario = u.id_usuario
            ORDER BY s.id_servicio DESC";
    return $this->query($sql);
}

    public function actualizarImagen($idServicio, $url) {
    $sql = "UPDATE servicio SET imagen_servicio = ? WHERE id_servicio = ?";
    return $this->exec($sql, [$url, $idServicio]);
}
public function borrar($idServicio) {
    // 1. Obtén todas las reservas de este servicio
    $reservas = $this->query("SELECT id_reserva FROM reserva WHERE id_servicio = ?", [$idServicio]);

    // 2. Borra pagos de cada reserva asociada
    foreach ($reservas as $res) {
        $this->exec("DELETE FROM pago WHERE id_reserva = ?", [$res['id_reserva']]);
    }

    // 3. Borra reservas asociadas
    $this->exec("DELETE FROM reserva WHERE id_servicio = ?", [$idServicio]);

    // 4. Borra el servicio (finalmente)
    $this->exec("DELETE FROM servicio WHERE id_servicio = ?", [$idServicio]);
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
public function actualizarPromedio($idServicio) {
    $sql = "UPDATE servicio 
               SET calificacion = (
                   SELECT AVG(calificacion) FROM calificacion WHERE id_servicio = ?
               )
             WHERE id_servicio = ?";
    $this->exec($sql, [$idServicio, $idServicio]);
}
public function update($idServicio, $data) {
        // Construye el SET dinámico a partir del array $data
        $sets = [];
        $values = [];
        foreach ($data as $campo => $valor) {
            $sets[] = "$campo = ?";
            $values[] = $valor;
        }
        // Agrega el id al final del array de valores
        $values[] = $idServicio;
        // Genera el SQL final
        $sql = "UPDATE servicio SET " . implode(', ', $sets) . " WHERE id_servicio = ?";
        // Ejecuta la query (usa tu método exec/prepare)
        return $this->exec($sql, $values);
    }
public function contarActivos() {
    $sql = "SELECT COUNT(*) as total FROM servicio WHERE estado = 'disponible'";
    $result = $this->db->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0;
}
 public function buscarServiciosFiltrados($filtros = []) {
        $sql = "SELECT s.*, 
                c.nombre_categoria as categoria,
                (SELECT imagen FROM imagen_servicio im WHERE im.id_servicio = s.id_servicio AND im.principal = 1 LIMIT 1) as imagen_principal,
                (SELECT AVG(r.calificacion) FROM resena r WHERE r.id_servicio = s.id_servicio) as calificacion
            FROM {$this->table} s
            LEFT JOIN categoria c ON s.id_categoria = c.id_categoria
            WHERE s.estado = 'disponible'";
        $params = [];
        $types = "";

        // Búsqueda de texto
        if (!empty($filtros['busqueda'])) {
            $sql .= " AND (s.titulo LIKE ? OR s.descripcion LIKE ? OR c.nombre_categoria LIKE ?)";
            $search = '%'.$filtros['busqueda'].'%';
            $params[] = $search; $params[] = $search; $params[] = $search;
            $types .= "sss";
        }

        // Orden
        if (!empty($filtros['orden'])) {
            switch ($filtros['orden']) {
                case 'precio_asc': $sql .= " ORDER BY s.precio ASC"; break;
                case 'precio_desc': $sql .= " ORDER BY s.precio DESC"; break;
                default: $sql .= " ORDER BY s.id_servicio DESC";
            }
        } else {
            $sql .= " ORDER BY s.id_servicio DESC";
        }

        $result = count($params)
            ? $this->query($sql, $params)
            : $this->query($sql);

        // Filtro de calificación mínima en PHP (para máxima compatibilidad)
        if (!empty($filtros['min_calif'])) {
            $min = (float)$filtros['min_calif'];
            $result = array_filter($result, function($srv) use($min) {
                return (float)($srv['calificacion'] ?? 0) >= $min;
            });
            $result = array_values($result);
        }

        return $result;
    }

}
?>