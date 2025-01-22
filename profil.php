<?php
session_start();
include_once('connexion.php');

//Créer une liste        

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addList'])){

    $listName = isset($_POST['list-name']) ? trim($_POST['list-name']) : '';
    $listDesc = isset($_POST['list-description']) ? trim($_POST['list-description']) : '';
    $limDate = isset($_POST['date']) ? $_POST['date'] : '';

    if(!empty($listName) && !empty($listDesc) && !empty($limDate)){

        $addList = $bdd->prepare("INSERT INTO `list`(`name`, `description`, `creation_date`, `limit_date`) VALUES (:listName, :listDesc, NOW(), :limDate)");
        $addList->bindParam('listName', $listName, PDO::PARAM_STR);
        $addList->bindParam('listDesc', $listDesc, PDO::PARAM_STR);
        $addList->bindParam('limDate', $limDate, PDO::PARAM_STR);
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

$reqList = $bdd->prepare("SELECT `list`.`id_list` AS `id_list`, `list`.`name` AS 'name', `description` AS 'desc', `list`.`creation_date` AS 'crea_date', `list`.`limit_date` AS 'lim_date' FROM `list` INNER JOIN `user_list` ON `list`.`id_list` = `user_list`.`id_list` WHERE `user_list`.`id_user` = :id_user");
$reqList->bindParam('id_user', $_SESSION['id_user'], PDO::PARAM_STR);
$reqList->execute();
$list = $reqList->fetchAll(PDO::FETCH_ASSOC);
// var_dump($list);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <header>
        <!-- <div id="top-bar">
            <figure>
                <img src="./assets/img/TaskHive.svg" alt="">
                <span>TaskHive</span>
            </figure>
            <div><?=ucfirst($_SESSION['username']) ?></div>
        </div> -->
        <div><a href="log-out.php">Se déconnecter</a></div>
    </header>
    <section>
        <div>
            <h1>Ma ruche</h1>
            <div>
                <button>Nouvelle liste +</button>
            </div>
        </div>
        <div>
            <span>Créer une liste</span>
            <div>
                <form action="profil.php" method="POST">
                    <input type="text" name="list-name" minlength="3" maxlength="16" required>
                    <textarea name="list-description" id="list-description" minlength="5" maxlength="40" required></textarea>
                    <input type="date" name="date" required>
                    <input type="submit" name="addList" id="submit">
                </form>
            </div>
        </div>
        <div>
        <?php
            if(count($list) > 0){
                foreach($list as $l){
            ?>
            <div>
                <h2><?= $l['name'] ?></h2>
                <p><?= $l['desc'] ?></p>
                <div><?= $l['crea_date'] ?></div>
                <div><?= $l['lim_date'] ?></div>
                <div><a href="./task.php?id_list=<?= $l['id_list'] ?>">Modifier</a></div>
                <i class="fa-solid fa-trash"></i>
            </div>
            <?php
                }
            } else {
                echo 'Aucune tâche pour le moment';
            }
            ?>
        </div>
    </section>
    <script src="./assets/js/add-task.js"></script>
</body>
</html>