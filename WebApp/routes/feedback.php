<?php
session_start();

if (!isset($_SESSION["nome"])) {
    echo "Non sei autenticato!";
    header("Location: ../index.php");
    die();
}

require_once("../connessione.php");

// Recuperiamo i dettagli dell'utente autenticato
$nome = $_SESSION["nome"];
$cognome = $_SESSION["cognome"];
$codiceFiscale = $_SESSION["codiceFiscale"];
$role = $_SESSION["role"];

$success = false;
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!(isset($_POST['gioco']) && isset($_POST['punteggio']) && isset($_POST['testo']))) {
        $error_message = "Errore: tutti i campi (gioco, punteggio, testo) devono essere compilati.";
    } else {
        $gioco = $_POST['gioco'];
        $punteggio = $_POST['punteggio'];
        $testo = $_POST['testo'];

        // Santificazione e validazione input
        if (!is_numeric($gioco) || $gioco < 0) {
            $error_message = "Errore: ID gioco non valido.";
        } elseif (!is_numeric($punteggio) || $punteggio < 1 || $punteggio > 5) {
            $error_message = "Errore: punteggio non valido. Deve essere un numero tra 1 e 5.";
        } else {
            $testo = trim($testo);
            if (empty($testo) || strlen($testo) > 160) {
                $error_message = "Errore: testo non valido. Deve essere non vuoto e massimo 160 caratteri.";
            } else {
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
                    $error_message = "Errore: non sei autorizzato a lasciare un feedback per questo gioco.";
                } else {
                    $stmt = $mysqli->prepare("
                    INSERT INTO feedback (`IdVideogioco`, `CodiceFiscale`, `Punteggio`, `Testo`)
                    VALUES (?,?,?,?);
                    ");

                    $stmt->bind_param("isis", $gioco, $codiceFiscale, $punteggio, $testo);

                    if ($stmt->execute()) {
                        $success = true;
                    } else {
                        $error_message = "Errore nell'inserimento del feedback";
                    }
                }
            }
        }
    }
}

// Recupera informazioni sul gioco
$gameInfo = null;
if (isset($_POST['gioco'])) {
    $gioco = $_POST['gioco'];
    $stmtGame = $mysqli->prepare("SELECT Titolo FROM Videogioco WHERE IdVideogioco = ?");
    $stmtGame->bind_param("i", $gioco);
    $stmtGame->execute();
    $stmtGame->bind_result($gameTitle);
    if ($stmtGame->fetch()) {
        $gameInfo = $gameTitle;
    }
    $stmtGame->close();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - Educational Games</title>
    <link rel="stylesheet" href="../homepage/homepage.css">
    <style>
        body {
            padding: 20px;
            background: linear-gradient(120deg, #f8fafc 0%, #e0e7ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .feedback-container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 18px;
            box-shadow: 0 6px 32px rgba(0,0,0,0.10);
            padding: 30px;
            text-align: center;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .feedback-title {
            color: #2a3a5e;
            margin-bottom: 5px;
        }
        .feedback-subtitle {
            color: #6366f1;
            margin-top: 0;
            margin-bottom: 30px;
        }
        .success-message {
            background-color: #d1fae5;
            color: #065f46;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .error-message {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .success-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #3b82f6;
            color: white;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            margin-top: 20px;
            transition: all 0.2s ease;
        }
        .btn:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="feedback-container">
        <h1 class="feedback-title">Feedback</h1>
        
        <?php if ($success): ?>
            <div class="success-message">
                <div class="success-icon">✓</div>
                <h2>Grazie per il tuo feedback!</h2>
                <p>La tua valutazione per <strong><?php echo htmlspecialchars($gameInfo); ?></strong> è stata registrata con successo.</p>
            </div>
            <a href="../homepage/dashboard_studente.php" class="btn">Torna alla dashboard</a>
        <?php elseif (!empty($error_message)): ?>
            <div class="error-message">
                <h2>Si è verificato un errore</h2>
                <p><?php echo htmlspecialchars($error_message); ?></p>
            </div>
            <a href="javascript:history.back()" class="btn">Torna indietro</a>
        <?php else: ?>
            <a href="../homepage/dashboard_studente.php" class="back-link">&lt; Torna alla dashboard</a>
            <h2 class="feedback-subtitle">Qualcosa è andato storto</h2>
            <p>Non hai fornito un feedback valido o la pagina è stata caricata direttamente.</p>
            <a href="../homepage/dashboard_studente.php" class="btn">Torna alla dashboard</a>
        <?php endif; ?>
    </div>
</body>
</html>