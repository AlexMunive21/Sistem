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

$user = $_SESSION['user'];


$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Usuario no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container">
    <h2>✏️ Editar Usuario</h2>
    <form action="../controllers/update_user.php" method="POST">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="name" value="<?= $user['name'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Correo</label>
            <input type="email" name="email" value="<?= $user['email'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Contraseña (solo si deseas cambiarla)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label>Rol</label>
            <select name="role" class="form-control">
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="usuario" <?= $user['role'] === 'usuario' ? 'selected' : '' ?>>Usuario</option>
            </select>
        </div>
        <button class="btn btn-primary">Guardar cambios</button>
    </form>
</div>

</body>
</html>
