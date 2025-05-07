<?php
require_once("../connessione.php");

// Crea la tabella Giochi se non esiste
$sql = "CREATE TABLE IF NOT EXISTS Giochi (
    IdGioco INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL,
    Descrizione TEXT,
    Icona VARCHAR(50)
)";

if ($mysqli->query($sql)) {
    echo "Tabella Giochi creata con successo<br>";
} else {
    echo "Errore nella creazione della tabella: " . $mysqli->error . "<br>";
}

// Crea la tabella GiochiClasse se non esiste
$sql = "CREATE TABLE IF NOT EXISTS GiochiClasse (
    IdClasse INT,
    IdGioco INT,
    PRIMARY KEY (IdClasse, IdGioco),
    FOREIGN KEY (IdClasse) REFERENCES ClasseVirtuale(IdClasse) ON DELETE CASCADE,
    FOREIGN KEY (IdGioco) REFERENCES Giochi(IdGioco) ON DELETE CASCADE
)";

if ($mysqli->query($sql)) {
    echo "Tabella GiochiClasse creata con successo<br>";
} else {
    echo "Errore nella creazione della tabella: " . $mysqli->error . "<br>";
}

// Importa i giochi dal file educational-games.sql
$sql_file = file_get_contents("../educational-games.sql");
if ($sql_file === false) {
    echo "Errore nella lettura del file educational-games.sql<br>";
    exit();
}

// Esegui le query dal file SQL
if ($mysqli->multi_query($sql_file)) {
    do {
        // Salta i risultati intermedi
        if ($result = $mysqli->store_result()) {
            $result->free();
        }
    } while ($mysqli->more_results() && $mysqli->next_result());
    
    if ($mysqli->error) {
        echo "Errore durante l'importazione dei giochi: " . $mysqli->error . "<br>";
    } else {
        echo "Giochi importati con successo dal file educational-games.sql<br>";
    }
} else {
    echo "Errore durante l'importazione dei giochi: " . $mysqli->error . "<br>";
}

echo "Setup completato!"; 