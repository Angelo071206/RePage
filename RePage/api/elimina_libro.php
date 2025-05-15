<?php
session_start();
header('Content-Type: application/json');
require_once '../connessione.php'; // Connessione al database

// Verifica se l'utente è loggato
if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'error' => 'Devi essere loggato per eliminare un libro.']);
    exit;
}

$user_id = $_SESSION['user']['id'];  // ID dell'utente loggato

// Ricevi i dati JSON dalla richiesta
$input = json_decode(file_get_contents("php://input"), true);

// Controlla se l'ID del libro è stato passato
if (!isset($input['id'])) {
    echo json_encode(['success' => false, 'error' => 'ID del libro mancante.']);
    exit;
}

$book_id = $input['id'];

// Verifica che il libro appartenga all'utente
$stmt = $conn->prepare("SELECT immagine FROM libro WHERE ID_Libro = ? AND ID_Utente = ?");
$stmt->bind_param("ii", $book_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Libro non trovato o non autorizzato.']);
    $stmt->close();
    exit;
}

// Se esiste, elimina anche il file immagine se non è quello di default
$row = $result->fetch_assoc();
if ($row['immagine'] !== 'default.jpg' && file_exists('../upload/' . $row['immagine'])) {
    unlink('../upload/' . $row['immagine']);  // Elimina il file immagine
}

// Elimina il libro dal database
$deleteStmt = $conn->prepare("DELETE FROM libro WHERE ID_Libro = ? AND ID_Utente = ?");
$deleteStmt->bind_param("ii", $book_id, $user_id);

if ($deleteStmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Libro eliminato con successo.']);
} else {
    echo json_encode(['success' => false, 'error' => 'Errore durante l\'eliminazione del libro.']);
}

$deleteStmt->close();
$conn->close();
?>
