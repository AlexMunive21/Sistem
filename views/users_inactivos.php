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

// Verifica si tiene permiso
if ($user['role'] != '1') {
    echo "Acceso denegado.";
    exit;
}

$stmt = $pdo->query("SELECT * FROM users WHERE status = 0");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios Inactivos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container">
    <h2>🛑 Usuarios Inactivos</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">→ Regresar al Dashboard</a>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td>
                    <a href="editar_usuario.php?id=<?= $u['id'] ?>" class="text-decoration-none">
                        <?= htmlspecialchars($u['name'] . ' ' . $u['last_name']) ?>
                    </a>
                </td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= $u['role'] ?></td>
                <td>
                    <a href="../controllers/restore_user.php?id=<?= $u['id'] ?>" class="btn btn-success btn-sm">
                        Restaurar
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
