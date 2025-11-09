<?php

class Database {
    private static $instance = null;
    private $conexion;

    // Constructor privado (Singleton)
    private function __construct() {
        // Cargar configuración desde config/database.php
        $config = require __DIR__ . '/../config/database.php';

        // Crear la conexión MySQLi
        $this->conexion = new mysqli(
            $config['host'],
            $config['username'],
            $config['password'],
            $config['database'],
            $config['port']
        );

        // Verificar errores
        if ($this->conexion->connect_error) {
            exit("Error de conexión: " . $this->conexion->connect_error);
        }

        // Configurar charset
        $this->conexion->set_charset($config['charset']);
    }

    // Devuelve la instancia única
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Devuelve la conexión activa
    public function getConnection() {
        return $this->conexion;
    }
}
?>    