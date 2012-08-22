<?php

define('PATH_TO_IMPORTANT_FILES', '');

/* 
 * Appel au fichier de configuration et des constantes
 */
require_once PATH_TO_IMPORTANT_FILES.'app/config.php';

/*
 * Appel des différends loaders
 */
require_once PATH_TO_IMPORTANT_FILES.'app/TwigLoader.php';
require_once PATH_TO_IMPORTANT_FILES.'app/DoctrineLoader.php';

/*
 * Appel aux classes utiles du Fwk
 */
require_once PATH_TO_IMPORTANT_FILES.'app/UtilsLoader.php';

/*
 * Appel du routing.php qui gèrera l'appel aux classes de controllers
 */
require_once PATH_TO_IMPORTANT_FILES.'app/routing.php';


?>