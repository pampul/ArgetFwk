<?php

/*
 * ArgetRouting :
 * Appel d'un controller en fonction de son pattern envoyé en GET
 * -----
 * Si pas de pattern : on est en homepage
 */


switch(GET_PATTERN){
    
    case 'pattern-de-l-url' :
        require_once PATH_TO_IMPORTANT_FILES.'controllers/DefaultController.php';
        DefaultController::execute();
        break;
    
    default :
        require_once PATH_TO_IMPORTANT_FILES.'controllers/DefaultController.php';
        DefaultController::execute();
        break;
    
}

?>
