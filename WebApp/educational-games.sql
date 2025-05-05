-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 05, 2025 at 11:25 AM
-- Server version: 5.7.11
-- PHP Version: 5.6.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `educational-games`
--

-- --------------------------------------------------------

--
-- Table structure for table `argomento`
--

CREATE TABLE `argomento` (
  `IdArgomento` int(11) NOT NULL,
  `Titolo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `argomento`
--

INSERT INTO `argomento` (`IdArgomento`, `Titolo`) VALUES
(1, 'Addizioni e sottrazioni'),
(2, 'Analisi grammaticale'),
(3, 'Capitali europee'),
(4, 'Leggi della fisica'),
(5, 'Epoche storiche');

-- --------------------------------------------------------

--
-- Table structure for table `classevirtuale`
--

CREATE TABLE `classevirtuale` (
  `IdClasse` int(11) NOT NULL,
  `Classe` varchar(200) NOT NULL,
  `Materia` varchar(200) NOT NULL,
  `CodiceFiscaleDocente` char(16) NOT NULL,
  `CodiceAccesso` char(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `classevirtuale`
--

INSERT INTO `classevirtuale` (`IdClasse`, `Classe`, `Materia`, `CodiceFiscaleDocente`, `CodiceAccesso`) VALUES
(1, '3A', 'Matematica', 'RSSMRA85M01H501Z', 'ABC123'),
(2, '2B', 'Italiano', 'BNCLRA70A01F205X', 'ITA456'),
(3, '1C', 'Geografia', 'VRDGNN60C45L219H', 'GEO789'),
(4, '4D', 'Scienze', 'MNTLSS71P60F205L', 'SCI101');

-- --------------------------------------------------------

--
-- Table structure for table `classe_videogioco`
--

CREATE TABLE `classe_videogioco` (
  `IdClasse` int(11) NOT NULL,
  `IdVideogioco` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `classe_videogioco`
--

INSERT INTO `classe_videogioco` (`IdClasse`, `IdVideogioco`) VALUES
(1, 1),
(2, 2),
(3, 3),
(3, 4),
(4, 4),
(1, 5),
(2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `docente`
--

CREATE TABLE `docente` (
  `CodiceFiscale` char(16) NOT NULL,
  `Nome` varchar(200) NOT NULL,
  `Cognome` varchar(200) NOT NULL,
  `Password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `docente`
--

INSERT INTO `docente` (`CodiceFiscale`, `Nome`, `Cognome`, `Password`) VALUES
('BNCLRA70A01F205X', 'Laura', 'Bianchi', 'laura2023'),
('MNTLSS71P60F205L', 'Luigi', 'Montella', 'luigi789'),
('RSSMRA85M01H501Z', 'Mario', 'Rossi', 'password123'),
('VRDGNN60C45L219H', 'Gianna', 'Verdi', 'gverdi60');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `IdVideogioco` int(11) NOT NULL,
  `CodiceFiscale` char(16) NOT NULL,
  `Punteggio` int(11) NOT NULL,
  `Testo` varchar(160) DEFAULT NULL,
  `Orario` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`IdVideogioco`, `CodiceFiscale`, `Punteggio`, `Testo`, `Orario`) VALUES
(1, 'PLLMRA03C12H501U', 5, 'Gioco molto utile per ripassare!', '2025-05-05 10:55:39'),
(2, 'NCLFNC04D22G273F', 4, 'Divertente, ma un po’ difficile.', '2025-05-05 10:55:39'),
(3, 'BRTDNL05E11H501C', 5, 'Ottimo per imparare la grammatica.', '2025-05-05 10:55:39'),
(4, 'VRLSRA06A01L219E', 5, 'Mi è piaciuto esplorare nuove nazioni!', '2025-05-05 10:55:39'),
(5, 'MMTLLT07R12H501F', 4, 'Bel gioco, ma sarebbe bello aggiungere più livelli.', '2025-05-05 10:55:39');

-- --------------------------------------------------------

--
-- Table structure for table `iscrizione`
--

