<?php

include_once('connexion.php');

$id_task = isset($_POST['id-task']) ? $_POST['id-task'] : null;
$title = isset($_POST['task-title']) ? trim($_POST['task-title']) : null;
$desc = isset($_POST['task-desc']) ? trim($_POST['task-desc']) : null;
$lim_date = isset($_POST['task-lim-date']) ? $_POST['task-lim-date'] : null;



$req = $bdd->prepare("UPDATE `task` SET `name`= :task_name, `description`= :task_desc, `limit_date`= :lim_date WHERE `id_task` = :task_id");

$req->bindParam('task_name', $title, PDO::PARAM_STR);
$req->bindParam('task_desc', $desc, PDO::PARAM_STR);
$req->bindParam('lim_date', $lim_date, PDO::PARAM_STR);
$req->bindParam('task_id', $id_task, PDO::PARAM_INT);
$req->execute();

echo json_encode($req->fetchAll(PDO::FETCH_ASSOC));

