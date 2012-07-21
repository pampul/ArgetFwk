<?php

// Appel au fichier de configuration
require_once '../AppConfig/config.php';

// Appel aux classes de base
$basePath = "../";
require_once '../AppConfig/ModelsController.php';

// Appel au fichiers de fonctions communes
require_once 'Tools/commun.php';

// Définition de la variable $content
$content = null;
if (isset($_GET['content'])) $content = $_GET['content'];
if ($content == null) $content = "home";
if (!is_file("Web/views/" . $content . ".php")) header('Location: ' . $siteURL . '/gestion/index.php?content=home');

// Vérification de l'utilisateur
if (!isset($_SESSION['login']) && $content != "login" && $content != "lostpassword" && $content != "changepassword") {

    header('Location: index.php?content=login');
} else {

    if($content != "login" && $content != "lostpassword" && $content != "changepassword"){
        // On recupere l'utilisateur
        $adminManager = new AdminManager();
        $userAdmin = $adminManager->getTableWithLoginMdp($_SESSION['login'], $_SESSION['password']);
    }
}

// Appel au controller par défaut
require_once 'Controllers/DefaultController.php';



?>