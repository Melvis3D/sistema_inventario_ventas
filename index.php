<<<<<<< HEAD
<?php
session_start();
?>
=======
>>>>>>> 35d56efb3488faf776d157cdf95f7706d22a7b98
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
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
=======
<title>Acceso al Sistema - Instituto Nacional de Ciudad Barrios</title>
<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color:
#f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
.login-card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px
15px rgba(0,0,0,0.1); width: 100%; max-width: 350px; }
h2 { text-align: center; color: #1e3a8a; margin-bottom: 20px; }
.form-group { margin-bottom: 15px; }
label { display: block; margin-bottom: 5px; font-weight: bold; color: #4b5563; }

input { width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 5px; box-
sizing: border-box; }

button { width: 100%; padding: 10px; background-color: #1e3a8a; color: white; border:
none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-top: 10px; }
button:hover { background-color: #1d4ed8; }
.error { color: #dc2626; text-align: center; font-size: 14px; margin-bottom: 10px; }
>>>>>>> 35d56efb3488faf776d157cdf95f7706d22a7b98
</style>
</head>
<body>
<div class="login-card">
<<<<<<< HEAD
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
=======
<h2>Control de Inventario</h2>

<?php if (isset($_GET['error'])): ?>
<div class="error">Usuario o contraseña incorrectos.</div>
>>>>>>> 35d56efb3488faf776d157cdf95f7706d22a7b98
<?php endif; ?>

<form action="procesar_login.php" method="POST">
<div class="form-group">
<label for="usuario">Nombre de Usuario:</label>
<<<<<<< HEAD
<input type="text" name="usuario" id="usuario" required autocomplete="off" placeholder="Ej: admin">
</div>
<div class="form-group">
<label for="password">Contraseña:</label>
<input type="password" name="password" id="password" required placeholder="••••••••">
=======
<input type="text" name="usuario" id="usuario" required autocomplete="off">
</div>
<div class="form-group">
<label for="password">Contraseña:</label>
<input type="password" name="password" id="password" required>
>>>>>>> 35d56efb3488faf776d157cdf95f7706d22a7b98
</div>
<button type="submit">Iniciar Sesión</button>
</form>
</div>
</body>
</html>