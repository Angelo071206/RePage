<?php
session_start();
if (!isset($_SESSION['ID_Utente'])) {
    echo json_encode(['success' => false, 'error' => 'Non loggato']);
    exit;
}
require '../connessione.php';

$mittente = $_SESSION['ID_Utente'];
$destinatario = intval($_POST['destinatario']);
$testo = htmlspecialchars($_POST['testo']);

if ($mittente && $destinatario && $testo !== '') {
    $stmt = $conn->prepare("INSERT INTO messaggio (ID_Mittente, ID_Destinatario, Testo, Timestamp) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $mittente, $destinatario, $testo);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Errore query']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Dati mancanti']);
}
?>
