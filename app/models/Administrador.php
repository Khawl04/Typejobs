<?php
// ==========================================
// app/models/Administrador.php
// ==========================================

class Administrador extends Model {
    protected $table = 'administrador';
    protected $primaryKey = 'id_admin';
    
    // RF-11: Crear administrador
    public function crearAdmin($idUsuario, $nivelAcceso = 'normal') {
        return $this->create([
            'id_usuario' => $idUsuario,
            'nivel_acceso' => $nivelAcceso
        ]);
    }
    
    // Obtener administrador con información de usuario
    public function obtenerConUsuario($idAdmin) {
        $sql = "SELECT a.*, u.*
                FROM {$this->table} a
                INNER JOIN usuario u ON a.id_usuario = u.id_usuario
                WHERE a.id_admin = ?";
        
        $result = $this->query($sql, [$idAdmin]);
        return $result[0] ?? null;
    }
    
    // Obtener por id de usuario
    public function obtenerPorUsuario($idUsuario) {
        return $this->findBy('id_usuario', $idUsuario);
    }
    
    // Obtener todos los administradores
    public function obtenerTodos() {
        $sql = "SELECT a.*, u.nombre, u.email, u.telefono, u.fecha_registro
                FROM {$this->table} a
                INNER JOIN usuario u ON a.id_usuario = u.id_usuario
                ORDER BY u.nombre";
        
        return $this->query($sql);
    }
    
    // Verificar si es super admin
    public function esSuperAdmin($idAdmin) {
        $admin = $this->find($idAdmin);
        return $admin && $admin['nivel_acceso'] === 'super';
    }
    
    // Contar total de administradores
    public function contarTotal() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->query($sql);
        return $result[0]['total'] ?? 0;
    }
}

?>