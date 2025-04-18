<?php
session_start(); // Avvio della sessione
header('Content-Type: application/json');
require_once '../connessione.php'; // Connessione al database

// Verifica che l'utente sia autenticato
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    echo json_encode(["success" => false, "error" => "Utente non autenticato."]);
    exit;
}

// Decodifica i dati JSON dalla richiesta
$data = json_decode(file_get_contents("php://input"), true);
$password = trim($data["password"] ?? "");

if (empty($password)) {
    echo json_encode(["success" => false, "error" => "La password è obbligatoria."]);
    exit;
}

$user_id = $_SESSION['user']['id']; // ID dell'utente dalla sessione

// Verifica la password dell'utente
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

// Elimina il profilo utente
$stmt = $conn->prepare("DELETE FROM Utente WHERE ID_Utente = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    // Chiusura della sessione
    session_destroy();

    // Risposta di successo con il reindirizzamento
    echo json_encode([
        "success" => true,
        "message" => "Profilo eliminato con successo.",
        "redirect" => "index.php"  // Redirigi alla homepage
    ]);
} else {
    echo json_encode(["success" => false, "error" => "Errore durante l'eliminazione del profilo."]);
}

$stmt->close();
$conn->close();
?>
