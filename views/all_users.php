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


// Solo permitir a roles con permiso (por ejemplo admin = 1)
if ($user['role'] != '1') {
    echo "Acceso denegado.";
    exit;
}

// Obtener todos los usuarios
$stmt = $pdo->query("SELECT * FROM users ORDER BY status DESC, role ASC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Todos los usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body.dark-mode {
            background-color: #121212;
            color: #fff;
        }
        .user-card {
            transition: transform 0.2s;
        }
        .user-card:hover {
            transform: scale(1.02);
        }
    </style>
</head>
<body class="p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>👥 Todos los usuarios</h2>
        <a href="dashboard.php" class="btn btn-secondary">← Volver al Dashboard</a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <a href="users_activos"><h4 class="text-success">🟢 Activos</h4></a>
            <?php foreach ($usuarios as $u): ?>
                <?php if ($u['status'] == 1): ?>
                    <div class="card mb-3 user-card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($u['name'] . ' ' . $u['last_name']) ?></h5>
                            <p class="card-text">
                                <strong>Email:</strong> <?= htmlspecialchars($u['email']) ?><br>
                                <strong>Rol:</strong> <?= $u['role'] ?>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <div class="col-md-6">
            <a href="users_inactivos.php"><h4 class="text-danger">🔴 Inactivos</h4></a>
            <?php foreach ($usuarios as $u): ?>
                <?php if ($u['status'] == 0): ?>
                    <div class="card mb-3 user-card shadow-sm border-danger">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($u['name'] . ' ' . $u['last_name']) ?></h5>
                            <p class="card-text">
                                <strong>Email:</strong> <?= htmlspecialchars($u['email']) ?><br>
                                <strong>Rol:</strong> <?= $u['role'] ?>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>
