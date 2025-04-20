<?php
require_once("../connessione.php");
session_start();

if (!isset($_SESSION["codiceFiscale"])) {
    header("Location: ../index.php");
    exit();
} else {
    $role = $_SESSION["role"];
    $nome = $_SESSION["nome"];
    $cognome = $_SESSION["cognome"];
    $codiceFiscale = $_SESSION["codiceFiscale"];

    $query = "SELECT * FROM ClasseVirtuale WHERE CodiceFiscaleDocente = '$codiceFiscale'";
    $result = $mysqli->query($query);

    //Controllo se ci sono risultati
    if ($result && $result->num_rows > 0) {
        // Ci sono classi per questo docente
        $classi = array();
        while ($row = $result->fetch_assoc()) {
            $classi[] = $row;
        }
    } else {
        //Nessun risultato trovato
        $noClasses = true;
    }

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
    <div class="div3">
        <?php if (isset($noClasses) && $noClasses && $role == "docente"): ?>
            <p>Non hai ancora creato una classe</p>
            <button type="button" onclick="window.location.href='./new-classe-virtuale.php'">Creane una</button>
        <?php elseif (isset($classi) && !empty($classi) && $role == "docente"): ?>
            <?php foreach ($classi as $classe): ?>
                <div class="class-box">
                    <h2 class="class-name"><?php echo $classe['Classe'] . ' ' . $classe['Materia']; ?></h2>
                    <h3 class="class-id">ID: <?php echo $classe['IdClasse']; ?></h3>

                    <?php
                    // Query per contare gli studenti in questa classe virtuale
                    $query = "SELECT COUNT(*) as total FROM Iscrizione WHERE IdClasse = " . $classe['IdClasse'];
                    $countResult = $mysqli->query($query);
                    $count = ($countResult && $countResult->num_rows > 0) ? $countResult->fetch_assoc()['total'] : 0;
                    ?>

                    <h4 class="class-n-participant">Studenti iscritti: <?php echo $count; ?></h4>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Errore nel caricamento delle classi</p>
        <?php endif; ?>
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