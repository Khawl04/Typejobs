<?php
require_once __DIR__ . '/../../core/database.php';

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Verifica si el email ya existe en la tabla usuario
    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT 1 FROM usuario WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $existe = $stmt->num_rows > 0;
        $stmt->close();
        return $existe;
    }

    // Verifica si el nombre de usuario ya existe
    public function nomusuarioExists($nomusuario) {
        $stmt = $this->db->prepare("SELECT 1 FROM usuario WHERE nomusuario = ? LIMIT 1");
        $stmt->bind_param("s", $nomusuario);
        $stmt->execute();
        $stmt->store_result();
        $existe = $stmt->num_rows > 0;
        $stmt->close();
        return $existe;
    }

    // Registro de un nuevo usuario
    public function registrar($data) {
        if ($this->emailExists($data['email'])) {
            throw new Exception("El email ya está registrado.");
        }
        if ($this->nomusuarioExists($data['nomusuario'])) {
            throw new Exception("El nombre de usuario ya está registrado.");
        }
        if ($data['contrasena'] !== $data['confirmarcontrasena']) {
            throw new Exception("Las contraseñas no coinciden.");
        }

        $sql = "INSERT INTO usuario 
            (email, contrasena, nombre, apellido, nomusuario, telefono, tipo_usuario, fecha_registro, estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 'activo')";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en el prepare: " . $this->db->error);
        }

        $hash = password_hash($data['contrasena'], PASSWORD_DEFAULT);

        $stmt->bind_param(
            "sssssss",
            $data['email'],
            $hash,
            $data['nombre'],
            $data['apellido'],
            $data['nomusuario'],
            $data['telefono'],
            $data['tipo_usuario']
        );  

        if (!$stmt->execute()) {
            $error = $stmt->error;
            $stmt->close();
            throw new Exception("Error al registrar usuario: " . $error);
        }

        $id_usuario = $stmt->insert_id;
        $stmt->close();

        return $id_usuario;
    }

    // Login por email o nomusuario

 public function login($usuarioOEmail, $contrasena) {
    $sql = "SELECT id_usuario, nombre, apellido, email, tipo_usuario, nomusuario, foto_perfil, contrasena
            FROM usuario
            WHERE email = ? OR nomusuario = ? LIMIT 1";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("ss", $usuarioOEmail, $usuarioOEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();


    if ($row && password_verify($contrasena, $row['contrasena'])) {
        unset($row['contrasena']);
        return $row;
    }
    return null;
}

    // Obtiene el tipo de usuario
    public function getTipoUsuario($id_usuario) {
        $stmt = $this->db->prepare("SELECT tipo_usuario FROM usuario WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result ? $result['tipo_usuario'] : null;
    }

    public function update($id_usuario, $data) {
    $campos = [];
    $params = [];
    // Solo agrega los campos que realmente llegan/lista blanca
    if (isset($data['nomusuario'])) {
        $campos[] = 'nomusuario = ?';
        $params[] = $data['nomusuario'];
    }
    if (isset($data['foto_perfil'])) {
        $campos[] = 'foto_perfil = ?';
        $params[] = $data['foto_perfil'];
    }
    // AGREGAR: Cambiar contraseña si se pasa
    if (isset($data['contrasena'])) {
        $campos[] = 'contrasena = ?';
        $params[] = $data['contrasena'];
    }
    if (isset($data['telefono'])) {
    $campos[] = 'telefono = ?';
    $params[] = $data['telefono'];
    }
    if (isset($data['email'])) {
    $campos[] = 'email = ?';
    $params[] = $data['email'];
    }


    // ...agrega más campos aquí, igual que arriba

    if (empty($campos)) {
        return false; // Nada para actualizar
    }

    $sql = "UPDATE usuario SET " . implode(", ", $campos) . " WHERE id_usuario = ?";
    $params[] = $id_usuario;

    $types = str_repeat('s', count($params)-1) . 'i'; // Asume id_usuario es int (i)
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

public function getById($id) {
    $sql = "SELECT * FROM usuario WHERE id_usuario = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result ? $result->fetch_assoc() : null;
}

public function findById($id_usuario) {
    $stmt = $this->db->prepare("SELECT * FROM usuario WHERE id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc(); // Devuelve array asociativo del usuario
}


}
?>