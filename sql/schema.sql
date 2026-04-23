-- sql/schema.sql - Database schema for ViandaLibre

CREATE DATABASE IF NOT EXISTS viandalibre;

USE viandalibre;

CREATE TABLE viandas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    imagen VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente VARCHAR(255) NOT NULL,
    telefono VARCHAR(50) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    costo_envio DECIMAL(10,2) NOT NULL DEFAULT 500.00,
    status ENUM('pendiente', 'preparando', 'listo', 'entregado', 'cancelado') DEFAULT 'pendiente',
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de detalles del pedido (integridad referencial con ON DELETE CASCADE)
CREATE TABLE pedido_detalles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    vianda_id INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    precio_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (vianda_id) REFERENCES viandas(id)
);

-- Insert sample data
INSERT INTO viandas (nombre, descripcion, precio, imagen) VALUES
('Vianda Clasica', 'Plato tradicional con carne, verduras y guarnicion', 15.50, 'vianda1.jpg'),
('Vianda Vegetariana', 'Plato vegetariano con tofu y verduras frescas', 12.00, 'vianda2.jpg');
