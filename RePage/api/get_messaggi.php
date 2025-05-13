<?php
session_start();
if (!isset($_SESSION['ID_Utente'])) {
    echo json_encode([]);
    exit;
}
require '../connessione.php';

$mittente = $_SESSION['ID_Utente'];
$destinatario = intval($_GET['destinatario']);

$stmt = $conn->prepare("
    SELECT ID_Mittente, Testo FROM messaggio 
    WHERE (ID_Mittente = ? AND ID_Destinatario = ?)
       OR (ID_Mittente = ? AND ID_Destinatario = ?)
    ORDER BY Timestamp ASC
");
$stmt->bind_param("iiii", $mittente, $destinatario, $destinatario, $mittente);
$stmt->execute();
$result = $stmt->get_result();

$messaggi = [];
while ($row = $result->fetch_assoc()) {
    $messaggi[] = $row;
}
echo json_encode($messaggi);
?>
