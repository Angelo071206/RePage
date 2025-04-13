-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Apr 11, 2025 alle 17:14
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `repage`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `annuncio`
--

CREATE TABLE `annuncio` (
  `ID_Annuncio` int(11) NOT NULL,
  `ID_Utente` int(11) NOT NULL,
  `ID_Libro` int(11) NOT NULL,
  `Data_Pubblicazione` timestamp NOT NULL DEFAULT current_timestamp(),
  `Stato` enum('Disponibile','Scambiato','Ritirato') DEFAULT 'Disponibile'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `libro`
--

CREATE TABLE `libro` (
  `ID_Libro` int(11) NOT NULL,
  `Titolo` varchar(255) NOT NULL,
  `Autore` varchar(100) NOT NULL,
  `ISBN` varchar(20) NOT NULL,
  `Materia` varchar(50) DEFAULT NULL,
  `Condizione` enum('Nuovo','Ottimo','Buono','Accettabile','Scadente') NOT NULL,
  `Descrizione` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `logaccessi`
--

CREATE TABLE `logaccessi` (
  `ID_Log` int(11) NOT NULL,
  `ID_Utente` int(11) NOT NULL,
  `Data_Accesso` timestamp NOT NULL DEFAULT current_timestamp(),
  `Indirizzo_IP` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `messaggio`
--

CREATE TABLE `messaggio` (
  `ID_Messaggio` int(11) NOT NULL,
  `ID_Utente_Mittente` int(11) NOT NULL,
  `ID_Utente_Destinatario` int(11) NOT NULL,
  `ID_Annuncio` int(11) NOT NULL,
  `Testo` text NOT NULL,
  `Data_Ora` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `ID_Utente` int(11) NOT NULL,
  `Nome` varchar(50) NOT NULL,
  `Cognome` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Data_Registrazione` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`ID_Utente`, `Nome`, `Cognome`, `Email`, `Password`, `Data_Registrazione`) VALUES
(1, 'Angelo', 'Depalo', 'ad@gm.com', '$2y$10$0HfkvkaQ31TO7yktT3Sr3.ROOFrSnCUyUiI3iWVW02Hc3LizEm7yK', '2025-03-26 08:03:10'),
(5, 'dyh', 'thfth', 'Aam@gmail.com', '$2y$10$0vWLPTCsVJzeFATjTnLYi.prd409DIbEqy.7GFI6YpVLkhmjQqHjO', '2025-03-26 08:20:12'),
(7, 'dyhthfht', 'thfthhft', 'Aahffthm@gmail.com', '$2y$10$hKoHlyj7C8Fjt7Jcf/S7ouAOWN1i2aRq9yW4tZekvUBbPlDoBmPj.', '2025-03-26 08:20:39'),
(8, 'Francesco', 'depalo', 'depalo.francesco@inwind.it', '$2y$10$QWH0hxUi0iGRMRibkwltq.KtKdIFRQyFkIk0vw1956e9ZxHBfJ8CS', '2025-03-26 13:30:10'),
(9, 'Tommaso', 'Bassoni', 'tb@gmail.com', '$2y$10$5qaRXjBbZ1VUpCHWhL3vWuMlup0SOqf7eDx9rn0Q4oWKi3eu5qnvy', '2025-04-11 14:58:19');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `annuncio`
--
ALTER TABLE `annuncio`
  ADD PRIMARY KEY (`ID_Annuncio`),
  ADD KEY `ID_Utente` (`ID_Utente`),
  ADD KEY `ID_Libro` (`ID_Libro`);

--
-- Indici per le tabelle `libro`
--
ALTER TABLE `libro`
  ADD PRIMARY KEY (`ID_Libro`),
  ADD UNIQUE KEY `ISBN` (`ISBN`);

--
-- Indici per le tabelle `logaccessi`
--
ALTER TABLE `logaccessi`
  ADD PRIMARY KEY (`ID_Log`),
  ADD KEY `ID_Utente` (`ID_Utente`);

--
-- Indici per le tabelle `messaggio`
--
ALTER TABLE `messaggio`
  ADD PRIMARY KEY (`ID_Messaggio`),
  ADD KEY `ID_Utente_Mittente` (`ID_Utente_Mittente`),
  ADD KEY `ID_Utente_Destinatario` (`ID_Utente_Destinatario`),
  ADD KEY `ID_Annuncio` (`ID_Annuncio`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`ID_Utente`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `annuncio`
--
ALTER TABLE `annuncio`
  MODIFY `ID_Annuncio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `libro`
--
ALTER TABLE `libro`
  MODIFY `ID_Libro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `logaccessi`
--
ALTER TABLE `logaccessi`
  MODIFY `ID_Log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `messaggio`
--
ALTER TABLE `messaggio`
  MODIFY `ID_Messaggio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `ID_Utente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `annuncio`
--
ALTER TABLE `annuncio`
  ADD CONSTRAINT `annuncio_ibfk_1` FOREIGN KEY (`ID_Utente`) REFERENCES `utente` (`ID_Utente`) ON DELETE CASCADE,
  ADD CONSTRAINT `annuncio_ibfk_2` FOREIGN KEY (`ID_Libro`) REFERENCES `libro` (`ID_Libro`) ON DELETE CASCADE;

--
-- Limiti per la tabella `logaccessi`
--
ALTER TABLE `logaccessi`
  ADD CONSTRAINT `logaccessi_ibfk_1` FOREIGN KEY (`ID_Utente`) REFERENCES `utente` (`ID_Utente`) ON DELETE CASCADE;

--
-- Limiti per la tabella `messaggio`
--
ALTER TABLE `messaggio`
  ADD CONSTRAINT `messaggio_ibfk_1` FOREIGN KEY (`ID_Utente_Mittente`) REFERENCES `utente` (`ID_Utente`) ON DELETE CASCADE,
  ADD CONSTRAINT `messaggio_ibfk_2` FOREIGN KEY (`ID_Utente_Destinatario`) REFERENCES `utente` (`ID_Utente`) ON DELETE CASCADE,
  ADD CONSTRAINT `messaggio_ibfk_3` FOREIGN KEY (`ID_Annuncio`) REFERENCES `annuncio` (`ID_Annuncio`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
