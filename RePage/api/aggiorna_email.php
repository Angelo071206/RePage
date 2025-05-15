<?php
session_start();
header('Content-Type: application/json');
require_once '../connessione.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    echo json_encode(["success" => false, "error" => "Utente non autenticato."]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$email = trim($data["email"] ?? "");
$encryptedPassword = trim($data["password"] ?? "");

if (empty($email) || empty($encryptedPassword)) {
    echo json_encode(["success" => false, "error" => "Email e password sono obbligatori."]);
    exit;
}

$key = "12345678901234567890123456789012";
$iv = substr($key, 0, 16);

$decryptedPassword = openssl_decrypt(
    base64_decode($encryptedPassword),
    'aes-256-cbc',
    $key,
    OPENSSL_RAW_DATA,
    $iv
);

if (!$decryptedPassword) {
    echo json_encode(["success" => false, "error" => "Errore nella decifratura della password."]);
    exit;
}

$user_id = $_SESSION['user']['id'];

// Verifica la password
$stmt = $conn->prepare("SELECT Password FROM Utente WHERE ID_Utente = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($password_hash);
$stmt->fetch();
$stmt->close();

if (!password_verify($decryptedPassword, $password_hash)) {
    echo json_encode(["success" => false, "error" => "Password errata."]);
    exit;
}

// Verifica che l'email non sia già in uso da un altro utente
$stmt = $conn->prepare("SELECT ID_Utente FROM Utente WHERE Email = ? AND ID_Utente != ?");
$stmt->bind_param("si", $email, $user_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(["success" => false, "error" => "Email già in uso."]);
    $stmt->close();
    exit;
}
$stmt->close();

// Aggiorna l'email
$stmt = $conn->prepare("UPDATE Utente SET Email = ? WHERE ID_Utente = ?");
$stmt->bind_param("si", $email, $user_id);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Email aggiornata con successo",
        "redirect" => "impostazioni.html"
    ]);
} else {
    echo json_encode(["success" => false, "error" => "Errore durante l'aggiornamento."]);
}

$stmt->close();
$conn->close();
?>
