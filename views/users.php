<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
require_once '../config/db.php';

$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container">
    <h2>👥 Lista de Usuarios</h2>
    <a href="create_user.php" class="btn btn-success my-3">+ Nuevo Usuario</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= $u['name'] ?></td>
                <td><?= $u['email'] ?></td>
                <td><?= $u['role'] ?></td>
                <td>
                    <a href="edit_user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                    <a href="../controllers/delete_user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar usuario?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>