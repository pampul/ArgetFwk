<?php

include_once(PATH_TO_IMPORTANT_FILES.'lib/Twig/Autoloader.php');
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('web/'); // Dossier contenant les templates
$twig = new Twig_Environment($loader, array(
            'cache' => false
        ));
?>