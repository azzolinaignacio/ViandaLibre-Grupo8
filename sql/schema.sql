-- sql/schema.sql - Database schema for ViandaLibre

CREATE DATABASE IF NOT EXISTS viandalibre_db;

USE viandalibre_db;

CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

--Las Categorías
--1 -> Plato Principal
--2 -> Ensaladas
--3 -> Minutas


--Tabla: viandas (El corazón del catálogo)

CREATE TABLE viandas (
    id_vianda INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    imagen_url VARCHAR(255), -- Ruta de la foto en la carpeta /assets
    id_categoria INT,
    disponible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
);

--15 Platos de Ejemplos

INSERT INTO viandas (nombre, descripcion, precio, imagen_url, id_categoria, disponible) VALUES ('Milanesa con Puré', 'Clásica milanesa de carne vacuna con puré de papas natural.', 4500.00, 'milanesa_pure.jpg', 1, 1), ('Ñoquis Bolognesa', 'Ñoquis de papa caseros con salsa bolognesa de la casa.', 3800.00, 'ñoquis_bolo.jpg', 1, 1), ('Pollo al Horno', 'Cuarto trasero de pollo al horno con papas rústicas.', 4200.00, 'pollo_papas.jpg', 1, 1), ('Ensalada César', 'Pechuga de pollo, lechuga, croutons, queso y aderezo césar.', 3500.00, 'ensalada_cesar.jpg', 2, 1), ('Canelones de Verdura', 'Dos unidades de canelones con salsa mixta.', 4000.00, 'canelones.jpg', 1, 1), ('Tarta de Jamón y Queso', 'Porción abundante de tarta con masa integral.', 2800.00, 'tarta_jq.jpg', 3, 1), ('Pastel de Papa', 'Capas de carne picada sazonada y puré de papa gratinado.', 4300.00, 'pastel_papa.jpg', 1, 1), ('Ensalada Mixta Plus', 'Lechuga, tomate, cebolla, huevo duro y zanahoria.', 2500.00, 'ensalada_mixta.jpg', 2, 1), ('Suprema Napolitana', 'Suprema de pollo con salsa de tomate, jamón y muzzarella.', 4800.00, 'suprema_napo.jpg', 3, 1), ('Guiso de Lentejas', 'Guiso tradicional con chorizo colorado y panceta.', 3900.00, 'guiso_lentejas.jpg', 1, 1), ('Arroz con Pollo', 'Arroz amarillo con trozos de pollo y arvejas.', 3700.00, 'arroz_pollo.jpg', 1, 1), ('Wok de Vegetales', 'Mezcla de vegetales de estación salteados con soja.', 3200.00, 'wok_veg.jpg', 2, 1), ('Hamburguesa Completa', 'Medallón de carne 180g con lechuga, tomate y queso.', 4100.00, 'hamburguesa.jpg', 3, 1), ('Tortilla de Papas', 'Porción individual de tortilla de papas y cebolla.', 3000.00, 'tortilla.jpg', 3, 1), ('Lasagna de Carne', 'Capas de pasta, carne picada, jamón y queso con bechamel.', 4600.00, 'lasagna.jpg', 1, 1); 

--Tabla: pedidos (Para recibir las ventas)

CREATE TABLE pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    cliente_nombre VARCHAR(100) NOT NULL,
    cliente_whatsapp VARCHAR(20) NOT NULL,
    direccion_entrega VARCHAR(255) NOT NULL,
    total_pago DECIMAL(10, 2) NOT NULL,
    estado ENUM('Pendiente', 'En Cocina', 'Enviado', 'Entregado') DEFAULT 'Pendiente'
);

--10 Pedidos de Ejemplos 

INSERT INTO pedidos (id_pedido, cliente_nombre, cliente_whatsapp, direccion_entrega, total_pago, estado) VALUES (40, 'Juan Pérez', '1122334455', 'Calle Falsa 123', 8300.00, 'Entregado'), (41, 'María García', '1199887766', 'Av. Siempre Viva 742', 4200.00, 'Entregado'), (42, 'Ricardo Fort', '1133445566', 'Calle Miami 500', 12500.00, 'Enviado'), (43, 'Elena Blanco', '1155667788', 'Pasaje Olivos 12', 3500.00, 'En Cocina'), (44, 'Carlos Saúl', '1144552211', 'Anillaco 45', 7800.00, 'En Cocina'), (45, 'Marta Minujín', '1188990022', 'Arte 999', 7000.00, 'Pendiente'), (46, 'Roberto Galán', '1177665544', 'Cupido 14', 4500.00, 'Pendiente'), (47, 'Mirtha Legrand', '1100000001', 'Av. Libertador 2000', 9200.00, 'Pendiente'), (48, 'Diego Maradona', '1110101010', 'Segurola y Habana 4310', 8100.00, 'Pendiente'), (49, 'Lionel Messi', '1100000010', 'Rosario 10', 4600.00, 'Pendiente'); 


--Tabla: detalle_pedido (El desglose)

CREATE TABLE detalle_pedido (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_vianda INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    precio_unitario DECIMAL(10, 2) NOT NULL, -- Guardamos el precio del momento por si cambia mañana
    subtotal DECIMAL(10, 2) AS (cantidad * precio_unitario), -- Campo calculado (opcional en versiones nuevas de MySQL)
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido) ON DELETE CASCADE,
    FOREIGN KEY (id_vianda) REFERENCES viandas(id_vianda)
);


--Detalle de los 15 pedidos

INSERT INTO detalle_pedido (id_pedido, id_vianda, cantidad, precio_unitario) VALUES -- Pedido 40: Milanesa (1) y Ñoquis (2) (40, 1, 1, 4500.00), (40, 2, 1, 3800.00), -- Pedido 41: Pollo al Horno (3) (41, 3, 1, 4200.00), -- Pedido 42: Suprema (9) x2 y Lasagna (15) (42, 9, 2, 4800.00), (42, 15, 1, 4600.00), -- Pedido 43: Ensalada César (4) (43, 4, 1, 3500.00), -- Pedido 44: Canelones (5) y Ñoquis (2) (44, 5, 1, 4000.00), (44, 2, 1, 3800.00), -- Pedido 45: Tarta (6) y Pollo (3) <-- El pedido del ejemplo anterior (45, 6, 1, 2800.00), (45, 3, 1, 4200.00), -- Pedido 46: Milanesa (1) (46, 1, 1, 4500.00), -- Pedido 47: Suprema (9) y Pastel de Papa (7) (47, 9, 1, 4800.00), (47, 7, 1, 4400.00), -- Pedido 48: Hamburguesa (13) x2 (48, 13, 2, 4050.00), -- Pedido 49: Lasagna (15) (49, 15, 1, 4600.00);
