<?php
require_once '../config/db.php';

$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];

if (!empty($password)) {
    $hashed = md5($password);
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ?, role = ? WHERE id = ?");
    $stmt->execute([$name, $email, $hashed, $role, $id]);
} else {
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
    $stmt->execute([$name, $email, $role, $id]);
}

header("Location: ../views/users.php");
exit;
