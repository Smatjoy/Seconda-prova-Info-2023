<?php
session_start();

if (!isset($_SESSION["nome"]) || $_SESSION["role"] != "docente") {
    echo "Non sei autenticato o non hai i permessi necessari!";
    die();
}

require_once("../connessione.php");

// Recuperiamo i dettagli dell'utente autenticato
$nome = $_SESSION["nome"];
$cognome = $_SESSION["cognome"];
$codiceFiscale = $_SESSION["codiceFiscale"];

// Recuperare la classe dall'URL ?classe={idClasse}
$classe = $_GET["classe"] ?? '';

// Verificare che il docente è davvero in quella classe
$stmt = $mysqli->prepare("
SELECT EXISTS (
SELECT 1
FROM ClasseVirtuale
WHERE IdClasse = ?
AND CodiceFiscaleDocente = ?)
");

$stmt->bind_param("is", $classe, $codiceFiscale);
$stmt->execute();
$stmt->bind_result($isIscritto);
$stmt->fetch();

if (!$isIscritto){
    echo "Non sei docente della classe richiesta!";
    die();
}

$stmt->close();

// Mostrare il titolo della classe
$stmt = $mysqli->prepare("
SELECT Materia, Classe
FROM ClasseVirtuale
WHERE IdClasse = ?
");

$stmt->bind_param("i", $classe);
$stmt->execute();
$stmt->bind_result($Materia, $NomeClasse);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classifica - <?php echo htmlspecialchars($Materia); ?> <?php echo htmlspecialchars($NomeClasse); ?></title>
    <link rel="stylesheet" href="../homepage/homepage.css">
    <style>
        body {
            padding: 20px;
            background: linear-gradient(120deg, #f8fafc 0%, #e0e7ff 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 6px 32px rgba(0,0,0,0.10);
            padding: 30px;
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
        .leaderboard-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .leaderboard-title {
            font-size: 28px;
            color: #2a3a5e;
            margin-bottom: 5px;
        }
        .leaderboard-subtitle {
            color: #6366f1;
            font-size: 18px;
            font-weight: normal;
            margin-top: 0;
        }
        .leaderboard-table {
            width: 100%;
            border-collapse: collapse;
        }
        .leaderboard-table th {
            background-color: #6366f1;
            color: white;
            text-align: left;
            padding: 15px;
        }
        .leaderboard-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        .leaderboard-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .leaderboard-table tr:hover {
            background-color: #f1f5fa;
        }
        .coins {
            font-weight: bold;
            color: #f59e0b;
        }
        .rank-1 td:first-child {
            position: relative;
        }
        .rank-1 td:first-child:before {
            content: "🥇";
            position: absolute;
            left: -25px;
        }
        .rank-2 td:first-child {
            position: relative;
        }
        .rank-2 td:first-child:before {
            content: "🥈";
            position: absolute;
            left: -25px;
        }
        .rank-3 td:first-child {
            position: relative;
        }
        .rank-3 td:first-child:before {
            content: "🥉";
            position: absolute;
            left: -25px;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            .leaderboard-table, .leaderboard-table thead, .leaderboard-table tbody, .leaderboard-table th, .leaderboard-table td, .leaderboard-table tr {
                display: block;
            }
            .leaderboard-table thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            .leaderboard-table tr {
                border: 1px solid #ccc;
                margin-bottom: 10px;
                padding-left: 25px;
            }
            .leaderboard-table td {
                border: none;
                position: relative;
                padding-left: 50%;
                text-align: right;
            }
            .leaderboard-table td:before {
                position: absolute;
                top: 12px;
                left: 12px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: bold;
                text-align: left;
            }
            .leaderboard-table td:nth-of-type(1):before { content: "Codice Fiscale"; }
            .leaderboard-table td:nth-of-type(2):before { content: "Nome"; }
            .leaderboard-table td:nth-of-type(3):before { content: "Cognome"; }
            .leaderboard-table td:nth-of-type(4):before { content: "Monete"; }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href='../homepage/dashboard_docente.php' class="back-link">&lt; Torna alla dashboard</a>
        
        <div class="leaderboard-header">
            <h1 class="leaderboard-title">Classifica di <?php echo htmlspecialchars($Materia); ?></h1>
            <h2 class="leaderboard-subtitle">Classe: <?php echo htmlspecialchars($NomeClasse); ?></h2>
        </div>

        <table class="leaderboard-table">
            <thead>
                <tr>
                    <th>Codice Fiscale</th>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Monete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Trovo monete totali degli studenti
                $stmt = $mysqli->prepare("
                SELECT partita.CodiceFiscale AS CodiceFiscaleStudente, studente.Nome AS Nome, studente.Cognome AS Cognome, SUM(partita.Monete) AS MoneteTotali
                FROM studente
                JOIN iscrizione ON (studente.CodiceFiscale = iscrizione.CodiceFiscale)
                JOIN partita ON (studente.CodiceFiscale = partita.CodiceFiscale)
                WHERE iscrizione.IdClasse = ?
                GROUP BY partita.CodiceFiscale
                ORDER BY MoneteTotali DESC 
                ");

                $stmt->bind_param("i", $classe);
                $stmt->execute();
                $result = $stmt->get_result();
                
                $rankCounter = 1;
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()){
                        $rankClass = ($rankCounter <= 3) ? "rank-" . $rankCounter : "";
                        
                        echo "<tr class='" . $rankClass . "'>";
                        echo "<td>" . htmlspecialchars($row["CodiceFiscaleStudente"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["Nome"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["Cognome"]) . "</td>";
                        echo "<td class='coins'>" . htmlspecialchars($row["MoneteTotali"]) . "</td>";
                        echo "</tr>";
                        
                        $rankCounter++;
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align: center; padding: 20px;'>Nessun risultato da mostrare</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>