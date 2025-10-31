<?php
class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Obtener todos los registros
    public function all() {
        $result = $this->db->query("SELECT * FROM {$this->table}");
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    // Buscar por ID
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Buscar por campo
    public function findBy($field, $value) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$field} = ?");
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Obtener varios registros por campo
    public function where($field, $value) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$field} = ?");
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    // Crear nuevo registro
    public function create($data) {
        $fields = array_keys($data);
        $values = array_values($data);
        $placeholders = array_fill(0, count($fields), '?');

        $types = str_repeat('s', count($values));

        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->table,
            implode(', ', $fields),
            implode(', ', $placeholders)
        );

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$values);
        $stmt->execute();

        return $this->db->insert_id;
    }

    // Actualizar registro
    public function update($id, $data) {
        $fields = [];
        foreach (array_keys($data) as $field) {
            $fields[] = "{$field} = ?";
        }

        $types = str_repeat('s', count($data)) . 'i';

        $sql = sprintf(
            "UPDATE %s SET %s WHERE %s = ?",
            $this->table,
            implode(', ', $fields),
            $this->primaryKey
        );

        $values = array_values($data);
        $values[] = $id;

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$values);
        return $stmt->execute();
    }

    // Eliminar registro
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // Query personalizado
    public function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        if ($params) {
            // Solo 's' para todos los campos como ejemplo, puedes ajustar según tu query
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
    public function exec($sql, $params = []) {
    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    return $stmt->affected_rows > 0;
}

}
?>