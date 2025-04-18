<?php
session_start(); // Iniziamo la sessione

require '../connessione.php'; // Connessione al database

// Verifica se il metodo della richiesta è POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405); // Metodo non consentito
    echo json_encode(["error" => "Metodo non consentito"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data["email"] ?? "");
$password = trim($data["password"] ?? "");

if (empty($email) || empty($password)) {
    http_response_code(400); // Bad request
    echo json_encode(["error" => "Email e password sono obbligatori"]);
    exit;
}

// Verifica che l'utente esista nel database
$stmt = $conn->prepare("SELECT ID_Utente, Nome, Email, Password FROM Utente WHERE Email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($id_utente, $nome, $email_db, $hashed_password);
    $stmt->fetch();

    // Verifica la password
    if (password_verify($password, $hashed_password)) {
        // Salva l'utente nella sessione
        $_SESSION['user'] = [
            'id' => $id_utente,
            'nome' => $nome,
            'email' => $email_db
        ];

        http_response_code(200);
        echo json_encode([
            "message" => "Login effettuato",
            "user" => $_SESSION['user']
        ]);
    } else {
        http_response_code(401); // Unauthorized
        echo json_encode(["error" => "Password errata"]);
    }
} else {
    http_response_code(404); // Not found
    echo json_encode(["error" => "Utente non trovato"]);
}

$stmt->close();
$conn->close();
?>
