<?php
session_start();
header('Content-Type: application/json');
require_once '../connessione.php'; // Connessione al database

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    echo json_encode(["success" => false, "error" => "Utente non autenticato."]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$password = trim($data["password"] ?? "");

if (empty($password)) {
    echo json_encode(["success" => false, "error" => "La password è obbligatoria."]);
    exit;
}

$user_id = $_SESSION['user']['id'];

// Verifica password
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

// 1. Elimina i libri dell'utente (per rispettare la foreign key)
$stmt = $conn->prepare("DELETE FROM Libro WHERE ID_Utente = ?");
$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
    echo json_encode(["success" => false, "error" => "Errore durante l'eliminazione dei libri."]);
    exit;
}
$stmt->close();

// 2. Elimina il profilo utente
$stmt = $conn->prepare("DELETE FROM Utente WHERE ID_Utente = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    session_destroy();
    echo json_encode([
        "success" => true,
        "message" => "Profilo eliminato con successo.",
        "redirect" => "index.php"
    ]);
} else {
    echo json_encode(["success" => false, "error" => "Errore durante l'eliminazione del profilo."]);
}

$stmt->close();
$conn->close();
?>