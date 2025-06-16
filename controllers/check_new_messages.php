<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false]);
    exit;
}

$currentUserId = $_SESSION['user']['id'];

// Buscar mensajes no leídos dirigidos a este usuario
$stmt = $pdo->prepare("SELECT COUNT(*) AS unread FROM messages WHERE receiver_id = ? AND seen = 0");
$stmt->execute([$currentUserId]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'unread' => $result['unread']]);
