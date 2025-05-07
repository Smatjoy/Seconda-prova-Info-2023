<?php
session_start();
require_once("../connessione.php");

// Verifica autenticazione
if (!isset($_SESSION["nome"]) || $_SESSION["role"] != "docente") {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Non autorizzato']);
    exit();
}

if (!isset($_GET['classId'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'ID classe mancante']);
    exit();
}

$classId = $_GET['classId'];
$codiceFiscaleDocente = $_SESSION["codiceFiscale"];

// Verifica che la classe appartenga al docente
$stmt = $mysqli->prepare("SELECT * FROM ClasseVirtuale WHERE IdClasse = ? AND CodiceFiscaleDocente = ?");
$stmt->bind_param("is", $classId, $codiceFiscaleDocente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Classe non trovata o non autorizzato']);
    exit();
}

// Ottieni i giochi assegnati alla classe
$stmt = $mysqli->prepare("
    SELECT v.* 
    FROM videogioco v 
    INNER JOIN classe_videogioco cv ON v.IdVideogioco = cv.IdVideogioco 
    WHERE cv.IdClasse = ?
");
$stmt->bind_param("i", $classId);
$stmt->execute();
$result = $stmt->get_result();

$games = [];
while ($row = $result->fetch_assoc()) {
    $games[] = [
        'id' => $row['IdVideogioco'],
        'nome' => $row['Titolo'],
        'descrizione' => $row['Descrizione'],
        'icon' => 'fa-gamepad' // Icona di default per tutti i giochi
    ];
}

header('Content-Type: application/json');
echo json_encode($games); 