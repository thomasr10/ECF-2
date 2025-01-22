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