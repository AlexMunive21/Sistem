<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

require_once '../config/db.php';

$user = $_SESSION['user'];

// Obtener conteo de usuarios activos e inactivos
$stmt = $pdo->query("SELECT status, COUNT(*) as total FROM users GROUP BY status");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$activos = 0;
$inactivos = 0;

foreach ($data as $row) {
    if ($row['status'] == 1) {
        $activos = $row['total'];
    } else {
        $inactivos = $row['total'];
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body.dark-mode {
            background-color: #121212;
            color: #fff;
        }
        .sidebar {
            height: 100vh;
            background-color: #0d6efd;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 1rem;
        }
        .sidebar a:hover {
            background-color: #0b5ed7;
        }
        .dark-mode .sidebar {
            background-color: #1f1f1f;
        }
        .dark-mode .sidebar a:hover {
            background-color: #333;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column p-2">
        <h4 class="text-white text-center">Sistema A</h4>
        <a href="dashboard.php"><i class="bi bi-house-door-fill"></i> Dashboard</a>
        <a href="profile.php"><i class="bi bi-person-fill"></i> Perfil</a>
        <a href="chat_users.php"><i class="bi bi-chat-dots-fill"></i> Chat</a>
        <?php if ($user['role'] == 1): ?>
            <a href="users.php"><i class="bi bi-people-fill"></i> Todos los usuarios</a>
        <?php endif; ?>

        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
        <hr class="bg-white">
        <div class="form-check form-switch text-white ms-2">
            <input class="form-check-input" type="checkbox" id="darkModeToggle">
            <label class="form-check-label" for="darkModeToggle">Modo Oscuro</label>
        </div>
    </div>

    <!-- Main content -->
    <div class="flex-grow-1 p-4" id="main-content">
        <h2>Bienvenido, <?php echo $user['name']; ?>!</h2>
        <p>Rol: <?php echo $user['role']; ?></p>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-chat-dots"></i> Chat</h5>
                        <p class="card-text">En desarrollo...</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-person-circle"></i> Perfil</h5>
                        <p class="card-text">Ver y editar tu información</p>
                        <a href="profile.php" class="btn btn-outline-primary btn-sm">Ver perfil</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-bar-chart-fill"></i> Usuarios Activos vs Inactivos</h5>
                        <canvas id="usuariosChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notificaciones -->
    <div class="position-relative mb-3">
        <br>
        <button id="notificationBtn" class="btn btn-light position-relative">
            <i class="bi bi-bell-fill"></i> Notificaciones
            <span id="notifBadge" class="position-absolute top-0 start-80 translate-middle badge rounded-pill bg-danger d-none">
                0
            </span>
        </button>
    </div>
</div>



<!-- JS Bootstrap + Dark Mode Logic -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const toggle = document.getElementById('darkModeToggle');
    const body = document.body;

    // Cargar preferencia
    if (localStorage.getItem('darkMode') === 'true') {
        toggle.checked = true;
        body.classList.add('dark-mode');
    }

    toggle.addEventListener('change', () => {
        if (toggle.checked) {
            body.classList.add('dark-mode');
            localStorage.setItem('darkMode', 'true');
        } else {
            body.classList.remove('dark-mode');
            localStorage.setItem('darkMode', 'false');
        }
    });
const ctx = document.getElementById('usuariosChart').getContext('2d');
const usuariosChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Activos', 'Inactivos'],
        datasets: [{
            label: 'Usuarios',
            data: [<?= $activos ?>, <?= $inactivos ?>],
            backgroundColor: ['#198754', '#dc3545'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
    const notifBtn = document.getElementById('notificationBtn');
    const notifBadge = document.getElementById('notifBadge');

    function checkMessages() {
        fetch('../controllers/check_new_messages.php')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.unread > 0) {
                    notifBadge.textContent = data.unread;
                    notifBadge.classList.remove('d-none');
                } else {
                    notifBadge.classList.add('d-none');
                }
            });
    }

    setInterval(checkMessages, 5000); // cada 5 segundos
    checkMessages();

    notifBtn.addEventListener('click', () => {
        window.location.href = 'chat_users.php'; // Redirige a lista de chats
    });


</script>


</body>
</html>
