<?php
echo '<link rel="stylesheet" type="text/css" href="./homepage.css">';
echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">';

require_once("../connessione.php");
$noClasses = true;

session_start();

$authenticated = isset($_SESSION["nome"]) && isset($_SESSION["role"]); // TRUE se autenticato

if (!$authenticated || $_SESSION["role"] != "docente") {
    echo "Non sei autenticato o non hai i permessi necessari!";
    die();
}

$nome = $_SESSION["nome"];
$cognome = $_SESSION["cognome"];
$codiceFiscale = $_SESSION["codiceFiscale"];
$role = $_SESSION["role"]; // role = "docente" va bene

// Inizio HTML strutturato
?>

<div class="dashboard-container">
    <h2><i class="fas fa-chalkboard-teacher"></i> Dashboard Docente</h2>
    
    <div class="user-info">
        <div class="user-info-item"><i class="fas fa-user-tag"></i> <b>Ruolo:</b> Docente</div>
        <div class="user-info-item"><i class="fas fa-user"></i> <b>Nome:</b> <?php echo $nome; ?></div>
        <div class="user-info-item"><i class="fas fa-signature"></i> <b>Cognome:</b> <?php echo $cognome; ?></div>
        <div class="user-info-item"><i class="fas fa-id-card"></i> <b>Codice fiscale:</b> <?php echo $codiceFiscale; ?></div>
    </div>
    
    <div style="text-align: center; margin: 20px 0;">
        <button type="button" onclick="window.location.href='../logout.php'"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>

    <hr>

    <h3><i class="fas fa-plus-circle"></i> Crea una nuova classe virtuale</h3>
    <form action='../routes/new-classe-virtuale.php' method='POST'>
        <label for='classe'><i class="fas fa-users"></i> Classe:</label>
        <input type='text' id='classe' name='classe' required placeholder="Es. 3A, 4B">
        <label for='materia'><i class="fas fa-book"></i> Materia:</label>
        <input type='text' id='materia' name='materia' required placeholder="Es. Matematica, Storia">
        <button type='submit'><i class="fas fa-plus"></i> Crea!</button>
    </form>

    <hr>
    
    <h3><i class="fas fa-list"></i> Le tue classi</h3>
    
    <?php
    // mostrare le classi del docente
    $stmt = $mysqli->prepare("SELECT * FROM ClasseVirtuale WHERE CodiceFiscaleDocente = ?");
    $stmt->bind_param("s", $codiceFiscale);
    $stmt->execute();
    $result = $stmt->get_result();

    //Controllo se ci sono risultati
    if ($result->num_rows > 0) {
        // Ci sono classi per questo docente
        $noClasses = false;
    } else {
        //Nessun risultato trovato
        $noClasses = true;
    }
    ?>

    <table class="dashboard-table">
        <tr>
            <th><i class="fas fa-graduation-cap"></i> Materia - Classe</th>
            <th><i class="fas fa-key"></i> Codice di accesso</th>
            <th><i class="fas fa-trophy"></i> Classifica</th>
            <th><i class="fas fa-users"></i> Studenti</th>
        </tr>

        <?php
        if (!$noClasses) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td><i class='fas fa-book-open'></i> " . htmlspecialchars($row['Materia']) . " - " . htmlspecialchars($row['Classe']) . "</td>";
                echo "<td><span class='access-code'>" . $row['CodiceAccesso'] . "</span></td>";
                echo "<td><a href='../routes/leadboard.php?classe=" . $row['IdClasse'] . "' class='action-btn leaderboard-btn' title='Visualizza classifica'><i class='fas fa-trophy'></i></a></td>";
                echo "<td><a href='../routes/view_class.php?classe=" . $row['IdClasse'] . "' class='action-btn students-btn' title='Visualizza studenti'><i class='fas fa-users'></i></a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'><i class='fas fa-info-circle'></i> Non hai ancora creato classi virtuali</td></tr>";
        }
        ?>
    </table>
</div>

<style>
    .access-code {
        font-family: monospace;
        background-color: #f1f5fa;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: bold;
        color: #3b82f6;
        letter-spacing: 1px;
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
    
    .leaderboard-btn {
        background-color: #f59e0b;
    }
    
    .leaderboard-btn:hover {
        background-color: #d97706;
        transform: scale(1.1);
    }
    
    .students-btn {
        background-color: #3b82f6;
    }
    
    .students-btn:hover {
        background-color: #2563eb;
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