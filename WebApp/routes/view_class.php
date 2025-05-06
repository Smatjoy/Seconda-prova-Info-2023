<?php
require_once("../connessione.php");
session_start();

// Verifica autenticazione
if (!isset($_SESSION["nome"]) || !isset($_SESSION["role"]) || $_SESSION["role"] != "docente") {
    echo "Non sei autenticato o non hai i permessi necessari!";
    die();
}

$classe = isset($_GET["classe"]) ? $_GET["classe"] : '';

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
    <title>Studenti della Classe - Educational Games</title>
    <link rel="stylesheet" href="../homepage/homepage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            padding: 20px;
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
            background: #f1f5fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 5px solid #6366f1;
        }
        .class-name {
            margin: 0;
            font-size: 22px;
            color: #2a3a5e;
        }
        .class-subject {
            margin: 5px 0 0;
            color: #6366f1;
            font-weight: normal;
        }
        .email-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: #3b82f6;
            color: white;
            border-radius: 50%;
            text-decoration: none;
            font-size: 16px;
            transition: transform 0.2s, background-color 0.2s;
        }
        .email-btn:hover {
            background-color: #2563eb;
            transform: scale(1.1);
        }
        .remove-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: #ef4444;
            color: white;
            border-radius: 50%;
            text-decoration: none;
            font-size: 16px;
            transition: transform 0.2s, background-color 0.2s;
        }
        .remove-btn:hover {
            background-color: #dc2626;
            transform: scale(1.1);
        }
        .btn-icon {
            font-size: 18px;
        }
        .students-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .students-table th {
            background-color: #6366f1;
            color: white;
            text-align: left;
            padding: 12px;
        }
        .students-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
        }
        .students-table td:nth-child(1),
        .students-table td:nth-child(2),
        .students-table td:nth-child(3) {
            text-align: left;
        }
        .students-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .students-table tr:hover {
            background-color: #f1f5fa;
        }
        
        @media (max-width: 768px) {
            .students-table, .students-table thead, .students-table tbody, .students-table th, .students-table td, .students-table tr {
                display: block;
            }
            .students-table thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            .students-table tr {
                border: 1px solid #ccc;
                margin-bottom: 10px;
            }
            .students-table td {
                border: none;
                position: relative;
                padding-left: 50%;
                text-align: right;
            }
            .students-table td:before {
                position: absolute;
                top: 12px;
                left: 12px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: bold;
                text-align: left;
            }
            .students-table td:nth-of-type(1):before { content: "Codice Fiscale"; }
            .students-table td:nth-of-type(2):before { content: "Nome"; }
            .students-table td:nth-of-type(3):before { content: "Cognome"; }
            .students-table td:nth-of-type(4):before { content: "Contatta"; }
            .students-table td:nth-of-type(5):before { content: "Azioni"; }
            
            .email-btn, .remove-btn {
                margin: 0 auto;
            }
        }
    </style>
</head>

<body>
    <a href='../homepage/dashboard_docente.php' class="back-link">&lt; Torna alla dashboard</a>
    
    <div class="class-header">
        <h2 class="class-name"><?php echo htmlspecialchars($materiaClasse); ?> - <?php echo htmlspecialchars($nomeClasse); ?></h2>
        <h3 class="class-subject">Lista degli studenti iscritti</h3>
    </div>
    
    <?php
    // Mostra eventuale messaggio di successo o errore
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        echo '<div style="background-color: #d1fae5; color: #065f46; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
            Studente rimosso con successo dalla classe.
        </div>';
    } elseif (isset($_GET['error']) && $_GET['error'] == 1) {
        echo '<div style="background-color: #fee2e2; color: #991b1b; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
            Si è verificato un errore durante la rimozione dello studente.
        </div>';
    }
    ?>
    
    <table class="students-table">
        <thead>
            <tr>
                <th>Codice Fiscale</th>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Contatta</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Modifica la query per unire (JOIN) le tabelle iscrizione e studente
            $stmt = $mysqli->prepare("
                SELECT iscrizione.CodiceFiscale, studente.Nome, studente.Cognome 
                FROM iscrizione 
                JOIN studente ON iscrizione.CodiceFiscale = studente.CodiceFiscale 
                WHERE iscrizione.IdClasse = ?
                ORDER BY studente.Cognome, studente.Nome
            ");
            
            $stmt->bind_param("s", $classe);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()){
                    // Creare l'indirizzo email dal nome e cognome
                    $email = strtolower($row["Nome"] . "." . $row["Cognome"] . "@itispaleocapa.it");
                    
                    echo "<tr>";
                    echo "<td>". htmlspecialchars($row["CodiceFiscale"]) . "</td>";
                    echo "<td>". htmlspecialchars($row["Nome"]) . "</td>";
                    echo "<td>". htmlspecialchars($row["Cognome"]) . "</td>";
                    echo "<td><a href='mailto:" . $email . "' class='email-btn' title='Invia email a " . $email . "'><i class='fas fa-envelope btn-icon'></i></a></td>";
                    echo "<td><a href='remove_student.php?classe=" . $classe . "&codiceFiscale=" . $row["CodiceFiscale"] . "' 
                         class='remove-btn' title='Rimuovi studente' onclick=\"return confirm('Sei sicuro di voler rimuovere questo studente dalla classe?');\"><i class='fas fa-trash-alt btn-icon'></i></a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' style='text-align: center; padding: 20px;'>Nessuno studente iscritto a questa classe</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>