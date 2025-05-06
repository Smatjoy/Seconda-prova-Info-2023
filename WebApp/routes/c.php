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

// Recuperare la classe dall'URL ?classe={idClasse}
$classe = $_GET["classe"] ?? '';

// Verificare che lo studente è davvero in quella classe
$stmt = $mysqli->prepare("
SELECT EXISTS (
SELECT 1
FROM iscrizione
WHERE IdClasse = ?
AND CodiceFiscale = ?)
");

$stmt->bind_param("is", $classe, $codiceFiscale);
$stmt->execute();
$stmt->bind_result($isIscritto);
$stmt->fetch();

if (!$isIscritto){
    echo "Non sei iscritto alla classe richiesta!";
    die();
}

$stmt->close();

// Recupera informazioni sulla classe
$stmtClass = $mysqli->prepare("SELECT Classe, Materia FROM ClasseVirtuale WHERE IdClasse = ?");
$stmtClass->bind_param("i", $classe);
$stmtClass->execute();
$stmtClass->bind_result($nomeClasse, $materiaClasse);
$stmtClass->fetch();
$stmtClass->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videogiochi - <?php echo htmlspecialchars($materiaClasse); ?> <?php echo htmlspecialchars($nomeClasse); ?></title>
    <link rel="stylesheet" href="../homepage/homepage.css">
    <style>
        body {
            padding: 20px;
            background: linear-gradient(120deg, #f8fafc 0%, #e0e7ff 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
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
        .class-header {
            background: white;
            padding: 20px 30px;
            border-radius: 18px;
            box-shadow: 0 6px 32px rgba(0,0,0,0.10);
            margin-bottom: 30px;
            text-align: center;
        }
        .class-title {
            color: #2a3a5e;
            margin-bottom: 5px;
        }
        .class-subtitle {
            color: #6366f1;
            margin-top: 0;
        }
        .games-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }
        .game-card {
            background: white;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 6px 24px rgba(0,0,0,0.08);
        }
        .game-image {
            height: 200px;
            overflow: hidden;
        }
        .game-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .game-card:hover .game-image img {
            transform: scale(1.05);
        }
        .game-info {
            padding: 20px;
        }
        .game-title {
            font-size: 20px;
            color: #2a3a5e;
            margin-top: 0;
            margin-bottom: 10px;
        }
        .game-description {
            color: #4b5563;
            margin-bottom: 15px;
            font-size: 14px;
            line-height: 1.5;
        }
        .game-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .game-coins {
            background: #fef3c7;
            color: #92400e;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
        .game-tags {
            font-size: 13px;
            color: #6b7280;
        }
        .game-actions {
            display: flex;
            gap: 10px;
        }
        .game-btn {
            flex: 1;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .play-btn {
            background: #3b82f6;
            color: white;
        }
        .play-btn:hover {
            background: #2563eb;
        }
        .feedback-form {
            margin-top: 15px;
            padding: 15px;
            border-top: 1px solid #e5e7eb;
        }
        .feedback-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
            font-size: 14px;
        }
        .feedback-input {
            width: 100%;
            padding: 8px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .feedback-btn {
            background: #10b981;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 8px 15px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.2s ease;
        }
        .feedback-btn:hover {
            background: #059669;
        }
        
        @media (max-width: 768px) {
            .games-grid {
                grid-template-columns: 1fr;
            }
            .game-image {
                height: 150px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href='../homepage/dashboard_studente.php' class="back-link">&lt; Torna alla dashboard</a>
        
        <div class="class-header">
            <h1 class="class-title"><?php echo htmlspecialchars($materiaClasse); ?></h1>
            <h2 class="class-subtitle">Classe <?php echo htmlspecialchars($nomeClasse); ?> - Videogiochi disponibili</h2>
        </div>

        <div class="games-grid">
            <?php
            $stmt = $mysqli->prepare("
            SELECT videogioco.IdVideogioco, videogioco.Titolo, videogioco.Descrizione, videogioco.DescrizioneEstesa, videogioco.MoneteMax, videogioco.Immagine1, videogioco.Immagine2, videogioco.Immagine3,
            GROUP_CONCAT(argomento.Titolo SEPARATOR ', ') AS TitoliArgomenti
            FROM videogioco
            JOIN classe_videogioco ON classe_videogioco.IdVideogioco = videogioco.IdVideogioco
            LEFT JOIN videogioco_argomento ON videogioco.IdVideogioco = videogioco_argomento.IdVideogioco
            LEFT JOIN argomento ON videogioco_argomento.IdArgomento = argomento.IdArgomento
            WHERE classe_videogioco.IdClasse = ?
            GROUP BY videogioco.IdVideogioco
            ");

            $stmt->bind_param("i", $classe);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()){
                    echo '<div class="game-card">';
                    echo '<div class="game-image">';
                    echo '<img src="../images/'. htmlspecialchars($row["Immagine1"]) . '" alt="' . htmlspecialchars($row["Titolo"]) . '">';
                    echo '</div>';
                    echo '<div class="game-info">';
                    echo '<h3 class="game-title">' . htmlspecialchars($row["Titolo"]) . '</h3>';
                    echo '<p class="game-description">' . htmlspecialchars($row["Descrizione"]) . '</p>';
                    
                    echo '<div class="game-meta">';
                    echo '<div class="game-coins">Monete max: ' . htmlspecialchars($row["MoneteMax"]) . '</div>';
                    echo '<div class="game-tags">Argomenti: ' . htmlspecialchars($row["TitoliArgomenti"]) . '</div>';
                    echo '</div>';
                    
                    echo '<div class="game-actions">';
                    echo '<a href="#" class="game-btn play-btn">Gioca</a>';
                    echo '</div>';
                    
                    echo '<form action="../routes/feedback.php" method="POST" class="feedback-form">';
                    echo '<input type="hidden" name="gioco" value="'. $row["IdVideogioco"] .'">';
                    echo '<label class="feedback-label" for="punteggio_'. $row["IdVideogioco"] .'">Valutazione (1-5):</label>';
                    echo '<input class="feedback-input" type="number" id="punteggio_'. $row["IdVideogioco"] .'" name="punteggio" min="1" max="5" required>';
                    echo '<label class="feedback-label" for="testo_'. $row["IdVideogioco"] .'">Feedback:</label>';
                    echo '<input class="feedback-input" type="text" id="testo_'. $row["IdVideogioco"] .'" name="testo" maxlength="160" required>';
                    echo '<button type="submit" class="feedback-btn">Invia Feedback</button>';
                    echo '</form>';
                    
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div style="grid-column: 1 / -1; text-align: center; padding: 30px; background: white; border-radius: 18px; box-shadow: 0 6px 24px rgba(0,0,0,0.08);">';
                echo '<h3>Nessun videogioco disponibile per questa classe</h3>';
                echo '<p>Contatta il tuo docente per maggiori informazioni.</p>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>