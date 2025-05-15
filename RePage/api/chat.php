<?php
session_start(); // Inizializza la sessione
require '../connessione.php'; // Include la connessione al database

// Verifica se l'utente è loggato
if (!isset($_SESSION['user'])) {
    // Se non è loggato, reindirizza al login
    header('Location: ../login.html');
    exit;
}

$userId = $_SESSION['user']['id']; // ID dell'utente loggato
$chatId = isset($_GET['id']) ? (int)$_GET['id'] : 0; // ID dell'utente con cui si vuole chattare

// Verifica se l'ID della chat è valido
if ($chatId <= 0) {
    echo "ID della chat non valido.";
    exit;
}

// Ottieni informazioni sull'utente con cui si sta chattando
$sql = "SELECT Nome, Cognome, Email FROM utente WHERE ID_Utente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $chatId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "L'utente con cui vuoi chattare non esiste.";
    exit;
}

$chatUser = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Chat con <?php echo htmlspecialchars($chatUser['Nome'] . ' ' . $chatUser['Cognome']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            text-align: center;
        }

        header a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
        }

        .chat-box {
            max-width: 800px;
            margin: 30px auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
        }

        #messages {
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fafafa;
        }

        #messages div {
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 8px;
            background-color: #e1f5fe;
        }

        #messages div strong {
            color: #2e7d32;
        }

        form textarea {
            width: 100%;
            height: 80px;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
            resize: none;
        }

        button[type="submit"] {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<header>
    <h1>Chat con <?php echo htmlspecialchars($chatUser['Nome'] . ' ' . $chatUser['Cognome']); ?></h1>
    <a href="../index.php">Torna alla homepage</a>
</header>

<div class="chat-box">
    <div id="messages">
        <?php
        // Ottieni i messaggi della chat
		
        $sql_messages = "SELECT m.Testo, m.Data_Ora, u.Nome, u.Cognome 
                         FROM messaggio m
                         JOIN utente u ON u.ID_Utente = m.ID_Utente_Mittente
                         WHERE (m.ID_Utente_Mittente = ? AND m.ID_Utente_Destinatario = ?) 
                         OR (m.ID_Utente_Mittente = ? AND m.ID_Utente_Destinatario = ?)
                         ORDER BY m.Data_Ora ASC";
        $stmt_messages = $conn->prepare($sql_messages);
        $stmt_messages->bind_param("iiii", $userId, $chatId, $chatId, $userId);
        $stmt_messages->execute();
        $result_messages = $stmt_messages->get_result();

        if ($result_messages->num_rows > 0) {
            while ($message = $result_messages->fetch_assoc()) {
                $nome = htmlspecialchars($message['Nome']);
                $cognome = htmlspecialchars($message['Cognome']);
                $testo = htmlspecialchars($message['Testo']);
                $data_ora = htmlspecialchars($message['Data_Ora']);

                echo "<div><strong>$nome $cognome</strong> <small>($data_ora)</small><br>$testo</div>";
            }
        } else {
            echo "<p>Nessun messaggio trovato.</p>";
        }
        ?>
    </div>

    <!-- Form per inviare un nuovo messaggio -->
    <form id="sendMessageForm" action="invia_messaggio.php" method="POST">
        <textarea name="message" placeholder="Scrivi un messaggio..." required></textarea>
        <input type="hidden" name="id_destinatario" value="<?php echo $chatId; ?>">
        <button type="submit">Invia Messaggio</button>
    </form>
	

</div>

</body>
</html>
