<?php
session_start();
require '../connessione.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Metodo non consentito"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$nome = trim($data["nome"] ?? "");
$cognome = trim($data["cognome"] ?? "");
$email = trim($data["email"] ?? "");
$encryptedPassword = trim($data["password"] ?? "");

// Validazioni base
if (empty($nome) || empty($cognome) || empty($email) || empty($encryptedPassword)) {
    http_response_code(400);
    echo json_encode(["error" => "Tutti i campi sono obbligatori"]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["error" => "Email non valida"]);
    exit;
}

// Decifra la password con stessa chiave/IV del login
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
    http_response_code(400);
    echo json_encode(["error" => "Password cifrata non valida"]);
    exit;
}

if (strlen($decryptedPassword) < 8) {
    http_response_code(400);
    echo json_encode(["error" => "La password deve avere almeno 8 caratteri"]);
    exit;
}

// Verifica email esistente
$stmt = $conn->prepare("SELECT ID_Utente FROM Utente WHERE Email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    http_response_code(409);
    echo json_encode(["error" => "Email già registrata"]);
    exit;
}
$stmt->close();

// Hash password decifrata
$hashed_password = password_hash($decryptedPassword, PASSWORD_DEFAULT);

// Inserimento
$stmt = $conn->prepare("INSERT INTO Utente (Nome, Cognome, Email, Password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome, $cognome, $email, $hashed_password);

if ($stmt->execute()) {
    $_SESSION['user'] = [
        'id' => $conn->insert_id,
        'nome' => $nome,
        'email' => $email
    ];
    http_response_code(201);
    echo json_encode(["message" => "Registrazione avvenuta con successo"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Errore nella registrazione, riprova più tardi"]);
}

$stmt->close();
$conn->close();
?>
