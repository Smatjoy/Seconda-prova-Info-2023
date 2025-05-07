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
    
    <?php
    if (isset($_SESSION["success"])) {
        echo '<div class="alert success"><i class="fas fa-check-circle"></i> ' . $_SESSION["success"] . '</div>';
        unset($_SESSION["success"]);
    }
    if (isset($_SESSION["error"])) {
        echo '<div class="alert error"><i class="fas fa-exclamation-circle"></i> ' . $_SESSION["error"] . '</div>';
        unset($_SESSION["error"]);
    }
    ?>
    
    <div class="user-info">
        <div class="user-info-item"><i class="fas fa-user-tag"></i> <b>Ruolo:</b> Docente</div>
        <div class="user-info-item"><i class="fas fa-user"></i> <b>Nome:</b> <?php echo $nome; ?></div>
        <div class="user-info-item"><i class="fas fa-signature"></i> <b>Cognome:</b> <?php echo $cognome; ?></div>
        <div class="user-info-item"><i class="fas fa-id-card"></i> <b>Codice fiscale:</b> <?php echo $codiceFiscale; ?></div>
    </div>
    
    <div style="text-align: center; margin: 20px 0;">
        <button type="button" onclick="window.location.href='../logout.php'"><i class="fas fa-sign-out-alt"></i> Logout</button>
        <button type="button" onclick="window.location.href='workshop.php'" style="margin-left: 10px; background-color: #10b981;"><i class="fas fa-gamepad"></i> Workshop Giochi</button>
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
            <th><i class="fas fa-edit"></i> Modifica</th>
        </tr>

        <?php
        if (!$noClasses) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td><i class='fas fa-book-open'></i> " . htmlspecialchars($row['Materia']) . " - " . htmlspecialchars($row['Classe']) . "</td>";
                echo "<td><span class='access-code'>" . $row['CodiceAccesso'] . "</span></td>";
                echo "<td><a href='../routes/leadboard.php?classe=" . $row['IdClasse'] . "' class='action-btn leaderboard-btn' title='Visualizza classifica'><i class='fas fa-trophy'></i></a></td>";
                echo "<td><a href='../routes/view_class.php?classe=" . $row['IdClasse'] . "' class='action-btn students-btn' title='Visualizza studenti'><i class='fas fa-users'></i></a></td>";
                echo "<td><button onclick='openEditModal(" . $row['IdClasse'] . ", \"" . htmlspecialchars($row['Materia']) . "\", \"" . htmlspecialchars($row['Classe']) . "\")' class='action-btn edit-btn' title='Modifica classe'><i class='fas fa-edit'></i></button></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'><i class='fas fa-info-circle'></i> Non hai ancora creato classi virtuali</td></tr>";
        }
        ?>
    </table>
</div>

<!-- Modal per la modifica della classe -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3><i class="fas fa-edit"></i> Modifica Classe Virtuale</h3>
        <form action='../routes/edit-classe-virtuale.php' method='POST'>
            <input type="hidden" id="editIdClasse" name="idClasse">
            <label for='editClasse'><i class="fas fa-users"></i> Classe:</label>
            <input type='text' id='editClasse' name='classe' required placeholder="Es. 3A, 4B">
            <label for='editMateria'><i class="fas fa-book"></i> Materia:</label>
            <input type='text' id='editMateria' name='materia' required placeholder="Es. Matematica, Storia">
            <button type='submit'><i class="fas fa-save"></i> Salva modifiche</button>
        </form>
    </div>
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
    
    /* Stili per il modal e il pulsante di modifica */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }
    
    .modal-content {
        background-color: #fff;
        margin: 15% auto;
        padding: 20px;
        border-radius: 8px;
        width: 80%;
        max-width: 500px;
        position: relative;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .close {
        position: absolute;
        right: 20px;
        top: 10px;
        font-size: 28px;
        font-weight: bold;
        color: #666;
        cursor: pointer;
    }
    
    .close:hover {
        color: #000;
    }
    
    .edit-btn {
        background-color: #10b981;
    }
    
    .edit-btn:hover {
        background-color: #059669;
        transform: scale(1.1);
    }
    
    /* Stili per il form nel modal */
    .modal-content form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .modal-content input {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .modal-content button {
        background-color: #10b981;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.2s;
    }
    
    .modal-content button:hover {
        background-color: #059669;
    }
    
    /* Stili per i messaggi di alert */
    .alert {
        padding: 12px 20px;
        margin: 20px 0;
        border-radius: 4px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .alert.success {
        background-color: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
    }
    
    .alert.error {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }
    
    .alert i {
        font-size: 18px;
    }
</style>

<script>
    // Funzione per aprire il modal di modifica
    function openEditModal(idClasse, materia, classe) {
        document.getElementById('editModal').style.display = 'block';
        document.getElementById('editIdClasse').value = idClasse;
        document.getElementById('editMateria').value = materia;
        document.getElementById('editClasse').value = classe;
    }
    
    // Chiudi il modal quando si clicca sulla X
    document.querySelector('.close').onclick = function() {
        document.getElementById('editModal').style.display = 'none';
    }
    
    // Chiudi il modal quando si clicca fuori da esso
    window.onclick = function(event) {
        if (event.target == document.getElementById('editModal')) {
            document.getElementById('editModal').style.display = 'none';
        }
    }
</script>