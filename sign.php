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
    <title>Document</title>
</head>
<body>
    <form action="./sign.php" method="POST">
        <input type="text" name="pseudo" placeholder="Pseudo">
        <input type="text" name="mail" placeholder="Adresse mail">
        <input type="password" name="password" placeholder="Mot de passe">
        <input type="password" name="conf_password" placeholder="Confirmer le mot de passe">
        <input type="submit" name="submit" placeholder="S'inscrire">
    </form>
</body>
</html>