<?php
session_start();

$authenticated = isset($_SESSION['nome']);

if (!$authenticated){
    echo "Non sei autenticato!";
    header("Location: ../index.php");
    die();
}

require_once("../connessione.php");

$codiceFiscale = $_SESSION['codiceFiscale'];
$success = false;
$error_message = "";
$classInfo = null;

if (isset($_GET['codice'])) {
    $codiceAccesso = $_GET['codice'];
    
    // recuperare l'idClasse in base al codice di accesso
    $stmt = $mysqli->prepare("
    SELECT IdClasse, Classe, Materia 
    FROM classevirtuale
    WHERE CodiceAccesso = ?
    ");

    $stmt->bind_param("s", $codiceAccesso);
    $stmt->execute();
    $stmt->bind_result($idClasse, $className, $subjectName);

    if (!$stmt->fetch()) {
        $error_message = "Classe non trovata! Verifica il codice di accesso.";
    } else {
        $classInfo = [
            'id' => $idClasse,
            'name' => $className,
            'subject' => $subjectName
        ];
        $stmt->close();

        // Verifica se lo studente è già iscritto
        $stmtCheck = $mysqli->prepare("
        SELECT EXISTS (
            SELECT 1 FROM iscrizione 
            WHERE IdClasse = ? AND CodiceFiscale = ?
        )");
        $stmtCheck->bind_param("is", $idClasse, $codiceFiscale);
        $stmtCheck->execute();
        $stmtCheck->bind_result($isAlreadyEnrolled);
        $stmtCheck->fetch();
        $stmtCheck->close();

        if ($isAlreadyEnrolled) {
            $error_message = "Sei già iscritto a questa classe!";
        } else {
            // eseguiamo l'iscrizione
            $stmt = $mysqli->prepare("INSERT INTO iscrizione (IdClasse, CodiceFiscale, Orario) VALUES (?,?,CURRENT_TIMESTAMP)");
            $stmt->bind_param("ss", $idClasse, $codiceFiscale);

            if ($stmt->execute()){
                $success = true;
            } else {
                $error_message = "Errore durante l'iscrizione!";
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
    <title>Entra nella classe - Educational Games</title>
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
        .join-container {
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
        .join-title {
            color: #2a3a5e;
            margin-bottom: 5px;
        }
        .join-subtitle {
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
        .btn-secondary {
            background: #6b7280;
            margin-right: 10px;
        }
        .btn-secondary:hover {
            background: #4b5563;
        }
        .class-info {
            background: #f1f5fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .class-info h3 {
            margin-top: 0;
            color: #2a3a5e;
        }
        .class-info p {
            margin-bottom: 5px;
            color: #4b5563;
        }
        .class-info-highlight {
            font-weight: bold;
            color: #6366f1;
        }
    </style>
</head>
<body>
    <div class="join-container">
        <?php if ($success): ?>
            <div class="success-message">
                <div class="success-icon">✓</div>
                <h2>Iscrizione completata!</h2>
                <p>Ti sei iscritto con successo alla classe.</p>
            </div>
            
            <div class="class-info">
                <h3><?php echo htmlspecialchars($classInfo['subject']); ?></h3>
                <p>Classe: <span class="class-info-highlight"><?php echo htmlspecialchars($classInfo['name']); ?></span></p>
                <p>Ora puoi accedere ai materiali e ai videogiochi della classe.</p>
            </div>
            
            <div>
                <a href="../routes/c.php?classe=<?php echo $classInfo['id']; ?>" class="btn">Vai alla classe</a>
                <a href="../homepage/dashboard_studente.php" class="btn btn-secondary">Torna alla dashboard</a>
            </div>
            
        <?php elseif (!empty($error_message)): ?>
            <div class="error-message">
                <h2>Non è stato possibile completare l'iscrizione</h2>
                <p><?php echo htmlspecialchars($error_message); ?></p>
            </div>
            <a href="../homepage/dashboard_studente.php" class="btn">Torna alla dashboard</a>
        <?php else: ?>
            <a href="../homepage/dashboard_studente.php" class="back-link">&lt; Torna alla dashboard</a>
            <h1 class="join-title">Entra in una classe virtuale</h1>
            <p>Inserisci il codice di accesso fornito dal tuo docente.</p>
            
            <form action="" method="GET" style="max-width: 300px; margin: 0 auto;">
                <input type="text" name="codice" placeholder="Codice di accesso" required
                       style="width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #c7d2fe; 
                              border-radius: 6px; font-size: 16px; background: #f1f5fa;">
                <button type="submit" class="btn">Entra nella classe</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>