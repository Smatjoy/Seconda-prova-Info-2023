<?php
require_once("../connessione.php");
session_start();

// Verifica che l'utente sia autenticato come docente
$authenticated = isset($_SESSION["nome"]) && isset($_SESSION["role"]); 
if (!$authenticated || $_SESSION["role"] != "docente") {
    echo "Non sei autenticato o non hai i permessi necessari!";
    die();
}

// Recupera i parametri dalla query string
$classe = $_GET["classe"];
$codiceFiscale = $_GET["codiceFiscale"];

// Prepara la query per eliminare lo studente dalla classe
$stmt = $mysqli->prepare("DELETE FROM iscrizione WHERE IdClasse = ? AND CodiceFiscale = ?");
$stmt->bind_param("ss", $classe, $codiceFiscale);

// Esegui la query
if ($stmt->execute()) {
    // Reindirizza alla pagina della classe con messaggio di successo
    header("Location: view_class.php?classe=$classe&success=1");
} else {
    // Reindirizza alla pagina della classe con messaggio di errore
    header("Location: view_class.php?classe=$classe&error=1");
}
exit();
?>