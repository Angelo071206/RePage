-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Apr 16, 2025 alle 09:19
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.0.30

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
  `Descrizione` text DEFAULT NULL,
  `immagine` varchar(255) DEFAULT 'default.jpg',
  `ID_Utente` int(11) DEFAULT NULL
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
(9, 'Tommaso', 'Bassoni', 'tb@gmail.com', '$2y$10$5qaRXjBbZ1VUpCHWhL3vWuMlup0SOqf7eDx9rn0Q4oWKi3eu5qnvy', '2025-04-11 14:58:19'),
(10, 'Stizza', 'Matteo', 'st@gmail.com', '$2y$10$X.jQH2YIOe2/vXqioQdVRO/uQEa..MDkCivm.3ME//31cSmQKcuwy', '2025-04-11 15:20:05'),
(11, 'Stizza', 'Matteo', '67@gm.c', '$2y$10$.AC4YBd3yjWukvfbXFR7CuxkTbXOje4wTzhAkOVI1ioTH6LK.QL3q', '2025-04-11 15:33:37'),
(12, 'Stizza', 'Matteo', '68@gm.c', '$2y$10$LNNQSVoqFrKaMZP/iHFgbeHzYpb.vgucOX/VFaL73qsCpflWu/av6', '2025-04-11 15:35:45'),
(13, 'Luca', 'Neri', 'luca@example.com', '$2y$10$yrS6vAN9Q6be7DCDrALvb.jxezVerudol6/skGmuT2SmFWIWfpjDK', '2025-04-11 15:51:55'),
(14, 'Stizza', 'Matteo', '6te7@gm.c', '$2y$10$TQg.j3AwjqBkkS/9Q3kFROT0kYCM4pSk3tXq/oSePKYIhnvxx1obK', '2025-04-11 16:41:59'),
(15, 'Stizza', 'Matteo', '6t6e7@gm.c', '$2y$10$v459tuzhi2Ub0FvXELxOe.PJ8ZlDUsog9y7OvHxDyE4uOJkK0CVou', '2025-04-11 16:42:28'),
(16, 'Angelo', 'Depalo', 'ad5@g.com', '$2y$10$EOAEXDTq/Ri60HsRIJgCi.JqHwkUqj2vWvwkAyUFTYB8wdynpNDEu', '2025-04-11 16:43:51'),
(17, 'Stizza', 'Matteo', 'st597@gmail.com', '$2y$10$YrBloOxjc9qwqqhssxW0funPdq8j0/q.WRtyOxDsFkgfjgpEJt0zS', '2025-04-11 16:56:14'),
(18, 'matteo', 'stizza', 'ms1@g.com', '$2y$10$YegKh2fIUrs8TDnS94VryuRgQRadX0THXngyw6Zq9RD.7bmTR0CiG', '2025-04-11 17:22:03'),
(19, 'lorenzo', 'tremolanti', 'tm@gm.com', '$2y$10$HgcAzL6vckTAeukc7LpQW.hkH2uORndmdG6zepQUiQQSXjz6mqRqS', '2025-04-16 06:23:09'),
(20, 'tommaso', 'buselli', 'tb@gm.com', '$2y$10$GQm2jZYbodeshbUC5yGwJ.TLEfhPec.9abrBTZSrf6An1j9nmXUDq', '2025-04-16 06:50:58'),
(21, 'tommaso', 'buselli', 'tbus@gm.com', '$2y$10$5eAXoWPuSMJr0h6oXaMdseKRBP9QTFwoT.DKsSNEXreIMoB4aThiW', '2025-04-16 06:51:14'),
(22, 'tommaso', 'buselli', 'tbus@gmail.com', '$2y$10$iTwGzCzA96YXtoT3CNj2y.2I1SUL4YBS8nsaLLRlZhLzBXMrR2qTq', '2025-04-16 06:54:12'),
(23, 'tommaso', 'buselli', 'bus@gmil.com', '$2y$10$hCx6Ah1fymP3VflBIv.speHT9LHSbo31tBgH2EB64kenG.CjgySgm', '2025-04-16 06:54:36'),
(24, 'ett', 'as', 'ettas@gmail.com', '$2y$10$pyRWrcrl7oxisJK/MEyOAuFuHQxv8w9EzCDe3nNQftxz3kwNNZsNe', '2025-04-16 06:56:27'),
(25, 'ett', 'as', 'etta443s@gmail.com', '$2y$10$rHysfFfScB71JE9FhYMo1efGMBXDbYqnbuuDsqKoP9xNcG9tpvyNe', '2025-04-16 07:00:57'),
(26, 'ett', 'as', 'etta55453s@gmail.com', '$2y$10$285hNPyfjzlFsfUsgmtk3OGX8b.hgl/plL2Kn900O61lt/4rY1XXW', '2025-04-16 07:02:03'),
(27, 'tommaso', 'buselli', 'tommasobus@gmail.com', '$2y$10$JoeG9w1WHh0PkEh5.laJfuZ3ym5MprPSsfORfBYErL9aATVHkpHae', '2025-04-16 07:04:02');

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
  ADD UNIQUE KEY `ISBN` (`ISBN`),
  ADD KEY `ID_Utente` (`ID_Utente`);

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
  MODIFY `ID_Utente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

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
-- Limiti per la tabella `libro`
--
ALTER TABLE `libro`
  ADD CONSTRAINT `libro_ibfk_1` FOREIGN KEY (`ID_Utente`) REFERENCES `utente` (`ID_Utente`);

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
