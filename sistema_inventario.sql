-- Crear y usar la base de datos del proyecto
CREATE DATABASE IF NOT EXISTS sistema_inventario_ventas
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE sistema_inventario_ventas;

-- 1. Tabla para login y seguridad
CREATE TABLE IF NOT EXISTS usuarios (
id INT AUTO_INCREMENT PRIMARY KEY,
nombre_completo VARCHAR(100) NOT NULL,
usuario VARCHAR(50) NOT NULL UNIQUE,
password VARCHAR(255) NOT NULL,
rol VARCHAR(20) NOT NULL
);

-- Usuario administrador inicial
-- Usuario: admin
-- Contrasena: admin123
INSERT INTO usuarios (nombre_completo, usuario, password, rol) VALUES
('Administrador del Sistema', 'admin', '$2y$10$dXUv40swYh9zJ6OckMlMku/3UeEkJgXrNQnLEGZAApBmCRDd3mubS', 'admin')
ON DUPLICATE KEY UPDATE usuario = usuario;

-- 2. Tabla de categorias
CREATE TABLE IF NOT EXISTS categorias (
id INT AUTO_INCREMENT PRIMARY KEY,
nombre_categoria VARCHAR(50) NOT NULL UNIQUE
);

-- 3. Tabla de productos
CREATE TABLE IF NOT EXISTS productos (
id INT AUTO_INCREMENT PRIMARY KEY,
nombre_producto VARCHAR(100) NOT NULL,
categoria_id INT NOT NULL,
stock INT NOT NULL,
precio DECIMAL(10, 2) NOT NULL,
FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- 4. Catalogos base
INSERT INTO categorias (nombre_categoria) VALUES
('Computadoras'),
('Accesorios'),
('Oficina')
ON DUPLICATE KEY UPDATE nombre_categoria = VALUES(nombre_categoria);

-- 5. Productos base
INSERT INTO productos (nombre_producto, categoria_id, stock, precio)
SELECT 'Laptop Dell Inspiron 15', 1, 15, 720.00
WHERE NOT EXISTS (SELECT 1 FROM productos WHERE nombre_producto = 'Laptop Dell Inspiron 15');

INSERT INTO productos (nombre_producto, categoria_id, stock, precio)
SELECT 'Mouse Inalambrico Logitech', 2, 25, 12.00
WHERE NOT EXISTS (SELECT 1 FROM productos WHERE nombre_producto = 'Mouse Inalambrico Logitech');

-- Reporte general de inventario
SELECT p.id, p.nombre_producto, c.nombre_categoria, p.stock, p.precio
FROM productos p
INNER JOIN categorias c ON p.categoria_id = c.id;

-- Reporte filtrado por categoria
SELECT p.id, p.nombre_producto, c.nombre_categoria, p.stock, p.precio
FROM productos p
INNER JOIN categorias c ON p.categoria_id = c.id
WHERE c.nombre_categoria = "Accesorios";

-- Estadisticas para el dashboard
SELECT COUNT(id) AS total_productos_catalogo FROM productos;
SELECT SUM(precio * stock) AS valor_monetario_inventario FROM productos;
SELECT MAX(precio) AS producto_mas_caro FROM productos;
SELECT c.nombre_categoria, SUM(p.stock) AS existencias_totales
FROM productos p
INNER JOIN categorias c ON p.categoria_id = c.id
GROUP BY c.nombre_categoria;

CREATE TABLE proveedores (
id INT AUTO_INCREMENT PRIMARY KEY,
nombre_empresa VARCHAR(100) NOT NULL,
contacto VARCHAR(100),
telefono VARCHAR(20),
direccion TEXT
);
INSERT INTO proveedores (nombre_empresa, contacto, telefono, direccion) VALUES
('Tech Data El Salvador', 'Juan Pérez', '2255-8899', 'San Salvador, Col. Escalón'),
('Distribuidora de Papel', 'María Gómez', '2666-4433', 'San Miguel, Centro');

-- ====================================================================
-- MÓDULO DE COMPRAS: ARQUITECTURA MAESTRO-DETALLE (Guía 23)
-- ====================================================================

-- 1. Tabla Maestra de Compras (Cabecera de Factura)
CREATE TABLE compras (
id INT AUTO_INCREMENT PRIMARY KEY,
proveedor_id INT NOT NULL,
usuario_id INT NOT NULL,
fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
total DECIMAL(10, 2) NOT NULL,
FOREIGN KEY (proveedor_id) REFERENCES proveedores(id),
FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- 2. Tabla Detalle de Compras (Líneas de los productos ingresados)
CREATE TABLE detalle_compras (
id INT AUTO_INCREMENT PRIMARY KEY,
compra_id INT NOT NULL,
producto_id INT NOT NULL,
cantidad INT NOT NULL,
precio_compra DECIMAL(10, 2) NOT NULL,
FOREIGN KEY (compra_id) REFERENCES compras(id),
FOREIGN KEY (producto_id) REFERENCES productos(id)
);
-- Primero, creamos la Cabecera de la factura en la tabla maestra
INSERT INTO compras (proveedor_id, usuario_id, total) VALUES (1, 1, 735.00);

-- Ahora, insertamos los detalles asociados a la compra número 1
INSERT INTO detalle_compras (compra_id, producto_id, cantidad, precio_compra) VALUES (1,
1, 1, 720.00);
INSERT INTO detalle_compras (compra_id, producto_id, cantidad, precio_compra) VALUES (1,
2, 1, 15.00);