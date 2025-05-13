<?php
session_start();
header('Content-Type: application/json');
require_once '../connessione.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    echo json_encode(["success" => false, "error" => "Utente non autenticato."]);
    exit;
}

$titolo = trim($_POST['titolo'] ?? "");
$autore = trim($_POST['autore'] ?? "");
$isbn = trim($_POST['isbn'] ?? "");
$condizione = $_POST['condizione'] ?? "";
$descrizione = trim($_POST['descrizione'] ?? "");
$id_utente = $_SESSION['user']['id'];

if (empty($titolo) || empty($autore) || empty($isbn) || empty($condizione)) {
    echo json_encode(["success" => false, "error" => "Tutti i campi obbligatori devono essere compilati."]);
    exit;
}

// Gestione upload immagine
$immagine_nome = "default.jpg";
if (isset($_FILES['immagine']) && $_FILES['immagine']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['immagine']['name'], PATHINFO_EXTENSION);
    $immagine_nome = uniqid('libro_') . "." . strtolower($ext);
    move_uploaded_file($_FILES['immagine']['tmp_name'], "../upload/" . $immagine_nome);
}

$stmt = $conn->prepare("INSERT INTO libro (Titolo, Autore, ISBN, Condizione, Descrizione, immagine, ID_Utente) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssi", $titolo, $autore, $isbn, $condizione, $descrizione, $immagine_nome, $id_utente);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Libro aggiunto con successo!", "redirect" => "../profilo.html"]);
} else {
    echo json_encode(["success" => false, "error" => "Errore durante l'aggiunta del libro."]);
}

$stmt->close();
$conn->close();
?>
