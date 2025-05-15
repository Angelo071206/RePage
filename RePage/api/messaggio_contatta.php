<?php
session_start();
require '../connessione.php';

header('Content-Type: application/json');

// Verifica login
if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'error' => 'Utente non autenticato.']);
    exit;
}

$mittente = $_SESSION['user']['id'];

// Verifica parametro
if (!isset($_POST['ID_Libro'])) {
    echo json_encode(['success' => false, 'error' => 'ID_Libro mancante.']);
    exit;
}

$idLibro = intval($_POST['ID_Libro']);

// Trova il proprietario del libro
$stmt = $conn->prepare("SELECT ID_Utente FROM libro WHERE ID_Libro = ?");
$stmt->bind_param("i", $idLibro);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Libro non trovato.']);
    exit;
}

$row = $result->fetch_assoc();
$destinatario = $row['ID_Utente'];

// Evita di inviare messaggi a sé stessi
if ($destinatario == $mittente) {
    echo json_encode(['success' => false, 'error' => 'Non puoi contattare te stesso.']);
    exit;
}

// Messaggio di avvio chat
$testo = "Ciao! Sono interessato al tuo libro.";

// Inserisce il messaggio
$stmt = $conn->prepare("INSERT INTO messaggio (ID_Utente_Mittente, ID_Utente_Destinatario, Testo) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $mittente, $destinatario, $testo);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Errore durante l\'invio del messaggio.']);
}
?>
