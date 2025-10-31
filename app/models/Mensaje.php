<?php
class Mensaje extends Model
{
    protected $table = 'mensaje';
    protected $primaryKey = 'id_mensaje';

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
    // Solo incluye los campos que existen de verdad en la base
    return $this->create([
        'id_usuario' => $idRemitente,
        'id_usuario_dest' => $idDestinatario,
        'contenido' => $contenido, // Si tu tabla es mensaje, cambia aquí a 'mensaje'
        'archivo_adjunto' => $archivoAdjunto,
        'leido' => 0,
        // 'fecha_envio' => date('Y-m-d H:i:s'), // Si no tienes por default en la BD
        // 'archivo_adjunto' => $archivoAdjunto // Solo si realmente existe este campo
    ]);
}


}
?>
