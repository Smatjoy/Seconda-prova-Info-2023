<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION["role"] = $_POST["role"];
    header("Location: onboarding.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Educational Games - Benvenuto</title>
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
        .welcome-container {
            max-width: 800px;
            width: 90%;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 6px 32px rgba(0,0,0,0.10);
            padding: 40px;
            text-align: center;
        }
        .logo {
            width: 120px;
            height: 120px;
            margin: 0 auto 24px;
            background: #6366f1;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo i {
            font-size: 60px;
            color: white;
        }
        h1 {
            color: #2a3a5e;
            margin-bottom: 12px;
            font-size: 2.4rem;
        }
        p.tagline {
            color: #6b7280;
            font-size: 1.2rem;
            margin-bottom: 40px;
        }
        .role-selection {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 40px;
            flex-wrap: wrap;
        }
        .role-card {
            background: #f9fafb;
            border-radius: 12px;
            padding: 25px;
            width: 200px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid transparent;
        }
        .role-card:hover {
            transform: translateY(-5px);
            border-color: #6366f1;
            box-shadow: 0 8px 15px rgba(99,102,241,0.1);
        }
        .role-icon {
            background: #e0e7ff;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .role-icon i {
            font-size: 30px;
            color: #6366f1;
        }
        .role-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2a3a5e;
            margin-bottom: 10px;
        }
        .role-desc {
            font-size: 0.9rem;
            color: #6b7280;
        }
        .role-btn {
            background: #6366f1;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            margin-top: 20px;
            cursor: pointer;
            transition: background 0.2s ease;
            width: 100%;
        }
        .role-btn:hover {
            background: #4f46e5;
        }
        
        @media (max-width: 600px) {
            .role-selection {
                flex-direction: column;
                align-items: center;
            }
            .role-card {
                width: 100%;
                max-width: 280px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="welcome-container">
        <div class="logo">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <h1>Educational Games</h1>
        <p class="tagline">Impara divertendoti con i nostri giochi educativi</p>
        
        <form method="post" action="index.php">
            <div class="role-selection">
                <div class="role-card" onclick="document.getElementById('docente-btn').click()">
                    <div class="role-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 class="role-title">Docente</h3>
                    <p class="role-desc">Crea classi virtuali, assegna giochi e monitora i progressi</p>
                    <button type="submit" name="role" value="docente" id="docente-btn" class="role-btn">Accedi come Docente</button>
                </div>
                
                <div class="role-card" onclick="document.getElementById('studente-btn').click()">
                    <div class="role-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3 class="role-title">Studente</h3>
                    <p class="role-desc">Unisciti alle classi, gioca e impara con i nostri giochi educativi</p>
                    <button type="submit" name="role" value="studente" id="studente-btn" class="role-btn">Accedi come Studente</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>