<?php
require_once("./connessione.php");
session_start();
$error_message = "";

// Questo controllo sta impedendo l'accesso - modifichiamolo
if (isset($_SESSION["role"])) {
    $role = $_SESSION["role"];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $codiceFiscale = htmlspecialchars(trim($_POST["codiceFiscale"]));
        $password = htmlspecialchars(trim($_POST["password"]));

        // Validazione dei dati
        if (empty($codiceFiscale) || empty($password)) {
            $error_message = "Tutti i campi sono obbligatori.";
        } else if (strlen($codiceFiscale) != 16) {
            $error_message = "Inserisci un codice fiscale valido";
        } else {
            // Recupero dell'utente dal database
            if ($role == "docente")
                $stmt = $mysqli->prepare("SELECT Password, Nome, Cognome FROM Docente WHERE CodiceFiscale = ?");
            else if ($role == "studente")
                $stmt = $mysqli->prepare("SELECT Password, Nome, Cognome FROM Studente WHERE CodiceFiscale = ?");

            $stmt->bind_param("s", $codiceFiscale);
            $stmt->execute();
            $stmt->store_result();
            
            // Controlla se l'utente esiste
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($stored_password, $nome, $cognome);
                $stmt->fetch();

                if ($stored_password == $password) {
                    //Login effettuato
                    $_SESSION["nome"] = $nome;
                    $_SESSION["cognome"] = $cognome;
                    $_SESSION["codiceFiscale"] = $codiceFiscale;
                    $_SESSION["role"] = $role;
                    
                    if ($role == "studente") {
                        header("Location: ./homepage/dashboard_studente.php");
                    } else {
                        header("Location: ./homepage/dashboard_docente.php");
                    }
                    exit();
                } else {
                    $error_message = "Password non corretta.";
                }
            } else {
                $error_message = "Utente non trovato.";
            }
        }
    }
}

// Se non è impostato il ruolo, imposta un valore di default
if (!isset($role)) {
    $role = "studente"; // Default role
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

<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="form-data">
    <div class="parent">
        <div class="div6">
            <label for="codiceFiscale">Codice Fiscale:</label>
            <input type="text" id="codiceFiscale" name="codiceFiscale" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <?php if (!empty($error_message)) echo "<p class='error'>$error_message</p>"; ?>
        </div>
        <div class="div5">
            <input type="submit" value="Conferma">
        </div>
    </div>
</form>
<div class="div5">
    <a href="./register.php">Non hai un account? Registrati!</a>
</div>
</html>