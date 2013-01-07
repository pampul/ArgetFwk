<?php

use Doctrine\ORM\EntityManager;

/**
 * Classe regroupant les fonctions de bases présentes dans tous les controllers ajax
 * @author f.mithieux
 */
class AjaxManager extends FwkManager {

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

    /**
     * Array regroupant l'ensemble des classes utiles au Fwk
     * 
     * @var array $fwkClasses
     */
    protected $fwkClasses;

    public function __construct() {
        $this->twig = FwkLoader::getTwigEnvironement();
        if (CONFIG_REQUIRE_BDD)
            $this->em = FwkLoader::getEntityManager();
        $this->fwkClasses = FwkLoader::getFwkEntities();

        $this->execute();
    }

    /**
     * Execution de la methode correspondante au GET_METHOD
     * 
     * Cette méthode vérifie dans un try/catch que la méthode dans la classe fille existe puis l'execute ou renvoi une exception.
     */
    private function execute() {
        $methodCalled = lcfirst((string) FwkUtils::Camelize(GET_METHOD) . 'Controller');
        if (method_exists($this, $methodCalled))
            $this->$methodCalled();
        else
            throw new Exception('Exception : Le controller appelé : "' . get_class($this) . '" ne possède pas de méthode qui a pour nom ' . $methodCalled);
    }

    /**
     * Fonction permettant de générer plus rapidement du Twig avec les variables envoyées de base
     * 
     * @param string $view
     * @param array $parameters
     */
    protected function renderView($view, $parameters = array()) {

        $parameters = array_merge($parameters, $this->getBaseParameters());

        $template = $this->twig->loadTemplate($view);
        echo $template->render($parameters);
    }

    /**
     * Retourne le temps de chargement
     * 
     * @return int
     */
    private function getLoadingTime() {

        return microtime(true) - MICRO_TIME;
    }

    /**
     * Création des paramètres de base à envoyer
     * 
     * @return array
     */
    private function getBaseParameters() {

        if (!isset($_SERVER["HTTP_REFERER"]))
            $serverReferer = SITE_URL;
        else
            $serverReferer = $_SERVER["HTTP_REFERER"];

        $parameters = array(
            'tempsChargement' => number_format($this->getLoadingTime(), 3),
            'siteUri' => 'http://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"],
            'sitePrevUri' => $serverReferer
        );

        unset($serverReferer);

        if (isset($_SESSION['admin']) && CONFIG_REQUIRE_BDD) {
            $objAdmin = $this->em->getRepository('Resources\Entities\Admin')->find($_SESSION['admin']['id']);
            $parameters = array_merge($parameters, array('admin' => $objAdmin));
        }

        return $parameters;
    }

}

?>
