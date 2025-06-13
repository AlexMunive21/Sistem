<?php
require_once '../config/db.php';

if (!isset($_GET['id'])) exit;

$id = $_GET['id'];
$stmt = $pdo->prepare("UPDATE users SET status = 1 WHERE id = ?");
$stmt->execute([$id]);

header("Location: ../views/users_inactivos.php");
exit;
