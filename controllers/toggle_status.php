<?php
require_once '../config/db.php';

$id = $_GET['id'];
$newStatus = $_GET['status'];

$stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
$stmt->execute([$newStatus, $id]);

header("Location: ../views/users.php");
exit;
