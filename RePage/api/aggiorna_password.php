<?php
session_start();
header('Content-Type: application/json');
require_once '../connessione.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    echo json_encode(["success" => false, "error" => "Utente non autenticato."]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$encryptedPassword = trim($data["password"] ?? "");
$encryptedNewPassword = trim($data["new_password"] ?? "");
$encryptedConfirmPassword = trim($data["confirm_password"] ?? "");

if (empty($encryptedPassword) || empty($encryptedNewPassword) || empty($encryptedConfirmPassword)) {
    echo json_encode(["success" => false, "error" => "Tutti i campi sono obbligatori."]);
    exit;
}

$key = "12345678901234567890123456789012";
$iv = substr($key, 0, 16);

function decryptAES($encrypted, $key, $iv) {
    $decoded = base64_decode($encrypted);
    return openssl_decrypt($decoded, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
}

$password = decryptAES($encryptedPassword, $key, $iv);
$new_password = decryptAES($encryptedNewPassword, $key, $iv);
$confirm_password = decryptAES($encryptedConfirmPassword, $key, $iv);

if ($new_password !== $confirm_password) {
    echo json_encode(["success" => false, "error" => "La nuova password e la conferma non corrispondono."]);
    exit;
}

$user_id = $_SESSION['user']['id'];

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

// Hash nuova password
$new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);

// Aggiorna la password
$stmt = $conn->prepare("UPDATE Utente SET Password = ? WHERE ID_Utente = ?");
$stmt->bind_param("si", $new_password_hash, $user_id);

if ($stmt->execute()) {
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
