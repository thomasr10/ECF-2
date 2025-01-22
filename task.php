<?php
session_start();

include_once('connexion.php');

if(isset($_GET['id_list'])){
    $idList = $_GET['id_list'];
}   else {
    echo 'Probleme';
}
// ajouter une nouvelle tâche

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addTask'])){
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $limitDate = isset($_POST['date']) ? $_POST['date'] : '';

    if(!empty($title) && !empty($description) && !empty($limitDate)){
        $newTask = $bdd->prepare("INSERT INTO `task`(`name`, `description`, `creation_date`, `limit_date`, `id_list`) VALUES (:title, :description, NOW(), :limitDate, :id_list)");
        $newTask->bindParam('title', $title, PDO::PARAM_STR);
        $newTask->bindParam('description', $description, PDO::PARAM_STR);
        $newTask->bindParam('limitDate', $limitDate, PDO::PARAM_STR);
        $newTask->bindParam('id_list', $idList, PDO::PARAM_INT);
        $newTask->execute();

        header('Location: task.php?id_list=' . $idList);
        exit();

    } else {
        echo 'Veuillez remplir tous les champs';
    }
}

// Récupérer le nom de la liste

$reqListName = $bdd->prepare("SELECT `name` FROM `list` WHERE `id_list` = :id_list");
$reqListName->bindParam('id_list', $idList, PDO::PARAM_INT);
$reqListName->execute();
$listName = $reqListName->fetch(PDO::FETCH_ASSOC);




//afficher les requêtes

$reqTask = $bdd->prepare("SELECT DISTINCT `task`.`id_task`, `task`.`name`, `task`.`description` AS 'desc', `task`.`creation_date` AS 'crea_date', `task`.`limit_date` AS 'lim_date', `task`.`statut` AS 'statut', `task`.`id_list`  FROM `task` WHERE `id_list` = :id_list");
$reqTask->bindParam('id_list', $idList, PDO::PARAM_INT);
$reqTask->execute();
$task = $reqTask->fetchAll(PDO::FETCH_ASSOC);



// ajouter un utilisateur

if($_SERVER['REQUEST_METHOD'] && isset($_POST['addUser'])){
    $newPseudo = isset($_POST['addPseudo']) ? trim($_POST['addPseudo']) : '';

    $reqSearchUser = $bdd->prepare("SELECT `id_user`, `name`, `email` FROM `user` WHERE `name` = :addPseudo OR `email` = :addPseudo");
    $reqSearchUser->bindParam('addPseudo', $newPseudo, PDO::PARAM_STR);
    $reqSearchUser->execute();

    if($reqSearchUser->rowCount() > 0){
        $searchUser = $reqSearchUser->fetch(PDO::FETCH_ASSOC);
        $addId = $searchUser['id_user'];

        $addUser = $bdd->prepare("INSERT INTO `user_list`(`id_user`, `id_list`) VALUES (:new_id, :id_list)");
        $addUser->bindParam('new_id', $addId, PDO::PARAM_STR);
        $addUser->bindParam('id_list', $idList, PDO::PARAM_STR);
        $addUser->execute();

        header('Location: task.php?id_list=' . $idList);
        exit();

    } else {
        echo 'Nom d\'utilisateur inconnu';
    }
} 


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
    </header>
    <section>
        <div>
            <h1></h1>
            <div>
                <button>Nouvelle tâche +</button>
                <button>Ajouter une abeille</button>
            </div>
        </div>
        <div>
            <h2><?= $listName['name'] ?></h2>
        </div>
        <div id="add-task" style="display: none">
            <span>Nouvelle tâche</span>
            <div>
                <form action="task.php?id_list=<?= $idList ?>" method="POST">
                    <input type="text" name="title" minlength="3" maxlength="16" required>
                    <textarea name="description" id="description" minlength="5" maxlength="40" required></textarea>
                    <input type="date" name="date" required>
                    <input type="submit" name="addTask" id="submit">
                </form>                
            </div>
        </div>
        <div>
            <div id="addBee">
                <span>Ajouter une abeille à la ruche</span>
                <form action="task.php?id_list=<?= $idList ?>" method="POST" name="addUser">
                    <input type="text" name="addPseudo" required>
                    <input type="submit" name="addUser">
                </form>
            </div>
        </div>
        <div>
            <?php
            if(count($task) > 0){
                foreach($task as $tasks){
            ?>
            <div>
                <h3><?= $tasks['name'] ?></h3>
                <p><?= $tasks['desc'] ?></p>
                <p><?= $tasks['statut'] ?></p>
                <div><?= $tasks['crea_date'] ?></div>
                <div><?= $tasks['lim_date'] ?></div>
                <div><a href="modify-task.php">Modifier</a></div>
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