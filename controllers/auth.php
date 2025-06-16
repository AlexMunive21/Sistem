<?php
session_start();
require_once '../config/db.php';

$email = $_POST['email'];
$password = md5($_POST['password']); // Asegúrate de que esté encriptado igual en la base

// Buscar usuario activo
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND password = ? AND status = 1 LIMIT 1");
$stmt->execute([$email, $password]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // Generar session_id único
    $session_id = bin2hex(random_bytes(32));

    // Guardar session_id y fecha de login
    $update = $pdo->prepare("UPDATE users SET session_id = ?, last_login = NOW() WHERE id = ?");
    $update->execute([$session_id, $user['id']]);

    // Actualizar también en la sesión
    $user['session_id'] = $session_id;
    $_SESSION['user'] = $user;

    header("Location: ../views/dashboard.php");
    exit;
} else {
    header("Location: ../views/login.php?error=1");
    exit;
}
