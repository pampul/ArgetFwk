<?php

/*
 * 
 * Controller par defaut
 * Le controller doit absolument posseder un constructeur et ses variables twig et em pour la suite du dev
 */
use Doctrine\ORM\EntityManager;

class DefaultController {
    
    protected $twig,
              $em;
    
    public function __construct(Twig_Environment $twig = null, EntityManager $em = null) {
        $this->twig = $twig;
        $this->em = $em;
    }

    public function execute() {

        switch (GET_CONTENT) {
            
            case 'home' :
                $this->homeController();
                break;

            default :
                $this->homeController();
                break;
        }
    }

    public function homeController() {
        
        $template = $this->twig->loadTemplate('views/home.html.twig');
        echo $template->render(array(
            'content' => 'test',
            'baseUrl' => SITE_URL
        ));
        
    }

}
?>