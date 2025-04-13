<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require '../connessione.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Metodo non consentito"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data["email"] ?? "");
$password = trim($data["password"] ?? "");

$stmt = $conn->prepare("SELECT ID_Utente, Password, Nome FROM Utente WHERE Email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($id_utente, $hashed_password, $nome);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        http_response_code(200);
        echo json_encode([
            "message" => "Login effettuato",
            "user" => [
                "id" => $id_utente,
                "nome" => $nome,
                "email" => $email
            ]
        ]);
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
