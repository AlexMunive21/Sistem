<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

// Solo permitir acceso a administradores
if ($user['role'] != '1') {
    echo "Acceso denegado.";
    exit;
}

require_once '../config/db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
<div class="container">
    <h2>➕ Crear nuevo usuario</h2>
    <form action="../controllers/create_user.php" method="POST">
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Apellidos</label>
            <input type="text" name="last_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Correo</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Rol</label>
            <select name="role" class="form-control" required>
                <option value="">Selecciona un rol</option>
                <option value="1">Admin</option>
                <option value="2">Dirección</option>
                <option value="3">Subdirección</option>
                <option value="4">Gerencias</option>
                <option value="5">Coordinadores</option>
                <option value="6">Generalistas</option>
                <option value="7">Analista</option>
                <option value="8">Logística</option>
                <option value="9">Becarios</option>
            </select>
        </div>
        <button class="btn btn-success">Crear usuario</button>
    </form>
</div>
</body>
</html>
