<?php

define('BACKOFFICE_ACTIVE', '');
define('PATH_TO_IMPORTANT_FILES', __DIR__.'/../');
define('PATH_TO_BACKOFFICE_FILES', __DIR__.'/../gestion/');

/**
 * Appel de la classe HTTP
 */
require_once PATH_TO_IMPORTANT_FILES.'lib/Resources/Core/HttpCore.php';

/**
 * Appel au fichier de configuration et des constantes
 */
require_once PATH_TO_IMPORTANT_FILES.'app/config.php';

/**
 * Appel aux classes utiles du Fwk
 */
require_once PATH_TO_IMPORTANT_FILES.'lib/Resources/bin/FwkLoader.php';
FwkLoader::getContext(PATH_TO_IMPORTANT_FILES);

?>