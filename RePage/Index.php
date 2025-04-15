<?php
session_start();
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
            gap: 10px;
            align-items: center;
            position: relative;
        }
        .nav-buttons a {
            text-decoration: none;
            color: white;
            background: #007BFF;
            padding: 8px 12px;
            border-radius: 5px;
        }
        .nav-buttons a:hover {
            background: #0056b3;
        }
        .dropdown {
            position: relative;
        }
        .dropdown > a {
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 35px;
            background-color: #333;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            z-index: 1;
        }
        .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            border-bottom: 1px solid #444;
        }
        .dropdown-content a:last-child {
            border-bottom: none;
        }
        .dropdown-content a:hover {
            background-color: #575757;
        }
        .dropdown:hover .dropdown-content {
            display: block;
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
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
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
        $sql = "SELECT Titolo, Autore, Immagine FROM Libro";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($libro = $result->fetch_assoc()) {
                $img = htmlspecialchars($libro['Immagine']);
                $titolo = htmlspecialchars($libro['Titolo']);
                $autore = htmlspecialchars($libro['Autore']);
                echo "<div class='book'>
                        <img src='immagini/$img' alt='$titolo'>
                        <h4>$titolo</h4>
                        <p>$autore</p>
                      </div>";
            }
        } else {
            echo "<p>Nessun libro disponibile.</p>";
        }
        $conn->close();
        ?>
    </div>
</div>

</body>
</html>
