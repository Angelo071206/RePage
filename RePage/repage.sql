-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 15, 2025 alle 16:17
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
-- Struttura della tabella `libro`
--

CREATE TABLE `libro` (
  `ID_Libro` int(11) NOT NULL,
  `Titolo` varchar(255) NOT NULL,
  `Autore` varchar(100) NOT NULL,
  `ISBN` varchar(20) NOT NULL,
  `Condizione` enum('Nuovo','Ottimo','Buono','Accettabile','Scadente') NOT NULL,
  `Descrizione` text DEFAULT NULL,
  `immagine` varchar(255) DEFAULT 'default.jpg',
  `ID_Utente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `messaggio`
--

CREATE TABLE `messaggio` (
  `ID_Messaggio` int(11) NOT NULL,
  `ID_Utente_Mittente` int(11) NOT NULL,
  `ID_Utente_Destinatario` int(11) NOT NULL,
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
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `libro`
--
ALTER TABLE `libro`
  ADD PRIMARY KEY (`ID_Libro`),
  ADD UNIQUE KEY `ISBN` (`ISBN`),
  ADD KEY `ID_Utente` (`ID_Utente`);

--
-- Indici per le tabelle `messaggio`
--
ALTER TABLE `messaggio`
  ADD PRIMARY KEY (`ID_Messaggio`),
  ADD KEY `ID_Utente_Mittente` (`ID_Utente_Mittente`),
  ADD KEY `ID_Utente_Destinatario` (`ID_Utente_Destinatario`);

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
-- AUTO_INCREMENT per la tabella `libro`
--
ALTER TABLE `libro`
  MODIFY `ID_Libro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `messaggio`
--
ALTER TABLE `messaggio`
  MODIFY `ID_Messaggio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `ID_Utente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `libro`
--
ALTER TABLE `libro`
  ADD CONSTRAINT `libro_ibfk_1` FOREIGN KEY (`ID_Utente`) REFERENCES `utente` (`ID_Utente`);

--
-- Limiti per la tabella `messaggio`
--
ALTER TABLE `messaggio`
  ADD CONSTRAINT `messaggio_ibfk_1` FOREIGN KEY (`ID_Utente_Mittente`) REFERENCES `utente` (`ID_Utente`) ON DELETE CASCADE,
  ADD CONSTRAINT `messaggio_ibfk_2` FOREIGN KEY (`ID_Utente_Destinatario`) REFERENCES `utente` (`ID_Utente`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
