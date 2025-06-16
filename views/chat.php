<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$current_user = $_SESSION['user'];
$receiver_id = $_GET['to'] ?? null;

if (!$receiver_id) {
    die("No se especificó el usuario con quien chatear.");
}

// Obtener info del receptor (nombre y foto)
$stmt = $pdo->prepare("SELECT id, name, photo FROM users WHERE id = ?");
$stmt->execute([$receiver_id]);
$receiver = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$receiver) {
    die("Usuario no encontrado");
}

// Obtener mensajes entre usuarios
$stmt = $pdo->prepare("SELECT m.*, u.name AS sender_name, u.photo AS sender_photo FROM messages m 
    JOIN users u ON m.sender_id = u.id 
    WHERE (m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?) 
    ORDER BY m.created_at ASC");
$stmt->execute([$current_user['id'], $receiver_id, $receiver_id, $current_user['id']]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Chat con <?= htmlspecialchars($receiver['name']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <style>
        body {
            background: #e5ddd5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        #chatBox {
            height: 400px;
            overflow-y: scroll;
            padding: 15px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .message {
            display: flex;
            margin-bottom: 12px;
            max-width: 70%;
        }
        .message.sent {
            margin-left: auto;
            flex-direction: row-reverse;
        }
        .bubble {
            padding: 10px 15px;
            border-radius: 20px;
            position: relative;
            font-size: 14px;
            line-height: 1.3;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
            word-wrap: break-word;
            white-space: pre-wrap;
        }
        .message.sent .bubble {
            background: #dcf8c6;
            color: #000;
            border-bottom-right-radius: 0;
        }
        .message.received .bubble {
            background: #fff;
            color: #333;
            border-bottom-left-radius: 0;
            border: 1px solid #ddd;
        }
        .profile-pic {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            margin: 0 8px;
            object-fit: cover;
            box-shadow: 0 0 3px rgba(0,0,0,0.2);
        }
        #chatHeader {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ccc;
            font-weight: 600;
            font-size: 18px;
            color: #222;
        }
        #chatHeader img {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 0 5px rgba(0,0,0,0.3);
        }
        form {
            margin-top: 15px;
            display: flex;
            gap: 8px;
        }
        form input[type=text] {
            flex-grow: 1;
            border-radius: 20px;
            padding: 10px 15px;
            border: 1px solid #ccc;
            font-size: 15px;
            transition: border-color 0.3s ease;
        }
        form input[type=text]:focus {
            outline: none;
            border-color: #128C7E;
        }
        form button {
            background: #128C7E;
            border: none;
            border-radius: 20px;
            color: white;
            padding: 10px 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        form button:hover {
            background: #075e54;
        }
    </style>
</head>
<body class="p-4">

<div id="chatHeader">
    <img src="<?= htmlspecialchars($receiver['photo'] ?: 'default-profile.png') ?>" alt="Foto de <?= htmlspecialchars($receiver['name']) ?>" />
    <?= htmlspecialchars($receiver['name']) ?>
</div>

<div id="chatBox" aria-live="polite" aria-relevant="additions">
    <?php foreach ($messages as $msg): ?>
        <?php
            $isSent = ($msg['sender_id'] == $current_user['id']);
            $classMsg = $isSent ? 'sent' : 'received';
            $senderName = $isSent ? 'Tú' : htmlspecialchars($msg['sender_name']);
            $senderPhoto = $msg['sender_photo'] ?: 'default-profile.png';
        ?>
        <div class="message <?= $classMsg ?>" role="article" aria-label="<?= $senderName ?> dice: <?= htmlspecialchars($msg['message']) ?>">
            <img class="profile-pic" src="<?= htmlspecialchars($senderPhoto) ?>" alt="Foto de <?= $senderName ?>" />
            <div class="bubble"><?= nl2br(htmlspecialchars($msg['message'])) ?></div>
        </div>
    <?php endforeach; ?>
</div>

<form id="chatForm" method="POST" autocomplete="off">
    <input type="hidden" name="receiver_id" value="<?= $receiver_id ?>" />
    <input type="text" name="message" placeholder="Escribe un mensaje..." required maxlength="500" aria-label="Escribe un mensaje" />
    <button type="submit" aria-label="Enviar mensaje">Enviar</button>
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
        }).catch(console.error);
    });

    let previousLength = 0;

    function loadMessages() {
        fetch('load_messages.php?to=<?= $receiver_id ?>')
            .then(res => res.text())
            .then(html => {
                const newLength = html.length;
                if (newLength > previousLength) {
                    const notifSound = new Audio('notif.mp3');
                    notifSound.play();
                }
                previousLength = newLength;
                chatBox.innerHTML = html;
                chatBox.scrollTop = chatBox.scrollHeight;
            })
            .catch(console.error);
    }

    setInterval(loadMessages, 3000);

    // Scroll al final al cargar la página
    chatBox.scrollTop = chatBox.scrollHeight;
</script>

</body>
</html>
