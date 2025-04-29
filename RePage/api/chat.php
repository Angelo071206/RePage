<?php
session_start();
if (!isset($_SESSION['ID_Utente'])) {
    header("Location: login.html");
    exit();
}
$mittente = $_SESSION['ID_Utente'];
$destinatario = intval($_GET['id_utente']);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Chat Privata</title>
    <style>
        body { font-family: Arial; background: #e9e9e9; padding: 20px; }
        .chat-box { width: 500px; margin: auto; background: white; border-radius: 8px; padding: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.2); }
        .message { border-bottom: 1px solid #eee; padding: 5px; }
        .me { text-align: right; color: green; }
        .them { text-align: left; color: blue; }
        textarea { width: 90%; height: 50px; }
        button { padding: 5px 10px; margin-top: 5px; }
    </style>
</head>
<body>

<div class="chat-box">
    <div id="chat"></div>
    <textarea id="testo"></textarea><br>
    <button onclick="inviaMessaggio()">Invia</button>
</div>

<script>
const mittente = <?php echo $mittente; ?>;
const destinatario = <?php echo $destinatario; ?>;

// Carica i messaggi ogni 2 secondi
setInterval(caricaMessaggi, 2000);
caricaMessaggi();

async function caricaMessaggi() {
    const response = await fetch(`api/get_messaggi.php?mittente=${mittente}&destinatario=${destinatario}`);
    const data = await response.json();
    let chat = document.getElementById('chat');
    chat.innerHTML = '';
    data.forEach(msg => {
        let classe = msg.ID_Mittente == mittente ? 'me' : 'them';
        chat.innerHTML += `<div class='message ${classe}'>${msg.Testo}</div>`;
    });
}

async function inviaMessaggio() {
    let testo = document.getElementById('testo').value.trim();
    if (!testo) return;
    const formData = new FormData();
    formData.append('mittente', mittente);
    formData.append('destinatario', destinatario);
    formData.append('testo', testo);

    await fetch('api/invia_messaggio.php', {
        method: 'POST',
        body: formData
    });

    document.getElementById('testo').value = '';
    caricaMessaggi();
}
</script>

</body>
</html>
