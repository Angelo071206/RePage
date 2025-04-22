<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require '../connessione.php'; // Include la connessione al database

// Verifica che la richiesta sia di tipo POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405); // Metodo non consentito
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
if (empty($nome) || empty($cognome) || empty($email) || empty($password)) {
    http_response_code(400); // Bad request
    echo json_encode(["error" => "Tutti i campi sono obbligatori"]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400); // Bad request
    echo json_encode(["error" => "Email non valida"]);
    exit;
}

if (strlen($password) < 8) {
    http_response_code(400); // Bad request
    echo json_encode(["error" => "La password deve avere almeno 8 caratteri"]);
    exit;
}

// Verifica se l'email esiste già nel database
$stmt = $conn->prepare("SELECT ID_Utente FROM Utente WHERE Email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    http_response_code(409); // Conflitto, email già registrata
    echo json_encode(["error" => "Email già registrata"]);
    exit;
}
$stmt->close();

// Crea un hash della password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Salvataggio nel database
$stmt = $conn->prepare("INSERT INTO Utente (Nome, Cognome, Email, Password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome, $cognome, $email, $hashed_password);

// Esegui il salvataggio e controlla il risultato
if ($stmt->execute()) {
    http_response_code(201); // Creato con successo
    echo json_encode(["message" => "Registrazione avvenuta con successo"]);
} else {
    http_response_code(500); // Errore interno del server
    echo json_encode(["error" => "Errore nella registrazione, riprova più tardi"]);
}

$stmt->close();
$conn->close();
?>
