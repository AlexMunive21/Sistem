<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$current_user = $_SESSION['user'];
$receiver_id = $_GET['to']; // ID del usuario con quien quieres chatear

// Obtener mensajes
$stmt = $pdo->prepare("SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY created_at ASC");
$stmt->execute([$current_user['id'], $receiver_id, $receiver_id, $current_user['id']]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="p-4 bg-light">

<h4>Chat con <?= $receiver_id ?></h4> //Nombre de la persona con la que estás chateando

<div id="chatBox" style="height: 400px; overflow-y: scroll;" class="border p-2 bg-white mb-3">
    <?php foreach ($messages as $msg): ?>
        <div class="<?= $msg['sender_id'] == $current_user['id'] ? 'text-end' : 'text-start' ?>">
            <p class="mb-1">
                <strong><?= $msg['sender_id'] == $current_user['id'] ? 'Tú' : 'Usuario ' . $msg['sender_id'] ?></strong>: <?= htmlspecialchars($msg['message']) ?>
            </p>
        </div>
    <?php endforeach; ?>
</div>

<form id="chatForm" method="POST">
    <input type="hidden" name="receiver_id" value="<?= $receiver_id ?>">
    <div class="input-group">
        <input type="text" name="message" class="form-control" placeholder="Escribe un mensaje..." required>
        <button class="btn btn-primary" type="submit">Enviar</button>
    </div>
</form>

<script>
    const chatForm = document.getElementById('chatForm');
    const chatBox = document.getElementById('chatBox');

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(chatForm);
        fetch('../controllers/send_message.php', {
            method: 'POST',
            body: formData
        }).then(() => {
            chatForm.reset();
            loadMessages();
        });
    });

    let previousLength = 0;

    function loadMessages() {
        fetch('load_messages.php?to=<?= $receiver_id ?>')
            .then(res => res.text())
            .then(html => {
                const newLength = html.length;
                if (newLength > previousLength) {
                    document.getElementById('notifSound').play();
                }
                previousLength = newLength;
                chatBox.innerHTML = html;
                chatBox.scrollTop = chatBox.scrollHeight;
            });
    }


    // Cargar mensajes cada 3 segundos
    setInterval(loadMessages, 3000);
</script>

</body>
</html>
