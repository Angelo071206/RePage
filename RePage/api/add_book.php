<?php
session_start();
header('Content-Type: application/json');
include '../connessione.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Non autorizzato']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['titolo'], $data['autore'], $data['immagine'])) {
    echo json_encode(['success' => false, 'error' => 'Dati incompleti']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO libri (titolo, autore, immagine, user_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $data['titolo'], $data['autore'], $data['immagine'], $_SESSION['user_id']);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Errore nel salvataggio']);
}
?>
