<?php
session_start();
include_once('connexion.php');

if($_SERVER['REQUEST_METHOD'] === "POST"){

    $pseudo = isset($_POST['pseudo']) ? trim($_POST['pseudo']) : '';
    $pass = isset($_POST['password']) ? trim($_POST['password']) : '';

    if(!empty($pseudo && !empty($pass))){

        $req = $bdd->prepare("SELECT `id_user`, `name`, `email`, `password` FROM `user` WHERE `name` = :pseudo OR `email` = :pseudo");
        $req->bindParam('pseudo', $pseudo, PDO::PARAM_STR);
        $req->execute();

        $user = $req->fetch(PDO::FETCH_ASSOC);

        if($user){
            if(password_verify($pass, $user['password'])){
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['username'] = $user['name'];

                header('Location: profil.php');
                exit();
            } else {
                echo 'Mot de passe incorrect ';
            }
        } else {
            echo 'Utilisateur inconnu';
        }
    } else {
        echo 'Tous les champs doivent être remplis';
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>TaskHive - Se connecter</title>
</head>
<body class="grey">
<div id="navbar-container" class="container">
        <nav>
            <div id="navbar">
                <figure>
                    <a href="profil.php"><img src="./assets/img/TaskHive.svg" alt="logo TaskHive"></a>
                </figure>
            </div>
        </nav>
    </div>
    <section id="login-page">
        <div class="container center-col">
            <h1 class="h1 popp-bold">TaskHive</h1>
            <p class="popp-reg">Plateforme de to-do-list collaborative</p>
            <p class="popp-reg">La to-do-list qui bourdonne d’idées !</p>
        </div>
        <div class="container center-col">
            <span class="h2 popp-bold">Se connecter</span>
        </div>
        <div class="container center-col">
            <form action="./index.php" method="POST" class="login-form">
                <div class="mb-3">
                    <input class="form-control login-input border-r form-text" type="text" name="pseudo" placeholder="Pseudo ou adresse mail">
                </div>
                <div >
                    <input class="form-control login-input border-r form-text" type="password" name="password" placeholder="Mot de passe">
                </div>
                <div class="login-btn">
                    <input class="btn btn-primary form-control yellow border-r popp-medium" type="submit" name="submit" value="Se connecter">
                </div>
            </form>
        </div>
        <div id="sign-container" class="container center-flex">
            <div><a class="black popp-reg" href="sign.php">S'inscrire</a></div>
        </div>
    </section>
</body>
</html>