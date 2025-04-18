<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION["role"] = $_POST["role"];
    header("Location: onboarding.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selezione ruolo - Educational Games</title>
    <link rel="stylesheet" href="style.css">
</head>

<form method="post" action="index.php">
    <div class="parent">
        <div class="div1">
            <h1>Benvenuto su Educational Games!<br>Seleziona il tuo ruolo</brz>
            </h1>
        </div>
        <div class="div2">
            <button type="submit" name="role" value="docente">Docente</button>
        </div>
        <div class="div3">
            <button type="submit" name="role" value="studente">Studente</button>
        </div>
    </div>
</form>

</html>