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
        $password = htmlspecialchars(trim($_POST["password"]));

        // Validazione dei dati
        if (empty($codiceFiscale) || empty($password)) {
            $error_message = "Tutti i campi sono obbligatori.";
        } else if (strlen($codiceFiscale) != 16) {
            $error_message = "Inserisci un codice fiscale valido";
        } else if (strlen($password) < 8) {
            $error_message = "La password deve essere lunga almeno 8 caratteri";
        } else {
            // Recupero dell'utente dal database
            if ($role == "docente")
                $stmt = $mysqli->prepare("SELECT Password, Nome, Cognome FROM Docente WHERE CodiceFiscale = ?");
            else if ($role == "studente")
                $stmt = $mysqli->prepare("SELECT Password, Nome, Cognome FROM Studente WHERE CodiceFiscale = ?");

            $stmt->bind_param("s", $codiceFiscale);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($stored_password, $nome, $cognome);
            $stmt->fetch();

            if ($stored_password == $password) {
                //Login effettuato
                $_SESSION["nome"] = $nome;
                $_SESSION["cognome"] = $cognome;
                $_SESSION["codiceFiscale"] = $codiceFiscale;
                header("Location: ./homepage/homepage.php");
                exit();
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
    <title>Login - Educational Games</title>
    <link rel="stylesheet" href="style.css">
</head>

<div class="div1">
    <h1>Login <?php echo $role ?></h1>
</div>

<form method="post" action="login.php" class="form-data">
    <div class="parent">
        <div class="div6">
            <label for="codiceFiscale">Codice Fiscale:</label>
            <input type="text" id="codiceFiscale" name="codiceFiscale" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <?php if ($error_message != "")
                echo $error_message ?>
            </div>
        </div>
        <div class="div5">
            <button><input type="submit" value="Conferma"></button>
        </div>
    </form>
    <div class="div5">
        <a href="./register.php">Non hai un account? Registrati!</a>
    </div>

    </html>