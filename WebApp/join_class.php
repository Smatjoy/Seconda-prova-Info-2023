<?php

session_start();

$authenticated = isset($_SESSION['nome']);

if (!$authenticated){
    echo "Non sei autenticato!";
    die();
}

if (!isset($_GET['codice'])){
    echo "codice GET parameter is required";
    die();
}

echo "<a href='dashboard_studente.php'>&lt-Torna indietro</a><br>";

$codiceFiscale = $_SESSION['codiceFiscale'];
$codiceAccesso = $_GET['codice'];

require_once("./connessione.php");

// recuperare l'idClasse in base al codice di accesso
$stmt = $mysqli->prepare("
SELECT IdClasse
FROM classevirtuale
WHERE CodiceAccesso = ?
");

$stmt->bind_param("s", $codiceAccesso);
$stmt->execute();
$stmt->bind_result($idClasse);

if (!$stmt->fetch()) {
    echo "Classe non trovata!";
    $stmt->close();
    die();
}

$stmt->close();



// eseguiamo l'iscrizione
$stmt = $mysqli->prepare("INSERT INTO iscrizione (IdClasse, CodiceFiscale, Orario) VALUES (?,?,CURRENT_TIMESTAMP)");
$stmt->bind_param("ss", $idClasse, $codiceFiscale);

if ($stmt->execute()){
    echo "Iscrizione avvenuta con successo!";
}else{
    echo "Errore nell'iscrizione!";
}
?>