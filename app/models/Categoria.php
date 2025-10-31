<?php

class Categoria extends Model
{
    protected $table = 'categoria';
    protected $primaryKey = 'id_categoria';

    // Trae todas las categorías ordenadas por nombre
    public function obtenerActivas() {
        return $this->query("SELECT * FROM categoria ORDER BY nombre_categoria ASC");
    }

    // Puedes agregar métodos para crear, editar, eliminar si los necesitas:
    public function crear($nombre, $descripcion = '') {
        return $this->create([
            'nombre_categoria' => $nombre,
            'descripcion' => $descripcion
        ]);
    }

    public function editar($id, $nombre, $descripcion = '') {
        return $this->update($id, [
            'nombre_categoria' => $nombre,
            'descripcion' => $descripcion
        ]);
    }

    public function eliminar($id) {
        return $this->delete($id);
    }
}
