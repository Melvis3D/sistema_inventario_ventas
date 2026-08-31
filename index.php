<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Acceso al Sistema - Control de Inventario</title>
<style>
* { box-sizing: border-box; }
body {
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
    padding: 20px;
}
.login-card {
    background: #ffffff;
    padding: 40px 35px;
    border-radius: 16px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 8px 10px -6px rgba(0, 0, 0, 0.2);
    width: 100%;
    max-width: 400px;
}
.brand-logo {
    text-align: center;
    font-size: 42px;
    margin-bottom: 10px;
}
h2 {
    text-align: center;
    color: #0f172a;
    margin: 0 0 5px 0;
    font-size: 24px;
    font-weight: 700;
}
.subtitle {
    text-align: center;
    color: #64748b;
    font-size: 14px;
    margin-bottom: 25px;
}
.active-session-banner {
    background-color: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
    padding: 12px 15px;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 20px;
    text-align: center;
}
.active-session-banner a {
    color: #15803d;
    font-weight: 700;
    text-decoration: underline;
    margin-left: 5px;
}
.form-group {
    margin-bottom: 18px;
}
label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #334155;
    font-size: 14px;
}
input {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 15px;
    transition: all 0.2s ease;
    outline: none;
}
input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}
button {
    width: 100%;
    padding: 12px;
    background-color: #2563eb;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    margin-top: 10px;
    transition: background-color 0.2s ease;
}
button:hover {
    background-color: #1d4ed8;
}
.error {
    background-color: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
    padding: 10px 14px;
    border-radius: 8px;
    text-align: center;
    font-size: 14px;
    margin-bottom: 20px;
}
</style>
</head>
<body>
<div class="login-card">
<div class="brand-logo">📦</div>
<h2>Control de Inventario</h2>
<p class="subtitle">Ingrese sus credenciales para continuar</p>

<?php if (isset($_SESSION['user_id'])): ?>
<div class="active-session-banner">
    🟢 Sesión activa como <strong><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Usuario'); ?></strong>.
    <br><a href="inventario.php">Ir al Inventario →</a>
</div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
<div class="error">⚠️ Usuario o contraseña incorrectos.</div>
<?php endif; ?>

<form action="procesar_login.php" method="POST">
<div class="form-group">
<label for="usuario">Nombre de Usuario:</label>
<input type="text" name="usuario" id="usuario" required autocomplete="off" placeholder="Ej: admin">
</div>
<div class="form-group">
<label for="password">Contraseña:</label>
<input type="password" name="password" id="password" required placeholder="••••••••">
</div>
<button type="submit">Iniciar Sesión</button>
</form>
</div>
</body>
</html>