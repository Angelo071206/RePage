<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require '../connessione.php'; // Assicurati che questo file contenga la connessione al database

// Verifica che la richiesta sia di tipo POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Metodo non consentito"]);
    exit;
}

// Ricezione dei dati JSON inviati dal client
$data = json_decode(file_get_contents("php://input"), true);

// Estrai i dati
$nome = trim($data["nome"] ?? "");
$cognome = trim($data["cognome"] ?? "");
$email = trim($data["email"] ?? "");
$password = trim($data["password"] ?? "");

// Validazione dei dati
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["error" => "Email non valida"]);
    exit;
}

if (strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(["error" => "La password deve avere almeno 8 caratteri"]);
    exit;
}

// Verifica se l'email esiste già nel database
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

// Crea un hash della password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Salvataggio nel database
$stmt = $conn->prepare("INSERT INTO Utente (Nome, Cognome, Email, Password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome, $cognome, $email, $hashed_password);

if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode(["message" => "Registrazione avvenuta con successo"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Errore nella registrazione"]);
}

$stmt->close();
$conn->close();
?>
