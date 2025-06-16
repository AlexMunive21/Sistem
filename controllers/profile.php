<?php
ob_start();
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$user = $_SESSION['user'];
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$lastName = $_POST['last_name'] ?? ''; // asegúrate de tener este campo en el formulario si lo usas

$photoName = $user['photo'];

if (!empty($_FILES['photo']['name'])) {
    $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $photoName = uniqid() . "." . $ext;
    move_uploaded_file($_FILES['photo']['tmp_name'], "../uploads/" . $photoName);
}

// Actualizar en la base de datos
$stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, last_name = ?, photo = ? WHERE id = ?");
$stmt->execute([$name, $email, $lastName, $photoName, $user['id']]);

// Actualizar sesión
$_SESSION['user']['name'] = $name;
$_SESSION['user']['email'] = $email;
$_SESSION['user']['last_name'] = $lastName;
$_SESSION['user']['photo'] = $photoName;

header("Location: ../views/profile.php");
ob_end_flush();
