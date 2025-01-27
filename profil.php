<?php
session_start();
include_once('connexion.php');

if(!isset($_SESSION['id_user'])){
    header('Location: index.php');
}
//Créer une liste        

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addList'])){

    $listName = isset($_POST['list-name']) ? trim($_POST['list-name']) : '';
    $listDesc = isset($_POST['list-description']) ? trim($_POST['list-description']) : '';
    $limDate = isset($_POST['date']) ? $_POST['date'] : '';

    if(!empty($listName) && !empty($listDesc) && !empty($limDate)){

        $addList = $bdd->prepare("INSERT INTO `list`(`name`, `description`, `creation_date`, `limit_date`, `creator_id`, `creator_name`) VALUES (:listName, :listDesc, NOW(), :limDate, :creator_id, :creator_name)");
        $addList->bindParam('listName', $listName, PDO::PARAM_STR);
        $addList->bindParam('listDesc', $listDesc, PDO::PARAM_STR);
        $addList->bindParam('limDate', $limDate, PDO::PARAM_STR);
        $addList->bindParam('creator_id', $_SESSION['id_user'], PDO::PARAM_INT);
        $addList->bindParam('creator_name', $_SESSION['username'], PDO::PARAM_STR);
        $addList->execute();

        if($addList->rowCount() > 0){
        
            $listId = $bdd->lastInsertID();

            $userList = $bdd->prepare("INSERT INTO `user_list`(`id_user`, `id_list`) VALUES (:id_user, :id_list)");
            $userList->bindParam('id_user', $_SESSION['id_user'], PDO::PARAM_INT);
            $userList->bindParam('id_list', $listId, PDO::PARAM_INT);
            $userList->execute();

            header('Location: profil.php');
            exit();
        } else {
            echo 'Erreur lors de l\'ajout de la tâche';
        }

    }
}


//afficher les listes

$reqList = $bdd->prepare("SELECT `list`.`id_list` AS `id_list`, `list`.`name` AS 'name', `description` AS 'desc', `list`.`creation_date` AS 'crea_date', `list`.`limit_date` AS 'lim_date', `list`.`creator_id` AS 'creator_id', `list`.`creator_name` AS 'creator_name' FROM `list` INNER JOIN `user_list` ON `list`.`id_list` = `user_list`.`id_list` WHERE `user_list`.`id_user` = :id_user ORDER BY `list`.`limit_date` ASC");
$reqList->bindParam('id_user', $_SESSION['id_user'], PDO::PARAM_STR);
$reqList->execute();
$list = $reqList->fetchAll(PDO::FETCH_ASSOC);

// supprimer une liste

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $id_list = isset($_POST['delete-list']) ? $_POST['delete-list'] : '';

    if($id_list){
        $deleteList = $bdd->prepare("DELETE FROM `list` WHERE `id_list` = :id_list");
        $deleteList->bindParam('id_list', $id_list, PDO::PARAM_INT);
        $deleteList->execute();

        header('Location: profil.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskHive - Ma Ruche</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body class="grey">
    <header>
        <div id="navbar-container" class="container">
            <nav>
                <div id="navbar">
                    <figure>
                        <a href="profil.php"><img src="./assets/img/TaskHive.svg" alt="logo TaskHive"></a>
                    </figure>
                    <div id="navbar-links">
                        <div><span class="black popp-medium"><?=ucfirst($_SESSION['username']) ?></span></div> 
                        <div><a class="black popp-medium" href="log-out.php">Logout</a></div>
                    </div>
                </div>
            </nav>
        </div>
        <section class="container">
            <div class="profil-title">
                <h1 class="h1 popp-semiBold">Ma ruche</h1>
                <button id="new-list">Nouvelle liste +</button>
            </div>
            <div id="create-list" class="new-list center-col none">
                <i id="close-list" class="fa-solid fa-x"></i>
                <span class="h3 popp-medium">Créer une liste</span>
                <div class="container center-col">
                    <form action="profil.php" method="POST" class="login-form">
                        <div>
                            <input class="form-control mb-3 login-input border-r form-text" type="text" name="list-name" minlength="3" maxlength="16" required placeholder="Nom de la liste">
                        </div>
                        <div>
                            <textarea class="form-control mb-3 form-text" name="list-description" id="list-description" minlength="5" maxlength="40" required placeholder="Description"></textarea>
                        </div>
                        <div>
                            <label class="popp-reg black" for="">Date de fin</label>
                            <input class="form-control mb-3 login-input border-r form-text" type="date" name="date" required>
                        </div>
                        <div class="login-btn">
                        <input class="btn btn-primary form-control yellow border-r popp-medium" type="submit" name="addList" id="submit"> 
                        </div>
                    </form>
                </div>
            </div>
            <div class="grid">
            <?php
            
                if(count($list) > 0){
                    foreach($list as $l){
                ?>
                <div class="display-list">
                    <a href="./task.php?id_list=<?= $l['id_list']?>">
                        <div class="list">
                            <h2 class="h2 black popp-regular"><?= $l['name'] . ' ' . '-' . ' ' . 'Créée par' . ' ' . $l['creator_name'] ?></h2>
                            <p class="black popp-regular"><?= $l['desc'] ?></p>
                            <div class="black popp-regular"><?= $l['crea_date'] ?></div>
                            <div class="black popp-regular mb-3"><?= $l['lim_date'] ?></div>
                            <form action="profil.php"  method="POST">
                                <input type="hidden" value="<?= $l['id_list'] ?>" name="check-list">  
                            </form>
                            <?php

                                if($_SESSION['id_user'] == $l['creator_id']){
                            ?>
                                <form action="profil.php"  method="POST">
                                    <input type="hidden" value="<?= $l['id_list'] ?>" name="delete-list">
                                    <button class="no-bg-btn"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            <?php
                                } 
                            ?>
                        </div>
    
                    </a>
                </div>
                <?php
                    }
                } else {
                ?>
                    <div class="empty-container">
                        <span class="yellow popp-bold">Aucune liste</span>
                    </div>
                <?php
                }
                ?>
            </div>
    </section>
    </header>
    <script src="./assets/js/new-list.js"></script>
</body>
</html>