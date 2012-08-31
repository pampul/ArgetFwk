<?php

/*
 * ArgetRouting :
 * Appel d'un controller en fonction de son pattern envoyé en GET
 * -----
 */
switch(GET_PATTERN){
    
    /*
     * Laisser le case url-error il permet le traitement de 404
     */
    case 'default-pattern' :
    case 'url-error' :
        require_once PATH_TO_IMPORTANT_FILES.'controllers/DefaultController.php';
        $controller = new DefaultController($twig, $em);
        $controller->execute();
        break;
    
    default :
        require_once PATH_TO_IMPORTANT_FILES.'controllers/DefaultController.php';
        $controller = new DefaultController($twig, $em);
        $controller->execute();
        break;
    
}

?>
