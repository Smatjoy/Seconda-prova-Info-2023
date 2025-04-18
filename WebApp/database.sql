CREATE DATABASE EducationalGames;

CREATE TABLE ClasseVirtuale(
    IdClasse char(5) PRIMARY KEY,
    Classe varchar(200) NOT NULL,
    Materia varchar(200) NOT NULL,
    IDDocente varchar(200) NOT NULL,
    CodiceAccesso char(6) NOT NULL,
    FOREIGN KEY (IdDocente) REFERENCES (Docente.IDDocente)
);

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

CREATE TABLE Iscrizione(
    IdClasse char(5),
    CodiceFiscale char(16),
    Orario TIMESTAMP,
    PRIMARY KEY (IdClasse, CodiceFiscale),
    FOREIGN KEY (IdClasse) REFERENCES Classe_Virtuale(IdClasse),
    FOREIGN KEY (CodiceFiscale) REFERENCES Studente(CodiceFiscale)
);

CREATE TABLE Videogioco(
    IdVideogioco char(5) PRIMARY KEY,
    Titolo varchar(50) NOT NULL,
    Descrizione varchar(200) NOT NULL,
    DescrizioneEstesa text NOT NULL,
    MoneteMax int NOT NULL,
    Immagine1 varchar(255) NOT NULL,
    Immagine2 varchar(255) NOT NULL,
    Immagine3 varchar(255) NOT NULL
);

CREATE TABLE Partita(
    CodiceFiscale char(16),
    IdVideogioco char(5) PRIMARY KEY,
    Orario TIMESTAMP NOT NULL,
    PRIMARY KEY (CodiceFiscale, IdVideogioco),
    FOREIGN KEY (CodiceFiscale) REFERENCES Studente(CodiceFiscale),
    FOREIGN KEY (IdVideogioco) REFERENCES Videogioco(IdVideogioco)
);

CREATE TABLE Classe_Videogioco(
    IdClasse char(5) NOT NULL,
    IdVideogioco char(5) NOT NULL,
    Classe varchar(10) NOT NULL
    PRIMARY KEY (IdClasse, IdVideogioco),
    FOREIGN KEY (IdClasse) REFERENCES ClasseVirtuale(IdClasse),
    FOREIGN KEY (IdVideogioco) REFERENCES Videogioco(IdVideogioco)
);