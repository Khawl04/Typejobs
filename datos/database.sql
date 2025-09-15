-- Base de datos TypeJobs
CREATE DATABASE typejobs;
USE typejobs;

-- Tabla usuarios
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL,
    apellido VARCHAR(30) NOT NULL,
    nomusuario VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(50) NOT NULL UNIQUE,
    telefono VARCHAR(13),
    contrasena VARCHAR(255) NOT NULL,
    tipo ENUM('Cliente', 'Proveedor') DEFAULT 'Cliente',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE CLIENTE (
    id_usuario_cliente INT PRIMARY KEY,
    fecha_ultimo_acceso DATETIME,
    FOREIGN KEY (id_usuario_cliente) REFERENCES USUARIOS(id_usuario) ON DELETE CASCADE
);

CREATE TABLE PROVEEDOR (
    id_usuario_proveedor INT PRIMARY KEY,
    calificacion_promedio DECIMAL(3,2),
    servicios_activos INT,
    fecha_verificacion DATETIME,
    FOREIGN KEY (id_usuario_proveedor) REFERENCES USUARIOS(id_usuario) ON DELETE CASCADE
);



-- Insertar algunos usuarios de ejemplo
INSERT INTO usuarios (nombre, apellido, nomusuario, email, telefono, contrasena, tipo) VALUES
('Juan', 'Perez', 'juanp', 'juan@email.com', '+598 099 123 456', '123456', 'CLIENTE'),
('Maria', 'Garcia', 'mariag', 'maria@email.com', '+598 099 654 321', '123456', 'PROVEEDOR'),
('Carlos', 'Lopez', 'carlosl', 'carlos@email.com', '+598 099 789 012', '123456', 'CLIENTE');