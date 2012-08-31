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
        $template = $this->twig->loadTemplate('views/'.GET_CONTENT.'.html.twig');
        echo $template->render(array(
            'baseUrl' => SITE_URL
        ));
        break;
    
}

?>
