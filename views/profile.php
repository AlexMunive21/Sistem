<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
        .form-label {
            font-weight: bold;
        }
        body.dark-mode {
        background-color: #121212 !important;
        color: #f1f1f1 !important;
        }
        .dark-mode .card, 
        .dark-mode .table,
        .dark-mode .form-control {
            background-color: #1e1e1e !important;
            color: #f1f1f1 !important;
            border-color: #333 !important;
        }
        .dark-mode .table thead {
            background-color: #2c2c2c !important;
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
    <?php if ($user['role'] == 1): ?>
        <a href="users.php"><i class="bi bi-people-fill"></i> Usuarios</a>
    <?php endif; ?>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
    <div class="form-check form-switch text-white mt-auto mx-2">
        <input class="form-check-input" type="checkbox" id="themeSwitch">
        <label class="form-check-label" for="themeSwitch">Modo oscuro</label>
    </div>
</div>

<!-- Main Content -->
<div class="flex-grow-1 p-4">
    <h2 class="mb-4"><i class="bi bi-person-circle"></i> Perfil de Usuario</h2>

    <div class="card shadow p-4">
        <form action="../controllers/profile.php" method="POST" enctype="multipart/form-data">
            <div class="text-center mb-4">
                <img src="../uploads/<?= htmlspecialchars($_SESSION['user']['photo'] ?? 'default-profile.png') ?>" alt="Tu foto" />
                <h5 class="mt-3"><?php echo htmlspecialchars($user['name']); ?></h5>
            </div>

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="mb-3 col-md-6">
                    <label class="form-label">Apellidos</label>
                    <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                </div>
                <div class="mb-3 col-md-6">
                    <label class="form-label">Correo</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Foto de perfil (opcional)</label>
                <input type="file" name="photo" class="form-control">
            </div>

            <div class="text-end">
                <button class="btn btn-primary"><i class="bi bi-save"></i> Guardar cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Aplicar modo desde localStorage al cargar
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
        document.getElementById('themeSwitch').checked = true;
    }

    // Toggle de modo oscuro/claro
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
