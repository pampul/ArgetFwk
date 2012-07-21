<?php
define('PATH_TO_IMPORTANT_FILES', '');

require_once 'app/TwigLoader.php';
require_once 'app/DoctrineLoader.php';

// Appel au fichier de configuration
require_once 'app/config.php';

// Appel aux classes de base
require_once 'app/ModelsController.php';

// Définition de la variable $content
$content = null;
if (isset($_GET['content']))
    $content = $_GET['content'];
if ($content == null)
    $content = "home";

// Appel au controller par défaut
require_once 'controllers/DefaultController.php';

?>