<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../views/login.php");
    exit;
}

$id = $_SESSION['user']['id'];
$name = $_POST['name'];
$email = $_POST['email'];

// Manejo de imagen
$photo = $_SESSION['user']['photo'];
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
    $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $newName = uniqid() . "." . $ext;
    move_uploaded_file($_FILES['photo']['tmp_name'], "../uploads/" . $newName);
    $photo = $newName;
}

$stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, photo = ? WHERE id = ?");
$stmt->execute([$name, $email, $photo, $id]);

// Actualiza sesión
$_SESSION['user']['name'] = $name;
$_SESSION['user']['email'] = $email;
$_SESSION['user']['photo'] = $photo;

header("Location: ../views/profile.php");
exit;
?>