<?php
require_once("./connessione.php");
session_start();
$error_message = "";

// Corretto il controllo di sessione
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
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Educational Games</title>
    <link rel="stylesheet" href="homepage/homepage.css">
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
</head>
<body>
    <div class="login-container">
        <h1 class="login-title">Login <?php echo $role ?></h1>
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="form-group">
                <label for="codiceFiscale">Codice Fiscale:</label>
                <input type="text" id="codiceFiscale" name="codiceFiscale" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <button type="submit" class="btn-submit">Accedi</button>
        </form>
        
        <div class="register-link">
            <a href="./register.php">Non hai un account? Registrati!</a>
        </div>
    </div>
</body>
</html>