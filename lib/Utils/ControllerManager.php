<?php

use Doctrine\ORM\EntityManager;

/**
 * 
 * Classe regroupant les fonctions de bases présentes dans tous les controllers
 */
class ControllerManager {

    /**
     * Twig Environnement permettant de générer les vues
     * 
     * @var Twig_Environment $twig 
     */
    protected $twig;

    /**
     * EntityManager permettant les interactions Entity-BDD
     * 
     * @var EntityManager $em 
     */
    protected $em;

    public function __construct(Twig_Environment $twig, EntityManager $em) {
        $this->twig = $twig;
        $this->em = $em;
    }

    /**
     * Redirection de la page sur une erreur 404
     */
    protected function error404Controller() {

        header('Location: ' . SITE_URL . 'url-error/404');
    }

    /**
     * Affichage de l'erreur 404
     */
    protected function error404DisplayController() {

        $template = $this->twig->loadTemplate('views/404.html.twig');
        echo $template->render(array(
            'baseUrl' => SITE_URL
        ));
    }

}

?>
