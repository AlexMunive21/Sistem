<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit;
}

$error = $_GET['error'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg,rgb(0, 0, 0),rgb(126, 126, 126));
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            animation: fadeIn 0.8s ease-in-out;
        }

        .login-card h2 {
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px;
        }

        .btn-login {
            background-color:rgb(160, 159, 163);
            color: #fff;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-login:hover {
            background-color:rgb(114, 111, 121);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-text {
            text-align: center;
            color: #777;
            margin-top: 15px;
        }

        .spinner-border {
            display: none;
            width: 1.5rem;
            height: 1.5rem;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h2><i class="bi bi-shield-lock-fill"></i> Iniciar sesión</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <form action="../controllers/auth.php" method="POST" onsubmit="handleSubmit(event)">
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" name="email" class="form-control" placeholder="ejemplo@correo.com" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" placeholder="********" required>
        </div>
        <button id="btnLogin" type="submit" class="btn btn-login w-100 d-flex justify-content-center align-items-center">
            <span class="me-2">Iniciar sesión</span>
            <div id="spinner" class="spinner-border text-light" role="status"></div>
        </button>
    </form>
    <div class="form-text">
        ¿Olvidaste tu contraseña?
    </div>
</div>

<script>
    function handleSubmit(e) {
        const btn = document.getElementById('btnLogin');
        const spinner = document.getElementById('spinner');
        btn.disabled = true;
        spinner.style.display = 'inline-block';
    }
    if (window.location.search.includes('error=')) {
        const url = new URL(window.location);
        url.searchParams.delete('error');
        window.history.replaceState({}, document.title, url.toString());
    }

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
