<?php
require_once("../connessione.php");
$noClasses = true;

/* // query per aggiungere uno studente
$stmt = $mysqli->prepare("INSERT INTO Studente (CodiceFiscale, Nome, Cognome, Password) VALUES (?, ?, ?, ?)");

$nome = "pippo";
$cognome = "baudo";
$codiceFiscale = "1234567890123456";
$password = "secret12345";


$stmt->bind_param("ssss", $codiceFiscale, $nome, $cognome, $password);

$stmt->execute(); */

session_start();

$authenticated = isset($_SESSION["nome"]) && isset($_SESSION["role"]); // TRUE se autenticato

if (!$authenticated || $_SESSION["role"] != "docente") {
    echo "Non sei autenticato o non hai i permessi necessari!";
    die();
}


$nome = $_SESSION["nome"];
$cognome = $_SESSION["cognome"];
$codiceFiscale = $_SESSION["codiceFiscale"];
$role = $_SESSION["role"]; // role = "docente" va bene


// mostrare le informazioni sull'utente attuale
echo "<h2>Le tue informazioni:</h2>";
echo "<b>Nome:</b> " . $nome . "<br>";

echo "<b>Cognome:</b> " . $cognome . "<br>";

echo "<b>Codice fiscale:</b> " . $codiceFiscale . "<br>";

echo "<hr>";

// Form nuova classe virtuale
echo "<form action='../routes/new-classe-virtuale.php' method='POST'>";
echo    "<label for='codice'>classe: </label>";
echo    "<input type='text' id='classe' name='classe' required>";
echo    "<label for='codice'>materia: </label>";
echo    "<input type='text' id='materia' name='materia' required>";
echo "<input type='submit' value='Crea!'>";
echo "<hr>";

// mostrare le classi del docente
$stmt = $mysqli->prepare("SELECT * FROM ClasseVirtuale WHERE CodiceFiscaleDocente = ?");
$stmt->bind_param("s", $codiceFiscale);
$stmt->execute();
$result = $stmt->get_result();

//Controllo se ci sono risultati
if ($result->num_rows > 0) {
    // Ci sono classi per questo docente
    $noClasses = false;
} else {
    //Nessun risultato trovato
    $noClasses = true;
}

echo "<table border='1'>";
echo "<tr>";
echo "<th>Materia - Classe</th>";
echo "<th>Codice di accesso</th>";
echo "<th>Classifica</th>";
echo "</tr>";

// Reset the result pointer
//$result = $stmt->get_result();

if (!$noClasses) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Materia']) . " - " . htmlspecialchars($row['Classe']) . "</td>";
        echo "<td>" . "<a href='c.php?classe=" . $row['IdClasse'] . "'>Apri</a>" . "</td>";
        echo "<td>" . $row['CodiceAccesso'] . "</td>";
        echo "<td>" . "<a href='leadboard.php?classe=" . $row['IdClasse'] . "'>Apri</a>" . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='2'>Non hai ancora creato classi virtuali</td></tr>";
}

echo "</table>";
?>