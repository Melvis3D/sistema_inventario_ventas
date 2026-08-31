<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require_once 'conexion.php';

if (isset($_GET['id'])) {
    $id_producto = intval($_GET['id']);
    
    if ($id_producto > 0) {
        try {
            $sql = "DELETE FROM productos WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_producto);
            $stmt->execute();
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            error_log("Error al eliminar producto: " . $e->getMessage());
            die("Error al intentar eliminar el registro.");
        }
    }
}

header("Location: inventario.php");
exit();
?>