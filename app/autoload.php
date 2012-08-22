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
 * Constantes de recuperation en HTTP
 */
if (isset($_GET['pattern'])) define('GET_PATTERN', $_GET['pattern']);
else define('GET_PATTERN', null);

if (isset($_GET['content'])) define('GET_CONTENT', $_GET['content']);
else{
    if(ENV_LOCAL){
        
    }else{
        
    }
}
//else define('GET_PATTERN', null);

/*
 * Appel du routing.php qui gèrera l'appel aux classes de controllers
 */
require_once PATH_TO_IMPORTANT_FILES.'app/routing.php';


?>