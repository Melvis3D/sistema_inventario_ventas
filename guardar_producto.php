<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_producto = trim($_POST['nombre'] ?? '');
    $categoria_id = intval($_POST['categoria'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $precio = floatval($_POST['precio'] ?? 0);

    if (!empty($nombre_producto) && $categoria_id > 0 && $stock >= 0 && $precio > 0) {
        try {
            $sql = "INSERT INTO productos (nombre_producto, categoria_id, stock, precio) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("siid", $nombre_producto, $categoria_id, $stock, $precio);
            $stmt->execute();
            $stmt->close();

            header("Location: inventario.php");
            exit();
        } catch (mysqli_sql_exception $e) {
            error_log("Error al guardar producto: " . $e->getMessage());
            die("Error al registrar el producto en la base de datos.");
        }
    }
}

header("Location: inventario.php");
exit();
?>