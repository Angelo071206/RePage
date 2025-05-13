<?php
session_start();
header('Content-Type: application/json');
require_once '../connessione.php'; // Connessione al database

// Verifica se l'utente è loggato
if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'error' => 'Devi essere loggato per eliminare il profilo.']);
    exit;
}

$user_id = $_SESSION['user']['id']; // ID dell'utente loggato

// Verifica se il metodo HTTP è DELETE
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Ricevi i dati JSON dalla richiesta
    $input = json_decode(file_get_contents("php://input"), true);

    // Controlla se la password è stata fornita
    if (!isset($input['password'])) {
        echo json_encode(['success' => false, 'error' => 'Password mancante.']);
        exit;
    }

    $password = $input['password'];

    // Verifica la password
    $stmt = $conn->prepare("SELECT Password FROM utente WHERE ID_Utente = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'error' => 'Utente non trovato.']);
        $stmt->close();
        exit;
    }

    $row = $result->fetch_assoc();
    if (!password_verify($password, $row['Password'])) {
        echo json_encode(['success' => false, 'error' => 'Password errata.']);
        $stmt->close();
        exit;
    }

    $stmt->close();

    // Elimina l'utente dal database
    $deleteStmt = $conn->prepare("DELETE FROM utente WHERE ID_Utente = ?");
    $deleteStmt->bind_param("i", $user_id);

    if ($deleteStmt->execute()) {
        // Distruggi la sessione
        session_destroy();
        echo json_encode(['success' => true, 'message' => 'Profilo eliminato con successo.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Errore durante l\'eliminazione del profilo.']);
    }

    $deleteStmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Metodo HTTP non supportato.']);
}

$conn->close();
?>
