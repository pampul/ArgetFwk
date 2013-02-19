<?php

use Doctrine\ORM\EntityManager;

/**
 * Classe regroupant les fonctions de bases présentes dans tous les controllers
 * @author f.mithieux
 */
class ControllerManager extends FwkManager {

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
     * Cette méthode vérifie que la méthode dans la classe fille existe puis l'execute ou renvoi une exception.
     */
    private function execute() {

        $methodCalled = lcfirst((string) FwkUtils::Camelize(GET_CONTENT) . 'Controller');
        if (method_exists($this, $methodCalled))
            $this->$methodCalled();
        else {
            if (ENV_DEV) {
                if (CONFIG_DEV_PHP || BACKOFFICE_ACTIVE != '')
                    throw new Exception('Exception : Le controller appelé : "' . get_class($this) . '" ne possède pas de méthode qui a pour nom ' . $methodCalled);
                else {
                    if (file_exists('web/views/' . GET_CONTENT . '.html.twig')) {
                        $this->renderView('views/' . GET_CONTENT . '.html.twig');
                    } else {
                        if (ERROR_LOGS_ENABLED) {
                            if (preg_match('#\.[a-zA-Z]+$#', SITE_CURRENT_URI))
                                FwkLog::add('Le fichier : ' . SITE_CURRENT_URI . ' n\'existe pas.', 'logs/', 'ErrorDocument/');
                            else
                                FwkLog::add ('Erreur 404 sur la page : ' . GET_CONTENT . ' du controller ' . get_class ($this), 'logs/', 'ErrorDocument/');
                        }
                        $this->error404Controller();
                    }
                }
            } else {
                if (ERROR_LOGS_ENABLED)
                    FwkLog::add('Erreur 404 sur la page : ' . GET_CONTENT . ' du controller ' . get_class($this), 'logs/', 'ErrorDocument/');
                $this->error404Controller();
            }
        }
    }

    /**
     * Fonction permettant de générer plus rapidement du Twig avec les variables envoyées de base
     * 
     * @param string $view
     * @param array $parameters
     */
    protected function renderView($view, $parameters = array()) {

        $parameters = array_merge($parameters, $this->getBaseParameters());
        if (method_exists($this, 'addParametersInView'))
            $parameters = array_merge($parameters, $this->addParametersInView());

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
        
        $seoTitle = null;
        $seoDescription = null;
        $seoH1 = null;
        
        $objSeo = $this->em->getRepository('Resources\Entities\Seo')->findOneBy(array('url' => $this->getCurrentSeoUrl()));
        
        if($objSeo instanceof Resources\Entities\Seo){
            $seoTitle = $objSeo->getTitre();
            $seoDescription = $objSeo->getDescription();
            $seoH1 = $objSeo->getH1();
        }

        $parameters = array(
            'tempsChargement' => number_format($this->getLoadingTime(), 3),
            'siteUri' => 'http://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"],
            'sitePrevUri' => $serverReferer,
            'seoTitle' => $seoTitle,
            'seoDescription' => $seoDescription,
            'seoH1' => $seoH1
        );

        unset($serverReferer);

        if (isset($_SESSION['admin']) && CONFIG_REQUIRE_BDD) {
            $objAdmin = $this->em->getRepository('Resources\Entities\Admin')->find($_SESSION['admin']['id']);
            $parameters = array_merge($parameters, array('admin' => $objAdmin));
        }

        return $parameters;
    }
    
    /**
     * Retourne l'URL courante, sans les "?" et la base du site
     * @return string
     */
    private function getCurrentSeoUrl(){
        
        $currentUri = $_SERVER['REQUEST_URI'];
        
        $arrayParseUri = explode('/', $currentUri);
        if(ENV_LOCALHOST){
            array_shift($arrayParseUri);
            array_shift($arrayParseUri);
        }else{
            array_shift($arrayParseUri);
        }
        $currentUri = implode('/', $arrayParseUri);
        unset($arrayParseUri);
        
        $arrayExploded = explode('/', $currentUri);
        if(preg_match('#gestion#', $arrayExploded[0]))
                return '';
        
        if(preg_match('#\?#', $currentUri)){
            $arrayExploded = explode('?', $currentUri);
            $currentUri = $arrayExploded[0];
        }
        
        if(preg_match('#page-#', $currentUri)){
            $currentUri = preg_replace('#/page-[0-9]+#', '', $currentUri);
        }
        
        if(preg_match('#/$#', $currentUri)){
            $arrayParseUri = explode('/', $currentUri);
            array_pop($arrayParseUri);
            $currentUri = implode('/', $arrayParseUri);
            unset($arrayParseUri);
        }
        
        unset($arrayExploded);
        return $currentUri;
        
    }

    /**
     * Redirection de la page sur une erreur 500
     * Si get_content = 500 alors affichage de la page
     */
    protected function error403Controller() {

        if (GET_CONTENT === 'error403')
            $this->error500DisplayController();
        else
            header('Location: ' . SITE_URL . 'url-error/error403');
    }

    /**
     * Affichage de l'erreur 500
     */
    protected function error403DisplayController() {

        $this->renderView('views/403.html.twig');
    }

    /**
     * Redirection de la page sur une erreur 404
     * Si get_content = 404 alors affichage de la page
     */
    protected function error404Controller() {

        if (GET_CONTENT === 'error404')
            $this->error404DisplayController();
        else
            header('Location: ' . SITE_URL . 'url-error/error404');
    }

    /**
     * Affichage de l'erreur 404
     */
    protected function error404DisplayController() {

        $this->renderView('views/404.html.twig');
    }

    /**
     * Redirection de la page sur une erreur 500
     * Si get_content = 500 alors affichage de la page
     */
    protected function error500Controller() {

        if (GET_CONTENT === 'error500')
            $this->error500DisplayController();
        else
            header('Location: ' . SITE_URL . 'url-error/error500');
    }

    /**
     * Affichage de l'erreur 500
     */
    protected function error500DisplayController() {

        $this->renderView('views/500.html.twig');
    }

}

?>
