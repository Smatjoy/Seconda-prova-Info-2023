<?php
require_once("./connessione.php");
session_start();
$error_message = "";
// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

if (!isset($_SESSION["role"])) {
    header("Location: index.php");
    exit();
} else {
    $role = $_SESSION["role"];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $codiceFiscale = htmlspecialchars(trim($_POST["codiceFiscale"]));
        $nome = htmlspecialchars(trim($_POST["nome"]));
        $cognome = htmlspecialchars(trim($_POST["cognome"]));
        $password = htmlspecialchars(trim($_POST["password"]));

        // Validazione dei dati
        if (empty($codiceFiscale) || empty($nome) || empty($cognome || empty($password))) {
            $error_message = "Tutti i campi sono obbligatori.";
        } else if (strlen($codiceFiscale) != 16) {
            $error_message = "Inserisci un codice fiscale valido";
        } else if (strlen($password) < 8) {
            $error_message = "La password deve essere lunga almeno 8 caratteri";
        } else {
            // Inserisco l'utente nel database
            if ($role = "docente")
                $stmt = $mysqli->prepare("INSERT INTO Docente (CodiceFiscale, Nome, Cognome, Password) VALUES (?, ?, ?, ?)");
            else if ($role = "studente")
                $stmt = $mysqli->prepare("INSERT INTO Studente (CodiceFiscale, Nome, Cognome, Password) VALUES (?, ?, ?, ?)");

            $stmt->bind_param("ssss", $codiceFiscale, $nome, $cognome, $password);
            if ($stmt->execute()) {
                $_SESSION["nome"] = $nome;
                $_SESSION["cognome"] = $cognome;
                $_SESSION["codiceFiscale"] = $codiceFiscale;
                echo "<script>alert('Dati inseriti con successo!');</script>";
                header("Location: ./homepage/homepage.php");
            } else {
                echo "<script>alert('Errore durante l\'inserimento dei dati: " . addslashes($stmt->error) . "');</script>";
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
    <title>Registrazione - Educational Games</title>
    <link rel="stylesheet" href="style.css">
</head>

<div class="div1">
    <h1>Registrazione <?php echo $role ?></h1>
</div>

<form method="post" action="register.php" class="form-data">
    <div class="parent">
        <div class="div6">
            <label for="codiceFiscale">Codice Fiscale:</label>
            <input type="text" id="codiceFiscale" name="codiceFiscale" required><br><br>
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required><br><br>
            <label for="cognome">Cognome:</label>
            <input type="text" id="cognome" name="cognome" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <?php if ($error_message != "")
                echo $error_message ?>
            </div>
        </div>
        <div class="div5">
            <input type="submit" value="Conferma">
        </div>
    </form>
    <div class="div5">
        <a href="./register.php">Non hai un account? Registrati!</a>
    </div>

    </html>