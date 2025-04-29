<?php
// Nel DB sarà presente la tabella feedback che collega studente a videogioco, entrambe chiavi primarie e tre attributi: punteggio, timedtamp e testo del feedback


// La pagina riceverà il gioco verso il quale si vuole inserire il feedback e lo inserirà nel DB

session_start();

if (!isset($_SESSION["nome"])) {
    // non è autenticato
    echo "Non sei autenticato!";
    header("Location: ../index.php");
    die();
}

require_once("../connessione.php");

echo "<a href='../homepage/dashboard_studente.php'>&lt-Torna alla dashboard</a><br>";

// Recuperiamo i dettagli dell'utente autenticato
$nome = $_SESSION["nome"];
$cognome = $_SESSION["cognome"];
$codiceFiscale = $_SESSION["codiceFiscale"];
$role = $_SESSION["role"];

if (!$_SERVER["REQUEST_METHOD"] === "POST") {
    die();
}

if (!(isset($_POST['gioco']) && isset($_POST['punteggio']) && isset($_POST['testo']))) {
    echo "Errore: tutti i campi (gioco, punteggio, testo) devono essere compilati.";
    die();
}

$gioco = $_POST['gioco'];
$punteggio = $_POST['punteggio'];
$testo = $_POST['testo'];

// Santificazione e validazione input
if (!is_numeric($gioco) || $gioco < 0) {
    echo "Errore: ID gioco non valido.";
    die();
}

if (!is_numeric($punteggio) || $punteggio < 1 || $punteggio > 5) {
    echo "Errore: punteggio non valido. Deve essere un numero tra 1 e 5.";
    die();
}

$testo = trim($testo);
if (empty($testo) || strlen($testo) > 160) {
    echo "Errore: testo non valido. Deve essere non vuoto e massimo 160 caratteri.";
    die();
}

// Controllo se lo studente è nella classe associata al gioco
$stmt = $mysqli->prepare("
SELECT EXISTS (
    SELECT 1
    FROM classe_videogioco
    JOIN iscrizione ON classe_videogioco.IdClasse = iscrizione.IdClasse
    WHERE classe_videogioco.IdVideogioco = ?
    AND iscrizione.CodiceFiscale = ?)");
$stmt->bind_param("is", $gioco, $codiceFiscale);
$stmt->execute();
$stmt->bind_result($isAuthorized);
$stmt->fetch();
$stmt->close();

if (!$isAuthorized) {
    echo "Errore: non sei autorizzato a lasciare un feedback per questo gioco.";
    die();
}

$stmt = $mysqli->prepare("
INSERT INTO feedback (`IdVideogioco`, `CodiceFiscale`, `Punteggio`, `Testo`)
VALUES (?,?,?,?);
");

$stmt->bind_param("isis", $gioco, $codiceFiscale, $punteggio, $testo);

if ($stmt->execute()) {
    echo "<br>Feedback inserito con successo";
    exit();
} else {
    echo "<br>Errore nell'inserimento del feedback";
    exit();
}


?>