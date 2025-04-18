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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onboarding - Educational Games</title>
    <link rel="stylesheet" href="style.css">
</head>

<div class="parent">
    <div class="div1">
        <h1>Benvenuto <?php echo $role ?>!</h1>
    </div>
    <div class="div4">
        <a href="./login.php"><button type="button">Login</button></a>
    </div>
    <div class="div5">
        <a href="./register.php">Non hai un account? Registrati!</a>
    </div>
</div>

</html>