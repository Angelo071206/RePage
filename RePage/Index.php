<?php
session_start(); // Avvia la sessione
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Benvenuto in RePage</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }

        header {
            background: #333;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            font-size: 20px;
        }

        .nav-buttons {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .nav-buttons a {
            text-decoration: none;
            color: white;
            background: #007BFF;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 14px;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .nav-buttons a:hover {
            background: #0056b3;
            transform: scale(1.1);
        }

        .nav-buttons .dropdown {
            position: relative;
        }

        .nav-buttons .dropdown a {
            background: #28a745;
            padding: 8px 15px;
        }

        .nav-buttons .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #333;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            min-width: 150px;
            padding: 5px 0;
            z-index: 999;
        }

        .nav-buttons .dropdown-content a {
            padding: 8px 15px;
            text-align: left;
        }

        .nav-buttons .dropdown:hover .dropdown-content {
            display: block;
        }

        .nav-buttons .dropdown-content a:hover {
            background: #444;
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        .book-list {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .book {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 180px;
            padding: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .book img {
            width: 100%;
            height: auto;
            border-radius: 4px;
        }

        .book h4 {
            margin: 10px 0 5px 0;
            font-size: 16px;
        }

        .book p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }

        .popup-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            display: none;
            justify-content: center;
            align-items: center;
        }

        .popup {
            background: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            text-align: left;
        }

        .popup img {
            width: 100%;
            margin-top: 10px;
            border-radius: 4px;
        }

        .popup button {
            background: #007BFF;
            color: white;
            border: none;
            padding: 10px 15px;
            margin-top: 15px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .popup button:hover {
            background: #0056b3;
            transform: scale(1.05);
        }

        .chat-list {
            margin-top: 40px;
        }

        .chat-list ul {
            list-style-type: none;
            padding: 0;
        }

        .chat-list li {
            background-color: #fff;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            cursor: pointer;
        }

        .chat-list li:hover {
            background-color: #f0f0f0;
        }

        .no-chats {
            color: #777;
            font-style: italic;
        }
    </style>
</head>
<body>

<header>
    <h1>RePage</h1>
    <div class="nav-buttons">
        <?php if (!isset($_SESSION['user'])): ?>
            <a href="login.html">Login</a>
            <a href="registrazione.html">Registrati</a>
        <?php else: ?>
            <div class="dropdown">
                <a href="#">Profilo</a>
                <div class="dropdown-content">
                    <a href="profilo.html">Il mio Profilo</a>
                    <a href="impostazioni.html">Impostazioni</a>
                </div>
            </div>
            <a href="logout.php">Esci</a>
        <?php endif; ?>
    </div>
</header>

<div class="content">
    <h2>Libri disponibili</h2>
    <div class="book-list">
        <?php
        require 'connessione.php';

        $sql = "SELECT ID_Libro, Titolo, Autore, ISBN, Condizione, Descrizione, immagine FROM Libro";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($libro = $result->fetch_assoc()) {
                $id = htmlspecialchars($libro['ID_Libro']);
                $titolo = htmlspecialchars($libro['Titolo']);
                $autore = htmlspecialchars($libro['Autore']);
                $isbn = htmlspecialchars($libro['ISBN']);
                $condizione = htmlspecialchars($libro['Condizione']);
                $descrizione = htmlspecialchars($libro['Descrizione']);
                $img = isset($libro['immagine']) ? htmlspecialchars($libro['immagine']) : '';
                $imgPath = 'upload/' . $img;
                if (!$img || !file_exists($imgPath)) {
                    $imgPath = 'upload/default.jpg';
                }

                echo "<div class='book' onclick='apriPopup(\"$titolo\",\"$autore\",\"$isbn\",\"$condizione\",\"$descrizione\",\"$imgPath\")'>
                        <img src='$imgPath' alt='$titolo'>
                        <h4>$titolo</h4>
                        <p>$autore</p>
                      </div>";
            }
        } else {
            echo "<p>Nessun libro disponibile.</p>";
        }
        ?>
    </div>

    <div class="chat-list">
        <h2>Le tue Chat</h2>
        <?php
        // Verifica che l'utente sia loggato
        if (isset($_SESSION['user']) && isset($_SESSION['user']['ID_Utente'])) {
            $userId = $_SESSION['user']['ID_Utente'];

            $sql_chat = "SELECT DISTINCT 
                            CASE 
                                WHEN ID_Utente_Mittente = ? THEN ID_Utente_Destinatario 
                                ELSE ID_Utente_Mittente 
                            END AS ID_Utente
                         FROM messaggio 
                         WHERE ID_Utente_Mittente = ? OR ID_Utente_Destinatario = ?";

            if ($stmt = $conn->prepare($sql_chat)) {
                $stmt->bind_param("iii", $userId, $userId, $userId);
                $stmt->execute();
                $result_chat = $stmt->get_result();

                if ($result_chat->num_rows > 0) {
                    echo "<ul>";
                    while ($chat = $result_chat->fetch_assoc()) {
                        $chatId = $chat['ID_Utente'];
                        echo "<li onclick='window.location.href=\"chat.php?id=$chatId\"'>Chat con Utente $chatId</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p class='no-chats'>Nessuna chat esistente.</p>";
                }
            }
        } else {
            echo "<p class='no-chats'>Devi essere loggato per visualizzare le chat.</p>";
        }
        ?>
    </div>
</div>

<div class="popup-overlay" id="popupOverlay">
    <div class="popup" id="popupContent">
        <h3 id="popupTitolo"></h3>
        <p><strong>Autore:</strong> <span id="popupAutore"></span></p>
        <p><strong>ISBN:</strong> <span id="popupISBN"></span></p>
        <p><strong>Condizione:</strong> <span id="popupCondizione"></span></p>
        <p><strong>Descrizione:</strong> <span id="popupDescrizione"></span></p>
        <img id="popupImg" src="" alt="Immagine libro">
        <button onclick="contatta()">Contatta</button>
        <button onclick="chiudiPopup()">Chiudi</button>
    </div>
</div>

<script>
function apriPopup(titolo, autore, isbn, condizione, descrizione, imgPath, idLibro) {
    document.getElementById('popupTitolo').textContent = titolo;
    document.getElementById('popupAutore').textContent = autore;
    document.getElementById('popupISBN').textContent = isbn;
    document.getElementById('popupCondizione').textContent = condizione;
    document.getElementById('popupDescrizione').textContent = descrizione;
    document.getElementById('popupImg').src = imgPath;
    document.getElementById('popupOverlay').style.display = 'flex';

    // Passa l'ID del libro al pulsante "Contatta"
    document.getElementById('contattaBtn').setAttribute('data-id-libro', idLibro);
}


function chiudiPopup() {
    document.getElementById('popupOverlay').style.display = 'none';
}

function contatta() {
    const titolo = document.getElementById('popupTitolo').textContent;
    const autore = document.getElementById('popupAutore').textContent;
    const idLibro = "<?php echo isset($id) ? $id : '' ?>"; // Assicurati che questo valore venga passato dinamicamente

    // Fai una richiesta AJAX al server per inviare il messaggio
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "api/invia_messaggio.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function() {
        const response = JSON.parse(xhr.responseText);
        if (response.success) {
            alert(response.message); // Mostra un messaggio di successo
        } else {
            alert(response.message); // Mostra un messaggio di errore
        }
    };

    // Invia la richiesta
    xhr.send("ID_Libro=" + idLibro);
}
</script>

</body>
</html>
