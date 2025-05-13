<?php
session_start();
require '../connessione.php'; // Include la connessione al database

// Verifica se l'utente è loggato
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Utente non autenticato']);
    exit;
}

$userId = $_SESSION['user']['id']; // ID dell'utente loggato
$chatId = isset($_POST['id_destinatario']) ? (int)$_POST['id_destinatario'] : 0;
$messaggio = isset($_POST['message']) ? $_POST['message'] : '';

// Verifica che ci sia un messaggio e che l'ID destinatario sia valido
if (empty($messaggio) || $chatId <= 0) {
    echo json_encode(['success' => false, 'error' => 'Dati mancanti']);
    exit;
}

// Inserisci il messaggio nel database
$sql = "INSERT INTO messaggio (ID_Utente_Mittente, ID_Utente_Destinatario, Testo, Data_Ora) 
        VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $userId, $chatId, $messaggio);

// Esegui la query
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Messaggio inviato']);
} else {
    echo json_encode(['success' => false, 'error' => 'Errore durante l\'invio del messaggio']);
}
?>
