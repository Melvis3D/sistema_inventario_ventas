<?php
<<<<<<< HEAD
// Configuracion de las credenciales de la base de datos.
$host = "localhost";
$username = "root";
$password = ""; // Vacio por defecto en XAMPP.

// El SQL actualizado usa sistema_inventario_ventas. El segundo nombre queda
// como respaldo por si la base ya fue importada con el nombre anterior.
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
    error_log("Error de conexion MySQL: " . ($last_error ? $last_error->getMessage() : "desconocido"));
    die("Error critico: No se pudo establecer la conexion segura con el servidor de datos.");
}
?>
=======
// Configuración de las credenciales de la base de datos
$host = "localhost";
$db_name = "sistema_inventario_ventas";
$username = "root";
$password = ""; // Vacío por defecto en XAMPP

// Habilitar el reporte de errores de mysqli para usar excepciones (try...catch)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    // 1. Instanciar el objeto mysqli (Esto inicia la conexión)
$conn = new mysqli($host, $username, $password, $db_name);

// 2. Configurar el juego de caracteres a UTF-8 para admitir tildes y eñes
$conn->set_charset("utf8");

// Mensaje opcional para pruebas locales (Comentar en producción)
// echo "Conexión exitosa y segura al sistema de inventario.";

} catch (mysqli_sql_exception $e) {
// Captura el error y detiene el script con un mensaje controlado de seguridad
die("Error crítico: No se pudo establecer la conexión segura con el servidor de datos.");
}
?>
>>>>>>> 35d56efb3488faf776d157cdf95f7706d22a7b98
