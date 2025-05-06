<?php
require_once("./connessione.php");
session_start();
$error_message = "";

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
        if (empty($codiceFiscale) || empty($nome) || empty($cognome) || empty($password)) {
            $error_message = "Tutti i campi sono obbligatori.";
        } else if (strlen($codiceFiscale) != 16) {
            $error_message = "Inserisci un codice fiscale valido";
        } else if (strlen($password) < 8) {
            $error_message = "La password deve essere lunga almeno 8 caratteri";
        } else {
            // Inserisco l'utente nel database
            if ($role == "docente")
                $stmt = $mysqli->prepare("INSERT INTO Docente (CodiceFiscale, Nome, Cognome, Password) VALUES (?, ?, ?, ?)");
            else if ($role == "studente")
                $stmt = $mysqli->prepare("INSERT INTO Studente (CodiceFiscale, Nome, Cognome, Password) VALUES (?, ?, ?, ?)");

            $stmt->bind_param("ssss", $codiceFiscale, $nome, $cognome, $password);
            if ($stmt->execute()) {
                echo "<script>alert('Dati inseriti con successo!');</script>";

                $_SESSION["role"] = $role;

                header("Location: ./login.php");

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

<style>
        body {
            background: linear-gradient(120deg, #f8fafc 0%, #e0e7ff 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
        }
        .login-container {
            background: white;
            border-radius: 18px;
            box-shadow: 0 6px 32px rgba(0,0,0,0.10);
            padding: 32px;
            width: 100%;
            max-width: 450px;
        }
        .login-title {
            text-align: center;
            color: #2a3a5e;
            margin-bottom: 24px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #c7d2fe;
            border-radius: 6px;
            font-size: 16px;
            background: #f1f5fa;
        }
        .error-message {
            color: #dc2626;
            margin: 10px 0;
            font-size: 14px;
        }
        .btn-submit {
            width: 100%;
            background: linear-gradient(90deg, #3b82f6 60%, #6366f1 100%);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            font-weight: 500;
        }
        .btn-submit:hover {
            background: linear-gradient(90deg, #6366f1 60%, #3b82f6 100%);
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
        .register-link a {
            color: #3b82f6;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>

<body>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="form-data">
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
            <a href="./login.php">Hai già un account? Accedi</a>
        </div>
    </body>

    </html>