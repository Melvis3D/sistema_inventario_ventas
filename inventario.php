<?php
// 1. Iniciar sesión y validar autenticación
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// 2. Incluir la conexión a la base de datos
require_once 'conexion.php';

// 3. Consultar la lista de productos relacional con categorías
$sql = "SELECT p.id, p.nombre_producto, c.nombre_categoria, p.stock, p.precio
        FROM productos p
        INNER JOIN categorias c ON p.categoria_id = c.id
        ORDER BY p.id ASC";

$resultado = $conn->query($sql);

// 4. Calcular estadísticas básicas para las tarjetas informativas
$total_productos = 0;
$valor_total = 0;
$stock_bajo_count = 0;

$productos_data = [];
if ($resultado && $resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $productos_data[] = $row;
        $total_productos++;
        $valor_total += ($row['precio'] * $row['stock']);
        if ($row['stock'] < 10) {
            $stock_bajo_count++;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inventario - Sistema de Ventas</title>
<style>
* { box-sizing: border-box; }
body {
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    background-color: #f1f5f9;
    margin: 0;
    padding: 0;
    color: #1e293b;
}

.navbar {
    background-color: #0f172a;
    color: white;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.navbar-brand {
    font-size: 20px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 14px;
}

.user-badge {
    background: #334155;
    padding: 5px 12px;
    border-radius: 20px;
    font-weight: 600;
}

.btn-salir {
    background-color: #ef4444;
    color: white;
    text-decoration: none;
    padding: 7px 14px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 13px;
    transition: background-color 0.2s;
}

.btn-salir:hover {
    background-color: #dc2626;
}

.container {
    max-width: 1100px;
    margin: 30px auto;
    padding: 0 20px;
}

/* Tarjetas de Estadísticas */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border-left: 4px solid #3b82f6;
}

.stat-card.alert {
    border-left-color: #f59e0b;
}

.stat-card.success {
    border-left-color: #10b981;
}

.stat-title {
    font-size: 13px;
    color: #64748b;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 26px;
    font-weight: 700;
    color: #0f172a;
    margin-top: 5px;
}

/* Panel Principal de la Tabla */
.main-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    padding: 25px;
}

.header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #f1f5f9;
    padding-bottom: 15px;
    margin-bottom: 20px;
}

.header-actions h2 {
    margin: 0;
    font-size: 20px;
    color: #0f172a;
}

.btn-nuevo {
    background-color: #2563eb;
    color: white;
    text-decoration: none;
    padding: 10px 18px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: background-color 0.2s;
}

.btn-nuevo:hover {
    background-color: #1d4ed8;
}

/* Estilos de Tabla */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    font-size: 14px;
}

th, td {
    padding: 14px 16px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

th {
    background-color: #f8fafc;
    color: #475569;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
}

tr:hover {
    background-color: #f8fafc;
}

.badge-category {
    background-color: #e0f2fe;
    color: #0369a1;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.stock-ok {
    color: #166534;
    font-weight: 600;
}

.stock-bajo {
    color: #dc2626;
    font-weight: 700;
    background-color: #fef2f2;
    padding: 4px 8px;
    border-radius: 6px;
    display: inline-block;
}

.action-buttons {
    display: flex;
    gap: 8px;
    align-items: center;
}

.btn-editar {
    background-color: #fef3c7;
    color: #92400e;
    padding: 6px 12px;
    text-decoration: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s;
}

.btn-editar:hover {
    background-color: #f59e0b;
    color: white;
}

.btn-eliminar {
    background-color: #fee2e2;
    color: #991b1b;
    padding: 6px 12px;
    text-decoration: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s;
}

.btn-eliminar:hover {
    background-color: #ef4444;
    color: white;
}
</style>
</head>
<body>

<nav class="navbar">
    <div class="navbar-brand">
        <span>📦</span> Sistema de Inventario
    </div>
    <div class="user-info">
        <span>Usuario: <span class="user-badge"><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Usuario'); ?></span></span>
        <a href="logout.php" class="btn-salir">Cerrar Sesión</a>
    </div>
</nav>

<div class="container">

    <!-- Tarjetas informativas -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">Total de Productos</div>
            <div class="stat-value"><?php echo $total_productos; ?></div>
        </div>
        <div class="stat-card success">
            <div class="stat-title">Valor del Inventario</div>
            <div class="stat-value">$<?php echo number_format($valor_total, 2); ?></div>
        </div>
        <div class="stat-card alert">
            <div class="stat-title">Productos con Stock Bajo (&lt; 10)</div>
            <div class="stat-value"><?php echo $stock_bajo_count; ?></div>
        </div>
    </div>

    <!-- Catálogo de Productos -->
    <div class="main-card">
        <div class="header-actions">
            <h2>Catálogo de Inventario</h2>
            <a href="nuevo_producto.php" class="btn-nuevo">➕ Registrar Producto</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre del Producto</th>
                    <th>Categoría</th>
                    <th>Stock</th>
                    <th>Precio Unitario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($productos_data) > 0): ?>
                    <?php foreach ($productos_data as $fila): ?>
                        <tr>
                            <td><strong>#<?php echo htmlspecialchars($fila['id']); ?></strong></td>
                            <td><?php echo htmlspecialchars($fila['nombre_producto']); ?></td>
                            <td><span class="badge-category"><?php echo htmlspecialchars($fila['nombre_categoria']); ?></span></td>
                            <td>
                                <?php if ($fila['stock'] < 10): ?>
                                    <span class="stock-bajo">⚠️ <?php echo $fila['stock']; ?> unds.</span>
                                <?php else: ?>
                                    <span class="stock-ok"><?php echo $fila['stock']; ?> unds.</span>
                                <?php endif; ?>
                            </td>
                            <td><strong>$<?php echo number_format($fila['precio'], 2); ?></strong></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="editar_producto.php?id=<?php echo $fila['id']; ?>" class="btn-editar">✏️ Editar</a>
                                    <a href="eliminar_producto.php?id=<?php echo $fila['id']; ?>" 
                                       class="btn-eliminar"
                                       onclick="return confirm('¿Estás seguro de eliminar el producto: <?php echo htmlspecialchars($fila['nombre_producto'], ENT_QUOTES); ?>?');">
                                       🗑️ Eliminar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #64748b; padding: 30px;">
                            No hay productos registrados en el sistema.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>