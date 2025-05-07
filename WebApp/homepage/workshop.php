<?php
session_start();
require_once("../connessione.php");

// Verifica autenticazione
if (!isset($_SESSION["nome"]) || $_SESSION["role"] != "docente") {
    header("Location: ../login.php");
    exit();
}

$codiceFiscaleDocente = $_SESSION["codiceFiscale"];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop - Gestione Giochi</title>
    <link rel="stylesheet" type="text/css" href="./homepage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="workshop-container">
        <h2><i class="fas fa-gamepad"></i> Workshop - Gestione Giochi</h2>
        
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

        <div class="workshop-header">
            <a href="dashboard_docente.php" class="back-btn"><i class="fas fa-arrow-left"></i> Torna alla Dashboard</a>
        </div>

        <div class="workshop-content">
            <div class="class-selection">
                <h3><i class="fas fa-chalkboard"></i> Seleziona una Classe</h3>
                <select id="classSelect" onchange="loadGames()">
                    <option value="">Seleziona una classe...</option>
                    <?php
                    $stmt = $mysqli->prepare("SELECT IdClasse, Materia, Classe FROM ClasseVirtuale WHERE CodiceFiscaleDocente = ?");
                    $stmt->bind_param("s", $codiceFiscaleDocente);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['IdClasse'] . "'>" . htmlspecialchars($row['Materia']) . " - " . htmlspecialchars($row['Classe']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="games-section">
                <div class="available-games">
                    <h3><i class="fas fa-puzzle-piece"></i> Giochi Disponibili</h3>
                    <div class="games-grid" id="availableGames">
                        <!-- I giochi disponibili verranno caricati qui via JavaScript -->
                    </div>
                </div>

                <div class="class-games">
                    <h3><i class="fas fa-star"></i> Giochi della Classe</h3>
                    <div class="games-grid" id="classGames">
                        <!-- I giochi della classe verranno caricati qui via JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .workshop-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .workshop-header {
            margin-bottom: 30px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background-color: #6366f1;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.2s;
        }

        .back-btn:hover {
            background-color: #4f46e5;
        }

        .workshop-content {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .class-selection {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .class-selection select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 10px;
        }

        .games-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .available-games, .class-games {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .games-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .game-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .game-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .game-card i {
            font-size: 24px;
            color: #6366f1;
            margin-bottom: 10px;
        }

        .game-card h4 {
            margin: 10px 0;
            color: #1e293b;
        }

        .game-card p {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .game-card button {
            width: 100%;
            padding: 8px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .add-game-btn {
            background-color: #10b981;
            color: white;
        }

        .add-game-btn:hover {
            background-color: #059669;
        }

        .remove-game-btn {
            background-color: #ef4444;
            color: white;
        }

        .remove-game-btn:hover {
            background-color: #dc2626;
        }

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
    </style>

    <script>
        function loadGames() {
            const classId = document.getElementById('classSelect').value;
            if (!classId) {
                document.getElementById('availableGames').innerHTML = '';
                document.getElementById('classGames').innerHTML = '';
                return;
            }

            // Carica i giochi disponibili
            fetch(`../routes/get-available-games.php?classId=${classId}`)
                .then(response => response.json())
                .then(data => {
                    const availableGamesHtml = data.map(game => `
                        <div class="game-card">
                            <i class="fas ${game.icon}"></i>
                            <h4>${game.nome}</h4>
                            <p>${game.descrizione}</p>
                            <button class="add-game-btn" onclick="addGame(${classId}, ${game.id})">
                                <i class="fas fa-plus"></i> Aggiungi
                            </button>
                        </div>
                    `).join('');
                    document.getElementById('availableGames').innerHTML = availableGamesHtml;
                });

            // Carica i giochi della classe
            fetch(`../routes/get-class-games.php?classId=${classId}`)
                .then(response => response.json())
                .then(data => {
                    const classGamesHtml = data.map(game => `
                        <div class="game-card">
                            <i class="fas ${game.icon}"></i>
                            <h4>${game.nome}</h4>
                            <p>${game.descrizione}</p>
                            <button class="remove-game-btn" onclick="removeGame(${classId}, ${game.id})">
                                <i class="fas fa-trash"></i> Rimuovi
                            </button>
                        </div>
                    `).join('');
                    document.getElementById('classGames').innerHTML = classGamesHtml;
                });
        }

        function addGame(classId, gameId) {
            fetch('../routes/add-game-to-class.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `classId=${classId}&gameId=${gameId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadGames();
                } else {
                    alert(data.message);
                }
            });
        }

        function removeGame(classId, gameId) {
            fetch('../routes/remove-game-from-class.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `classId=${classId}&gameId=${gameId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadGames();
                } else {
                    alert(data.message);
                }
            });
        }
    </script>
</body>
</html> 