CREATE DATABASE `educational-games`;
USE `educational-games`;

CREATE TABLE Docente(
    CodiceFiscale char(16) PRIMARY KEY,
    Nome varchar(200) NOT NULL,
    Cognome varchar(200) NOT NULL,
    Password varchar(50) NOT NULL
);

CREATE TABLE Studente(
    CodiceFiscale char(16) PRIMARY KEY,
    Nome varchar(200) NOT NULL,
    Cognome varchar(200) NOT NULL,
    Password varchar(50) NOT NULL
);

CREATE TABLE Videogioco(
    IdVideogioco int AUTO_INCREMENT PRIMARY KEY,
    Titolo varchar(50) NOT NULL,
    Descrizione varchar(200) NOT NULL,
    DescrizioneEstesa text NOT NULL,
    MoneteMax int NOT NULL,
    Immagine1 varchar(255) NOT NULL,
    Immagine2 varchar(255) NOT NULL,
    Immagine3 varchar(255) NOT NULL
);

CREATE TABLE ClasseVirtuale(
    IdClasse int AUTO_INCREMENT PRIMARY KEY,
    Classe varchar(200) NOT NULL,
    Materia varchar(200) NOT NULL,
    CodiceFiscaleDocente char(16) NOT NULL,
    CodiceAccesso char(6) NOT NULL,
    FOREIGN KEY (CodiceFiscaleDocente) REFERENCES Docente(CodiceFiscale)
);

CREATE TABLE Iscrizione(
    IdClasse int,
    CodiceFiscale char(16),
    Orario TIMESTAMP,
    PRIMARY KEY (IdClasse, CodiceFiscale),
    FOREIGN KEY (IdClasse) REFERENCES ClasseVirtuale(IdClasse),
    FOREIGN KEY (CodiceFiscale) REFERENCES Studente(CodiceFiscale)
);

CREATE TABLE Partita(
    CodiceFiscale char(16),
    IdVideogioco int,
    Orario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Monete int NOT NULL,
    PRIMARY KEY (CodiceFiscale, IdVideogioco, Orario),
    FOREIGN KEY (CodiceFiscale) REFERENCES Studente(CodiceFiscale),
    FOREIGN KEY (IdVideogioco) REFERENCES Videogioco(IdVideogioco)
);

CREATE TABLE Classe_Videogioco(
    IdClasse int NOT NULL,
    IdVideogioco int NOT NULL,
    PRIMARY KEY (IdClasse, IdVideogioco),
    FOREIGN KEY (IdClasse) REFERENCES ClasseVirtuale(IdClasse),
    FOREIGN KEY (IdVideogioco) REFERENCES Videogioco(IdVideogioco)
);

CREATE TABLE Argomento(
    IdArgomento int AUTO_INCREMENT PRIMARY KEY,
    Titolo varchar(50) NOT NULL
);

CREATE TABLE Videogioco_Argomento(
    IdVideogioco int NOT NULL,
    IdArgomento int NOT NULL,
    PRIMARY KEY (IdVideogioco, IdArgomento),
    FOREIGN KEY (IdVideogioco) REFERENCES Videogioco(IdVideogioco),
    FOREIGN KEY (IdArgomento) REFERENCES Argomento(IdArgomento)
);

CREATE TABLE Feedback(
    IdVideogioco int NOT NULL,
    CodiceFiscale char(16) NOT NULL,
    Punteggio int NOT NULL,
    Testo VARCHAR(160),
    Orario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (IdVideogioco, CodiceFiscale, Orario),
    FOREIGN KEY (IdVideogioco) REFERENCES Videogioco(IdVideogioco),
    FOREIGN KEY (CodiceFiscale) REFERENCES Studente(CodiceFiscale)
);