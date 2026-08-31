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
