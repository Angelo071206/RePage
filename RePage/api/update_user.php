<?php
session_start();
header('Content-Type: application/json');
include '../connessione.php';

// Verifica se l'utente è loggato
if (!isset($_SESSION['ID_Utente'])) {
    echo json_encode(['success' => false, 'error' => 'Non autorizzato']);
    exit;
}

// Ottieni i dati dal corpo della richiesta
$data = json_decode(file_get_contents('php://input'), true);

// Verifica che la password sia stata inviata
if (!isset($data['password'])) {
    echo json_encode(['success' => false, 'error' => 'Password mancante']);
    exit;
}

// Recupera la password inserita dall'utente
$password = $data['password'];
$fields = [];
$values = [];

// Verifica i dati da aggiornare
if (isset($data['data']['email'])) {
    $fields[] = "email = ?";
    $values[] = $data['data']['email'];
}
if (isset($data['data']['nome'])) {
    $fields[] = "nome = ?";
    $values[] = $data['data']['nome'];
}
if (isset($data['data']['cognome'])) {
    $fields[] = "cognome = ?";
    $values[] = $data['data']['cognome'];
}

// Se non ci sono campi da aggiornare, restituire errore
if (empty($fields)) {
    echo json_encode(['success' => false, 'error' => 'Nessun dato fornito']);
    exit;
}

// Ottieni la password memorizzata nel database
$stmt = $conn->prepare("SELECT Password FROM Utente WHERE id = ?");
$stmt->bind_param("i", $_SESSION['ID_Utente']);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($db_password);
$stmt->fetch();

if ($stmt->num_rows === 0 || !password_verify($password, $db_password)) {
    echo json_encode(['success' => false, 'error' => 'Password errata']);
    exit;
}

// Prepara la query di aggiornamento
$query = "UPDATE Utente SET " . implode(", ", $fields) . " WHERE id = ?";
$values[] = $_SESSION['ID_Utente'];

$stmt = $conn->prepare($query);
$stmt->bind_param(str_repeat("s", count($values)-1) . "i", ...$values);
$stmt->execute();

// Verifica se le modifiche sono state effettuate
if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Nessuna modifica effettuata']);
}
?>
