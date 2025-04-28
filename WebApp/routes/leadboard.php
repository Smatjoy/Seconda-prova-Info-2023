<?php

// Mostriamo i giochi che sono disponibili nella classe scelta

// Recuperare la classe dall'URL
// Verificare che lo studente è davvero in quella classe
// Se lo studente è nella classe, mostrare una tabella con le informazioni sui giochi

session_start();

if (!isset($_SESSION["nome"])) {
    // non è autenticato
    echo "Non sei autenticato!";
    header("Location: ../index.php");
    die();
}

require_once("../connessione.php");

echo "<a href='../homepage/dashboard_docente.php'>&lt-Torna indietro</a><br>";


// Recuperiamo i dettagli dell'utente autenticato
$nome = $_SESSION["nome"];
$cognome = $_SESSION["cognome"];
$codiceFiscale = $_SESSION["codiceFiscale"];
$role = $_SESSION["role"];

// Recuperare la classe dall'URL ?classe={idClasse}
$classe = $_GET["classe"];




// Verificare che il docente è davvero in quella classe
// Controllo se nella tabella 'ClasseVirtuale' è presente una riga con 'CodiceFiscaleDocente'= $codiceFiscale e IdCLasse = $classe

$stmt = $mysqli->prepare("
SELECT EXISTS (
SELECT 1
FROM ClasseVirtuale
WHERE IdClasse = ?
AND CodiceFiscaleDocente = ?)
");

$stmt->bind_param("is", $classe, $codiceFiscale);
$stmt->execute();
$stmt->bind_result($isIscritto);
$stmt->fetch();

if (!$isIscritto){
    // Il docente non è docente della classe richiesta
    echo "Non sei docente della classe richiesta!";
    die();
}

$stmt->close();


// Mostrare il titolo della classe
$stmt = $mysqli->prepare("
SELECT Materia, Classe
FROM classevirtuale
WHERE IdClasse = ?
");

$stmt->bind_param("i", $classe);

$stmt->execute();
$stmt->bind_result($Materia, $NomeClasse);

$stmt->fetch();

echo "<h1> Classfica di: ". $Materia . " - " . $NomeClasse . "</h1>";

$stmt->close();


// Mostrare la classifica della classe


echo "<table border='1'>";
echo    "<tr>";
echo        "<th>Codice fiscale</th>";
echo        "<th>Nome</th>";
echo        "<th>Cognome</th>";
echo        "<th>Monete</th>";
echo    "</tr>";



$stmt = $mysqli->prepare("
SELECT partita.CodiceFiscale AS CodiceFiscaleStudente, studente.Nome AS Nome, studente.Cognome AS Cognome, SUM(partita.Monete) AS MoneteTotali
FROM studente
JOIN iscrizione ON (studente.CodiceFiscale = iscrizione.CodiceFiscale)
JOIN partita ON (studente.CodiceFiscale = partita.CodiceFiscale)

WHERE iscrizione.IdClasse = ?

GROUP BY partita.CodiceFiscale

ORDER BY MoneteTotali DESC 
");


$stmt->bind_param("i", $classe);

$stmt->execute();
$result = $stmt->get_result();


while ($row = $result->fetch_assoc()){
    echo "<tr>";
    echo "<td>". $row["CodiceFiscaleStudente"] . "</td>";
    echo "<td>". $row["Nome"] . "</td>";
    echo "<td>". $row["Cognome"] . "</td>";
    echo "<td>". $row["MoneteTotali"] . "</td>";
    echo "</tr>";
}

echo "</table>";
?>