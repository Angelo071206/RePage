<?php
header('Content-Type: application/json');
require_once '../connessione.php';

if (!isset($_POST['id_libro'])) {
    echo json_encode(['success' => false, 'error' => 'ID libro mancante']);
    exit;
}

$id_libro = intval($_POST['id_libro']);
$titolo = trim($_POST['titolo']);
$autore = trim($_POST['autore']);
$isbn = trim($_POST['isbn']);
$condizione = trim($_POST['condizione']);
$descrizione = trim($_POST['descrizione']);
$immagine_nome = null;

// Gestione upload immagine
if (isset($_FILES['immagine']) && $_FILES['immagine']['error'] === UPLOAD_ERR_OK) {
    $target_dir = "../upload/";
    $file_ext = pathinfo($_FILES['immagine']['name'], PATHINFO_EXTENSION);
    $immagine_nome = uniqid('libro_', true) . '.' . $file_ext;
    $target_file = $target_dir . $immagine_nome;

    if (!move_uploaded_file($_FILES['immagine']['tmp_name'], $target_file)) {
        echo json_encode(['success' => false, 'error' => 'Errore durante il salvataggio dell\'immagine.']);
        exit;
    }
}

// Prepara SQL per aggiornare con o senza immagine
if ($immagine_nome) {
    $sql = "UPDATE libro SET Titolo=?, Autore=?, ISBN=?, Condizione=?, Descrizione=?, immagine=? WHERE ID_Libro=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $titolo, $autore, $isbn, $condizione, $descrizione, $immagine_nome, $id_libro);
} else {
    $sql = "UPDATE libro SET Titolo=?, Autore=?, ISBN=?, Condizione=?, Descrizione=? WHERE ID_Libro=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $titolo, $autore, $isbn, $condizione, $descrizione, $id_libro);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Errore durante l\'aggiornamento del libro.']);
}

$stmt->close();
$conn->close();
?>

