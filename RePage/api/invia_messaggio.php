<?php
session_start(); // Avvia la sessione
require 'connessione.php'; // Connessione al database

// Verifica che l'utente sia loggato
if (isset($_SESSION['user']) && isset($_SESSION['user']['ID_Utente']) && isset($_POST['ID_Libro'])) {
    $userId = $_SESSION['user']['ID_Utente']; // Ottieni l'ID utente loggato
    $idLibro = $_POST['ID_Libro']; // Ottieni l'ID del libro che l'utente vuole contattare
    $testo = "Messaggio relativo al libro ID: " . $idLibro; // Questo può essere personalizzato come preferisci

    // Verifica che il libro esista
    $sql_check_libro = "SELECT ID_Libro FROM Libro WHERE ID_Libro = ?";
    if ($stmt_check = $conn->prepare($sql_check_libro)) {
        $stmt_check->bind_param("i", $idLibro);
        $stmt_check->execute();
        $result_check_libro = $stmt_check->get_result();

        if ($result_check_libro->num_rows > 0) {
            // Inserisci il messaggio nel database
            $sql_insert = "INSERT INTO messaggio (ID_Utente_Mittente, ID_Utente_Destinatario, ID_Annuncio, Testo) 
                           SELECT ?, ID_Utente, ID_Annuncio, ? 
                           FROM Libro 
                           WHERE ID_Libro = ?";

            if ($stmt = $conn->prepare($sql_insert)) {
                // Passiamo l'ID dell'utente mittente, il testo del messaggio e l'ID del libro
                $stmt->bind_param("isi", $userId, $testo, $idLibro);
                $stmt->execute();

                // Risposta di successo
                echo json_encode(["success" => true, "message" => "Messaggio inviato con successo!"]);
            } else {
                // Errore nell'inserimento del messaggio
                echo json_encode(["success" => false, "message" => "Errore nell'invio del messaggio."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Il libro selezionato non esiste."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Errore nella verifica del libro."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Devi essere loggato per inviare un messaggio."]);
}
?>
