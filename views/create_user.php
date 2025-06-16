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
    <title>➕ Crear nuevo usuario</title>
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
        .dark-mode .form-control,
        .dark-mode .form-select {
            background-color: #1e1e1e !important;
            color: #f1f1f1 !important;
            border-color: #333 !important;
        }
        .dark-mode .form-select option {
            background-color: #1e1e1e;
            color: #f1f1f1;
        }
        .toggle-theme {
            cursor: pointer;
        }
        img.profile-photo {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #fff;
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
    <h2 class="mb-4"><i class="bi bi-person-plus-fill"></i> Crear nuevo usuario</h2>

    <div class="card shadow p-4">
        <form action="../controllers/create_user.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3 col-md-6">
                    <label class="form-label">Apellidos</label>
                    <input type="text" name="last_name" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Correo</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Rol</label>
                <select name="role" class="form-select" required>
                    <option value="" disabled selected>Selecciona un rol</option>
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

            <div class="text-end">
                <button class="btn btn-success"><i class="bi bi-person-plus"></i> Crear usuario</button>
            </div>
        </form>
    </div>
</div>

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
