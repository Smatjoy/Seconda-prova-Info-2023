<?php
require_once("../connessione.php");


/* // query per aggiungere uno studente
$stmt = $mysqli->prepare("INSERT INTO Studente (CodiceFiscale, Nome, Cognome, Password) VALUES (?, ?, ?, ?)");

$nome = "pippo";
$cognome = "baudo";
$codiceFiscale = "1234567890123456";
$password = "secret12345";


$stmt->bind_param("ssss", $codiceFiscale, $nome, $cognome, $password);

$stmt->execute(); */



session_start();

$authenticated = isset($_SESSION["nome"]); // TRUE se autenticato

if (!$authenticated) {
    echo "Non sei autenticato!";
    die();
}


$nome = $_SESSION["nome"];
$cognome = $_SESSION["cognome"];
$codiceFiscale = $_SESSION["codiceFiscale"];
$role = $_SESSION["role"]; // role = "studente" va bene


// mostrare le informazioni sull'utente attuale
echo "<h2>Le tue informazioni:</h2>";
echo "<b>Nome:</b> " . $nome . "<br>";

echo "<b>Cognome:</b> " . $cognome . "<br>";

echo "<b>Codice fiscale:</b> " . $codiceFiscale . "<br>";



echo "<hr>";
// form per iscriversi ad una nuova classe
echo "<form action='../routes/join_class.php'>";
echo    "<label for='codice'>Codice corso: </label>";
echo    "<input type='text' id='codice' name='codice' required>";
echo "<input type='submit' value='Entra!'>";





echo "<hr>";
// mostrare le classi in cui è lo studente
$stmt = $mysqli->prepare("
SELECT
classevirtuale.Classe,
classevirtuale.Materia,
docente.Nome,
docente.Cognome,
classevirtuale.IdClasse
FROM studente
JOIN iscrizione ON studente.CodiceFiscale = iscrizione.CodiceFiscale
JOIN classevirtuale ON iscrizione.IdClasse = classevirtuale.IdClasse
JOIN docente ON classevirtuale.CodiceFiscaleDocente = docente.CodiceFiscale
WHERE studente.CodiceFiscale = ?
");

$stmt->bind_param("s", $codiceFiscale);
$stmt->execute();
$result = $stmt->get_result();

echo "<table border='1'>";
echo    "<tr>";
echo        "<th>Materia - Classe</th>";
echo        "<th>Nome Docente</th>";
echo        "<th>Apri</th>";
echo    "</tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo    "<td>". htmlspecialchars($row['Materia'])." - ". htmlspecialchars($row['Classe']). "</td>";
    echo    "<td>". htmlspecialchars($row['Nome']) . " " . htmlspecialchars($row['Cognome']). "</td>";
    echo    "<td>". "<a href='../routes/c.php?classe=" . $row['IdClasse'] . "'>Apri</a>" . "</td>";
    echo "</tr>";
}




echo "</table>";
?>