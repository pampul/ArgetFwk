<?php


/**
 * 
 * Auth Controller
 * Le controller doit absolument heriter de ControllerManager
 */
class AuthController extends ControllerManager{
    
    /**
     * Affichage de la vue correspondant au GET_CONTENT
     */
    public function execute() {

        switch (GET_CONTENT) {
            
            case 'login' :
                $this->loginController();
                break;
            
            /*default :
                $this->error404Controller();
                break;*/
        }
    }

    /**
     * Affichage de la page login
     * 
     * @return view
     */
    private function loginController() {
        
        $template = $this->twig->loadTemplate('views/login.html.twig');
        echo $template->render(array(
            'baseUrl' => SITE_URL
        ));
        
    }

}
?>