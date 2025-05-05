<?php
require_once("../connessione.php");
session_start();

echo "<a href='../homepage/dashboard_docente.php'>&lt-Torna indietro</a><br>";

$classe = $_GET["classe"];

echo "<table border='1'>";
echo    "<tr>";
echo        "<th>Codice Fiscale</th>";
echo "</tr>";

$stmt = $mysqli->prepare("SELECT * FROM iscrizione WHERE IdClasse = ?");
$stmt->bind_param("s", $classe);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()){
    echo "<tr>";
    echo "<td>". $row["CodiceFiscale"] . "</td>";
    echo "</tr>";
}

echo "</table>";

?>