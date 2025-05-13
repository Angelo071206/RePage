<?php
require '../connessione.php';  // Assicurati che il percorso sia corretto

// Controlla se chatId è passato come parametro
if (isset($_GET['chatId'])) {
    $chatId = $_GET['chatId'];

    // Query per ottenere i messaggi della chat
    $sql = "SELECT m.*, u.nome AS sender_name
            FROM messaggio m
            JOIN utente u ON m.ID_Mittente = u.ID_Utente
            WHERE m.ID_Chat = ? 
            ORDER BY m.Data_Messaggio ASC"; // Assicurati che la colonna Data_Messaggio esista nel tuo database

    // Preparare la query
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $chatId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Controlla se ci sono messaggi
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Mostra i messaggi
                echo "<div class='message'>";
                echo "<p><strong>" . htmlspecialchars($row['sender_name']) . ":</strong> " . htmlspecialchars($row['Testo_Messaggio']) . "</p>";
                echo "<p class='timestamp'>" . $row['Data_Messaggio'] . "</p>"; // Assicurati che Data_Messaggio sia nel formato giusto
                echo "</div>";
            }
        } else {
            echo "<p>No messages yet.</p>";
        }
    } else {
        echo "<p>Errore nella query.</p>";
    }
}
?>
