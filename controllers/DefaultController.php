<?php

/*
 * 
 * Controller par defaut
 */

class DefaultController {

    public function execute() {

        switch (GET_CONTENT) {
            
            case 'home' :
                self::homeController();
                break;

            default :
                self::homeController();
                break;
        }
    }

    public function homeController() {
        
        $template = $twig->loadTemplate('views/home.html.twig');
        echo $template->render(array(
            'content' => CURRENT_PAGE,
            'baseUrl' => SITE_URL
        ));
        
    }

}
?>