<?php
session_start();
header('Content-Type: application/json');
include '../connessione.php';

if (!isset($_SESSION['ID_Utente'])) {
    echo json_encode(['success' => false, 'error' => 'Non autorizzato']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['ID_Libro'], $data['Titolo'], $data['Autore'])) {
    echo json_encode(['success' => false, 'error' => 'Dati incompleti']);
    exit;
}

$stmt = $conn->prepare("UPDATE Libro SET Titolo = ?, Autore = ? WHERE ID_Libro = ? AND ID_Utente = ?");
$stmt->bind_param("ssii", $data['Titolo'], $data['Autore'], $data['ID_Libro'], $_SESSION['ID_Utente']);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Nessuna modifica o permessi insufficienti']);
}
?>
