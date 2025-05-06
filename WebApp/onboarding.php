<?php
session_start();

if (!isset($_SESSION["role"])) {
    header("Location: index.php");
    exit();
} else {
    $role = $_SESSION["role"];
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benvenuto <?php echo ucfirst($role); ?> - Educational Games</title>
    <link rel="stylesheet" href="homepage/homepage.css">
    <style>
        body {
            background: linear-gradient(120deg, #f8fafc 0%, #e0e7ff 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .onboarding-container {
            max-width: 600px;
            width: 90%;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 6px 32px rgba(0,0,0,0.10);
            padding: 40px;
            text-align: center;
        }
        .welcome-icon {
            font-size: 60px;
            color: #6366f1;
            margin-bottom: 20px;
        }
        h1 {
            color: #2a3a5e;
            margin-bottom: 12px;
            font-size: 2.2rem;
        }
        .role-badge {
            display: inline-block;
            background: #6366f1;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 30px;
        }
        p.description {
            color: #6b7280;
            font-size: 1.1rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
        }
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-primary {
            background: #6366f1;
            color: white;
            border: none;
        }
        .btn-primary:hover {
            background: #4f46e5;
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: white;
            color: #6366f1;
            border: 2px solid #6366f1;
        }
        .btn-secondary:hover {
            background: #e0e7ff;
            transform: translateY(-2px);
        }
        
        @media (max-width: 600px) {
            .action-buttons {
                flex-direction: column;
            }
            .btn {
                width: 100%;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="onboarding-container">
        <?php if ($role == "docente"): ?>
            <i class="fas fa-chalkboard-teacher welcome-icon"></i>
            <h1>Benvenuto Docente!</h1>
            <div class="role-badge">Docente</div>
            <p class="description">
                Grazie per esserti unito a Educational Games. Come docente, puoi creare classi virtuali, 
                assegnare giochi educativi e monitorare i progressi dei tuoi studenti.
                Inizia accedendo o creando un nuovo account.
            </p>
        <?php else: ?>
            <i class="fas fa-user-graduate welcome-icon"></i>
            <h1>Benvenuto Studente!</h1>
            <div class="role-badge">Studente</div>
            <p class="description">
                Grazie per esserti unito a Educational Games. Come studente, puoi partecipare 
                alle classi virtuali, giocare ai giochi educativi e competere con i tuoi compagni.
                Inizia accedendo o creando un nuovo account.
            </p>
        <?php endif; ?>
        
        <div class="action-buttons">
            <a href="login.php" class="btn btn-primary">Accedi</a>
            <a href="register.php" class="btn btn-secondary">Registrati</a>
        </div>
    </div>
</body>
</html>