<?php
// Iniciar el motor de sesiones de PHP para recordar al usuario.
session_start();

// Incluir la conexion oficial de MySQLi.
require_once 'conexion.php';

// Validar que la informacion provenga del formulario POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password'] ?? '');

    try {
        $sql = "SELECT id, nombre_completo, password, rol FROM usuarios WHERE usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            // Acepta contraseñas con password_hash y tambien registros antiguos en texto plano.
            $passwordCorrecta = password_verify($password, $row['password'])
                || ($row['password'] === $password);

            if ($passwordCorrecta) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['nombre'] = $row['nombre_completo'];
                $_SESSION['rol'] = $row['rol'];

                header("Location: inventario.php");
                exit();
            }
        }

        $stmt->close();
        header("Location: index.php?error=1");
        exit();
    } catch (mysqli_sql_exception $e) {
        error_log("Error de autenticacion: " . $e->getMessage());
        die("Error de autenticación en el servidor.");
    }
}

header("Location: index.php");
exit();
?>
