<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user'])) exit;

$current_user = $_SESSION['user'];
$receiver_id = $_GET['to'];

$stmt = $pdo->prepare("SELECT * FROM messages WHERE 
    (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
    ORDER BY created_at ASC");
$stmt->execute([$current_user['id'], $receiver_id, $receiver_id, $current_user['id']]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($messages as $msg) {
    echo '<div class="' . ($msg['sender_id'] == $current_user['id'] ? 'text-end' : 'text-start') . '">';
    echo '<p class="mb-1"><strong>' . ($msg['sender_id'] == $current_user['id'] ? 'Tú' : 'Usuario ' . $msg['sender_id']) . '</strong>: ' . htmlspecialchars($msg['message']) . '</p>';
    echo '</div>';
}
