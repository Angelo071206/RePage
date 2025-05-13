<?php
session_start();
require '../connessione.php';

// Verifica se l'utente è loggato
if (!isset($_SESSION['user']) || !isset($_POST['message'])) {
    echo json_encode(['success' => false, 'error' => 'Utente non loggato o messaggio vuoto']);
    exit();
}

$userId = $_SESSION['user']['ID_Utente'];
$message = htmlspecialchars(trim($_POST['message']));
$libroId = intval($_POST['libroId']);
$annuncioId = intval($_POST['annuncioId']);

// Controlla che il libro esista
$sql_check_libro = "SELECT * FROM libro WHERE ID_Libro = ?";
$stmt_check_libro = $conn->prepare($sql_check_libro);
$stmt_check_libro->bind_param("i", $libroId);
$stmt_check_libro->execute();
$result_check_libro = $stmt_check_libro->get_result();

if ($result_check_libro->num_rows == 0) {
    echo json_encode(['success' => false, 'error' => 'Libro non trovato']);
    exit();
}

// Inserisce il messaggio nel database
$sql_insert_message = "INSERT INTO messaggio (ID_Utente_Mittente, ID_Utente_Destinatario, ID_Annuncio, Testo) VALUES (?, ?, ?, ?)";
$stmt_insert_message = $conn->prepare($sql_insert_message);

// Ottieni l'ID dell'utente destinatario, che è l'utente che ha pubblicato l'annuncio
$sql_get_destinatario = "SELECT ID_Utente FROM annuncio WHERE ID_Annuncio = ?";
$stmt_get_destinatario = $conn->prepare($sql_get_destinatario);
$stmt_get_destinatario->bind_param("i", $annuncioId);
$stmt_get_destinatario->execute();
$result_destinatario = $stmt_get_destinatario->get_result();

if ($result_destinatario->num_rows == 0) {
    echo json_encode(['success' => false, 'error' => 'Annuncio non trovato']);
    exit();
}

$destinatario = $result_destinatario->fetch_assoc()['ID_Utente'];

// Aggiungi il messaggio
$stmt_insert_message->bind_param("iiis", $userId, $destinatario, $annuncioId, $message);

if ($stmt_insert_message->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Errore nell\'invio del messaggio']);
}
?>
