<?php
define('BACKOFFICE_ACTIVE', 'gestion/');
define('PATH_TO_IMPORTANT_FILES', '../');
define('PATH_TO_BACKOFFICE_FILES', '');

/* 
 * Appel au fichier de configuration et des constantes
 */
require_once PATH_TO_IMPORTANT_FILES.'app/config.php';

/*
 * Appel des différents loaders
 */
require_once PATH_TO_IMPORTANT_FILES.'app/TwigLoader.php';
require_once PATH_TO_IMPORTANT_FILES.'app/DoctrineLoader.php';

/*
 * Appel aux classes utiles du Fwk
 */
require_once PATH_TO_IMPORTANT_FILES.'app/UtilsLoader.php';

/*
 * Appel des Filters pour la gestion de sessions ou autre
 */
require_once PATH_TO_BACKOFFICE_FILES.'filters.php';

/*
 * Appel du routing.php qui gèrera l'appel aux classes de controllers
 */
require_once PATH_TO_BACKOFFICE_FILES.'routing.php';




?>