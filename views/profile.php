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
    <title>Perfil | ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">

<div class="container">
    <h2 class="mb-4">Perfil de Usuario</h2>
    <div class="card shadow p-4">
        <form action="../controllers/profile.php" method="POST" enctype="multipart/form-data">
            <div class="text-center mb-3">
                <img src="../uploads/<?php echo $user['photo'] ?: 'default.png'; ?>" alt="Foto" class="rounded-circle" width="120">
            </div>
            <div class="mb-3">
                <label>Nombre</label>
                <input type="text" name="name" class="form-control" value="<?php echo $user['name']; ?>" required>
            </div>
            <div class="mb-3">
                <label>Correo</label>
                <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label>Foto de perfil (opcional)</label>
                <input type="file" name="photo" class="form-control">
            </div>
            <button class="btn btn-primary">Guardar cambios</button>
        </form>
    </div>
</div>

</body>
</html>