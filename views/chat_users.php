<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$current_user = $_SESSION['user'];


// Obtener todos los usuarios excepto tú
$stmt = $pdo->prepare("SELECT id, name, last_name FROM users WHERE id != ?");
$stmt->execute([$current_user['id']]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<audio id="notifSound" src="https://notificationsounds.com/storage/sounds/file-sounds-1145-pristine.mp3" preload="auto"></audio>
<h4>Usuarios disponibles para chatear</h4>
<ul class="list-group">
    <?php foreach ($users as $user): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <?= htmlspecialchars($user['name'] . ' ' . $user['last_name']) ?>
            <a href="chat.php?to=<?= $user['id'] ?>" class="btn btn-primary btn-sm">Chatear</a>
        </li>
    <?php endforeach; ?>
</ul>
