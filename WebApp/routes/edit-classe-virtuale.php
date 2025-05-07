<?php
session_start();
require_once("../connessione.php");

// Verifica autenticazione
if (!isset($_SESSION["nome"]) || $_SESSION["role"] != "docente") {
    header("Location: ../login.php");
    exit();
}

// Verifica che tutti i campi necessari siano stati inviati
if (!isset($_POST["idClasse"]) || !isset($_POST["classe"]) || !isset($_POST["materia"])) {
    $_SESSION["error"] = "Tutti i campi sono obbligatori";
    header("Location: ../homepage/dashboard_docente.php");
    exit();
}

$idClasse = $_POST["idClasse"];
$classe = trim($_POST["classe"]);
$materia = trim($_POST["materia"]);
$codiceFiscaleDocente = $_SESSION["codiceFiscale"];

// Verifica che la classe appartenga al docente
$stmt = $mysqli->prepare("SELECT * FROM ClasseVirtuale WHERE IdClasse = ? AND CodiceFiscaleDocente = ?");
$stmt->bind_param("is", $idClasse, $codiceFiscaleDocente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION["error"] = "Non hai i permessi per modificare questa classe";
    header("Location: ../homepage/dashboard_docente.php");
    exit();
}

// Aggiorna i dati della classe
$stmt = $mysqli->prepare("UPDATE ClasseVirtuale SET Classe = ?, Materia = ? WHERE IdClasse = ? AND CodiceFiscaleDocente = ?");
$stmt->bind_param("ssis", $classe, $materia, $idClasse, $codiceFiscaleDocente);

if ($stmt->execute()) {
    $_SESSION["success"] = "Classe virtuale modificata con successo";
} else {
    $_SESSION["error"] = "Errore durante la modifica della classe virtuale";
}

header("Location: ../homepage/dashboard_docente.php");
exit(); 