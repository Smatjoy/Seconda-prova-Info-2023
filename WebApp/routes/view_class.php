<?php
require_once("../connessione.php");
session_start();

echo "<a href='../homepage/dashboard_docente.php'>&lt-Torna indietro</a><br>";

$classe = $_GET["classe"];

echo "<br><table border='1'>";
echo    "<tr>";
echo        "<th>Codice Fiscale</th>";
echo        "<th>Nome</th>";
echo        "<th>Cognome</th>";
echo        "<th>Contatta</th>";
echo        "<th>Azioni</th>";
echo    "</tr>";

// Modifica la query per unire (JOIN) le tabelle iscrizione e studente
$stmt = $mysqli->prepare("
    SELECT iscrizione.CodiceFiscale, studente.Nome, studente.Cognome 
    FROM iscrizione 
    JOIN studente ON iscrizione.CodiceFiscale = studente.CodiceFiscale 
    WHERE iscrizione.IdClasse = ?
");
$stmt->bind_param("s", $classe);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()){
    // Creare l'indirizzo email dal nome e cognome
    $email = strtolower($row["Nome"] . "." . $row["Cognome"] . "@itispaleocapa.it");
    
    echo "<tr>";
    echo "<td>". htmlspecialchars($row["CodiceFiscale"]) . "</td>";
    echo "<td>". htmlspecialchars($row["Nome"]) . "</td>";
    echo "<td>". htmlspecialchars($row["Cognome"]) . "</td>";
    echo "<td><a href='mailto:" . $email . "' class='email-btn'>Invia Email</a></td>";
    echo "<td><a href='remove_student.php?classe=" . $classe . "&codiceFiscale=" . $row["CodiceFiscale"] . "' 
             onclick=\"return confirm('Sei sicuro di voler rimuovere questo studente dalla classe?');\">Rimuovi</a></td>";
    echo "</tr>";
}

echo "</table>";
?>