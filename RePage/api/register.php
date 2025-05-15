<?php
session_start(); // Avvia la sessione

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
$password = trim($data["password"] ?? "");

// Validazioni base
if (empty($nome) || empty($cognome) || empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["error" => "Tutti i campi sono obbligatori"]);
    exit;
}

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

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Inserimento utente
$stmt = $conn->prepare("INSERT INTO Utente (Nome, Cognome, Email, Password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome, $cognome, $email, $hashed_password);

if ($stmt->execute()) {
    // Prendi ID inserito
    $userId = $conn->insert_id;

    // Salva i dati nella sessione (come nel login)
    $_SESSION['user'] = [
        'id' => $userId,
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