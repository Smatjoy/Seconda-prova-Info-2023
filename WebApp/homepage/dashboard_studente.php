<?php
echo '<link rel="stylesheet" type="text/css" href="./homepage.css">';
echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">';

require_once("../connessione.php");
$noClasses = true;

session_start();

$authenticated = isset($_SESSION["nome"]) && isset($_SESSION["role"]); // TRUE se autenticato

if (!$authenticated) {
    echo "Non sei autenticato!";
    die();
} elseif ($_SESSION["role"] != "studente") {
    // Se è autenticato ma non è uno studente, reindirizzalo alla dashboard appropriata
    header("Location: dashboard_docente.php");
    exit();
}

$nome = $_SESSION["nome"];
$cognome = $_SESSION["cognome"];
$codiceFiscale = $_SESSION["codiceFiscale"];
$role = $_SESSION["role"]; // role = "studente" va bene

// Inizio HTML strutturato
?>

<div class="dashboard-container">
    <h2><i class="fas fa-user-graduate"></i> Dashboard Studente</h2>
    
    <div class="user-info">
        <div class="user-info-item"><i class="fas fa-user-tag"></i> <b>Ruolo:</b> Studente</div>
        <div class="user-info-item"><i class="fas fa-user"></i> <b>Nome:</b> <?php echo $nome; ?></div>
        <div class="user-info-item"><i class="fas fa-signature"></i> <b>Cognome:</b> <?php echo $cognome; ?></div>
        <div class="user-info-item"><i class="fas fa-id-card"></i> <b>Codice fiscale:</b> <?php echo $codiceFiscale; ?></div>
    </div>
    
    <div style="text-align: center; margin: 20px 0;">
        <button type="button" onclick="window.location.href='../logout.php'"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>

    <hr>

    <h3><i class="fas fa-door-open"></i> Entra in una nuova classe</h3>
    <form action='../routes/join_class.php' method="GET">
        <label for='codice'><i class="fas fa-key"></i> Codice corso:</label>
        <input type='text' id='codice' name='codice' required placeholder="Inserisci il codice di 6 caratteri">
        <button type='submit'><i class="fas fa-sign-in-alt"></i> Entra!</button>
    </form>

    <hr>
    
    <h3><i class="fas fa-chalkboard"></i> Le tue classi</h3>
    
    <?php
    // mostrare le classi in cui è lo studente
    $stmt = $mysqli->prepare("
    SELECT
        classevirtuale.Classe,
        classevirtuale.Materia,
        docente.Nome,
        docente.Cognome,
        classevirtuale.IdClasse,
        IFNULL(SUM(partita.Monete), 0) AS MoneteTotali
    FROM studente
    JOIN iscrizione ON studente.CodiceFiscale = iscrizione.CodiceFiscale
    JOIN classevirtuale ON iscrizione.IdClasse = classevirtuale.IdClasse
    JOIN docente ON classevirtuale.CodiceFiscaleDocente = docente.CodiceFiscale
    LEFT JOIN classe_videogioco ON classevirtuale.IdClasse = classe_videogioco.IdClasse
    LEFT JOIN partita ON (classe_videogioco.IdVideogioco = partita.IdVideogioco AND studente.CodiceFiscale = partita.CodiceFiscale)
    WHERE studente.CodiceFiscale = ?
    GROUP BY classevirtuale.IdClasse, classevirtuale.Classe, classevirtuale.Materia, docente.Nome, docente.Cognome
    ");

    $stmt->bind_param("s", $codiceFiscale);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<table class='dashboard-table'>";
    echo    "<tr>";
    echo        "<th><i class='fas fa-book'></i> Materia - Classe</th>";
    echo        "<th><i class='fas fa-chalkboard-teacher'></i> Docente</th>";
    echo        "<th><i class='fas fa-coins'></i> Monete</th>";
    echo        "<th><i class='fas fa-gamepad'></i> Giochi</th>";
    echo    "</tr>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo    "<td><i class='fas fa-book-open'></i> ". htmlspecialchars($row['Materia'])." - ". htmlspecialchars($row['Classe']). "</td>";
            echo    "<td><i class='fas fa-user-tie'></i> ". htmlspecialchars($row['Nome']) . " " . htmlspecialchars($row['Cognome']). "</td>";
            echo    "<td><span class='coins-badge'><i class='fas fa-coins'></i> ". htmlspecialchars($row['MoneteTotali']) . "</span></td>";
            echo    "<td><a href='../routes/c.php?classe=" . $row['IdClasse'] . "' class='action-btn play-btn' title='Accedi ai giochi'><i class='fas fa-gamepad'></i></a></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'><i class='fas fa-info-circle'></i> Non sei iscritto a nessuna classe</td></tr>";
    }
    echo "</table>";
    ?>
</div>

<style>
    .coins-badge {
        background-color: #fef3c7;
        color: #92400e;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: bold;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        text-decoration: none;
        color: white;
        transition: transform 0.2s, background-color 0.2s;
    }
    
    .play-btn {
        background-color: #10b981;
    }
    
    .play-btn:hover {
        background-color: #059669;
        transform: scale(1.1);
    }
    
    .dashboard-table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }
    
    .dashboard-table th {
        background-color: #6366f1;
        color: white;
        padding: 12px 15px;
    }
    
    .dashboard-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .dashboard-table tr:nth-child(even) {
        background-color: #f9fafb;
    }
    
    .dashboard-table tr:hover {
        background-color: #f1f5fa;
    }
    
    /* Miglioramenti generali */
    i {
        margin-right: 6px;
    }
    
    h3 i {
        color: #6366f1;
    }
    
    .user-info-item i {
        color: #3b82f6;
        width: 18px;
        text-align: center;
    }
    
    button i {
        margin-right: 8px;
    }
</style>