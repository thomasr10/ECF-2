<?php

include_once('connexion.php');


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskHive - Se connecter</title>
</head>
<body>
    <form action="./index.php" method="POST">
        <input type="text" name="pseudo" placeholder="Pseudo ou adresse mail">
        <input type="password" name="password" placeholder="Mot de passe">
        <input type="submit" name="submit" placeholder="Se connecter">
    </form>
    <div>
        <div><a href="">Mot de passe oublié</a></div>
        <div><a href="sign.php">S'inscrire</a></div>
    </div>
</body>
</html>