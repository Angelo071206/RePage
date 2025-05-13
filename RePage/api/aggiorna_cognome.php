<?php
session_start(); // Avvio della sessione
header('Content-Type: application/json');
require_once '../connessione.php'; // Connessione al database

// Verifica che l'utente sia autenticato
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    echo json_encode(["success" => false, "error" => "Utente non autenticato."]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$cognome = trim($data["cognome"] ?? "");
$password = trim($data["password"] ?? "");

if (empty($cognome) || empty($password)) {
    echo json_encode(["success" => false, "error" => "Cognome e password sono obbligatori."]);
    exit;
}

$user_id = $_SESSION['user']['id']; // ID dell'utente dalla sessione

// Verifica la password
$stmt = $conn->prepare("SELECT Password FROM Utente WHERE ID_Utente = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($password_hash);
$stmt->fetch();
$stmt->close();

if (!password_verify($password, $password_hash)) {
    echo json_encode(["success" => false, "error" => "Password errata."]);
    exit;
}

// Aggiornamento del cognome
$stmt = $conn->prepare("UPDATE Utente SET Cognome = ? WHERE ID_Utente = ?");
$stmt->bind_param("si", $cognome, $user_id);
if ($stmt->execute()) {
    // Risposta di successo con il reindirizzamento
    echo json_encode([
        "success" => true,
        "message" => "Cognome aggiornato con successo",
        "redirect" => "impostazioni.html" 
    ]);
} else {
    echo json_encode(["success" => false, "error" => "Errore durante l'aggiornamento."]);
}

$stmt->close();
$conn->close();
?>
