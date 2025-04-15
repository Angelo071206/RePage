<?php
session_start();
header('Content-Type: application/json');
include '../connessione.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Non autorizzato']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['book_id'])) {
    echo json_encode(['success' => false, 'error' => 'ID mancante']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM libri WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $data['book_id'], $_SESSION['user_id']);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Nessuna autorizzazione o libro non trovato']);
}
?>
