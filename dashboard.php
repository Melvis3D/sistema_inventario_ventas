<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require_once 'conexion.php';

// 1. Total de productos en el catálogo
$res_total = $conn->query("SELECT COUNT(id) AS total FROM productos");
$total_productos = ($res_total) ? ($res_total->fetch_assoc()['total'] ?? 0) : 0;

// 2. Valor monetario total del inventario
$res_valor = $conn->query("SELECT SUM(precio * stock) AS valor_total FROM productos");
$valor_monetario = ($res_valor) ? ($res_valor->fetch_assoc()['valor_total'] ?? 0) : 0;

// 3. Precio del producto más caro
$res_max = $conn->query("SELECT MAX(precio) AS max_precio FROM productos");
$producto_mas_caro = ($res_max) ? ($res_max->fetch_assoc()['max_precio'] ?? 0) : 0;

// 4. Existencias totales de stock
$res_stock = $conn->query("SELECT SUM(stock) AS total_stock FROM productos");
$existencias_totales = ($res_stock) ? ($res_stock->fetch_assoc()['total_stock'] ?? 0) : 0;

// 5. Cantidad de productos con stock bajo (< 10)
$res_bajo = $conn->query("SELECT COUNT(id) AS bajo FROM productos WHERE stock < 10");
$stock_bajo = ($res_bajo) ? ($res_bajo->fetch_assoc()['bajo'] ?? 0) : 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Principal - Sistema de Inventario</title>
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
    margin: 35px auto;
    padding: 0 20px;
}

.welcome-header {
    background: white;
    padding: 25px 30px;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.welcome-text h1 {
    margin: 0 0 5px 0;
    font-size: 24px;
    color: #0f172a;
}

.welcome-text p {
    margin: 0;
    color: #64748b;
    font-size: 14px;
}

.btn-ir-inventario {
    background-color: #2563eb;
    color: white;
    text-decoration: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 15px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
}

.btn-ir-inventario:hover {
    background-color: #1d4ed8;
    transform: translateY(-1px);
}

/* Tarjetas Numéricas */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 20px;
    margin-bottom: 35px;
}

.metric-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    position: relative;
    overflow: hidden;
    transition: transform 0.2s ease;
}

.metric-card:hover {
    transform: translateY(-3px);
}

.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 5px;
    height: 100%;
    background-color: #2563eb;
}

.metric-card.green::before { background-color: #10b981; }
.metric-card.purple::before { background-color: #8b5cf6; }
.metric-card.amber::before { background-color: #f59e0b; }
.metric-card.red::before { background-color: #ef4444; }

.metric-icon {
    font-size: 28px;
    margin-bottom: 12px;
}

.metric-title {
    font-size: 13px;
    color: #64748b;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.metric-value {
    font-size: 28px;
    font-weight: 800;
    color: #0f172a;
    margin-top: 6px;
}

.status-badge {
    display: inline-block;
    margin-top: 10px;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    background-color: #f1f5f9;
    color: #475569;
}
</style>
</head>
<body>

<nav class="navbar">
    <div class="navbar-brand">
        <span>📊</span> Panel de Control - Sistema de Inventario
    </div>
    <div class="user-info">
        <span>Bienvenido: <span class="user-badge"><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Usuario'); ?></span></span>
        <a href="logout.php" class="btn-salir">Cerrar Sesión</a>
    </div>
</nav>

<div class="container">

    <!-- Encabezado Principal -->
    <div class="welcome-header">
        <div class="welcome-text">
            <h1>¡Hola, <?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Usuario'); ?>! 👋</h1>
            <p>Resumen estadístico del sistema en tiempo real.</p>
        </div>
        <a href="inventario.php" class="btn-ir-inventario">
            📦 Ir al Catálogo de Inventario →
        </a>
    </div>

    <!-- Grid de Tarjetas Numéricas -->
    <div class="dashboard-grid">
        
        <!-- Tarjeta 1: Total Productos -->
        <div class="metric-card">
            <div class="metric-icon">📦</div>
            <div class="metric-title">Productos Registrados</div>
            <div class="metric-value"><?php echo number_format($total_productos); ?></div>
            <div class="status-badge">Catálogo Activo</div>
        </div>

        <!-- Tarjeta 2: Valor Total -->
        <div class="metric-card green">
            <div class="metric-icon">💰</div>
            <div class="metric-title">Valor Total Inventario</div>
            <div class="metric-value">$<?php echo number_format($valor_monetario, 2); ?></div>
            <div class="status-badge">Cálculo en Vivo</div>
        </div>

        <!-- Tarjeta 3: Producto Más Caro -->
        <div class="metric-card purple">
            <div class="metric-icon">🏷️</div>
            <div class="metric-title">Producto Más Caro</div>
            <div class="metric-value">$<?php echo number_format($producto_mas_caro, 2); ?></div>
            <div class="status-badge">Precio Máximo</div>
        </div>

        <!-- Tarjeta 4: Stock Total -->
        <div class="metric-card amber">
            <div class="metric-icon">📊</div>
            <div class="metric-title">Existencias Totales</div>
            <div class="metric-value"><?php echo number_format($existencias_totales); ?> unds.</div>
            <div class="status-badge">Unidades en Almacén</div>
        </div>

        <!-- Tarjeta 5: Stock Bajo -->
        <div class="metric-card red">
            <div class="metric-icon">⚠️</div>
            <div class="metric-title">Alertas de Stock Bajo</div>
            <div class="metric-value"><?php echo number_format($stock_bajo); ?></div>
            <div class="status-badge">&lt; 10 unidades</div>
        </div>

    </div>

</div>

</body>
</html>