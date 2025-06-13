<?php
require_once '../config/db.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = md5($_POST['password']);
$role = $_POST['role'];

$stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->execute([$name, $email, $password, $role]);

header("Location: ../views/users.php");
exit;