CREATE TABLE `iscrizione` (
  `IdClasse` int(11) NOT NULL,
  `CodiceFiscale` char(16) NOT NULL,
  `Orario` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `iscrizione`
--

INSERT INTO `iscrizione` (`IdClasse`, `CodiceFiscale`, `Orario`) VALUES
(1, 'BLCCNT13T65F205M', '2025-05-05 10:55:39'),
(1, 'BRTDNL05E11H501C', '2025-05-05 10:55:39'),
(1, 'CFLLNS14F75L219X', '2025-05-05 10:55:39'),
(1, 'MGLFNC11R45H501K', '2025-05-05 10:55:39'),
(1, 'MMTLLT07R12H501F', '2025-05-05 10:55:39'),
(1, 'NCLFNC04D22G273F', '2025-05-05 10:55:39'),
(1, 'PLLMRA03C12H501U', '2025-05-05 10:55:39'),
(1, 'RLGVNC10A35H501P', '2025-05-05 10:55:39'),
(1, 'SGRFRN09S25F205R', '2025-05-05 10:55:39'),
(1, 'SNRVNC12R55L219B', '2025-05-05 10:55:39'),
(1, 'VLSMNT08D14F205G', '2025-05-05 10:55:39'),
(1, 'VRLSRA06A01L219E', '2025-05-05 10:55:39'),
(1, 'ZNCGLS15T85H501F', '2025-05-05 10:55:39'),
(2, 'BLCCNT13T65F205M', '2025-05-05 10:55:39'),
(2, 'BRTDNL05E11H501C', '2025-05-05 10:55:39'),
(2, 'CFLLNS14F75L219X', '2025-05-05 10:55:39'),
(2, 'MGLFNC11R45H501K', '2025-05-05 10:55:39'),
(2, 'MMTLLT07R12H501F', '2025-05-05 10:55:39'),
(2, 'NCLFNC04D22G273F', '2025-05-05 10:55:39'),
(2, 'PLLMRA03C12H501U', '2025-05-05 10:55:39'),
(2, 'RLGVNC10A35H501P', '2025-05-05 10:55:39'),
(2, 'SGRFRN09S25F205R', '2025-05-05 10:55:39'),
(2, 'SNRVNC12R55L219B', '2025-05-05 10:55:39'),
(2, 'VLSMNT08D14F205G', '2025-05-05 10:55:39'),
(2, 'VRLSRA06A01L219E', '2025-05-05 10:55:39'),
(2, 'ZNCGLS15T85H501F', '2025-05-05 10:55:39'),
(3, 'BLCCNT13T65F205M', '2025-05-05 10:55:39'),
(3, 'BRTDNL05E11H501C', '2025-05-05 10:55:39'),
(3, 'CFLLNS14F75L219X', '2025-05-05 10:55:39'),
(3, 'MGLFNC11R45H501K', '2025-05-05 10:55:39'),
(3, 'MMTLLT07R12H501F', '2025-05-05 10:55:39'),
(3, 'NCLFNC04D22G273F', '2025-05-05 10:55:39'),
(3, 'PLLMRA03C12H501U', '2025-05-05 10:55:39'),
(3, 'RLGVNC10A35H501P', '2025-05-05 10:55:39'),
(3, 'SGRFRN09S25F205R', '2025-05-05 10:55:39'),
(3, 'SNRVNC12R55L219B', '2025-05-05 10:55:39'),
(3, 'VLSMNT08D14F205G', '2025-05-05 10:55:39'),
(3, 'VRLSRA06A01L219E', '2025-05-05 10:55:39'),
(3, 'ZNCGLS15T85H501F', '2025-05-05 10:55:39'),
(4, 'BLCCNT13T65F205M', '2025-05-05 10:55:39'),
(4, 'BRTDNL05E11H501C', '2025-05-05 10:55:39'),
(4, 'CFLLNS14F75L219X', '2025-05-05 10:55:39'),
(4, 'MGLFNC11R45H501K', '2025-05-05 10:55:39'),
(4, 'MMTLLT07R12H501F', '2025-05-05 10:55:39'),
(4, 'NCLFNC04D22G273F', '2025-05-05 10:55:39'),
(4, 'PLLMRA03C12H501U', '2025-05-05 10:55:39'),
(4, 'RLGVNC10A35H501P', '2025-05-05 10:55:39'),
(4, 'SGRFRN09S25F205R', '2025-05-05 10:55:39'),
(4, 'SNRVNC12R55L219B', '2025-05-05 10:55:39'),
(4, 'VLSMNT08D14F205G', '2025-05-05 10:55:39'),
(4, 'VRLSRA06A01L219E', '2025-05-05 10:55:39'),
(4, 'ZNCGLS15T85H501F', '2025-05-05 10:55:39');

-- --------------------------------------------------------

--
-- Table structure for table `partita`
--

CREATE TABLE `partita` (
  `CodiceFiscale` char(16) NOT NULL,
  `IdVideogioco` int(11) NOT NULL,
  `Orario` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Monete` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `partita`
--

INSERT INTO `partita` (`CodiceFiscale`, `IdVideogioco`, `Orario`, `Monete`) VALUES
('BRTDNL05E11H501C', 2, '2025-05-05 10:55:39', 75),
('MMTLLT07R12H501F', 4, '2025-05-05 10:55:39', 80),
('NCLFNC04D22G273F', 1, '2025-05-05 10:55:39', 90),
('PLLMRA03C12H501U', 1, '2025-05-05 10:55:39', 85),
('VLSMNT08D14F205G', 5, '2025-05-05 10:55:39', 100),
('VRLSRA06A01L219E', 3, '2025-05-05 10:55:39', 95);

-- --------------------------------------------------------

--
-- Table structure for table `studente`
--

CREATE TABLE `studente` (
  `CodiceFiscale` char(16) NOT NULL,
  `Nome` varchar(200) NOT NULL,
  `Cognome` varchar(200) NOT NULL,
  `Password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `studente`
--

INSERT INTO `studente` (`CodiceFiscale`, `Nome`, `Cognome`, `Password`) VALUES
('BLCCNT13T65F205M', 'Tommaso', 'Bianchi', 'tommy13333'),
('BRTDNL05E11H501C', 'Daniele', 'Bertoli', 'bert05'),
('CFLLNS14F75L219X', 'Nicole', 'Cipriani', 'nicole14'),
('MGLFNC11R45H501K', 'Luca', 'Migliorini', 'luca90'),
('MMTLLT07R12H501F', 'Luigi', 'Matteo', 'luigi123'),
('NCLFNC04D22G273F', 'Francesca', 'Nicolini', 'franci04'),
('PLLMRA03C12H501U', 'Marco', 'Pellegrini', 'marco321'),
('RLGVNC10A35H501P', 'Giovanni', 'Ruggeri', 'giova23'),
('SGRFRN09S25F205R', 'Francesco', 'Sgrò', 'franco09'),
('SNRVNC12R55L219B', 'Anna', 'Serra', 'anna02'),
('VLSMNT08D14F205G', 'Simona', 'Valenti', 'simona21'),
('VRLSRA06A01L219E', 'Sara', 'Veroli', 'sarina06'),
('ZNCGLS15T85H501F', 'Giulia', 'Zanni', 'giulia99');

-- --------------------------------------------------------

--
-- Table structure for table `videogioco`
--

CREATE TABLE `videogioco` (
  `IdVideogioco` int(11) NOT NULL,
  `Titolo` varchar(50) NOT NULL,
  `Descrizione` varchar(200) NOT NULL,
  `DescrizioneEstesa` text NOT NULL,
  `MoneteMax` int(11) NOT NULL,
  `Immagine1` varchar(255) NOT NULL,
  `Immagine2` varchar(255) NOT NULL,
  `Immagine3` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `videogioco`
--

INSERT INTO `videogioco` (`IdVideogioco`, `Titolo`, `Descrizione`, `DescrizioneEstesa`, `MoneteMax`, `Immagine1`, `Immagine2`, `Immagine3`) VALUES
(1, 'Math Battle', 'Sfida matematica a tempo', 'Gioco a quiz per esercitarsi con le operazioni aritmetiche di base, con livelli a difficoltà crescente.', 100, 'math1.jpg', 'math2.jpg', 'math3.jpg'),
(2, 'Grammar Hero', 'Missione grammatica italiana', 'Aiuta l’eroe della grammatica a correggere frasi e scoprire errori nascosti in un mondo immaginario.', 80, 'gram1.jpg', 'gram2.jpg', 'gram3.jpg'),
(3, 'Geo Explorer', 'Geografia interattiva', 'Viaggia per il mondo rispondendo a domande su capitali, fiumi e confini, sbloccando nuove mappe.', 120, 'geo1.jpg', 'geo2.jpg', 'geo3.jpg'),
(4, 'Science Quest', 'Scienza e natura', 'Risolvi enigmi scientifici per scoprire i segreti della natura e dei fenomeni fisici.', 150, 'sci1.jpg', 'sci2.jpg', 'sci3.jpg'),
(5, 'History Adventures', 'Avventure storiche', 'Esplora epoche storiche risolvendo puzzle e scoprendo eventi storici chiave.', 200, 'hist1.jpg', 'hist2.jpg', 'hist3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `videogioco_argomento`
--

CREATE TABLE `videogioco_argomento` (
  `IdVideogioco` int(11) NOT NULL,
  `IdArgomento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `videogioco_argomento`
--

INSERT INTO `videogioco_argomento` (`IdVideogioco`, `IdArgomento`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `argomento`
--
ALTER TABLE `argomento`
  ADD PRIMARY KEY (`IdArgomento`);

--
-- Indexes for table `classevirtuale`
--
ALTER TABLE `classevirtuale`
  ADD PRIMARY KEY (`IdClasse`),
  ADD KEY `CodiceFiscaleDocente` (`CodiceFiscaleDocente`);

--
-- Indexes for table `classe_videogioco`
--
ALTER TABLE `classe_videogioco`
  ADD PRIMARY KEY (`IdClasse`,`IdVideogioco`),
  ADD KEY `IdVideogioco` (`IdVideogioco`);

--
-- Indexes for table `docente`
--
ALTER TABLE `docente`
  ADD PRIMARY KEY (`CodiceFiscale`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`IdVideogioco`,`CodiceFiscale`,`Orario`),
  ADD KEY `CodiceFiscale` (`CodiceFiscale`);

--
-- Indexes for table `iscrizione`
--
ALTER TABLE `iscrizione`
  ADD PRIMARY KEY (`IdClasse`,`CodiceFiscale`),
  ADD KEY `CodiceFiscale` (`CodiceFiscale`);

--
-- Indexes for table `partita`
--
ALTER TABLE `partita`
  ADD PRIMARY KEY (`CodiceFiscale`,`IdVideogioco`,`Orario`),
  ADD KEY `IdVideogioco` (`IdVideogioco`);

--
-- Indexes for table `studente`
--
ALTER TABLE `studente`
  ADD PRIMARY KEY (`CodiceFiscale`);

--
-- Indexes for table `videogioco`
--
ALTER TABLE `videogioco`
  ADD PRIMARY KEY (`IdVideogioco`);

--
-- Indexes for table `videogioco_argomento`
--
ALTER TABLE `videogioco_argomento`
  ADD PRIMARY KEY (`IdVideogioco`,`IdArgomento`),
  ADD KEY `IdArgomento` (`IdArgomento`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `argomento`
--
ALTER TABLE `argomento`
  MODIFY `IdArgomento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `classevirtuale`
--
ALTER TABLE `classevirtuale`
  MODIFY `IdClasse` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `videogioco`
--
ALTER TABLE `videogioco`
  MODIFY `IdVideogioco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `classevirtuale`
--
ALTER TABLE `classevirtuale`
  ADD CONSTRAINT `classevirtuale_ibfk_1` FOREIGN KEY (`CodiceFiscaleDocente`) REFERENCES `docente` (`CodiceFiscale`);

--
-- Constraints for table `classe_videogioco`
--
ALTER TABLE `classe_videogioco`
  ADD CONSTRAINT `classe_videogioco_ibfk_1` FOREIGN KEY (`IdClasse`) REFERENCES `classevirtuale` (`IdClasse`),
  ADD CONSTRAINT `classe_videogioco_ibfk_2` FOREIGN KEY (`IdVideogioco`) REFERENCES `videogioco` (`IdVideogioco`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`IdVideogioco`) REFERENCES `videogioco` (`IdVideogioco`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`CodiceFiscale`) REFERENCES `studente` (`CodiceFiscale`);

--
-- Constraints for table `iscrizione`
--
ALTER TABLE `iscrizione`
  ADD CONSTRAINT `iscrizione_ibfk_1` FOREIGN KEY (`IdClasse`) REFERENCES `classevirtuale` (`IdClasse`),
  ADD CONSTRAINT `iscrizione_ibfk_2` FOREIGN KEY (`CodiceFiscale`) REFERENCES `studente` (`CodiceFiscale`);

--
-- Constraints for table `partita`
--
ALTER TABLE `partita`
  ADD CONSTRAINT `partita_ibfk_1` FOREIGN KEY (`CodiceFiscale`) REFERENCES `studente` (`CodiceFiscale`),
  ADD CONSTRAINT `partita_ibfk_2` FOREIGN KEY (`IdVideogioco`) REFERENCES `videogioco` (`IdVideogioco`);

--
-- Constraints for table `videogioco_argomento`
--
ALTER TABLE `videogioco_argomento`
  ADD CONSTRAINT `videogioco_argomento_ibfk_1` FOREIGN KEY (`IdVideogioco`) REFERENCES `videogioco` (`IdVideogioco`),
  ADD CONSTRAINT `videogioco_argomento_ibfk_2` FOREIGN KEY (`IdArgomento`) REFERENCES `argomento` (`IdArgomento`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
