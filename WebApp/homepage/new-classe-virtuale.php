<?php
require_once("../connessione.php");
session_start();

if (!isset($_SESSION["codiceFiscale"])) {
    header("Location: ../index.php");
    exit();
} else {
    $codiceFiscale = $_SESSION["codiceFiscale"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $classe = htmlspecialchars(trim($_POST["classe"]));
        $materia = htmlspecialchars(trim($_POST["materia"]));

        // Validazione dei dati
        if (empty($classe) || empty($materia)) {
            $error_message = "Tutti i campi sono obbligatori.";
        } else {
            // Creo un codice per l'accesso
            $codiceAccesso = substr(str_shuffle(strtoupper(md5(uniqid(rand(), true)))), 0, 6);
            
            $stmt = $mysqli->prepare("INSERT INTO ClasseVirtuale (Classe, Materia, CodiceFiscaleDocente, CodiceAccesso) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $classe, $materia, $codiceFiscale, $codiceAccesso);

            if ($stmt->execute()) {
                echo "<script>alert(\"Classe creata con successo\")</script>";
                header("Location: ./homepage.php");
                exit();
            } else {
                echo "<script>alert(\"Errore nella creazione della classe\")</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuova Classe Virtuale - Educational Games</title>
    <link rel="stylesheet" href="../style.css">
</head>

<div class="div1">
    <h1>Nuova Classe Virtuale</h1>
</div>

<form method="post" action="new-classe-virtuale.php" class="form-data">
    <div class="parent">
        <div class="div6">
            <?php if ($error_message != "")
                echo $error_message ?>
                <label for="classe">Classe:</label>
                <input type="text" id="classe" name="classe" required><br><br>
                <label for="materia">Materia:</label>
                <input type="text" id="materia" name="materia" required><br><br>
            </div>
        </div>
        <div class="div5">
            <input type="submit" value="Conferma">
        </div>
    </form>

    </html>