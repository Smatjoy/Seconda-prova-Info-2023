<?php
require_once("../connessione.php");
session_start();

if (!isset($_SESSION["codiceFiscale"])) {
    echo "Non sei autenticato!";
    header("Location: ../index.php");
    exit();
} else {
    echo "<a href='../homepage/dashboard_studente.php'>&lt-Torna indietro</a><br>";

    $codiceFiscale = $_SESSION["codiceFiscale"];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        echo "DEBUG: post\n";
        if (isset($_POST['classe']) and isset($_POST['materia'])) {
            echo "DEBUG: variable read\n";

            // Recupero le variabili ottenute tramite POST
            $classe = htmlspecialchars(trim($_POST["classe"]));
            $materia = htmlspecialchars(trim($_POST["materia"]));

            // Creo un codice per l'accesso
            $codiceAccesso = substr(str_shuffle(strtoupper(md5(uniqid(rand(), true)))), 0, 6);

            $stmt = $mysqli->prepare("INSERT INTO ClasseVirtuale (Classe, Materia, CodiceFiscaleDocente, CodiceAccesso) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $classe, $materia, $codiceFiscale, $codiceAccesso);

            if ($stmt->execute()) {
                echo "<br>Classe creata con successo";
                exit();
            } else {
                echo "<br>Errore nella creazione della classe";
                exit();
            }

        }
    }
}
?>