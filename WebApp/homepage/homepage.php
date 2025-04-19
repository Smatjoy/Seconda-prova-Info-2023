<?php
require_once("../connessione.php");
session_start();

if (!isset($_SESSION["codiceFiscale"])) {
    header("Location: ../index.php");
    exit();
} else {
    $nome = $_SESSION["nome"];
    $cognome = $_SESSION["cognome"];
    $codiceFiscale = $_SESSION["codiceFiscale"];

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage - Educational Games</title>
    <link rel="stylesheet" href="./homepage.css">
</head>

<div class="parent">
    <div class="div1">
        <h1>Benvenuto <?php echo $nome ?>!</h1>
    </div>
    <div class="div2">
        <button type="button" onclick="window.location.href='./new-classe-virtuale.php'">Nuova Classe Virtuale</button>
    </div>
    <div class="div4">
        Le tue informazioni:
    </div>
    <div class="div5" id="info-container">
        <div class="info-label">Nome:</div>
        <div class="info-content"><?php echo $nome; ?></div>
    </div>
    <div class="div6" id="info-container">
        <div class="info-label">Cognome:</div>
        <div class="info-content"><?php echo $cognome; ?></div>
    </div>
    <div class="div7" id="info-container">
        <div class="info-label">Codice Fiscale:</div>
        <div class="info-content"><?php echo $codiceFiscale; ?></div>
    </div>
    <div class="div8">
        <button type="button" onclick="window.location.href='../logout.php'">Logout</button>
    </div>
</div>

</html>