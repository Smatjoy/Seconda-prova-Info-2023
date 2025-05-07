<?php
session_start();
require_once("../connessione.php");

// Verifica autenticazione
if (!isset($_SESSION["nome"]) || $_SESSION["role"] != "docente") {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Non autorizzato']);
    exit();
}

if (!isset($_POST['classId']) || !isset($_POST['gameId'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Parametri mancanti']);
    exit();
}

$classId = $_POST['classId'];
$gameId = $_POST['gameId'];
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

// Rimuovi il gioco dalla classe
$stmt = $mysqli->prepare("DELETE FROM classe_videogioco WHERE IdClasse = ? AND IdVideogioco = ?");
$stmt->bind_param("ii", $classId, $gameId);

if ($stmt->execute()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Errore durante la rimozione del gioco']);
} 