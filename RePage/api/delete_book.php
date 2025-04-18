<?php
session_start();
header('Content-Type: application/json');
include '../connessione.php';

if (!isset($_SESSION['ID_Utente'])) {
    echo json_encode(['success' => false, 'error' => 'Non autorizzato']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['ID_Libro'])) {
    echo json_encode(['success' => false, 'error' => 'ID mancante']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM Libro WHERE ID_Libro = ? AND ID_Utente = ?");
$stmt->bind_param("ii", $data['ID_Libro'], $_SESSION['ID_Utente']);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Nessuna autorizzazione o libro non trovato']);
}
?>
