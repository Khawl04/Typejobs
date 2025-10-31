<?php
class Cliente extends Model {
    protected $table = 'cliente';
    protected $primaryKey = 'id_cliente';

    // Obtener perfil extendido (RF-02)
    public function perfilCompletoPorUsuario($idUsuario) {
        $sql = "SELECT c.*, u.nomusuario, u.email, u.telefono,u.foto_perfil, u.nombre, u.apellido
                FROM cliente c 
                JOIN usuario u ON c.id_usuario = u.id_usuario
                WHERE c.id_usuario = ?";
        return $this->query($sql, [$idUsuario])[0] ?? null;
    }

    // Todas las reservas (RF-62)
    public function reservas($idCliente) {
        $sql = "SELECT * FROM reserva WHERE id_cliente = ?";
        return $this->query($sql, [$idCliente]);
    }

    // Pagos (RF-61)
    public function pagos($idCliente) {
        $sql = "SELECT * FROM pago WHERE id_cliente = ?";
        return $this->query($sql, [$idCliente]);
    }

   
    public function create($data) {
    // Solo insertamos el id_usuario, que es lo necesario según tu estructura
    $sql = "INSERT INTO cliente (id_usuario) VALUES (?)";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $data['id_usuario']);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}
public function eliminarCliente($idUsuario) {
    $cliente = $this->findBy('id_usuario', $idUsuario);
    if ($cliente) {
        $idCliente = $cliente['id_cliente']; // Confirma en tu estructura que existe 

        // Borra dependientes directos (ajusta/agrega si hay más tablas)
        $this->exec("DELETE FROM pago WHERE id_cliente = ?", [$idCliente]);
        $this->exec("DELETE FROM reserva WHERE id_cliente = ?", [$idCliente]);
        $this->exec("DELETE FROM calificacion WHERE id_cliente = ?", [$idCliente]);
        $this->exec("DELETE FROM resena WHERE id_cliente = ?", [$idCliente]);
        // Agrega aquí los deletes de otras tablas que usen id_cliente o id_usuario según la relación real

        // Borra el cliente
        $this->exec("DELETE FROM cliente WHERE id_cliente = ?", [$idCliente]);
    }
    // Finalmente, borra el usuario
    $this->exec("DELETE FROM usuario WHERE id_usuario = ?", [$idUsuario]);
}




}
?>