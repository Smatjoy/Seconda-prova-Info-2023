USE `educational-games`;

-- Docenti
INSERT INTO Docente (CodiceFiscale, Nome, Cognome, Password) VALUES
('RSSMRA85M01H501Z', 'Mario', 'Rossi', 'rossi123'),
('BNCLRA70A01F205X', 'Laura', 'Bianchi', 'bianchi123'),
('VRDGNN60C45L219H', 'Gianna', 'Verdi', 'verdi123'),
('FNTGLL55A12H501K', 'Gillo', 'Fantini', 'fantini55');

-- Studenti (13 x 4 classi = 52)
-- CF: codici fittizi CFSTUD01...52
-- Classe 1
INSERT INTO Studente (CodiceFiscale, Nome, Cognome, Password) VALUES
('CFSTUD01', 'Alessandro', 'Neri', 'pass01'),
('CFSTUD02', 'Beatrice', 'Conti', 'pass02'),
('CFSTUD03', 'Carlo', 'Martini', 'pass03'),
('CFSTUD04', 'Diana', 'Ricci', 'pass04'),
('CFSTUD05', 'Edoardo', 'Seri', 'pass05'),
('CFSTUD06', 'Francesca', 'Colombo', 'pass06'),
('CFSTUD07', 'Giorgio', 'Marin', 'pass07'),
('CFSTUD08', 'Helena', 'Biagi', 'pass08'),
('CFSTUD09', 'Ivan', 'Grossi', 'pass09'),
('CFSTUD10', 'Jessica', 'Fontana', 'pass10'),
('CFSTUD11', 'Kevin', 'Pini', 'pass11'),
('CFSTUD12', 'Ludovica', 'De Luca', 'pass12'),
('CFSTUD13', 'Marco', 'Barone', 'pass13');

-- Classe 2
INSERT INTO Studente (CodiceFiscale, Nome, Cognome, Password) VALUES
('CFSTUD14', 'Nadia', 'Farina', 'pass14'),
('CFSTUD15', 'Oscar', 'Gentile', 'pass15'),
('CFSTUD16', 'Paola', 'Silvestri', 'pass16'),
('CFSTUD17', 'Quirino', 'Sanna', 'pass17'),
('CFSTUD18', 'Rita', 'D’Amico', 'pass18'),
('CFSTUD19', 'Samuele', 'Ferrari', 'pass19'),
('CFSTUD20', 'Tina', 'Nobili', 'pass20'),
('CFSTUD21', 'Umberto', 'Parodi', 'pass21'),
('CFSTUD22', 'Valeria', 'Sartori', 'pass22'),
('CFSTUD23', 'Walter', 'Neri', 'pass23'),
('CFSTUD24', 'Xenia', 'Bruni', 'pass24'),
('CFSTUD25', 'Yuri', 'Grandi', 'pass25'),
('CFSTUD26', 'Zoe', 'Leone', 'pass26');

-- Classe 3
INSERT INTO Studente (CodiceFiscale, Nome, Cognome, Password) VALUES
('CFSTUD27', 'Alberto', 'Sala', 'pass27'),
('CFSTUD28', 'Bruna', 'Palumbo', 'pass28'),
('CFSTUD29', 'Claudio', 'Moretti', 'pass29'),
('CFSTUD30', 'Denise', 'Lombardi', 'pass30'),
('CFSTUD31', 'Elia', 'Coppola', 'pass31'),
('CFSTUD32', 'Fabiola', 'Barbieri', 'pass32'),
('CFSTUD33', 'Gianni', 'Vitali', 'pass33'),
('CFSTUD34', 'Hassan', 'Ali', 'pass34'),
('CFSTUD35', 'Irene', 'Rossi', 'pass35'),
('CFSTUD36', 'Jacopo', 'Bernardi', 'pass36'),
('CFSTUD37', 'Katia', 'Pellegrini', 'pass37'),
('CFSTUD38', 'Luca', 'Simeoni', 'pass38'),
('CFSTUD39', 'Marta', 'Cattaneo', 'pass39');

-- Classe 4
INSERT INTO Studente (CodiceFiscale, Nome, Cognome, Password) VALUES
('CFSTUD40', 'Nicola', 'Fabbri', 'pass40'),
('CFSTUD41', 'Olga', 'Romano', 'pass41'),
('CFSTUD42', 'Pietro', 'Bellini', 'pass42'),
('CFSTUD43', 'Quinta', 'Greco', 'pass43'),
('CFSTUD44', 'Ruggero', 'De Angelis', 'pass44'),
('CFSTUD45', 'Silvia', 'Rinaldi', 'pass45'),
('CFSTUD46', 'Tommaso', 'Caputo', 'pass46'),
('CFSTUD47', 'Ugo', 'Serra', 'pass47'),
('CFSTUD48', 'Vera', 'Negri', 'pass48'),
('CFSTUD49', 'William', 'Ferraro', 'pass49'),
('CFSTUD50', 'Xavier', 'Donati', 'pass50'),
('CFSTUD51', 'Yasmine', 'Piras', 'pass51'),
('CFSTUD52', 'Zaccaria', 'Testa', 'pass52');

-- Classi Virtuali
INSERT INTO ClasseVirtuale (Classe, Materia, CodiceFiscaleDocente, CodiceAccesso) VALUES
('1A', 'Matematica', 'RSSMRA85M01H501Z', 'ABC001'),
('2B', 'Italiano', 'BNCLRA70A01F205X', 'ABC002'),
('3C', 'Geografia', 'VRDGNN60C45L219H', 'ABC003'),
('4D', 'Scienze', 'FNTGLL55A12H501K', 'ABC004');

