<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    exit('No autorizado');
}

$current_user = $_SESSION['user'];
$receiver_id = $_GET['to'] ?? null;

if (!$receiver_id) {
    exit('No se especificó usuario receptor');
}

$stmt = $pdo->prepare("SELECT m.*, u.name AS sender_name, u.photo AS photo FROM messages m 
    JOIN users u ON m.sender_id = u.id 
    WHERE (m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?) 
    ORDER BY m.created_at ASC");
$stmt->execute([$current_user['id'], $receiver_id, $receiver_id, $current_user['id']]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($messages as $msg) {
    $isSent = ($msg['sender_id'] == $current_user['id']);
    $classMsg = $isSent ? 'sent' : 'received';
    $senderName = $isSent ? 'Tú' : htmlspecialchars($msg['sender_name']);
    $senderPhoto = $msg['photo'] ?: 'default-profile.png';

    echo '<div class="message ' . $classMsg . '" role="article" aria-label="' . $senderName . ' dice: ' . htmlspecialchars($msg['message']) . '">';
    echo '<img class="profile-pic" src="' . htmlspecialchars($senderPhoto) . '" alt="Foto de ' . $senderName . '">';
    echo '<div class="bubble">' . nl2br(htmlspecialchars($msg['message'])) . '</div>';
    echo '</div>';
}
?>
