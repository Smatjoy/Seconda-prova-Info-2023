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

echo "<a href='../homepage/dashboard_studente.php'>&lt-Torna indietro</a><br>";


// Recuperiamo i dettagli dell'utente autenticato
$nome = $_SESSION["nome"];
$cognome = $_SESSION["cognome"];
$codiceFiscale = $_SESSION["codiceFiscale"];
$role = $_SESSION["role"];

// Recuperare la classe dall'URL ?classe={idClasse}
$classe = $_GET["classe"];

// Verificare che lo studente è davvero in quella classe
// Controllo se nella tabella 'iscrizione' è presente una riga con 'IdClasse'= $classe & 'CodiceFisclae' = $codiceFiscale

$stmt = $mysqli->prepare("
SELECT EXISTS (
SELECT 1
FROM iscrizione
WHERE IdClasse = ?
AND CodiceFiscale = ?)
");

$stmt->bind_param("is", $classe, $codiceFiscale);
$stmt->execute();
$stmt->bind_result($isIscritto);
$stmt->fetch();

if (!$isIscritto){
    // Lo studente non è in quella classe
    echo "Non sei iscritto alla classe richiesta!";
    die();
}

$stmt->close();


// Mostriamo i dettagli dei giochi della classe

echo "<table border='1'>";
echo    "<tr>";
echo        "<th>ID Videogioco</th>";
echo        "<th>Titolo</th>";
echo        "<th>Descrizione</th>";
echo        "<th>Descrizione estesa</th>";
echo        "<th>Monete massime</th>";
echo        "<th>Immagine 1</th>";
echo        "<th>Immagine 2</th>";
echo        "<th>Immagine 3</th>";
echo        "<th>Argomento</th>";
echo        "<th>Link al gioco</th>";
echo        "<th>Invia Feedback</th>";
echo "</tr>";



$stmt = $mysqli->prepare("
SELECT videogioco.IdVideogioco, videogioco.Titolo, videogioco.Descrizione, videogioco.DescrizioneEstesa, videogioco.MoneteMax, videogioco.Immagine1, videogioco.Immagine2, videogioco.Immagine3,
GROUP_CONCAT(argomento.Titolo SEPARATOR ', ') AS TitoliArgomenti
FROM videogioco
JOIN classe_videogioco ON classe_videogioco.IdVideogioco = videogioco.IdVideogioco
JOIN videogioco_argomento ON classe_videogioco.IdVideogioco = videogioco_argomento.IdVideogioco
JOIN argomento ON videogioco_argomento.IdArgomento = argomento.IdArgomento
WHERE classe_videogioco.IdClasse = ?
GROUP BY videogioco.IdVideogioco
");


$stmt->bind_param("i", $classe);

$stmt->execute();
$result = $stmt->get_result();

$urlGioco = "#";

while ($row = $result->fetch_assoc()){
    echo "<tr>";
    echo "<td>". $row["IdVideogioco"] . "</td>";
    echo "<td>". $row["Titolo"] . "</td>";
    echo "<td>". $row["Descrizione"] . "</td>";
    echo "<td>". $row["DescrizioneEstesa"] . "</td>";
    echo "<td>". $row["MoneteMax"] . "</td>";
    echo "<td><img src='./images/". $row["Immagine1"] . ".png' width='100%'></td>";
    echo "<td><img src='./images/". $row["Immagine2"] . ".png' width='100%'></td>";
    echo "<td><img src='./images/". $row["Immagine3"] . ".png' width='100%'></td>";
    echo "<td>". $row["TitoliArgomenti"] . "</td>";
    echo "<td><a href='" . $urlGioco . "'>Gioca</a></td>";
    echo "<td>";
    echo "<form action='../routes/feedback.php' method='POST'>";
    echo "<input type='hidden' name='gioco' value='". $row["IdVideogioco"] ."'>";
    echo "<label for='punteggio_". $row["IdVideogioco"] ."'>Punteggio (1-5):</label><br>";
    echo "<input type='number' id='punteggio_". $row["IdVideogioco"] ."' name='punteggio' min='1' max='5' required><br>";
    echo "<label for='testo_". $row["IdVideogioco"] ."'>Testo feedback:</label><br>";
    echo "<input type='text' id='testo_". $row["IdVideogioco"] ."' name='testo' maxlength='160' required><br>";
    echo "<button type='submit'>Invia</button>";
    echo "</form>";
    echo "</td>";
    echo "</tr>";
}

echo "</table>";
?>