<?php
// Configuración de las credenciales de la base de datos.
$host = "localhost";
$username = "root";
$password = ""; // Vacío por defecto en XAMPP.

$database_names = [
    "sistema_inventario_ventas",
    "sistema_inventario",
];

// Habilitar el reporte de errores de mysqli para usar excepciones.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = null;
$last_error = null;

foreach ($database_names as $db_name) {
    try {
        $conn = new mysqli($host, $username, $password, $db_name);
        $conn->set_charset("utf8mb4");
        break;
    } catch (mysqli_sql_exception $e) {
        $last_error = $e;
        $conn = null;
    }
}

if (!$conn) {
    error_log("Error de conexión MySQL: " . ($last_error ? $last_error->getMessage() : "desconocido"));
    die("Error crítico: No se pudo establecer la conexión segura con el servidor de datos.");
}
?>
