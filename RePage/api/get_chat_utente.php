<?php
session_start();
if (!isset($_SESSION['ID_Utente'])) {
    echo json_encode([]);
    exit();
}
require '../connessione.php';

$id_utente = $_SESSION['ID_Utente'];

// Recupera gli ID degli utenti con cui l'utente loggato ha scambiato messaggi
$stmt = $conn->prepare("
    SELECT DISTINCT
        CASE 
            WHEN ID_Mittente = ? THEN ID_Destinatario
            ELSE ID_Mittente
        END AS ID_Utente
    FROM messaggio 
    WHERE ID_Mittente = ? OR ID_Destinatario = ?
");
$stmt->bind_param("iii", $id_utente, $id_utente, $id_utente);
$stmt->execute();
$result = $stmt->get_result();

$chat_utenti = [];
while ($row = $result->fetch_assoc()) {
    $id_destinatario = $row['ID_Utente'];
    
    // Recupera nome dell'utente con cui ha avuto la chat
    $stmt2 = $conn->prepare("SELECT Nome FROM utente WHERE ID_Utente = ?");
    $stmt2->bind_param("i", $id_destinatario);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $nome_utente = $result2->fetch_assoc()['Nome'];

    $chat_utenti[] = [
        'ID_Utente' => $id_destinatario,
        'Nome' => $nome_utente
    ];
}

echo json_encode($chat_utenti);
?>
