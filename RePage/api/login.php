<?php
session_start();
require '../connessione.php'; // Connessione DB

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Metodo non consentito"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$email = trim($data["email"] ?? "");
$encryptedPassword = trim($data["password"] ?? "");

if (empty($email) || empty($encryptedPassword)) {
    http_response_code(400);
    echo json_encode(["error" => "Email e password sono obbligatori"]);
    exit;
}

// Chiave usata anche nel client
$key = "12345678901234567890123456789012";
$iv = substr($key, 0, 16);

// Decifra la password
$decryptedPassword = openssl_decrypt(
    base64_decode($encryptedPassword),
    'aes-256-cbc',
    $key,
    OPENSSL_RAW_DATA,
    $iv
);

if (!$decryptedPassword) {
    http_response_code(400);
    echo json_encode(["error" => "Password cifrata non valida"]);
    exit;
}

$stmt = $conn->prepare("SELECT ID_Utente, Nome, Email, Password FROM Utente WHERE Email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($id_utente, $nome, $email_db, $hashed_password);
    $stmt->fetch();

    if (password_verify($decryptedPassword, $hashed_password)) {
        $_SESSION['user'] = [
            'id' => $id_utente,
            'nome' => $nome,
            'email' => $email_db
        ];
        http_response_code(200);
        echo json_encode(["message" => "Login effettuato", "user" => $_SESSION['user']]);
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Password errata"]);
    }
} else {
    http_response_code(404);
    echo json_encode(["error" => "Utente non trovato"]);
}

$stmt->close();
$conn->close();
?>
