<?php

class Administrador extends Model {
    protected $table = 'administrador';
    protected $primaryKey = 'id_admin';

    // Crear un nuevo administrador
    public function crear($idUsuario, string $nivelAcceso = 'normal') {
        return $this->create([
            'id_usuario'    => $idUsuario,
            'nivel_acceso'  => $nivelAcceso
        ]);
    }

    // Obtener datos completos del admin y usuario asociado
    public function obtenerConUsuario($idAdmin): ?array {
        $sql = "SELECT a.*, u.*
                FROM {$this->table} a
                INNER JOIN usuario u ON a.id_usuario = u.id_usuario
                WHERE a.id_admin = ?";
        $res = $this->query($sql, [$idAdmin]);
        return $res[0] ?? null;
    }

    // Obtener administrador por usuario
    public function obtenerPorUsuario($idUsuario): ?array {
        $res = $this->findBy('id_usuario', $idUsuario);
        return $res ? $res[0] : null;
    }

    // Listar todos los administradores con datos clave de usuario
    public function obtenerTodos(): array {
        $sql = "SELECT a.*, u.nombre, u.email, u.telefono, u.fecha_registro
                FROM {$this->table} a
                INNER JOIN usuario u ON a.id_usuario = u.id_usuario
                ORDER BY u.nombre";
        return $this->query($sql);
    }

    // Saber si es super administrador
    public function esSuperAdmin($idAdmin): bool {
        $admin = $this->find($idAdmin);
        return $admin && ($admin['nivel_acceso'] ?? '') === 'super';
    }

    // Total administradores
    public function contarTotal(): int {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $res = $this->query($sql);
        return (int)($res[0]['total'] ?? 0);
    }

    // Cambiar nivel de acceso de un admin
    public function actualizarNivel($idAdmin, string $nuevoNivel) {
        return $this->update($idAdmin, ['nivel_acceso' => $nuevoNivel]);
    }

    // Eliminar admin por id
    public function eliminar($idAdmin) {
        return $this->delete($idAdmin);
    }
}
?>