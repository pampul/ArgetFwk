<?php


use Doctrine\ORM\EntityManager;

/**
 * 
 * Controller par defaut
 * Le controller doit absolument heriter de ControllerManager
 */
class DefaultController extends ControllerManager{
    
    protected $twig,
            $em;
    
    public function __construct(Twig_Environment $twig, EntityManager $em) {
        $this->twig = $twig;
        $this->em = $em;
    }

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

    /**
     * Affichage de la page d'accueil
     * 
     * @return view
     */
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