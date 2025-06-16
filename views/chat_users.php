<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$current_user = $_SESSION['user'];

// Obtener todos los usuarios excepto tú
$stmt = $pdo->prepare("SELECT id, name, last_name FROM users WHERE id != ?");
$stmt->execute([$current_user['id']]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Chat - Usuarios disponibles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        .sidebar {
            width: 220px;
            min-height: 100vh;
            background-color: #343a40;
        }
        .sidebar a {
            padding: 10px;
            color: #fff;
            text-decoration: none;
            display: block;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        body.dark-mode {
            background-color: #121212 !important;
            color: #f1f1f1 !important;
        }
        .dark-mode .card,
        .dark-mode .list-group-item {
            background-color: #1e1e1e !important;
            color: #f1f1f1 !important;
            border-color: #333 !important;
        }
        .toggle-theme {
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-light d-flex">

<!-- Sidebar -->
<div class="sidebar d-flex flex-column p-2">
    <h4 class="text-white text-center"><i class="bi bi-person-gear"></i> Admin</h4>
    <a href="dashboard.php"><i class="bi bi-house-door-fill"></i> Dashboard</a>
    <a href="profile.php"><i class="bi bi-person-fill"></i> Perfil</a>
    <a href="chat_users.php"><i class="bi bi-chat-dots-fill"></i> Chat</a>
    <?php if ($current_user['role'] == 1): ?>
        <a href="users.php"><i class="bi bi-people-fill"></i> Usuarios</a>
    <?php endif; ?>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
    <div class="form-check form-switch text-white mt-auto mx-2">
        <input class="form-check-input" type="checkbox" id="themeSwitch" />
        <label class="form-check-label" for="themeSwitch">Modo oscuro</label>
    </div>
</div>

<!-- Main Content -->
<div class="flex-grow-1 p-4">
    <h4 class="mb-4"><i class="bi bi-chat-dots-fill"></i> Usuarios disponibles para chatear</h4>

    <div class="card shadow p-3">
        <ul class="list-group">
            <?php foreach ($users as $user): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($user['name'] . ' ' . $user['last_name']) ?>
                    <a href="chat.php?to=<?= $user['id'] ?>" class="btn btn-primary btn-sm">Chatear</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<audio id="notifSound" src="https://notificationsounds.com/storage/sounds/file-sounds-1145-pristine.mp3" preload="auto"></audio>

<script>
    // Aplicar modo oscuro desde localStorage al cargar la página
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
        document.getElementById('themeSwitch').checked = true;
    }

    // Toggle modo oscuro
    document.getElementById('themeSwitch').addEventListener('change', function () {
        if (this.checked) {
            document.body.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark');
        } else {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('theme', 'light');
        }
    });
</script>

</body>
</html>