-- Iscrizioni Studenti
-- (Distribuzione studenti su classi 1-4, 13 ciascuna)
INSERT INTO Iscrizione (IdClasse, CodiceFiscale, Orario) VALUES
-- Classe 1
(1, 'CFSTUD01', NOW()), (1, 'CFSTUD02', NOW()), (1, 'CFSTUD03', NOW()), (1, 'CFSTUD04', NOW()),
(1, 'CFSTUD05', NOW()), (1, 'CFSTUD06', NOW()), (1, 'CFSTUD07', NOW()), (1, 'CFSTUD08', NOW()),
(1, 'CFSTUD09', NOW()), (1, 'CFSTUD10', NOW()), (1, 'CFSTUD11', NOW()), (1, 'CFSTUD12', NOW()), (1, 'CFSTUD13', NOW()),
-- Classe 2
(2, 'CFSTUD14', NOW()), (2, 'CFSTUD15', NOW()), (2, 'CFSTUD16', NOW()), (2, 'CFSTUD17', NOW()),
(2, 'CFSTUD18', NOW()), (2, 'CFSTUD19', NOW()), (2, 'CFSTUD20', NOW()), (2, 'CFSTUD21', NOW()),
(2, 'CFSTUD22', NOW()), (2, 'CFSTUD23', NOW()), (2, 'CFSTUD24', NOW()), (2, 'CFSTUD25', NOW()), (2, 'CFSTUD26', NOW()),
-- Classe 3
(3, 'CFSTUD27', NOW()), (3, 'CFSTUD28', NOW()), (3, 'CFSTUD29', NOW()), (3, 'CFSTUD30', NOW()),
(3, 'CFSTUD31', NOW()), (3, 'CFSTUD32', NOW()), (3, 'CFSTUD33', NOW()), (3, 'CFSTUD34', NOW()),
(3, 'CFSTUD35', NOW()), (3, 'CFSTUD36', NOW()), (3, 'CFSTUD37', NOW()), (3, 'CFSTUD38', NOW()), (3, 'CFSTUD39', NOW()),
-- Classe 4
(4, 'CFSTUD40', NOW()), (4, 'CFSTUD41', NOW()), (4, 'CFSTUD42', NOW()), (4, 'CFSTUD43', NOW()),
(4, 'CFSTUD44', NOW()), (4, 'CFSTUD45', NOW()), (4, 'CFSTUD46', NOW()), (4, 'CFSTUD47', NOW()),
(4, 'CFSTUD48', NOW()), (4, 'CFSTUD49', NOW()), (4, 'CFSTUD50', NOW()), (4, 'CFSTUD51', NOW()), (4, 'CFSTUD52', NOW());

-- Videogiochi
INSERT INTO Videogioco (Titolo, Descrizione, DescrizioneEstesa, MoneteMax, Immagine1, Immagine2, Immagine3) VALUES
('Math Battle', 'Sfida con i numeri', 'Calcoli e logica in una gara contro il tempo.', 100, 'math1.png', 'math2.png', 'math3.png'),
('Grammar Hero', 'Impara la grammatica', 'Esercizi di analisi grammaticale e sintattica.', 80, 'gram1.png', 'gram2.png', 'gram3.png'),
('Geo Explorer', 'Scopri il mondo', 'Quiz su paesi, capitali e mappe.', 120, 'geo1.png', 'geo2.png', 'geo3.png'),
('Science Quest', 'Avventure scientifiche', 'Domande su biologia, fisica e chimica.', 90, 'sci1.png', 'sci2.png', 'sci3.png'),
('History Jump', 'Salta nel tempo', 'Ripassa la storia con sfide a tema.', 110, 'hist1.png', 'hist2.png', 'hist3.png');

-- Classe_Videogioco
INSERT INTO Classe_Videogioco (IdClasse, IdVideogioco) VALUES
(1, 1), (1, 4),
(2, 2), (2, 5),
(3, 3), (3, 5),
(4, 1), (4, 4);

-- Argomenti
INSERT INTO Argomento (Titolo) VALUES
('Addizioni e sottrazioni'),
('Analisi grammaticale'),
('Capitali del mondo'),
('Ciclo dell’acqua'),
('Seconda guerra mondiale');

-- Videogioco_Argomento
INSERT INTO Videogioco_Argomento (IdVideogioco, IdArgomento) VALUES
(1, 1), (2, 2), (3, 3), (4, 4), (5, 5);

-- Partite (solo per i primi 2 studenti di ogni classe)
INSERT INTO Partita (CodiceFiscale, IdVideogioco, Monete) VALUES
('CFSTUD01', 1, 85), ('CFSTUD02', 1, 90),
('CFSTUD14', 2, 70), ('CFSTUD15', 2, 75),
('CFSTUD27', 3, 95), ('CFSTUD28', 3, 92),
('CFSTUD40', 4, 88), ('CFSTUD41', 4, 90);

-- Feedback
INSERT INTO Feedback (IdVideogioco, CodiceFiscale, Punteggio, Testo) VALUES
(1, 'CFSTUD01', 5, 'Ottimo per ripassare la matematica!'),
(2, 'CFSTUD14', 4, 'Grammatica resa divertente.'),
(3, 'CFSTUD27', 5, 'Mi sono sentito un vero esploratore.'),
(4, 'CFSTUD40', 4, 'Bella grafica e contenuti chiari.'),
(5, 'CFSTUD15', 5, 'La storia non è mai stata così interessante!');
