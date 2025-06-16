<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
if ($user['role'] != '1') {
    echo "Acceso denegado.";
    exit;
}

$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
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
    </style>
</head>
<body class="bg-light d-flex">

<!-- Sidebar -->
<div class="sidebar d-flex flex-column p-2">
    <h4 class="text-white text-center"><i class="bi bi-person-gear"></i> Admin</h4>
    <a href="dashboard.php"><i class="bi bi-house-door-fill"></i> Dashboard</a>
    <a href="profile.php"><i class="bi bi-person-fill"></i> Perfil</a>
    <a href="chat_users.php"><i class="bi bi-chat-dots-fill"></i> Chat</a>
    <a href="users.php"><i class="bi bi-people-fill"></i> Usuarios</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
</div>

<!-- Main Content -->
<div class="flex-grow-1 p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-people-fill"></i> Lista de Usuarios</h2>
        <a href="create_user.php" class="btn btn-success"><i class="bi bi-plus-lg"></i> Nuevo Usuario</a>
    </div>

    <!-- Filtro de estado -->
    <div class="mb-3">
        <label for="statusFilter" class="form-label">Filtrar por estado:</label>
        <select id="statusFilter" class="form-select w-auto">
            <option value="todos">Todos</option>
            <option value="1">Activos</option>
            <option value="0">Inactivos</option>
        </select>
    </div>

    
    <table class="table table-bordered table-hover" id="myTable">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
            <tr data-status="<?= $u['status'] ?>">
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td><?= htmlspecialchars($u['last_name'])?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= $u['role'] ?></td>
                <td>
                    <?php if ($u['status'] == 1): ?>
                        <span class="badge bg-success">Activo</span>
                        <a href="../controllers/toggle_status.php?id=<?= $u['id'] ?>&status=0" class="btn btn-sm btn-warning ms-2">Desactivar</a>
                    <?php else: ?>
                        <span class="badge bg-danger">Inactivo</span>
                        <a href="../controllers/toggle_status.php?id=<?= $u['id'] ?>&status=1" class="btn btn-sm btn-success ms-2">Activar</a>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit_user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> </a>
                    <a href="../controllers/delete_user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar usuario?')"><i class="bi bi-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- JS para filtro -->
<script>
    document.getElementById('statusFilter').addEventListener('change', function () {
        const selected = this.value;
        document.querySelectorAll('#userTable tbody tr').forEach(row => {
            const status = row.getAttribute('data-status');
            if (selected === 'todos' || selected === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    var tabla = document.querySelector("#myTable");
        var dataTable = new DataTable(tabla);
</script>


<!-- jQuery (obligatorio para DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    // Filtro por estado
    document.getElementById('statusFilter').addEventListener('change', function () {
        const selected = this.value;
        document.querySelectorAll('#myTable tbody tr').forEach(row => {
            const status = row.getAttribute('data-status');
            if (selected === 'todos' || selected === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Inicializa DataTables
    $(document).ready(function() {
        $('#myTable').DataTable({
            language: {
                search: "Buscar:",
                lengthMenu: "Mostrar _MENU_ usuarios",
                info: "Mostrando _START_ a _END_ de _TOTAL_ usuarios",
                paginate: {
                    first:    "Primero",
                    last:     "Último",
                    next:     "→",
                    previous: "←"
                },
                zeroRecords: "No se encontraron usuarios"
            }
        });
    });
</script>


</body>
</html>
