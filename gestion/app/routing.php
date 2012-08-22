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
    case 'default-pattern' :
    case 'url-error' :
        require_once PATH_TO_BACKOFFICE_FILES.'controllers/DefaultController.php';
        $controller = new DefaultController($twig, $em);
        $controller->execute();
        break;
    
    case 'auth' :
        require_once PATH_TO_BACKOFFICE_FILES.'controllers/AuthController.php';
        $controller = new AuthController($twig, $em);
        $controller->execute();
        break;
    
    default :
        require_once PATH_TO_BACKOFFICE_FILES.'controllers/DefaultController.php';
        $controller = new DefaultController($twig, $em);
        $controller->execute();
        break;
    
}

?>
