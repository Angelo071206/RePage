<?php
session_start();
header('Content-Type: application/json');
require_once '../connessione.php'; 

// Verifica se l'utente è loggato
if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'error' => 'Devi essere loggato per vedere i tuoi libri.']);
    exit;
}

$user_id = $_SESSION['user']['id'];  // ID dell'utente loggato

// Recupera i libri associati all'utente
$stmt = $conn->prepare("SELECT ID_Libro, Titolo, Autore, ISBN, Condizione, Descrizione, immagine FROM libro WHERE ID_Utente = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$libri = [];
while ($row = $result->fetch_assoc()) {
    $libri[] = [
        'id' => $row['ID_Libro'],
        'titolo' => $row['Titolo'],
        'autore' => $row['Autore'],
        'isbn' => $row['ISBN'],
        'condizione' => $row['Condizione'],
        'descrizione' => $row['Descrizione'],
        'immagine' => $row['immagine']
    ];
}

$stmt->close();
$conn->close();

if (!empty($libri)) {
    echo json_encode(['success' => true, 'libri' => $libri]);
} else {
    echo json_encode(['success' => false, 'error' => 'Non hai libri aggiunti.']);
}
?>
