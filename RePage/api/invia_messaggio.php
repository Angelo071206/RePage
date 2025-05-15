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
$chatId = isset($_POST['id_destinatario']) ? (int)$_POST['id_destinatario'] : 0; // ID dell'utente con cui si vuole chattare
$message = isset($_POST['message']) ? $_POST['message'] : '';

// Verifica se i dati sono validi
if ($chatId <= 0 || empty($message)) {
    echo "Dati non validi.";
    exit;
}

// Inserisci il nuovo messaggio nel database
$sql = "INSERT INTO messaggio (ID_Utente_Mittente, ID_Utente_Destinatario, Testo) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $userId, $chatId, $message);

if ($stmt->execute()) {
    // Dopo aver inserito il messaggio, reindirizza alla pagina della chat per aggiornare la vista
    header("Location: " . $_SERVER['HTTP_REFERER']); // Ritorna alla pagina di chat corrente
    exit;
} else {
    echo "Errore durante l'invio del messaggio.";
}
?>
