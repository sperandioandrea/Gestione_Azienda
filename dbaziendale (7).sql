-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Dic 27, 2024 alle 16:34
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
-- Database: `dbaziendale`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `buste_paga`
--

CREATE TABLE `buste_paga` (
  `id` int(11) NOT NULL,
  `stipendio` decimal(10,2) DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `data_stipendio` date NOT NULL,
  `ore_mensili` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `buste_paga`
--

INSERT INTO `buste_paga` (`id`, `stipendio`, `id_user`, `data_stipendio`, `ore_mensili`) VALUES
(4, 1600.00, 8, '2024-11-27', 160),
(5, 2240.00, 10, '2024-11-27', 160),
(6, 2560.00, 16, '2024-11-27', 160),
(7, 1650.00, 17, '2024-12-27', 165);

-- --------------------------------------------------------

--
-- Struttura della tabella `carrello`
--

CREATE TABLE `carrello` (
  `id` int(11) NOT NULL,
  `quantita` int(11) NOT NULL,
  `prezzo_totale` decimal(10,2) NOT NULL,
  `id_compratore` int(11) NOT NULL,
  `id_prodotto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `prodotti_magazzino`
--

CREATE TABLE `prodotti_magazzino` (
  `id` int(11) NOT NULL,
  `nome_prodotto` varchar(100) NOT NULL,
  `quantita` int(11) NOT NULL DEFAULT 0,
  `prezzo` decimal(10,2) DEFAULT NULL,
  `descrizione` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `prodotti_magazzino`
--

INSERT INTO `prodotti_magazzino` (`id`, `nome_prodotto`, `quantita`, `prezzo`, `descrizione`) VALUES
(6, 'tavolo da biliardo', 4, 500.00, 'Tavolo da biliardo con superficie rettangolare rivestita in panno verde, dotata di sponde elastiche e sei buche distribuite agli angoli e sui lati lunghi. Realizzato in legno o metallo, offre un piano liscio e livellato, ideale per il gioco di precisione con stecche e bilie.'),
(7, 'ping pong', 2, 400.00, 'Ping pong con superficie rettangolare, divisa da una rete centrale, progettata per il gioco del ping pong, con un rivestimento che garantisce un rimbalzo uniforme della pallina.'),
(8, 'calciobalilla', 2, 700.00, 'Gioco da tavolo che simula il calcio, con piccole figure montate su barre rotanti, utilizzate per far scorrere una pallina e segnare gol contro l\'avversario.'),
(9, 'racchette ping pong', 8, 50.00, 'Realizzata con un manico ergonomico in legno di alta qualità e gomma resistente, garantisce un\'ottima aderenza e precisione nei colpi, perfetta per ogni livello di gioco. Disponibile in diverse varianti, ideali per principianti e professionisti.');

-- --------------------------------------------------------

--
-- Struttura della tabella `prodotti_vendita`
--

CREATE TABLE `prodotti_vendita` (
  `id` int(11) NOT NULL,
  `prezzo_unitario` decimal(10,2) NOT NULL,
  `id_prodotto` int(11) NOT NULL,
  `quantità` int(11) NOT NULL,
  `data_vendita` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `mansione` enum('dirigente','gestore vendite','gestore contabilita','magazziniere','compratore') DEFAULT NULL,
  `stipendio_orario` decimal(5,2) DEFAULT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `nome`, `cognome`, `email`, `username`, `password`, `mansione`, `stipendio_orario`, `data_creazione`) VALUES
(8, 'Alberto', 'Rossi', 'alberto.rossi@gmail.com', 'ARossi', '$2y$10$eq6dBzTqC9e2Z.FLNM/Khe2X0GyM7vXnAskO2mZ9gFtU421JhqyXe', 'magazziniere', NULL, '2024-12-26 21:16:37'),
(9, 'Andrea', 'Otto', 'andrea.otto@gmail.com', 'AOtto', '$2y$10$uUY6g4PvTHJitY08Rps.uOSvxWQ1zc9JMx37OC.dvC1XDHHWOJhuC', 'dirigente', NULL, '2024-12-26 21:18:01'),
(10, 'Aurora', 'Gallo', 'aurora.gallo@gmail.com', 'AGallo', '$2y$10$cZZ7WfFeUdPKYiMbtJ4g9.rQbfj3KFvgKwzp6sR67tzKHAkb92TMq', 'gestore vendite', NULL, '2024-12-26 21:46:04'),
(12, 'Luca', 'Rota', 'luca.rota@gmail.com', 'LRota', '$2y$10$zu0e8Gu2qEy809tHXp1pyOjXa1A6x8rcrXtcJZ48V0GxI9fEwvOc.', 'compratore', NULL, '2024-12-26 22:44:02'),
(15, 'Marcella', 'Gotti', 'marcella.gotti@gmail.com', 'MGotti', '$2y$10$r8fKvqUpIkBoK8aF3rDMZOhtSrl7g7MiLb4NjQyHuzw9dEOzWwruG', 'compratore', NULL, '2024-12-27 07:42:06'),
(16, 'Oscar', 'Ferrari', 'oscar.ferrari@gmail.com', 'OFerrari', '$2y$10$mmJlDhOrBl4UDleb7u8Ufe20/5A9Z6NdHWDIWFdhqCrvatZutvlvO', 'gestore contabilita', NULL, '2024-12-27 07:46:36'),
(17, 'Alessandro', 'Tonali', 'alessandro.tonali@gmail.com', 'ATonali', '$2y$10$FZoYSnOlL0AhRaL29Jg2/.4U3jpcuP.y4VJH9/otIXJgLmjvAlVMO', 'magazziniere', NULL, '2024-12-27 14:26:34');

-- --------------------------------------------------------

--
-- Struttura della tabella `vendite`
--

CREATE TABLE `vendite` (
  `id` int(11) NOT NULL,
  `quantità` int(11) NOT NULL,
  `prezzo` decimal(10,2) NOT NULL,
  `data_vendita` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_prodotto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `buste_paga`
--
ALTER TABLE `buste_paga`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utenti-buste_paga` (`id_user`);

--
-- Indici per le tabelle `carrello`
--
ALTER TABLE `carrello`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users-carrello` (`id_compratore`),
  ADD KEY `prodotti_magazzino-carrello` (`id_prodotto`);

--
-- Indici per le tabelle `prodotti_magazzino`
--
ALTER TABLE `prodotti_magazzino`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `prodotti_vendita`
--
ALTER TABLE `prodotti_vendita`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prodotti_magazzino-prodotti_vendite` (`id_prodotto`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unico_email` (`email`),
  ADD UNIQUE KEY `unico_utente` (`username`);

--
-- Indici per le tabelle `vendite`
--
ALTER TABLE `vendite`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prodotti_magazzino-prodotti_vendute` (`id_prodotto`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `buste_paga`
--
ALTER TABLE `buste_paga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `carrello`
--
ALTER TABLE `carrello`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `prodotti_magazzino`
--
ALTER TABLE `prodotti_magazzino`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `prodotti_vendita`
--
ALTER TABLE `prodotti_vendita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT per la tabella `vendite`
--
ALTER TABLE `vendite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `buste_paga`
--
ALTER TABLE `buste_paga`
  ADD CONSTRAINT `users-buste_paga` FOREIGN KEY (`id_user`) REFERENCES `utenti` (`id`);

--
-- Limiti per la tabella `carrello`
--
ALTER TABLE `carrello`
  ADD CONSTRAINT `prodotti_magazzino-carrello` FOREIGN KEY (`id_prodotto`) REFERENCES `prodotti_magazzino` (`id`),
  ADD CONSTRAINT `users-carrello` FOREIGN KEY (`id_compratore`) REFERENCES `utenti` (`id`);

--
-- Limiti per la tabella `prodotti_vendita`
--
ALTER TABLE `prodotti_vendita`
  ADD CONSTRAINT `prodotti_magazzino-prodotti_vendita` FOREIGN KEY (`id_prodotto`) REFERENCES `prodotti_magazzino` (`id`);

--
-- Limiti per la tabella `vendite`
--
ALTER TABLE `vendite`
  ADD CONSTRAINT `vendite-prodotti_vendita` FOREIGN KEY (`id_prodotto`) REFERENCES `prodotti_vendita` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
