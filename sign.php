<?php

include_once('connexion.php');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
    $pseudo = isset($_POST['pseudo']) ? trim($_POST['pseudo']) : '';
    $mail = isset($_POST['mail']) ? trim($_POST['mail']) : '';
    $pass = isset($_POST['password']) ? trim($_POST['password']) : '';
    $conf_password = isset($_POST['conf_password']) ? trim($_POST['conf_password']) : '';

    if(!empty($pseudo) && !empty($mail) && !empty($pass)){
        if(filter_var($mail, FILTER_VALIDATE_EMAIL) === false){
            echo 'Adresse mail invalide';
            return;
        }
        if(strlen($pass) < 8){
            echo 'Le mot de passe doit contenir au moins 8 caractères';
            return;
        }
        if($pass != $conf_password){
            echo 'Mots de passe différents';
            return;
        }

        $check_user = $bdd->prepare("SELECT `name`, `email` FROM `user` WHERE `name` = :pseudo OR `email` = :mail");
        $check_user->bindParam('pseudo', $pseudo, PDO::PARAM_STR);
        $check_user->bindParam('mail', $mail, PDO::PARAM_STR);
        $check_user->execute();

        if($check_user->rowCount() > 0){
            echo 'Nom d\'utilisateur ou adresse mail déjà utilisé';
        } else {
            $hashed_pass = password_hash($pass, PASSWORD_BCRYPT);

            $new_user = $bdd->prepare("INSERT INTO `user`(`name`, `email`, `password`, `signing_date`) VALUES (:pseudo, :mail, :hashed_pass, NOW())");
            $new_user->bindParam('pseudo', $pseudo, PDO::PARAM_STR);
            $new_user->bindParam('mail', $mail, PDO::PARAM_STR);
            $new_user->bindParam('hashed_pass', $hashed_pass, PDO::PARAM_STR);
            $new_user->execute();

            if($new_user){
                header('Location: index.php');
                exit;
            } else {
                echo 'Tous les champs doivent être remplis';
            }
        }
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
    <title>TaskHive - Inscription</title>
</head>
<body class="grey">
    <div id="top-bar">
        <nav>
            <figure><img src="./assets/img/TaskHive.svg" alt="logo TaskHive" width="4%"></figure>
        </nav>
    </div>
    <section id="sign-page">
        <div class="container center-col">
            <h1 class="h1 popp-bold">TaskHive</h1>
            <p class="popp-reg">Plateforme de to-do-list collaborative</p>
            <p class="popp-reg">La to-do-list qui bourdonne d’idées !</p>
        </div>
        <div class="container center-col">
            <span class="h2 popp-bold">S'inscrire</span>
        </div>
        <div class="container center-col">
            <form action="./sign.php" method="POST" class="login-form">
                <div class="mb-3">
                    <input class="form-control login-input border-r form-text" type="text" name="pseudo" placeholder="Pseudo">
                </div>
                <div class="mb-3">
                    <input class="form-control login-input border-r form-text" type="text" name="mail" placeholder="Adresse mail">
                </div>
                <div class="mb-3">
                   <input class="form-control login-input border-r form-text" type="password" name="password" placeholder="Mot de passe"> 
                </div>
                <div class="mb-3">
                    <input class="form-control login-input border-r form-text" type="password" name="conf_password" placeholder="Confirmer le mot de passe">
                </div>
                <div class="login-btn">
                    <input class="btn btn-primary form-control yellow border-r popp-medium" type="submit" name="submit" value="S'inscrire">
                </div>
            </form>          
        </div>
    </section>
</body>
</html>