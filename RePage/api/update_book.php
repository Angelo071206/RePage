<?php
session_start();
header('Content-Type: application/json');
include '../connessione.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Non autorizzato']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['book_id'], $data['titolo'], $data['autore'])) {
    echo json_encode(['success' => false, 'error' => 'Dati incompleti']);
    exit;
}

$stmt = $conn->prepare("UPDATE libri SET titolo = ?, autore = ? WHERE id = ? AND user_id = ?");
$stmt->bind_param("ssii", $data['titolo'], $data['autore'], $data['book_id'], $_SESSION['user_id']);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Nessuna modifica o permessi insufficienti']);
}
?>
