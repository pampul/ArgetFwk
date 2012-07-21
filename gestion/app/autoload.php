<?php

define('PATH_TO_IMPORTANT_FILES', '../');

// Appel au fichier de configuration
require_once '../app/config.php';

// On inclus Twig via le loader
require_once '../app/TwigLoader.php';
require_once '../app/DoctrineLoader.php';

// Appel aux classes de base
require_once '../app/ModelsController.php';

// Définition de la variable $content
$content = null;
if (isset($_GET['content']))
    $content = $_GET['content'];
if ($content == null)
    $content = "home";

/*
// Vérification de l'utilisateur
if (!isset($_SESSION['login']) && $content != "login" && $content != "lostpassword" && $content != "changepassword") {

    header('Location: index.php?content=login');
} else {

    if($content != "login" && $content != "lostpassword" && $content != "changepassword"){
        // On recupere l'utilisateur
        $adminManager = new AdminManager();
        $userAdmin = $adminManager->getTableWithLoginMdp($_SESSION['login'], $_SESSION['password']);
    }
}*/

// Appel au controller par défaut
require_once 'controllers/DefaultController.php';



?>