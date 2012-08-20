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
 * Définition de la page actuelle (si local : un slash en trop à suppr)
 * La page actuelle se définit entre le deuxieme et le troisieme slash (si existant) ex : www.argetweb.fr/site-internet/PAGEACTUELLE
 */
if(ENV_LOCAL){
    $arrayVars = explode('/', SITE_CURRENT_URI);
    if(isset($arrayVars[3]))
        define('CURRENT_PAGE', $arrayVars[3]);
    else
        define('CURRENT_PAGE', 'home');
}else{
    $arrayVars = explode('/', SITE_CURRENT_URI);
    if(isset($arrayVars[2]))
        define('CURRENT_PAGE', $arrayVars[2]);
    else
        define('CURRENT_PAGE', 'home');
}

/*
 * Appel des entités / classes utiles / managers
 */
require_once PATH_TO_IMPORTANT_FILES.'app/EntityController.php';


/*
 * Appel des controllers
 */
require_once PATH_TO_IMPORTANT_FILES.'controllers/DefaultController.php';

?>