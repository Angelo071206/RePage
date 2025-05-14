<?php
header('Content-Type: application/json');
require_once '../connessione.php';

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'error' => 'ID libro mancante']);
    exit;
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM libro WHERE ID_Libro = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $libro = $result->fetch_assoc();
    echo json_encode(['success' => true, 'libro' => $libro]);
} else {
    echo json_encode(['success' => false, 'error' => 'Libro non trovato']);
}

$stmt->close();
$conn->close();
?>
