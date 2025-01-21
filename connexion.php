<?php

try {
    $bdd = new PDO("mysql:host=localhost;dbname=to_do_list",'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
} 
catch(PDOException $e) {
    echo $e->getMessage();
}