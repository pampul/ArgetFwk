<?php

// Appel au fichier de configuration
require_once 'AppConfig/config.php';

// Appel aux classes de base
$basePath = "";
require_once 'AppConfig/ModelsController.php';

// Définition de la variable $content
$content = null;
if (isset($_GET['content'])) $content = $_GET['content'];
if ($content == null) $content = "home";

// Appel au fichier commun
require_once 'gestion/Tools/commun.php';

// Appel au controller par défaut
require_once 'Controllers/DefaultController.php';

// Appel au fichier de référencement
require_once 'AppConfig/SEO.php';

?>