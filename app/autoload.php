<?php

define('PATH_TO_IMPORTANT_FILES', '');
define('PATH_TO_BACKOFFICE_FILES', 'gestion/');

/* 
 * Appel au fichier de configuration et des constantes
 */
require_once PATH_TO_IMPORTANT_FILES.'app/config.php';

/*
 * Appel des différents loaders
 */
require_once PATH_TO_IMPORTANT_FILES.'app/TwigLoader.php';
if(CONFIG_REQUIRE_BDD) require_once PATH_TO_IMPORTANT_FILES.'app/DoctrineLoader.php';

/*
 * Appel aux classes utiles du Fwk
 */
require_once PATH_TO_IMPORTANT_FILES.'app/UtilsLoader.php';

/*
 * Appel des Filters pour la gestion de sessions ou autre
 */
require_once PATH_TO_IMPORTANT_FILES.'app/filters.php';

/*
 * Appel du routing.php qui gèrera l'appel aux classes de controllers
 * Si on utilise les controllers PHP on fait appel au routing_dev
 */
if (CONFIG_DEV_PHP)
    require_once PATH_TO_IMPORTANT_FILES.'app/routing_dev.php';
else
    require_once PATH_TO_IMPORTANT_FILES.'app/routing.php';


?>