<?php

require_once 'connessione.php'; // Connessione al database

// Query per selezionare tutti gli utenti
$sql = "SELECT ID_Utente, Nome, Cognome, Email, Data_Registrazione FROM Utente";
$result = $conn->query($sql);

// Verifica se ci sono risultati
if ($result->num_rows > 0) {
    echo "<h2>Lista Utenti Registrati</h2>";
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Email</th>
                <th>Data Registrazione</th>
            </tr>";

    // Stampa i dati degli utenti
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["ID_Utente"] . "</td>
                <td>" . $row["Nome"] . "</td>
                <td>" . $row["Cognome"] . "</td>
                <td>" . $row["Email"] . "</td>
                <td>" . $row["Data_Registrazione"] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "Non ci sono utenti registrati.";
}

// Chiudi la connessione
$conn->close();
?>
