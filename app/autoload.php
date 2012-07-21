<?php
define('PATH_TO_IMPORTANT_FILES', '');

/* 
 * Appel au fichier de configuration et des constantes
 */
require_once 'app/config.php';

/*
 * Appel des différends loaders
 */
require_once 'app/TwigLoader.php';
require_once 'app/DoctrineLoader.php';

/*
 * Définition de la page actuelle
 */
$content = null;
if (isset($_GET['content']))
    $content = $_GET['content'];
if ($content == null)
    $content = "home";

/*
 * Appel des controllers
 */
require_once 'controllers/DefaultController.php';

?>