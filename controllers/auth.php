<?php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Encriptamos con MD5

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND password = ? AND status = 1");
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user'] = $user;
        header("Location: ../views/dashboard.php");
        exit;
    } else {
        header("Location: ../views/login.php?error=1");
        exit;
    }
}
?>
