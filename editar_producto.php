<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require_once 'conexion.php';

// Validar que se reciba un ID entero válido por GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: inventario.php");
    exit();
}

$id_producto = intval($_GET['id']);

// 1. Consultar los datos actuales del producto
$sql = "SELECT id, nombre_producto, categoria_id, stock, precio FROM productos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$resultado = $stmt->get_result();

// Si el producto no existe en la BD, redirigir al inventario
if ($resultado->num_rows === 0) {
    $stmt->close();
    header("Location: inventario.php");
    exit();
}

$producto = $resultado->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Producto #<?php echo $producto['id']; ?> - Sistema de Inventario</title>
<style>
* { box-sizing: border-box; }
body {
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    background-color: #f1f5f9;
    margin: 0;
    padding: 40px 20px;
    color: #1e293b;
}

.container {
    max-width: 550px;
    margin: 0 auto;
    background: white;
    padding: 35px;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
}

.btn-volver {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 20px;
    color: #2563eb;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: color 0.2s;
}

.btn-volver:hover {
    color: #1d4ed8;
}

h2 {
    color: #0f172a;
    margin: 0 0 25px 0;
    font-size: 22px;
    border-bottom: 2px solid #f1f5f9;
    padding-bottom: 12px;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #334155;
    font-size: 14px;
}

input, select {
    width: 100%;
    padding: 12px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 15px;
    outline: none;
    transition: all 0.2s;
}

input:focus, select:focus {
    border-color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15);
}

.btn-guardar {
    width: 100%;
    padding: 12px;
    background-color: #f59e0b;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 10px;
    font-weight: 600;
    transition: background-color 0.2s;
}

.btn-guardar:hover {
    background-color: #d97706;
}

.btn-cancelar {
    display: block;
    text-align: center;
    margin-top: 15px;
    color: #64748b;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
}

.btn-cancelar:hover {
    color: #334155;
}
</style>
</head>
<body>

<div class="container">
    <a href="inventario.php" class="btn-volver">← Volver al Inventario</a>
    <h2>✏️ Editar Producto #<?php echo htmlspecialchars($producto['id']); ?></h2>

    <form action="actualizar_producto.php" method="POST">
        <!-- Campo oculto indispensable con el ID del producto -->
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($producto['id']); ?>">

        <div class="form-group">
            <label for="nombre">Nombre del Producto:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($producto['nombre_producto']); ?>" required autocomplete="off">
        </div>

        <div class="form-group">
            <label for="categoria">Categoría:</label>
            <select id="categoria" name="categoria" required>
                <option value="">-- Seleccione una categoría --</option>
                <?php
                $sql_cat = "SELECT id, nombre_categoria FROM categorias ORDER BY nombre_categoria ASC";
                $res_cat = $conn->query($sql_cat);
                if ($res_cat && $res_cat->num_rows > 0) {
                    while($cat = $res_cat->fetch_assoc()) {
                        $selected = ($cat['id'] == $producto['categoria_id']) ? "selected" : "";
                        echo "<option value='" . htmlspecialchars($cat['id']) . "' $selected>" . htmlspecialchars($cat['nombre_categoria']) . "</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="stock">Cantidad (Stock):</label>
            <input type="number" id="stock" name="stock" min="0" value="<?php echo htmlspecialchars($producto['stock']); ?>" required>
        </div>

        <div class="form-group">
            <label for="precio">Precio Unitario ($):</label>
            <input type="number" id="precio" name="precio" step="0.01" min="0.01" value="<?php echo htmlspecialchars($producto['precio']); ?>" required>
        </div>

        <button type="submit" class="btn-guardar">💾 Guardar Cambios</button>
        <a href="inventario.php" class="btn-cancelar">Cancelar</a>
    </form>
</div>

</body>
</html>