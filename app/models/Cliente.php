<?php
class Cliente extends Model {
    protected $table = 'cliente';
    protected $primaryKey = 'id_cliente';

    // Obtener perfil extendido (RF-02)
    public function perfilCompletoPorUsuario($idUsuario) {
        $sql = "SELECT c.*, u.nomusuario, u.email, u.telefono,u.foto_perfil
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
}
?>