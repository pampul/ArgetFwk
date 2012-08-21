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
    
    public function __construct(Twig_Environment $twig, EntityManager $em) {
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