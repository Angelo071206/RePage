<?php
session_start();
header('Content-Type: application/json');
include '../connessione.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Non autorizzato']);
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT ID_Libro, Titolo, Autore, Immagine FROM Libro WHERE ID_Utente = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$books = [];

while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

echo json_encode(['success' => true, 'data' => $books]);
?>
