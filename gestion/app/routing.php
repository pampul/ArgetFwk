<?php

/*
 * ArgetRouting :
 * Appel d'un controller en fonction de son pattern envoyé en GET
 * -----
 * Si pas de pattern : on est en homepage
 */
switch(GET_PATTERN){
    
    /*
     * Laisser le case url-error il permet le traitement de 404
     */
    case 'dashboard' :
    case 'url-error' :
        require_once PATH_TO_BACKOFFICE_FILES.'controllers/DefaultController.php';
        new DefaultController();
        break;
    
    case 'private' :
        require_once PATH_TO_BACKOFFICE_FILES.'controllers/PrivateController.php';
        new PrivateController();
        break;
    
    case 'auth' :
        require_once PATH_TO_BACKOFFICE_FILES.'controllers/AuthController.php';
        new AuthController();
        break;
    
    case 'test':
        require_once PATH_TO_BACKOFFICE_FILES.'controllers/TestController.php';
        new TestController();
        break;
    
    default :
        require_once PATH_TO_BACKOFFICE_FILES.'controllers/DefaultController.php';
        new DefaultController();
        break;
    
}

?>
