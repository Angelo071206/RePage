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
$password = trim($data["password"] ?? "");
$new_password = trim($data["new_password"] ?? "");
$confirm_password = trim($data["confirm_password"] ?? "");

if (empty($password) || empty($new_password) || empty($confirm_password)) {
    echo json_encode(["success" => false, "error" => "Tutti i campi sono obbligatori."]);
    exit;
}

if ($new_password !== $confirm_password) {
    echo json_encode(["success" => false, "error" => "La nuova password e la conferma non corrispondono."]);
    exit;
}

$user_id = $_SESSION['user']['id']; // ID dell'utente dalla sessione

// Verifica la password attuale
$stmt = $conn->prepare("SELECT Password FROM Utente WHERE ID_Utente = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($password_hash);
$stmt->fetch();
$stmt->close();

if (!password_verify($password, $password_hash)) {
    echo json_encode(["success" => false, "error" => "Password attuale errata."]);
    exit;
}

// Crea l'hash per la nuova password
$new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);

// Aggiornamento della password
$stmt = $conn->prepare("UPDATE Utente SET Password = ? WHERE ID_Utente = ?");
$stmt->bind_param("si", $new_password_hash, $user_id);
if ($stmt->execute()) {
    // Risposta di successo con il reindirizzamento
    echo json_encode([
        "success" => true,
        "message" => "Password aggiornata con successo",
        "redirect" => "impostazioni.html" 
    ]);
} else {
    echo json_encode(["success" => false, "error" => "Errore durante l'aggiornamento."]);
}

$stmt->close();
$conn->close();
?>
