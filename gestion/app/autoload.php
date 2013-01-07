<?php
define('BACKOFFICE_ACTIVE', 'gestion/');
define('PATH_TO_IMPORTANT_FILES', '../');
define('PATH_TO_BACKOFFICE_FILES', '');

/* 
 * Appel au fichier de configuration et des constantes
 */
require_once PATH_TO_IMPORTANT_FILES.'app/config.php';

if (!CONFIG_REQUIRE_BDD)
    header('Location: ' . SITE_URL_BASE . 'url-error/404');

/*
 * Appel aux classes utiles du Fwk
 */
require_once PATH_TO_IMPORTANT_FILES.'lib/Resources/bin/FwkLoader.php';
FwkLoader::getContext(PATH_TO_BACKOFFICE_FILES);

?>