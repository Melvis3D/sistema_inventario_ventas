<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar y sanitizar datos del formulario (incluyendo el ID oculto)
    $id_producto = intval($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $categoria_id = intval($_POST['categoria'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $precio = floatval($_POST['precio'] ?? 0);

    if ($id_producto > 0 && !empty($nombre) && $categoria_id > 0 && $stock >= 0 && $precio > 0) {
        try {
            // Sentencia UPDATE con marcadores (?) para prevenir Inyección SQL
            $sql = "UPDATE productos SET nombre_producto = ?, categoria_id = ?, stock = ?, precio = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            
            // Vincular parámetros: string, int, int, double, int ("siidi")
            $stmt->bind_param("siidi", $nombre, $categoria_id, $stock, $precio, $id_producto);
            $stmt->execute();
            $stmt->close();

            header("Location: inventario.php");
            exit();
        } catch (mysqli_sql_exception $e) {
            error_log("Error al actualizar producto: " . $e->getMessage());
            die("Error al actualizar el producto en la base de datos.");
        }
    }
}

header("Location: inventario.php");
exit();
?>