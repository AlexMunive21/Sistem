<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user'])) {
    exit('No autorizado');
}

$sender_id = $_SESSION['user']['id'];
$receiver_id = $_POST['receiver_id'];
$message = trim($_POST['message']);

if ($message != '') {
    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->execute([$sender_id, $receiver_id, $message]);
}
