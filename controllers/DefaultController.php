<?php

/*
 * 
 * Controller par defaut
 * Le controller doit absolument heriter de ControllerManager
 */

class DefaultController extends ControllerManager{

    public function execute() {

        switch (GET_CONTENT) {
            
            case 'home' :
                $this->homeController();
                break;
            
            case '404' :
                $this->error404DisplayController();
                break;

            default :
                $this->error404Controller();
                break;
        }
    }

    private function homeController() {
        
        $value = 'Don\'t work ...';
        //$value = $this->em->getRepository('Entities\User')->myFirstFunction();
        $template = $this->twig->loadTemplate('views/home.html.twig');
        echo $template->render(array(
            'content' => $value,
            'baseUrl' => SITE_URL
        ));
        
    }

}
?>