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

        $checkUserList = $bdd->prepare("SELECT `id_list`, `id_user` FROM `user_list` WHERE `id_user` = :id_user AND `id_list` = :id_list");
        $checkUserList->bindParam('id_user', $addId, PDO::PARAM_INT);
        $checkUserList->bindParam('id_list', $idList, PDO::PARAM_INT);
        $checkUserList->execute();

        if($checkUserList->rowCount() === 0){
            $addUser = $bdd->prepare("INSERT INTO `user_list`(`id_user`, `id_list`) VALUES (:new_id, :id_list)");
            $addUser->bindParam('new_id', $addId, PDO::PARAM_STR);
            $addUser->bindParam('id_list', $idList, PDO::PARAM_STR);
            $addUser->execute();

            header('Location: task.php?id_list=' . $idList);
            exit();

        } else {
            echo 'Cette liste est déjà partagée à l\'utilisateur';
        }
        
    } else {
        echo 'Nom d\'utilisateur inconnu';
    }
} 


// afficher les utilisateurs

$reqUser = $bdd->prepare("SELECT `user`.`id_user`, `user`.`name` AS 'name' FROM `user` INNER JOIN `user_list` ON `user`.`id_user` = `user_list`.`id_user` INNER JOIN `list` ON `user_list`.`id_list` = `list`.`id_list` WHERE `list`.`id_list` = :id_list");
$reqUser->bindParam('id_list', $idList, PDO::PARAM_INT);
$reqUser->execute();

$displayUser = $reqUser->fetchAll();


// Valider la tâche

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
    $idTask = isset($_POST['check-task']) ? $_POST['check-task'] : '';

    if($idTask){
        $changeStatut = $bdd->prepare("UPDATE `task` SET `statut`= 1 WHERE `id_task` = :id_task");
        $changeStatut->bindParam('id_task', $idTask, PDO::PARAM_INT);
        $changeStatut->execute();

        header('Location: task.php?id_list=' . $idList);
        exit();
    }
}

// Supprimer la tâche

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
    $idTask = isset($_POST['delete-task']) ? $_POST['delete-task'] : '';

    if($idTask){
        $deleteTask = $bdd->prepare("DELETE  FROM `task` WHERE `task`.`id_task` = :id_task");
        $deleteTask->bindParam('id_task', $idTask, PDO::PARAM_INT);
        $deleteTask->execute();

        header('Location: task.php?id_list=' . $idList);
        exit();
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
    </header>
    <section class="container">
        <div class="profil-title">
            <h2 class="h1 popp-semiBold"><?= $listName['name'] ?></h2>
            <button id="new-task">Nouvelle tâche +</button>
            <button id="new-bee">Ajouter une abeille</button>
        </div>
        <div id="create-task" class="new-list center-col none">
            <i id="close-task" class="fa-solid fa-x"></i>
            <span class="h3 popp-medium">Nouvelle tâche</span>
            <div class="container center-col">
                <form action="task.php?id_list=<?= $idList ?>" method="POST" class="login-form">
                    <div>
                        <input class="form-control mb-3 login-input border-r form-text" type="text" name="title" minlength="3" maxlength="24" required placeholder="Nom de la tâche">
                    </div>
                    <div>
                        <textarea class="form-control mb-3 form-text" name="description" id="description" minlength="5" maxlength="60" required placeholder="Description"></textarea>
                    </div>
                    <div>
                        <label class="popp-reg black" for="">Date de fin</label>
                        <input class="form-control mb-3 login-input border-r form-text" type="date" name="date" required>
                    </div>
                    <div class="login-btn">
                        <input class="btn btn-primary form-control yellow border-r popp-medium" type="submit" name="addTask" id="submit">
                    </div>
                </form>                
            </div>
        </div>
        <div id="add-bee" class="new-list center-col none">
            <i id="close-bee" class="fa-solid fa-x"></i>
            <span class="h3 popp-medium">Ajouter une abeille à la ruche</span>
            <div class="container center-col">
                <form action="task.php?id_list=<?= $idList ?>" method="POST" name="addUser" class="login-form">
                    <div>
                        <input class="form-control mb-3 login-input border-r form-text" type="text" name="addPseudo" required placeholder="Pseudo ou adresse mail">  
                    </div>
                    <div class="login-btn">
                        <input class="btn btn-primary form-control yellow border-r popp-medium" type="submit" name="addUser" value="Ajouter">
                    </div>
                </form>
            </div>
        </div>
        <div id="task-container">
            <div class="side-container">
                <span class="h4 popp-regular">Les abeilles</span>
                <ul>
                <?php
                foreach($displayUser as $user){
                    $user['name'] = $user['name'] === $_SESSION['username'] ? $user['name'] . ' ' . '(Vous)' : $user['name'];
                 ?>
                    <li class="black popp-regular"><?= $user['name'] ?></li>
                 <?php
                }
                ?>
                </ul>
            </div>
            <div class="list-container">
                <?php
                if(count($task) > 0){
                    foreach($task as $tasks){
                ?>
                <div class="display-task">
                    <a href="">
                        <div class="list task">
                            <div>
                                <h2 class="h2 black popp-medium"><?= $tasks['name'] ?></h2>
                                <p class="black popp-regular"><?= $tasks['desc'] ?></p>
                                <p class="black popp-regular"><?= $tasks['statut'] ?></p>                            
                            </div>
                            <div id="date">
                                <div class="black popp-medium"><?= $tasks['crea_date'] ?> /</div>
                                <div class="black popp-medium"><?= $tasks['lim_date'] ?></div>                            
                            </div>
                            <div id="list-icon">
                                <form action="task.php?id_list=<?= $idList ?>" method="POST">
                                    <input type="hidden" name="check-task" value="<?= $tasks['id_task']?>">
                                    <button class="no-bg-btn"><i class="fa-solid fa-check"></i></button>
                                </form>
                                <form action="task.php?id_list=<?= $idList ?>" method="POST">
                                    <input type="hidden" name="delete-task" value="<?= $tasks['id_task']?>">
                                    <button class="no-bg-btn"><i class="fa-solid fa-trash"></i></button>
                                </form>                     
                            </div>
                        </div>
                    </a>
                </div>
                <?php
                    }
                } else {
                ?>
                    <div class="empty-container">
                        <span class="yellow popp-bold">Aucune tâche</span>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </section>
    <script src="./assets/js/new-task.js"></script>
</body>
</html>