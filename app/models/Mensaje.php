<?php

class Mensaje extends Model
{
    protected $table = 'mensaje';
    protected $primaryKey = 'id_mensaje';

    // ============= CONVERSACIONES ENTRE USUARIOS =============
    
    // Obtener conversaciones del usuario
    public function obtenerConversaciones($idUsuario) {
        $sql = "SELECT DISTINCT
                    CASE 
                        WHEN m.id_usuario = ? THEN m.id_usuario_dest
                        ELSE m.id_usuario
                    END as id_otro_usuario
                FROM mensaje m
                WHERE m.id_usuario = ? OR m.id_usuario_dest = ?";
        return $this->query($sql, [$idUsuario, $idUsuario, $idUsuario]);
    }

    // Contar no leídos para usuario
    public function noLeidosPorUsuario($idUsuario) {
        $sql = "SELECT COUNT(*) as total FROM mensaje WHERE id_usuario_dest = ? AND leido = 0";
        $res = $this->query($sql, [$idUsuario]);
        return $res[0]['total'] ?? 0;
    }

    // Marcar como leídos los mensajes de remitente para destinatario
    public function marcarLeidos($idRemitente, $idDestinatario) {
        $sql = "UPDATE mensaje SET leido = 1 
                WHERE id_usuario = ? AND id_usuario_dest = ? AND leido = 0";
        $this->exec($sql, [$idRemitente, $idDestinatario]);
    }

    // Obtener mensajes de una conversación entre dos usuarios
    public function obtenerMensajes($idUsuario, $idDestinatario) {
        $sql = "SELECT * FROM mensaje
                WHERE (id_usuario = ? AND id_usuario_dest = ?)
                   OR (id_usuario = ? AND id_usuario_dest = ?)
                ORDER BY id_mensaje ASC";
        return $this->query($sql, [$idUsuario, $idDestinatario, $idDestinatario, $idUsuario]);
    }

    // Enviar un mensaje
    public function enviar($idRemitente, $idDestinatario, $contenido, $archivoAdjunto = null) {
        return $this->create([
            'id_usuario' => $idRemitente,
            'id_usuario_dest' => $idDestinatario,
            'contenido' => $contenido,
            'archivo_adjunto' => $archivoAdjunto,
            'leido' => 0
        ]);
    }

    // ============= MENSAJES DE CONTACTO (ADMIN) =============
    
    // Obtener todos los mensajes con filtros (usa fecha_envio en lugar de fecha_creacion)
    public function obtenerTodos($estado = null, $buscar = null)
    {
        $query = "SELECT * FROM mensaje WHERE 1=1";

        if ($estado) {
            $query .= " AND estado = '" . $this->escape($estado) . "'";
        }

        if ($buscar) {
            $buscar = $this->escape('%' . $buscar . '%');
            $query .= " AND (email LIKE '{$buscar}' OR asunto LIKE '{$buscar}' OR nombre LIKE '{$buscar}')";
        }

        $query .= " ORDER BY fecha_envio DESC";  // ← CAMBIO AQUÍ

        return $this->query($query);
    }

    // Obtener un mensaje por ID
    public function obtenerPorId($id)
    {
        $query = "SELECT * FROM mensaje WHERE id_mensaje = ?";
        $resultado = $this->query($query, [$id]);
        return $resultado ? $resultado[0] : null;
    }

    // Contar todos los mensajes
    public function contarTodos()
    {
        $query = "SELECT COUNT(*) as total FROM mensaje";
        $resultado = $this->query($query);
        return $resultado ? $resultado[0]['total'] : 0;
    }

    // Contar mensajes no leídos
    public function contarNoLeidos()
    {
        $query = "SELECT COUNT(*) as total FROM mensaje WHERE estado = 'no_leido'";
        $resultado = $this->query($query);
        return $resultado ? $resultado[0]['total'] : 0;
    }

    // Marcar mensaje como leído
    public function marcarComoLeido($id)
    {
        $query = "UPDATE mensaje SET estado = 'leido' WHERE id_mensaje = ?";
        return $this->exec($query, [$id]);
    }

    // Eliminar mensaje
    public function eliminarMensaje($id)
    {
        $query = "DELETE FROM mensaje WHERE id_mensaje = ?";
        return $this->exec($query, [$id]);
    }

    // Método helper para escapar strings
    protected function escape($valor)
    {
        return addslashes($valor);
    }
}
?>
